<?php

namespace App\Http\Controllers\Backend;

use App\Http\Requests\Backend\UpdateRoleRequest;
use Illuminate\Http\Request;

use App\Roles;
use App\Permissions;
use App\User;
use App\Http\Requests\Backend\RoleRequest;


class RoleController extends BackendController
{

    private $RoleRepository;

    public function __construct()
    {
        //        $this->middleware('auth:admin');
        //        parent::__construct();
        //        $this->RoleRepository = $RoleRepositoryInterface;
    }
    public function index()
    {

        $Roles =  Roles::where('id', '!=' , Permissions::SUPER_ADMIN_ROLE_ID)
                         ->where( 'type' , Roles::ROLE_TYPE_NAME)
                         ->orderBy('display_name','ASC')
                         ->get();

        /*dd($Roles);*/

        return backend_view('Roles.role',compact(
            'Roles'
        ));
    }
    public function create()
    {
 
        // getting dashnboard card permissions
        // $dashboard_card_permissions = Permissions::GetAllDashboardCardPermissions();

        return backend_view('Roles.roleadd');
    }

    public function store(RoleRequest $request)
    {
//        dd('dsdss');

        $data = $request->except(
            [
                '_token',
                '_method',
            ]
        );

        /*including dashboard cards rights*/
//        $dashboard_cards_rights = '';
//        if(isset($data['dashboard_card_permission']))
//        {
//            $dashboard_cards_rights = implode(",",$data['dashboard_card_permission']);
//        }

        /*createing inserting data*/
        $create = [
            'display_name' => $data['display_name'],
            'role_name' => SlugMaker($data['display_name']),
            'type' => Roles::ROLE_TYPE_NAME,
//            'dashbaord_cards_rights' => $dashboard_cards_rights,
        ];

        /*inserting data*/
        /*$this->RoleRepository->create($create);*/
        Roles::create($create);

        /*return data */
        session()->flash('alert-success', 'Role has been created successfully!');
        return redirect()
            ->route('role.create');
            


    }

    /**
     * show action
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($id)
    {
        $role_id = base64_decode($id);
        $role = Roles::where(['id' => $role_id])->get();

        $role = $role[0];
        $permissions =  config('permissions');//Permissions::GetAllPermissions();
        $route_names = $role->Permissions->pluck('route_name')->toArray();
        //dd($permissions,$role);

        return backend_view('Roles.show',compact(
            'role',
            'route_names',
            'permissions'
        ));
    }


    /**
     * edit action
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        $role_id = base64_decode($id);
        $role = Roles::find($role_id);


        // getting dashnboard card permissions
        /*$dashboard_card_permissions = Permissions::GetAllDashboardCardPermissions();*/
        return backend_view('Roles.roleedit',compact(
            'role'
        ));
    }


    /**
     * update action
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function update(UpdateRoleRequest $request,Roles $role)
    {
        /*getting all requests data*/
        $Postdata = $request->all();

        /*including dashboard cards rights*/
        /*$dashboard_cards_rights = '';
        if(isset($Postdata['dashboard_card_permission']))
        {
            $dashboard_cards_rights = implode(",",$Postdata['dashboard_card_permission']);
        }*/

        /*creating updating data*/
        $update_data = [
            'display_name' => $Postdata['display_name'],
            'role_name' => SlugMaker($Postdata['display_name']),
            'type' => Roles::ROLE_TYPE_NAME,
            /*'dashbaord_cards_rights' => $dashboard_cards_rights,*/
        ];


        /*updating data*/
        $role->update($update_data);
        /*return data */
        return redirect()
            ->route('role.index')
            ->with('success', 'Role updated successfully');

    }


    public function setPermissions(Roles $role)
    {
//        dd('dfds');
        // getting permissions
        $permissions_list = config('permissions');//Permissions::getAllPermissions();

        //dd($permissions_list);
        return backend_view('Roles.set-permissions',compact(
            'role',
            'permissions_list'
        ));
    }

    public function setPermissionsUpdate(Request $request,$role)
    {
        // now creating insert data of permissions
        $insert_permissions = [];

        $role_permissions = ($request->permissions != null) ? $request->permissions :[];

        foreach($role_permissions as $role_permission)
        {
            if(strpos($role_permission, '|') !== false)
            {
                foreach(explode('|',$role_permission) as $child_permission )
                {
                    $insert_permissions[] =['route_name'=> $child_permission, 'role_id'=>$role];
                }
            }
            else
            {
                $insert_permissions[] = ['route_name'=> $role_permission, 'role_id'=>$role];
            }

        }

        // deleting old data
        //$delete = Permissions::where('role_id',$role)->delete();
		$delete = Permissions::where('role_id',$role)->update([
            'is_delete' => 1
        ]);

        //inserting new data
        $crate_permissions = Permissions::insert($insert_permissions);

        /*return data */
        return redirect()
            ->route('role.index')
            ->with('success', 'Role permissions updated successfully');

    }


}
