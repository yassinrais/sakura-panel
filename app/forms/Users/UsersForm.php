<?php
namespace SakuraPanel\Forms\Users;


// form elements
use Phalcon\Forms\Form;
use Phalcon\Forms\Element\Email;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\Hidden;
use Phalcon\Forms\Element\Select;
use Phalcon\Forms\Element\Password;

// controllers / models
use SakuraPanel\Forms\BaseForm;

class UsersForm extends BaseForm
{
    public function initialize()
    {
      parent::initialize();
       
       /**
         *
         * Text Input
         *
         */
        $fullname = new Text(
          'fullname',
          [
            'required'=>true,
            'class'=>"form-control",
            'placeholder'=>'Full Name',
          ]
        );
        $fullname->addFilter('string');
       /**
         *
         * Text Input
         *
         */
        $username = new Text(
          'username',
          [
            'required'=>true,
            'class'=>"form-control",
            'placeholder'=>'Username',
          ]
        );
        $username->addFilter('string');
        /**
          *
          * Text Input
          *
          */
         $email = new Email(
           'email',
           [
             'required'=>true,
             'class'=>"form-control",
             'placeholder'=>'Email',
           ]
         );
         $email->addFilter('string');

       /**
         *
         * Password Input
         *
         */
        $password = new Password(
          'password',
          [
            'class'=>"form-control",
            'placeholder'=>'New Password',
          ]
        );
 
        /**
         *
         * Select
         *
         */
        $role = new Select(
          'role_name',
          [
            'Select Role'=>$this::ROLES_LIST
          ],
          [
            'required'=>true,
            'class'=>"form-control",
          ]
        );
        $role->addFilter('string');

           /**
         * 
         * Stats Select
         *
         */
        $status = new Select(
            'status',
            [
              'Select Status'=>$this::STATUS_LIST
            ],
            [
              'required'=>true,
              'class'=>"form-control",
            ]
          );
  
    
        // add Validators
     	$this->add($fullname);
     	$this->add($username);
     	$this->add($email);
     	$this->add($password);
     	$this->add($role);

     	$this->add($status);

    }


}