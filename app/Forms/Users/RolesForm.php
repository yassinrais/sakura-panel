<?php
namespace Sakura\Forms\Users;


// form elements
use Phalcon\Forms\Form;
use Phalcon\Forms\Element\Email;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\Hidden;
use Phalcon\Forms\Element\Select;
use Phalcon\Forms\Element\Password;

// controllers / models
use Sakura\Forms\BaseForm;

class RolesForm extends BaseForm
{
    public function initialize()
    {
      parent::initialize();
       
       /**
         *
         * Text Input
         *
         */
        $name = new Text(
            'name',
            [
              'required'=>true,
              'class'=>"form-control",
              'placeholder'=>'Name',
            ]
          );
          $name->addFilter('string');
        /**
         *
         * Text Input
         *
         */
        $title = new Text(
            'title',
            [
              'required'=>true,
              'class'=>"form-control",
              'placeholder'=>'Title',
            ]
          );
          $title->addFilter('string');

          
          
           /**
         * 
         * Stats Select
         *
         */
        $inherit = new Select(
          'inherit',
          [
            'Inherit Role'=> $this::ROLES_LIST + [null=>'No Inherit']
          ],
          [
            'required'=>true,
            'class'=>"form-control",
          ]
        );
 
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
     	$this->add($name);
     	$this->add($title);
     	$this->add($inherit);

     	$this->add($status);

    }


}