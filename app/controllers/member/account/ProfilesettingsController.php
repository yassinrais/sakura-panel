<?php 
declare(strict_types=1);

namespace SakuraPanel\Controllers\Member\Account;

use SakuraPanel\Controllers\Member\MemberControllerBase;
use SakuraPanel\Forms\{
	ProfilesettingsForm
};

/**
 * Profilesettings
 */
class ProfilesettingsController extends MemberControllerBase
{
    // Implement common logic
    public function initialize(){
    	parent::initialize();

        $this->page->set('title','Profile Settings');
	
		$this->form = new ProfilesettingsForm($this->user);
		$this->form->bind($this->user->toArray() ,$this->user);
    }
	public function indexAction()
	{
		$form = $this->form;
		$this->view->form = $form;

		if (!empty($this->request->isPost())) {
			if (false === $form->isValid($_POST)) {
			    $messages = $form->getMessages();

			    foreach ($messages as $message) {
			        $this->flashSession->warning((string) $message);
			    }
			}else{
				$cpassword = $form->get('currentPassword')->getValue();
				

				if ($this->security->checkHash($cpassword , $this->user->password)) {
					$form->bind($_POST, $this->user);
	
					$this->user->password = $this->request->getPost('npassword');
										
					if ($this->user->save()) {
						$this->flashSession->success('Profile Updated Successffully ');
						return $this->response->redirect('member/dashboard');
					}else{
						$this->flashSession->error('Error !' . implode(" & ", $this->user->getMessages()));
					}
				}else{
					$this->flashSession->error('Password incorrect !');
				}
			}
		}
		

		return $this->view->pick('member/account/profile-settings');
	}


}