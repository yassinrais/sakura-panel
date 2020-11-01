<?php 

namespace Sakura\Library\Widgets;

class Widget {

    private $partial;
    private $permissions = []; // example: ['admins','moderators','members'] :: >< roleName
    private $toggle = false;


    /** 
     * Init The Widget Configs
     * @param $partial : String
     */
    function __construct(String $partial) 
    {
        $this->partial = $partial;
    }

    /** 
     * Set Permissions
     * @param $permissions : Array
     */
    public function setPermissions(Array $permissions) : self
    {
        $this->permissions = $permissions;
        
        return $this;
    }

    /**
     * Set the value of partial
     *
     * @return  self
     */ 
    public function setPartial($partial) : self
    {
        $this->partial = $partial;

        return $this;
    }

    /**
     * Get the value of partial
     */ 
    public function getPartial() : String
    {
        return $this->partial;
    }

    /**
     * Get the value of toggle
     */ 
    public function getToggle() : bool
    {
        return $this->toggle;
    }

    /**
     * Set the value of toggle
     *
     * @return  self
     */ 
    public function setToggle(bool $toggle) : self
    {
        $this->toggle = $toggle;

        return $this;
    }


    /** 
     * Has Permissions
     * @return $has bool
     */
    public function hasPermissions(String $permission)
    {
        return in_array($permission , $this->permissions);
    }
}