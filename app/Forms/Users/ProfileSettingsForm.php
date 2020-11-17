<?php 
/**
 * ProfilesettingsForm
 */

namespace Sakura\Forms\Users;

use Sakura\Forms\BaseForm;


use Phalcon\Forms\Form; 
use Phalcon\Forms\Element\{
    Text,
    Email,
    Password,
    File,
    Hidden
};

use Phalcon\Validation\Validator\{
    PresenceOf ,
    StringLength ,
    Email as EmailVal,
    Callback
};


class ProfileSettingsForm extends BaseForm
{
    public function initialize()
    {
        // Set the same form as entity
        $this->setEntity($this);

        // avatar
        $avatar = new File('avatarfile',['class'=>'form-control','placeholder'=>'Avatar']);
        $this->add($avatar);


        //  full name
        $fullname = new Text(
        	'fullname',
        	[
        		"required"=>true,
        		"class"=>"form-control",
                "id"=>"fullname",
                "placeholder"=>"Full Name"
        	]
        );

        $fullname->addFilter('string');
    

        // email adresse
        $email = new Email(
            'email',
            [
                "required"=> true,
                "class"=>"form-control",
                "id"=>"email",
                "placeholder"=>"Email"
            ]
        );
   

        // new password
        $npassword = new Password(
        	'npassword',
        	[
        		"class"=>"form-control",
                "id"=>"npassword",
                "placeholder"=>"New Password",
        	]
        );
        $npassword->addValidator(
            new StringLength(
                [
                    'min'            => $this->config->security->min_password_length ?? 10,
                    'max'            => $this->config->security->max_password_length ?? 100,
                    'messageMinimum' => 'New Password is too short',
                    'allowEmpty'=>true,
                ]
            )
        );

        // confirm password
        $cpassword = new Password(
        	'cpassword',
        	[
        		"class"=>"form-control",
                "id"=>"cpassword",
                "placeholder"=>"Confirm Password",
        	]
        );
        
        $cpassword->addValidator(
            new Callback(
                [
                    'callback' => function($data) {
                        return $data['cpassword'] === $this->get('npassword')->getValue();
                    },
                    'message'  => 'Confirmation password does not match'
                ]
            )
        );

      
        // currnet password
        $password = new Password(
        	'currentPassword',
        	[
        		"required"=>true,
        		"class"=>"form-control",
                "id"=>"currentPassword",
                "placeholder"=>"Current Password"
        	]
        );
        $password->addValidator(
            new PresenceOf(
                [
                    'message' => 'The :field is required',
                ]
            )
        );


        $this->add($fullname);
        $this->add($email);
        $this->add($password);
        $this->add($npassword);
        $this->add($cpassword);

        // Add a text element to put a hidden CSRF
        $this->add(
            new Hidden(
                'csrf'
            )
        );
    }

    public function getCsrf()
    {
        return $this->security->getToken();
    }
}