<?php 
namespace SakuraPanel\Plugins\Auth;

// sakura lib 
use \SakuraPanel\Library\SharedConstInterface;
use \SakuraPanel\Models\User\{
    UsersSessions,
    Users
};
use \SakuraPanel\Models\Security\AuthSecurity;


// AuthMiddleware lib
use \Sid\Phalcon\AuthMiddleware\MiddlewareInterface;

// phalcon
use \Phalcon\Mvc\Dispatcher;

class AuthMiddleware extends \ControllerBase implements MiddlewareInterface , SharedConstInterface
{
    private $authKey = "user";
    private $authKeyRemember = "user_rm";
    private $authKeyRememberLength = 50;
    private $auth_data = ['id','username','email' , 'role_name','status'];

    protected $user;
    protected $authUser = false;
    // check role / acl permissions
    public function beforeExecuteRoute(Dispatcher $dispatcher)
    {
        $this->authUser = $this->isLoggedIn();

        $controllerName = $dispatcher->getControllerName();
        // Check if the user have permission to the current option
        $actionName = $dispatcher->getActionName();
        
        $role_name = $this->authUser->role_name  ?? $this::ROLE_DEFAULT;


        if (!$this->acl->isAllowed($role_name, $controllerName, $actionName)) {
            if (!$this->request->isAjax()){
                $this->flashSession->error($role_name.' -  You don\'t have access to this module: ' . $controllerName . ':' . $actionName);
               
                $this->response->redirect('../404');
            }else{
                $this->ajax->error($role_name.' -  You don\'t have access to this module: ' . $controllerName . ':' . $actionName)->sendResponse();
            }
            
            if (!$this->acl->isAllowed($role_name, $controllerName, 'index')) {
                if ($this->request->isAjax())
                    return $this->ajax->error('Please reload your page ! ')->sendResponse();

                $this->response->redirect('../404');
            }
            
            return false;
        }
    }

    public function authenticate() : bool
    {

        $this->authUser = $this->isLoggedIn();
        if (!$this->authUser) {
            
            $this->flashSession->error(
                "You must be logged in."
            );

            if ($this->request->isAjax()){
                $this->ajax->error('You must be logged in ! ')->sendResponse();
                return false;
            }

            $this->response->redirect(
                "auth/login"
            );
            return false;
        }
        if ($this->authUser->status != $this::ACTIVE){
            $this::clearUserSession();
            
            if ($this->request->isAjax()){
                $this->ajax->error("Your account is ". $Users::getStatusById($this->authUser->status)->title)->sendResponse();
                return false;
            }
            else 
                $this->flashSession->error(
                    "Your account is ". Users::getStatusById($this->authUser->status)->title
                );
            
            $this->response->redirect(
                "auth/login?disabledStatus"
            );
            return false;
        }

        return true;
    }

    public function isLoggedIn()
    {
        $auth = false;

        // check if ip is banned
        if (AuthSecurity::isIpBanned($this->request->getClientAddress())) {
            $this->flash->error('You are banned from our services ! ');
            return false;
        }

        // check session
        if ($this->session->has($this->authKey)) { 
            $auth = $this::getUserSession();
            if(!$auth) $this::clearUserSession();
        }else{
            if ($this->cookies->has($this->authKeyRemember)) {
                $auth = $this::checkUserSession($this->cookies->get($this->authKeyRemember));
            }
        }

        return $auth;
    }


    /**
     *
     *  Cookie / Session Saved
     *  Check cookie session
     *
     *
     */

    public function checkUserSession($session_key)
    {
        $session = new UsersSessions();

        $user_auth = $session::findFirst([
            'session = ?0',
            'bind'=>[
                $session_key
            ]
        ]);

        if (!$user_auth || $user_auth->expired_at < time()) {
            $user_auth = false;
        }elseif ($user_auth && $user_auth->expired_at > time()) {
            // set session info
            $user_auth = Users::findFirst([
                'id = ?0',
                'bind'=>[
                    $user_auth->user_id
                ]
            ]);
            if ($user_auth) {
                $this->setUserSession($user_auth);
            }
        }

        if (!$user_auth) $this::clearUserSession();
        $this->diSaveUser($user_auth);

        return $user_auth;
    }


        /**
     * 
     *
     *  Clear User Session
     *
     */
    public function clearUserSession()
    {

        $ds = $this->session->remove($this->authKey);
        $dc = $this->cookies->get($this->authKeyRemember)->delete() && $this->cookies->set($this->authKeyRemember,null);

        return $ds && $dc;
    }


    /**
     * Save User Session
     * @param Object
     */

    public function diSaveUser($user_session)
    {
        $this->user = $user_session;
        $this->di->setShared('user', !empty($user_session) ? $user_session : null);
    }

    /**
     * 
     *
     *  Set User Session
     *
     *
     */
    public function setUserSession($user=[] , $remember=false)
    {
        if ($remember && !empty($user->id)) {
            $rand_crypt_token = $this->randToken($this->authKeyRememberLength ?: 13);

            $session = new UsersSessions();
            $session->user_id = $user->id;
            $session->expired_at = time() + $this->di->getShared('site')->get('session-lifetime', 60 * 60 * 24 * 10);
            $session->session = $rand_crypt_token;

            if ($session->create()) $this->cookies->set($this->authKeyRemember , $rand_crypt_token);

            $user = $this::filterUserSession($user);
        }
        return $this->session->set($this->authKey , $this::filterUserSession($user));
    }


    /**
     * 
     *
     *  Get User By Session Information
     *
     */
    public function getUserSession($filter=true)
    {
        $_user = false;
        $_session = (!empty($this->session->has($this->authKey))) ? $this->session->get($this->authKey) : null;

        if (!empty($_session)) {
            $user_session = Users::findFirstById(@$_session->id ?: null);
            if ($user_session) 
                {
                    $_user = ($filter) ? $this::filterUserSession($user_session) : $user_session;
                    $this->diSaveUser($user_session);
                }
        }

        return $_user;
    }

    /**
     * 
     *
     *  Sesssion
     *  Filter User Data to save into session
     *
     *
     */
    public function filterUserSession($data)
    {
        $user_session = [];

        $data = (!is_object($data)) ? (object) $data : $data;

        if (!empty($this->auth_data)) {
            foreach ($this->auth_data as $name) {
                $user_session[$name] = (!empty($data->$name)) ? $data->$name : null;
            }
        }

        return (object) $user_session;
    }



    /**
     * 
     *  Random Token
     *
     */
    public function randToken($size=30)
    {
        $random = new \Phalcon\Security\Random();
        return $random->base64($size);
    }


}