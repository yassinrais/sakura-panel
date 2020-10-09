<?php
namespace SakuraPanel\Library;

use Phalcon\Acl\Adapter\Memory;



class RoleMemory extends Memory {


    public function getInherits(String $role = null) : Array {
        if (!empty($this->roleInherits[$role]))
            return $this->roleInherits[$role];

        return [];
    }

    /** 
     * return array of roles (name:description)
     * @return $roles | Array
     */
    public function getRolesArray() : Array {
        $roles = [];

        foreach($this->getRoles() as $i=> $r)
          $roles[$r->getName()] = $r->getDescription() ?: ucfirst($r->getName());

        return $roles;
    }
}