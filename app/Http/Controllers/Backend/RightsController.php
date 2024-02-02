<?php

namespace App\Http\Controllers\Backend;


use Illuminate\Http\Request;
use App\Rights;
use App\RightPermissions;

use App\Http\Requests\Backend\RightCreateRequest;
use App\Http\Requests\Backend\RightUpdateRequest;

class RightsController extends BackendController
{

    private $RoleRepository;

    public function __construct()
    {

    }

    /**
     * Index action
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {


        $rights = Rights::where('is_delete',0)->groupBy('role_name')->get();
        
        // portals options
        $portals = Rights::$portals;

        return backend_view('rights.index',compact(
            'rights',
            'portals'
        ));
    }
    /**
     * Create action
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        // getting all portals static permissions
        $permissions_static_data = $this->getAllPortalsStaticPermissions();
        
        // portals options
        $portals = Rights::$portals;

        // dd($permissions_static_data['dashboard']);

        return backend_view('rights.create',compact(
            'permissions_static_data',
            'portals'
        ));
    }

    /**
     * store action
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function store(RightCreateRequest $request)
    {

        $data = $request->except(
            [
                '_token',
                '_method',
            ]
        );

        // creating selection data
        $selected_options = explode('_',$data['seletec_options']);
        foreach ($selected_options as $key => $value)
        {
            /*createing inserting data*/
                $create = [
                    'display_name' => $data['right_name'],
                    'role_name' => $data['slug_name'],
                    'type' => $data['portal_name'][$key],
                ];
                /*inserting data*/
                $right = Rights::create($create);
            // creating permissions
            if(isset($data[$value.'_permissions'])){
                $this->setPermissionsUpdate($data[$value.'_permissions'],$right->id);
            }
        }



        /*return data */
        session()->flash('alert-success', 'Right has been created successfully!');
        return redirect()
            ->route('right.index');


    }

    /**
     * show action
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($id)
    {
        $right_id = base64_decode($id);
        $rights = Rights::with('GetAttachedPlans')
            ->where('id',$right_id)
            ->where('is_delete',0)
            ->first();

        // getting dashnboard card permissions
        $permissions_static_data = $this->getAllPortalsStaticPermissions();

        // portals options
        //$portals = Rights::$portals[$rights->type];
        $portals = Rights::$portals;

        // creating selected permissions array
        $selected_permissions = [];
        $selected_options = [];
        foreach($rights->GetAttachedPlans as $key => $single_right_data)
        {
            //updating selected options
            $selected_options[] = $single_right_data->type;

            $selected_permissions_key = strtolower($portals[$single_right_data->type]);
            $selected_permissions[$selected_permissions_key] = $single_right_data->Permissions->pluck('route_name')->toArray();
        }

        return backend_view('rights.show',compact(
            'rights',
            'permissions_static_data',
            'portals',
            'selected_permissions',
            'selected_options'
        ));
    }


    /**
     * edit action
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        $right_id = base64_decode($id);
        $rights = Rights::with('GetAttachedPlans')
        ->where('id',$right_id)
        ->where('is_delete',0)
        ->first();

        // getting dashnboard card permissions
        $permissions_static_data = $this->getAllPortalsStaticPermissions();

        // portals options
        //$portals = Rights::$portals[$rights->type];
        $portals = Rights::$portals;

        // creating selected permissions array
        $selected_permissions = [];
        $selected_options = [];
        foreach($rights->GetAttachedPlans as $key => $single_right_data)
        {
            //updating selected options
            $selected_options[] = $single_right_data->type;

            $selected_permissions_key = strtolower($portals[$single_right_data->type]);
            $selected_permissions[$selected_permissions_key] = $single_right_data->Permissions->pluck('route_name')->toArray();
        }

//        // get right associate permission
//        $associate_permission = $rights->Permissions->pluck('route_name')->toArray();


        return backend_view('rights.edit',compact(
            'rights',
            'permissions_static_data',
            'portals',
            'selected_permissions',
            'selected_options'
//            'associate_permission'
        ));
    }


    /**
     * update action
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(RightUpdateRequest $request,$Rights)
    {
        //dd($Rights,$request->all());

        //$rights = Rights::where('id',$Rights)->where('is_delete',0)->first();

        $data = $request->except(
            [
                '_token',
                '_method',
            ]
        );

        // getting old seleceted options and new
        $old_selected_options = explode('_',$data['old_seletec_options']);
        $new_selected_options = explode('_',$data['seletec_options']);
        $new_add_oprions = array_diff($new_selected_options,$old_selected_options);
        $remove_selections = array_diff($old_selected_options,$new_selected_options);


        //dd($old_selected_options,$new_selected_options,$new_add_oprions,$remove_selections,$data);
        // creating or updating existing options
        foreach ($new_selected_options as $key => $value)
        {
            $updating_id = (isset($data[$value.'_edit_id']))? $data[$value.'_edit_id'] : 0;
            $right = null;
            // now checking the selection is already exist or it is new one
            if($updating_id > 0 )
            {
                $right = Rights::where('id',$updating_id)->where('is_delete',0)->first();
            }
            else
            {
                $right = new Rights;
            }

            /*createing inserting data*/

            $right->display_name = $data['right_name'];
            $right->role_name = $data['slug_name'];
            $right->type = $data['portal_name'][$key];
            $right->save();

            // creating permissions
            if(isset($data[$value.'_permissions'])){
                $this->setPermissionsUpdate($data[$value.'_permissions'],$right->id);
            }
        }

        // deleteing remove sections
        foreach ($remove_selections as $key => $remove_selection)
        {
            $removeing_id = (isset($data[$remove_selection.'_edit_id']))? $data[$remove_selection.'_edit_id'] : 0;
            Rights::where('id',$removeing_id)->update(["is_delete"=> 1]);
            RightPermissions::where('role_id',$removeing_id)->update(['is_delete'=>1]);
        }

        return redirect()
            ->route('right.index')
            ->with('alert-success', 'Right updated successfully');

    }

    private function getAllPortalsStaticPermissions($type = 'all')
    {
//        $data = ["dashboard"=>config('permissions'),"finance"=>config('permissions1')];
//        $dataEdit = ["joeyco_dashboard"=>config('permissions'),"finance_dashboard"=>config('permissions1')];
//        $return = [];
//
//        if($portal != 'all')
//        {
//            $name = ["joeyco_dashboard"=>'dashboard','finance_dashboard'=>'finance'];
//            $return = [$name[$portal] =>$dataEdit[$portal]];
//        }
//        else
//        {
//            $return =$data;
//        }
//        return $return;

        $records = [];
        if (in_array($type, ['all','joeyco_dashboard'])) {
            $records['dashboard'] =  config('permissions');
        }
        if (in_array($type, ['all','joeyco_routing'])) {
            $records['routing'] =  config('routing_permissions');
        }
        if (in_array($type, ['all','joeyco_admin'])) {
            $records['admin'] =  config('admin_permissions');
        }
        if (in_array($type, ['all','finance_dashboard'])) {
            $records['finance'] = config('finance_permissions');
        }
        if (in_array($type, ['all','onboarding'])) {
            $records['onboarding'] = config('onboarding_permissions');
        }
        if (in_array($type, ['all','attendance'])) {
            $records['attendance'] = config('hr_permissions');
        }
        if (in_array($type, ['all','claim'])) {
            $records['claim'] = config('claim_permissions');
        }
        if (in_array($type, ['all','fresh_desk'])) {
            $records['fresh-desk'] = config('freshdesk_permissions');
        }
        if (in_array($type, ['all','fresh_caller'])) {
            $records['fresh-caller'] = config('freshcaller_permissions');
        }
        if (in_array($type, ['all','slack_group'])) {
            $records['slack-group'] = config('slack_group_permissions');
        }
        if (in_array($type, ['all','universal_slack_group'])) {
            $records['universal-slackgroup'] = config('universal_slack_permissions');
        }
        if (in_array($type, ['all','indeed'])) {
            $records['indeed'] = config('indeed');
        }
        if (in_array($type, ['all','park_time'])) {
            $records['park-time'] = config('park_time');
        }
        if (in_array($type, ['all','email'])) {
            $records['email'] = config('email');
        }
        if (in_array($type, ['all','facebook'])) {
            $records['facebook'] = config('facebook');
        }
        if (in_array($type, ['all','linkedin'])) {
            $records['linkedin'] = config('linkedin');
        }

        return $records;
    }

    private function setPermissionsUpdate($permissions,$role)
    {
        // now creating insert data of permissions
        $insert_permissions = [];


        foreach($permissions as $role_permission)
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
        $delete = RightPermissions::where('role_id',$role)->update(['is_delete'=>1]);

        //inserting new data
        $crate_permissions = RightPermissions::insert($insert_permissions);

        return $crate_permissions;

    }

    public function rightDuplicate(Request $request)
    {

        $slug = $request->get('right_name');
        $checkRoleName = Rights::where('role_name', $request->get('role_name'))->get(['type','id']);
        foreach ($checkRoleName as $check){
            $slug = $slug.'_';
            $checkDisplayName = Rights::where('type', $check->type)->where('display_name', $request->get('right_name'))->first();
            if($checkDisplayName){
                return json_encode(['error' => 'Right name already exists']);
            }
            $slug = $slug.$check->type;
        }

        foreach($checkRoleName as $right){
            $rightsData = [
                'display_name' => $request->get('right_name'),
                'role_name' => $slug,
                'type' => $right->type
            ];
            $insertRight = Rights::create($rightsData);

            $permissions = RightPermissions::where('role_id',$right->id)->get();

            foreach($permissions as $permission){

                RightPermissions::create([
                    'role_id' => $insertRight->id,
                    'route_name' => $permission->route_name
                ]);
            }

        }




//        $roleName = [];
//        foreach($request->get('portal_name') as $key => $portal){
//            $roleName[] = $request->get('right_name').'_'.$portal;
//        }
//        var_dump($roleName);
//        dd('test');

//        $roleName = $request->get('right_name');
//
//        Rights::create([
//            'display_name' => $request->get('right_name'),
////            'role_name' => $request->get('slug_name'),
//            'type' => $request->get('portal_name')[0],
//        ]);
//
//        $rightsPermission = RightPermissions::where('role_id',$request->get('right_id'))->get();
//
//        foreach($rightsPermission as $permission){
//            RightPermissions::create([
//                'role_id' => $permission->role_id,
//                'route_name' => $permission->route_name,
//            ]);
//        }

        return json_encode(['message' => 'success']);
    }


}
