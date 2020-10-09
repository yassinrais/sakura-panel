<?php
namespace Sakura\Forms\Website;


// form elements
use Phalcon\Forms\Form;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\Select;

// controllers / models
use Sakura\Models\User\Users;
use Sakura\Forms\BaseForm;

// validation
use Phalcon\Validation\Validator\{ PresenceOf , StringLength, Regex, InclusionIn};

class ThemeFileForm extends BaseForm
{
    const THEME_FILES_TYPE = [
        "min.js"    => "Script .min.js",
        "min.css"   => "Style .min.css",
        "js"    => "Script .js",
        "css"   => "Style .css",
    ];
    public function initialize()
    {
    	parent::initialize();


        $name =  new Text(
            'name',
            [ 
                'placeholder'=>"test", 
                'class'=>"form-control" ,
                'required'=>'required',
            ]
        );
        $name->addValidator(
            new StringLength(
                [
                    'min'            => 1,
                    'max'            => 100,
                    'messageMinimum' => 'The :field is too short',
                    'messageMaximum' => 'The :field is too long',
                ]
            )
        );
        $name->addValidator(
            new Regex([
                'pattern' => "/^[a-zA-Z0-9_.-]{1,100}$/",
                'message' => 'The :field is invalid',
            ])
        );

        $type = new Select(
            'type',
            [
              'Select File Type'=>$this::THEME_FILES_TYPE
            ],
            [
              'required'=>true,
              'class'=>"form-control",
            ]
          );

        $type->addValidator(
            new InclusionIn(
                [
                    "message" => "The :field must be ". implode(",",$this::THEME_FILES_TYPE),
                    "domain"  => array_keys($this::THEME_FILES_TYPE),
                ]
            )
        );
  
        $this->add($name);
        $this->add($type);
    }


    public function getCsrf()
    {
        return $this->security->getToken();
    }

}