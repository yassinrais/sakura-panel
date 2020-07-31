<?php
declare(strict_types=1);

namespace SakuraPanel\Plugins\Products\Models;

use Phalcon\Validation;
use Phalcon\Validation\Validator\{ Between , Ip as IpValidator , PresenceOf , Email as EmailValidateur  , StringLength , Regex , Numericality , Callback  , InclusionIn };


class Products extends \ModelBase
{

    /**
     *
     * @var integer
     */
    public $id;

    /**
     *
     * @var integer
     */
    public $user_id;

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
    public $note;

    /**
     *
     * @var string
     */
    public $hostname;

    /**
     *
     * @var string
     */
    public $ip;

    /**
     *
     * @var integer
     */
    public $port;

    /**
     *
     * @var integer
     */
    public $status;

    /**
     *
     * @var integer
     */
    public $created_at;

    /**
     *
     * @var string
     */
    public $created_ip;

    /**
     *
     * @var integer
     */
    public $updated_at;

    /**
     *
     * @var string
     */
    public $updated_ip;

    /**
     *
     * @var string
     */
    protected $_safe_delete = true;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSource($this->getSourceByName("products"));
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
            'port',
            new Callback([
                'callback' => function($data) {
                    $p = $data->port;
                    return (is_numeric($p) && $p > 0 && $p <= 65535 && intval($p) == $p);
                },
                "message" => "The :field must be between ! 0-65535 allowed ",
            ])
        );

        $validator->add(
            'ip',
            new IpValidator(
            [
                "allowEmpty"    => true,

                "message"       => ":field must contain only ip addresses",
                "version"       => IpValidator::VERSION_4 | IpValidator::VERSION_6, // v6 and v4. The same if not specified
                "allowReserved" => true,   // False if not specified. Ignored for v6
                "allowPrivate"  => true,   // False if not specified
            ]
          )
        );

        $validator->add(
            'hostname',
            new Regex(
                [
                    "pattern"=> "/^(([a-zA-Z0-9]|[a-zA-Z0-9][a-zA-Z0-9\-]*[a-zA-Z0-9])\.)*([A-Za-z0-9]|[A-Za-z0-9][A-Za-z0-9\-]*[A-Za-z0-9](\.?))$/",
                    "message" => 'The :field is invalid',
                    "allowEmpty"    => true,
                ]
            )
        );


        $validator->add(
            'note',
            new StringLength(
                [
                    "min"=> 0,
                    "max"=> 500,
                    "messageMaximum" => "You cant use more then 5000 characters in note !",
                    "allowEmpty"=>true
                ]
            )
        );


        $validator->add(
            'title',
            new PresenceOf(
                [
                    'message' => 'The :field is required',
                ]
            )
        );


        $validator->add(
            'name',
            new PresenceOf(
                [
                    'message' => 'The :field is required',
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
     * @return Products[]|Products|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null): \Phalcon\Mvc\Model\ResultsetInterface
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Products|\Phalcon\Mvc\Model\ResultInterface
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
