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
    Hidden
};

use Phalcon\Validation\Validator\{
    PresenceOf ,
    StringLength ,
    Regex,
    Email as EmailVal,
    Callback
};


class ProfileSettingsForm extends BaseForm
{
    public function initialize()
    {
        // Set the same form as entity
        $this->setEntity($this);

        //  full name
        $fullname = new Text(
        	'fullname',
        	[
        		"required"=>true,
        		"class"=>"form-control",
        		"id"=>"fullname",
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
            ]
        );
   

        // new password
        $npassword = new Password(
        	'npassword',
        	[
        		"class"=>"form-control",
        		"id"=>"npassword"
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
        		"id"=>"cpassword"
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