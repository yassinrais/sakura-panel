<?php 
declare(strict_types=1);

namespace Sakura\Controllers\Auth;
use \Sakura\Controllers\Pages\PageControllerBase;


use \Sakura\Forms\Auth\LoginForm;
use \Sakura\Models\User\{Users , UsersSessions};
use \Sakura\Models\Security\AuthSecurity;
use \Phalcon\Http\Response;

/**
 * LoginController
 */
class AuthController extends PageControllerBase
{	
    // Implement common logic
    public function onConstruct(){
        if ($this->isLoggedIn()){
    		return $this->response->redirect('member');
    	}
    }
    
    public function initialize(){
        parent::initialize();

        $this->client_ip = $this->request->getClientAddress();

        $this->assetsPack->footer->addJs('assets/js/auth.js');

        $this->view->setMainView('auth/index');
        $this->view->form = new LoginForm();
    }
	public function indexAction(){
		return $this->response->redirect("auth/login?fromI");
	}	

	public function loginAction()
    {
        $this->page->set('title','Authentification');
        
        $form = $this->view->form;

        return $this->view->pick('auth/login');
    }

    public function ajaxLoginAction()
    {
        $this->view->disable();

        // check if ip is banned
        $isbanned = AuthSecurity::isIpBanned($this->client_ip);

        if ($isbanned) {
            // fake sleep
            sleep($this->config->security->auth_fake_delay);

            return $this->ajax->error('Access denied. you are banned from this service !')->sendResponse();
        }

        $suspended = AuthSecurity::isIpSuspend($this->client_ip);
        if ($suspended) {
            // fake sleep
            sleep($this->config->security->auth_fake_delay);
            
            return $this->ajax->warning('You are suspended , because of the many attempts ! Time Rest '.$suspended->getTimeRest())->sendResponse();
        }


        $form = $this->view->form;
        if (!$form->isValid($_POST)) {
            foreach ($form->getMessages() as $msg) 
                $this->ajax->error((string) $msg);
        }else{
            $user = Users::findFirstByEmail((string) $this->request->getPost('email'));

            if ($user && $this->security->checkHash($this->request->getPost('password') , $user->password)) {
                if ($user->isActive()) {
                    $this->setUserSession($user , $this->request->getPost('remember') ?? false);
                    $this->ajax->success('Login successful. redirecting ... ');
                    AuthSecurity::deleteByIp($this->client_ip);
                    return $this->ajax->sendResponse();
                }else{
                    // mesage
                    $this->ajax->{$user->getStatusInfo()->type}('Your account is '. $user->getStatusInfo()->title);

                    // fake time x 20%
                    sleep(intval($this->config->security->auth_fake_delay*0.2));
                }
            }else{
                AuthSecurity::increaseAttempsByIp($this->client_ip);

                // fake time x 20%
                sleep(intval($this->config->security->auth_fake_delay*0.2));

                // mesage
                $this->ajax->error('Wrong information ! ');
            }
        }

        return $this->ajax->sendResponse();
    }


    public function logoutAction()
    {
        $this::clearUserSession();
        $this->response->redirect('auth/login');
    }
}