<?php 
declare(strict_types=1);

namespace SakuraPanel\Controllers\Auth;
use \SakuraPanel\Controllers\Pages\PageControllerBase;


use \SakuraPanel\Forms\LoginForm;
use \SakuraPanel\Models\User\Users;

/**
 * LoginController
 */
class AuthController extends PageControllerBase
{	
    // Implement common logic
    public function onConstruct(){
        if ($this->isLoggedIn()){
    		return $this->response->redirect('./home');
    	}
    }
    
    public function initialize(){
        parent::initialize();

        $this->page->set('body.class','bg-gradient-primary');

        $this->view->setMainView('auth/index');
        
        $this->view->form = new LoginForm();
    }
	public function indexAction(){
		return $this->response->redirect("auth/login");
	}	

	public function loginAction()
    {
        $this->page->set('title','Authentification');
        
        $form = $this->view->form;

        if ($this->request->getPost('action') == "login") {
            // check login
            if ($this->security->checkToken('csrf')) {
                if (!$form->isValid($_POST)) {
                    foreach ($form->getMessages() as $msg) 
                        $this->flashSession->error((string) $msg);
                }else{
                    $user = Users::findFirstByEmail((string) $this->request->getPost('email'));

                    if ($user && $this->security->checkHash($this->request->getPost('password') , $user->password)) {
                        if ($user->isActive()) {
                            $this->setUserSession($user);
                            $this->response->redirect('member/dashboard');
                        }else
                            $this->flashSession->{$user->getStatus()->type}('Your account is '. $user->getStatus()->title);
                    }else
                        $this->flashSession->error('Wrong information ! ');
                }
                        
            
            }else{
                $this->flashSession->error('Error ! Protection mesures ! try again ');
            }
        }

        return $this->view->pick('auth/login');
    }

    public function registerAction()
    {
        return 'REGISTER';
    }


    public function logoutAction()
    {
        $this::clearUserSession();
        $this->response->redirect('auth/login');
    }
}