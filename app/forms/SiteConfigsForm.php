<?php 
/**
 * SiteConfigsForm
 */

namespace SakuraPanel\Forms;

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


class SiteConfigsForm extends BaseForm
{
    public function initialize()
    {
        // Set the same form as entity
        $this->setEntity($this);

        //  full name
        $key = new Text(
        	'key',
        	[
        		"required"=>true,
        		"class"=>"form-control",
        		"id"=>"key",
        	]
        );

        $key->addFilter('string');

        //  full name
        $value = new Text(
        	'val',
        	[
        		"required"=>true,
        		"class"=>"form-control",
        		"id"=>"value",
        	]
        );

        $value->addFilter('string');
    

 
      

        $this->add($key);
        $this->add($value);

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