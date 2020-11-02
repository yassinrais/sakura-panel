<?php
namespace Sakura\Forms;

use Phalcon\Forms\Form; 
use Phalcon\Forms\Element\Hidden;

class BaseForm extends Form implements  \Sakura\Library\SharedConstInterface
{
    public function initialize()
    {
        // Set the same form as entity
        $this->setEntity($this);

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