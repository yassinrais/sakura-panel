<?php
namespace SakuraPanel\Plugins\Discordbots\Forms;


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

class DiscordBotsForm extends BaseForm
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
        $name->addFilter('string');

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
        $title->addFilter('string');

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
        $note->addFilter('string');


        /**
         *
         * HostName Input
         *
         */
        $hostname = new Text(
          'hostname',
          [
            'required'=>true,
            'placeholder'=>'Hostname',
            'class'=>"form-control"
          ]
        );


        /**
         *
         * IP Input
         *
         */
        $ip = new Text(
          'ip',
          [
            'required'=>true,
            'placeholder'=>'Bot IP',
            'class'=>"form-control"
          ]
        );


        /**
         *
         * Port Input
         *
         */
        $port = new Numeric(
          'port',
          [
            'required'=>true,
            'placeholder'=>'Bot Port',
            'class'=>"form-control",
            'min'=>0,
            'max'=>65535,
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

        $this->add($name);
        $this->add($title);
        $this->add($note);
        $this->add($hostname);
        $this->add($ip);
        $this->add($port);
        $this->add($status);
    }


}