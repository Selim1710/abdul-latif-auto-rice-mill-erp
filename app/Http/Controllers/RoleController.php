<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Role;
use App\Models\Module;
use App\Models\ModuleRole;
use Illuminate\Http\Request;
use App\Models\PermissionRole;
use App\Http\Requests\RoleFormRequest;

class RoleController extends BaseController
{
    public function __construct(Role $model)
    {
        $this->model = $model;
    }

    public function index()
    {
        if(permission('role-access')){
            $setTitle = __('file.Role');
            $setSubTitle = __('file.Role Manage');
            $this->setPageData($setTitle,$setSubTitle,'fas fa-bars',[['name' => $setTitle]]);
            $deletable = self::DELETABLE;
            return view('role.index',compact('deletable'));
        }else{
            return $this->access_blocked();
        }
    }

    public function get_datatable_data(Request $request)
    {
        if($request->ajax()){
            if (!empty($request->role_name)) {
                $this->model->setRoleName($request->role_name);
            }

            $this->set_datatable_default_properties($request);//set datatable default properties
            $list = $this->model->getDatatableList();//get table data
            $data = [];
            $no = $request->input('start');
            foreach ($list as $value) {
                $no++;
                $action = '';
                if(permission('role-edit')){
                    $action .= ' <a class="dropdown-item" href="'.route('role.edit',['id'=>$value->id]).'">'.$this->actionButton('Edit').'</a>';
                }
                if(permission('role-view')){
                    $action .= ' <a class="dropdown-item" href="'.route('role.view',['id'=>$value->id]).'">'.$this->actionButton('View').'</a>';
                }
                if(permission('role-delete')){
                    if($value->deletable == 2){
                        $action .= ' <a class="dropdown-item delete_data"  data-id="' . $value->id . '" data-name="' . $value->role_name . '">'.$this->actionButton('Delete').'</a>';
                    }
                }

                $row = [];
                if(permission('role-bulk-delete')){
                    $row[] = ($value->deletable == 2) ? row_checkbox($value->id) : '';//custom helper function to show the table each row checkbox
                }
                $row[] = $no;
                $row[] = $value->role_name;
                $row[] = self::DELETABLE[$value->deletable];
                $row[] = action_button($action);//custom helper function for action button
                $data[] = $row;
            }
            return $this->datatable_draw($request->input('draw'),$this->model->count_all(),
             $this->model->count_filtered(), $data);
        }else{
            return response()->json($this->unauthorized());
        }
    }

    public function create()
    {
        if(permission('role-add')){
            $setTitle = __('file.Role');
            $setSubTitle = __('file.Role Create');
            $this->setPageData($setSubTitle,$setSubTitle,'fas fa-bars',[['name' => $setTitle,'link' => route('role')],['name' => $setSubTitle]]);
            $permission_modules = Module::permission_module_list();
            return view('role.form',compact('permission_modules'));
        }else{
            return $this->access_blocked();
        }
    }

    public function store_or_update_data(RoleFormRequest $request)
    {
        if($request->ajax()){
            if(permission('role-add') || permission('role-edit')){
                $collection = collect($request->validated());
                $role       = $this->model->updateOrCreate(['id'=>$request->update_id],$collection->all());
                if($role){
                    $role->module_role()->sync($request->module);
                    $role->permission_role()->sync($request->permission);
                }
                $output = $this->store_message($role, $request->update_id);
            }else{
                $output       = $this->unauthorized();
            }
            return response()->json($output);
        }else{
            return response()->json($this->unauthorized());
        }
    }

    public function edit(int $id)
    {
        if(permission('role-edit')){
            $this->setPageData('Role Edit','Role Edit','fas fa-bars',[['name' => 'Role','link' => route('role')],['name' => 'Role Edit']]);
            $role = $this->model->with('module_role','permission_role')->find($id);
            $role_module = [];
            if(!$role->module_role->isEmpty())
            {
                foreach ($role->module_role as $value) {
                    array_push($role_module,$value->id);
                }
            }
            $role_permission = [];
            if(!$role->permission_role->isEmpty())
            {
                foreach ($role->permission_role as $value) {
                    array_push($role_permission,$value->id);
                }
            }

            $data = [
                'role'            => $role,
                'role_module'     => $role_module,
                'role_permission' => $role_permission,
                'permission_modules' => Module::permission_module_list()
            ];

            return view('role.form',$data);
        }else{
            return $this->access_blocked();
        }
    }

    public function show(int $id)
    {
        if(permission('role-view')){
            $setTitle = __('file.Role');
            $setSubTitle = __('file.Role Details');
            $this->setPageData($setSubTitle,$setSubTitle,'fas fa-bars',[['name' => $setTitle,'link' => route('role')],['name' => $setSubTitle]]);
            $role = $this->model->with('module_role','permission_role')->find($id);
            $role_module = [];
            if(!$role->module_role->isEmpty())
            {
                foreach ($role->module_role as $value) {
                    array_push($role_module,$value->id);
                }
            }
            $role_permission = [];
            if(!$role->permission_role->isEmpty())
            {
                foreach ($role->permission_role as $value) {
                    array_push($role_permission,$value->id);
                }
            }

            $data = [
                'role'            => $role,
                'role_module'     => $role_module,
                'role_permission' => $role_permission,
                'permission_modules' => Module::permission_module_list()
            ];
            return view('role.view',$data);
        }else{
            return $this->access_blocked();
        }
    }

    public function delete(Request $request)
    {
        if($request->ajax()){
            if(permission('role-delete')){
                $role = $this->model->find($request->id);
                if(!$role->users->isEmpty()) {  $output = ['status'=>'error','message'=>$this->responseMessage('Related Data')];  }else{
                    $delete_module_role     = $role->module_role()->detach();
                    $delete_permission_role = $role->permission_role()->detach();
                    if($delete_module_role && $delete_permission_role){
                        $result = $role->delete();
                        $output = $this->delete_message($result);
                    }else{
                        $output = ['status'=>'error','message' => $this->responseMessage('Data Delete Failed') ];
                    }
                }
            }else{
                $output       = $this->unauthorized();
            }
            return response()->json($output);

        }else{
            return response()->json($this->unauthorized());
        }
    }

    public function bulk_delete(Request $request)
    {
        if($request->ajax()){
            if(permission('role-bulk-delete')){
                $delete_list = [];
                $undelete_list = [];
                foreach ($request->ids as $id) {
                    $role = $this->model->find($id);
                    if(!$role->users->isEmpty()){
                        array_push($undelete_list,$role->role_name);
                    }else{
                        array_push($delete_list,$id);
                    }
                }
                if(!empty($delete_list)){
                    $delete_module_role     = ModuleRole::whereIn('role_id',$delete_list)->delete();
                    $delete_permission_role = PermissionRole::whereIn('role_id',$delete_list)->delete();
                    if($delete_module_role && $delete_permission_role){
                        $result = $role->destroy($delete_list);
                        $output = $result ?  ['status'=>'success','message'=> $this->responseMessage('Selected Data Delete'). (!empty($undelete_list) ? $this->responseMessage('Expected Role').'('.implode(',',$undelete_list).')'.$this->responseMessage('Associated Other Data'): '')] : ['status'=>'error','message'=>$this->responseMessage('Data Delete Failed')];
                    }else{
                        $output = ['status'=>'error','message'=>$this->responseMessage('Data Delete Failed')];
                    }
                }else{
                    $output = ['status'=>'error','message'=> !empty($undelete_list) ? $this->responseMessage('Roles').'('.implode(',',$undelete_list).')'.$this->responseMessage('Related Data'): ''];
                }
            }else{
                $output    = $this->unauthorized();
            }
            return response()->json($output);
        }else{
            return response()->json($this->unauthorized());
        }
    }
}
