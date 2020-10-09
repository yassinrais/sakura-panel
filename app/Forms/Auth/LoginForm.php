<?php
namespace SakuraPanel\Forms\Auth;



// form elements
use Phalcon\Forms\Form;
use Phalcon\Forms\Element\Password;
use Phalcon\Forms\Element\Email;
use Phalcon\Forms\Element\Check;
use Phalcon\Forms\Element\Hidden;

// controllers / models
use SakuraPanel\Forms\BaseForm;
use SakuraPanel\Models\User\Users;


// validation
use Phalcon\Validation\Validator\{ PresenceOf , Email as EmailValidateur  , StringLength};

class LoginForm extends BaseForm
{
    public function initialize()
    {
    	parent::initialize();


        $email =  new Email(
                'email',
                [ 
                    'placeholder'=>"email@example.com", 
                    'class'=>"form-control" ,
                    'required'=>'required',
                ]
            );


        $email->addValidator(
            new EmailValidateur(
                [
                    'message' => 'The :field is required',
                ]
            )
        );

        $password =  new Password(
                'password',
                [ 
                    'required'=>'required',
                ]
            );

        $password->addValidator(
            new PresenceOf(
                [
                    'message' => 'The :field is required',
                ]
            )
        );

        $password->addValidator(
            new StringLength(
                [
                    'min'            => $this->getDI()->getConfig()->security->min_password_length,
                    'messageMinimum' => 'The :field is too short',
                ]
            )
        );



        $remember = new Check(
            'remember'
        );

		$csrf = new Hidden(
            'csrf'
        );
    


        $this->add($email);
        $this->add($password);
        $this->add($remember);
        $this->add($csrf);
    }


    public function getCsrf()
    {
        return $this->security->getToken();
    }

}