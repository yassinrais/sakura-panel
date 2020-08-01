<?php 
declare(strict_types=1);

namespace SakuraPanel\Controllers\Auth;
use \SakuraPanel\Controllers\Pages\PageControllerBase;


use \SakuraPanel\Forms\LoginForm;
use \SakuraPanel\Models\User\{Users , UsersSessions};
use \Phalcon\Http\Response;

/**
 * LoginController
 */
class AuthController extends PageControllerBase
{	
    // Implement common logic
    public function onConstruct(){
        if ($this->isLoggedIn()){
            print_r("Redirect to panel ...");
    		return $this->response->redirect('member');
    	}
    }
    
    public function initialize(){
        parent::initialize();

        $this->page->set('body.class','bg-gradient-primary');

        $this->view->setMainView('auth/index');
        
        $this->assetsPack->footer->addJs('assets/js/auth.js');
        
        $this->view->form = new LoginForm();
    }
	public function indexAction(){
		return $this->response->redirect("auth/login?fromIndex");
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
                }else
                    $this->ajax->{$user->getStatusInfo()->type}('Your account is '. $user->getStatusInfo()->title);
            }else
                $this->ajax->error('Wrong information ! ');
        }


        return $this->ajax->sendResponse();
    }


    public function logoutAction()
    {
        $this::clearUserSession();
        $this->response->redirect('auth/login');
    }
}