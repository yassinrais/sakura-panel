<?php
namespace SakuraPanel\Models\User;

use SakuraPanel\Models\ModelBase;

use Phalcon\Validation;
use Phalcon\Validation\Validator\{
    Email as EmailValidator ,
    Uniqueness ,
    Regex,
    InclusionIn
};


use SakuraPanel\Models\Security\Roles;

class Users extends ModelBase
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
     * Safe Delete
     */
    public $_safe_delete = true;

    /**
     * Validations and business logic
     *
     * @return boolean
     */
    public function validation()
    {
        $validator = new Validation();
        $roles = $this->getDI()->getAcl()->getRolesArray();

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

        $validator->add(
            'role_name',
            new InclusionIn(
                [
                    "message" => "The role must be ". implode(",",$roles),
                    "domain"  => array_keys($roles),
                ]
            )
        );

        $validator->add(
            'status',
            new InclusionIn(
                [
                    "message" => "The :field must be ". implode(",",$this::STATUS_LIST),
                    "domain"  => array_keys($this::STATUS_LIST),
                ]
            )
        );

        return $this->_ignore_validation || $this->validate($validator);
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

    /** 
     * Get User Role By Name    
     * @param string $role 
     * @return object $info 
     */
    public static function getRoleByName(string $name = null)
    {
        $info = (object) [
			'title'=>'Unknown',
			'icon'=>'info',
			'id'=>-2,
			'type'=>'info',
			'color'=>'info',
		];
		switch ($name) {
			
			case self::ROLE_ADMIN:
				$info->title = "Admin";
				$info->icon = "user-secret";
				$info->color = "danger";
				break;
			
			case self::ROLE_MEMBER:
				$info->title = "Member";
				$info->icon = "user-circle";
				$info->color = "success";
				break;
			
			case self::ROLE_GUEST:
				$info->title = "Guest";
				$info->icon = "user";
				$info->color = "warning";
				break;

            default :
                $info->title = (Roles::getRoleByName($name) ?: new Roles())->title;
                break;
		}

		return (object) $info;
    }
}