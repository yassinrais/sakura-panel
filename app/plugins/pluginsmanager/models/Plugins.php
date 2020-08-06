<?php
declare(strict_types=1);

namespace SakuraPanel\Plugins\PluginsManager\Models;

use Phalcon\Validation;
use Phalcon\Validation\Validator\{ Between , Ip as IpValidator , PresenceOf , Email as EmailValidateur  , StringLength , Regex , Numericality , Callback  , InclusionIn };


class Plugins extends \ModelBase
{

    
    /**
     *
     * @var integer
     */
    public $id;

    /**
     *
     * @var string
     */
    public $name;

    /**
     *
     * @var string
     */
    public $title;

    /**
     *
     * @var string
     */
    public $description;

    /**
     *
     * @var string
     */
    public $author;

    /**
     *
     * @var string
     */
    public $version;

    /**
     *
     * @var string
     */
    public $image;

    /**
     *
     * @var integer
     */
    public $installed = 0;

    /**
     *
     * @var integer
     */
    public $status;

    /**
     *
     * @var integer
     */
    public $created_at = 0;

    /**
     *
     * @var string
     */
    public $created_ip;

    /**
     *
     * @var integer
     */
    public $updated_at = 0;

    /**
     *
     * @var string
     */
    public $updated_ip;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSource($this->getSourceByName("plugins"));
    }

    /**
     * Validations and business logic
     *
     * @return boolean
     */
    public function validation()
    {
        $validator = new Validation();


        if (!empty($this->_safe_delete) &&  $this->_safe_delete === true ) 
            return true;

        
        $validator->add(
            'description',
            new StringLength(
                [
                    "min"=> 0,
                    "max"=> 5000,
                    "messageMaximum" => "You cant use more then 5000 characters in description !",
                    "allowEmpty"=>true
                ]
            )
        );

        $validator->add(
            'name',
            new Regex(
                [
                  "pattern" => "/^[a-z0-9]*([a-z0-9][_-]{0,1})*[a-z0-9]$/",
                  "message" => 'The :field is invalid',
                ]
            )
        );

        $validator->add(
            'status',
            new InclusionIn( // iknow its exclu not inclu
                [
                    "message" => "The status must be " . implode("/",  $this::STATUS_LIST),
                    "domain"  => array_keys($this::STATUS_LIST)
                ]
            )
        );

        return $this->validate($validator);
    }
    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Plugins[]|Plugins|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null): \Phalcon\Mvc\Model\ResultsetInterface
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Plugins|\Phalcon\Mvc\Model\ResultInterface
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
