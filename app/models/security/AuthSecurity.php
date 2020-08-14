<?php
namespace SakuraPanel\Models\Security;


class AuthSecurity extends \ModelBase
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
    public $attemps;

    /**
     *
     * @var integer
     */
    public $suspend;

    /**
     *
     * @var integer
     */
    public $banned;

    /**
     *
     * @var string
     */
    public $ip;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSource($this->getSourceByName("auth_security"));
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return AuthSecurity[]|AuthSecurity|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null): \Phalcon\Mvc\Model\ResultsetInterface
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return AuthSecurity|\Phalcon\Mvc\Model\ResultInterface
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

    public function beforeCreate()
    {
        parent::beforeCreate();

        // remove caches 
        self::removeCache();
    }

    public function beforeUpdate()
    {
        parent::beforeUpdate();

        // remove caches 
        self::removeCache();
    }

    public function removeCache()
    {
        if ($this->getDI()->getSecurityCache()->has(md5($this->ip)))
            $this->getDI()->getSecurityCache()->delete(md5($this->ip));
    }


    /**
     * Method to check if ip is banned
     * @param string $ip
     * @return mixed : AuthSecurity|\Phalcon\Mvc\Model\ResultInterface / bool
     */
    public static function isIpBanned($ip){
        $ip = $ip ?: -1;
        
        $row = parent::findFirst([
            'ip = ?0 AND banned = ?1',
            'bind'=>[
                $ip,
                self::ACTIVE
            ]
        ]);
        return $row ?: false;
    }

    /**
     * Method to check if ip is suspended
     * @param string $ip
     * @return mixed : AuthSecurity|\Phalcon\Mvc\Model\ResultInterface / bool
     */
    public static function isIpSuspend($ip){
        $ip = $ip ?: -1;
        
        return parent::findFirst([
            'ip = ?0 AND suspend = ?1',
            'bind'=>[
                $ip,
                self::ACTIVE
            ]
        ]) ?: false;
    }

    /**
     * Method to increase the attemps counter of user
     * @param string $ip
     * @return bool $save
     */
    public static function increaseAttempsByIp($ip){
        $ip = $ip ?: -1;
        
        $row = parent::findFirst([
            'ip = ?0 ',
            'bind'=>[
                $ip
            ]
        ]) ?: new AuthSecurity();

        $row->ip = $ip;

        if (($row->attemps++) > 1) {
            $configs = $row->getDI()->getConfig()->security;

            if (($row->attemps - $row->last_attemps) >= $configs->auth_suspend_max_attemps){
                $row->suspend = self::ACTIVE;
                $row->last_attemps = $row->attemps;
            }

            if ($row->attemps >= $configs->auth_banned_max_attemps)
                $row->banned = self::ACTIVE;
        }

        
        return $row->save();
    }
    /**
     * Method to delete the attemps counter of user
     * @param string $ip
     * @return bool $save
     */
    public static function deleteByIp($ip){
        $ip = $ip ?: -1;
        
        $row = parent::findFirst([
            'ip = ?0 ',
            'bind'=>[
                $ip
            ]
        ]) ;

        if ($row) 
            return $row->delete();
        
        return false;
    }
}
