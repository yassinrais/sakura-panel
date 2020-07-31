<?php
namespace SakuraPanel\Plugins\Products\Forms;


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

class ProductSForm extends BaseForm
{
    public function initialize()
    {
      parent::initialize();
        /**
         *
         * Name Input
         *
         */
        $name = new Text(
          'name',
          [
            'required'=>true,
            'class'=>"form-control",
            'placeholder'=>'Bot Name',
          ]
        );

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
         * Note Input
         *
         */
        $note = new TextArea(
          'note',
          [
            'required'=>false,
            'placeholder'=>'Note',
            'class'=>"form-control"
          ]
        );



        /**
         *
         * Image Input
         *
         */
        $image = new Text(
          'image',
          [
            'required'=>true,
            'placeholder'=>'Image',
            'class'=>"form-control"
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



        $name->addFilter('string');
        $title->addFilter('string');
        $note->addFilter('string');
        // $status->addFilter('int');

        $this->add($name);
        $this->add($title);
        $this->add($note);
        $this->add($image);
        $this->add($status);
    }


}