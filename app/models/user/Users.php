<?php
namespace SakuraPanel\Models\User;

use Phalcon\Validation;
use Phalcon\Validation\Validator\{
    Email as EmailValidator ,
    Uniqueness ,
    Regex
};

class Users extends \ModelBase
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
    public $username;

    /**
     *
     * @var string
     */
    public $email;

    /**
     *
     * @var string
     */
    public $password;

    /**
     *
     * @var string
     */
    public $role_name;

    /**
     *
     * @var string
     */
    public $fullname;

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
     * Validations and business logic
     *
     * @return boolean
     */
    public function validation()
    {
        $validator = new Validation();

        $validator->add(
            'email',
            new EmailValidator(
                [
                    'model'   => $this,
                    'message' => 'Please enter a correct email address',
                ]
            )
        );
        $validator->add(
            'email',
            new Uniqueness(
                [
                    'model'   => $this,
                    'message' => 'This email address already used !',
                ]
            )
        );

        $validator->add(
            'fullname',
            new Regex(
                [
                    'message' => 'The full name must contains alphanumeric characters',
                    'pattern' => '/^[a-zA-Z]{4,}(?: [a-zA-Z]+){0,2}$/',
                ]
            )
        );

        return $this->validate($validator);
    }

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSource($this->getSourceByName("users"));
        
        $this->keepSnapshots(true);
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Users[]|Users|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null): \Phalcon\Mvc\Model\ResultsetInterface
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Users|\Phalcon\Mvc\Model\ResultInterface
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

    public function beforeUpdate()
    {
        parent::beforeUpdate();
        if ($this->hasChanged('password')) 
            $this->password = $this->getDI()->getSecurity()->hash($this->password);
    }

    public function beforeCreate()
    {
        parent::beforeCreate();
        $this->password = $this->getDI()->getSecurity()->hash($this->password);
    }

}