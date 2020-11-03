<?php
declare (strict_types = 1);

namespace Sakura\Controllers\Admin;

use Sakura\Controllers\Member\MemberControllerBase;

use Sakura\Models\Acl\{
    Permissions,
    Roles,
    Accesses,
    Resources
};

use Sakura\Library\Datatables\DataTable;

/**
 * Permissions
 */
class PermissionsController extends MemberControllerBase
{
    // Implement common logic
    public function initialize()
    {

        parent::initialize();

        $this->page->set('title', 'Permissions');
        $this->page->set('description', 'Manage Roles Permissions & Access.');
        $this->page->set('base_route', 'admin/permissions');
        $this->view->dataTable = true;

    }

    public function indexAction()
    {
        $this->view->roles = Roles::find();
        return $this->view->pick('admin/permissions/index');
    }

    public function ajaxAction()
    {
        // 3 roles 
        // 2 resource
        // 2 action 

        // line :: 1 resource - 1 action - 3 roles
        // line :: 1 resource - 1 action - 3 roles
        $roles = Roles::find();

        $perClass = Permissions::class;

        $roleCols = [];
        foreach($roles as $role)
            $roleCols[] = "( SELECT allowed from $perClass as p WHERE p.role_id = {$role->id} AND p.access_id = a.id AND p.resource_id = r.id) as role_{$role->name}";

        $builder = $this->modelsManager->createBuilder()
            ->columns(
                implode(",", 
                    array_merge(
                        ['r.id as r_id,a.id as a_id, r.description as resource, a.description as access'],
                        $roleCols
                    )
                )
            )
            ->from(['r'=>Resources::class])
            ->join(Accesses::class, null, 'a')
            ;

        $dataTables = new DataTable();
        $dataTables->setIngoreUpperCase(true);
        $dataTables->fromBuilder($builder);

        foreach($roles as $role)
            $dataTables->addCustomColumn("role_{$role->name}", function($key,$data) use ($role){
                $allowed = $data["role_{$role->name}"] ? "checked" : "";
                return "<div name='{$role->id}:{$data['r_id']}:{$data['a_id']}' class='custom-control custom-switch'><input type='checkbox' $allowed class='custom-control-input' id='customSwitch{$key}{$role->name}'><label class='custom-control-label' for='customSwitch{$key}{$role->name}'>Allow</label></div>";
            });

        return $dataTables->sendResponse();
    }

}
