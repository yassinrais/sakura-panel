<?php
namespace SakuraPanel\Plugins\Pluginsmanager\Forms;


// form elements
use Phalcon\Forms\Form;
use Phalcon\Forms\Element\Numeric;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\TextArea;
use Phalcon\Forms\Element\Hidden;
use Phalcon\Forms\Element\Select;

// controllers / models
use SakuraPanel\Forms\BaseForm;


// validation
use Phalcon\Validation\Validator\{ Between , Ip as IpValidator , PresenceOf , Email as EmailValidateur  , StringLength , Regex , Numericality , Callback };

class PluginsForm extends BaseForm
{
    public function initialize()
    {
      parent::initialize();
        /**
         *
         * Title Input
         *
         */
        $title = new Text(
          'title',
          [
            'required'=>true,
            'class'=>"form-control",
            'placeholder'=>'Bot Title',
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



        $title->addFilter('string');
        $status->addFilter('int');

        $this->add($title);
        $this->add($status);
    }


}