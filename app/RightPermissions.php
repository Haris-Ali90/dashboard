<?php

namespace App;


use Illuminate\Foundation\Auth\User as Authenticatable;

class RightPermissions extends Authenticatable
{

    /**
     * Table name.
     *
     * @var array
     */
    public $table = 'right_permissions';

    /**
     * The attributes that are guarded.
     *
     * @var array
     */
    protected $guarded = [
    ];


    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [];


    /**
     * Get all the permissions list from config file.
     *
     * @var array
     */

    function scopeGetAllPermissions()
    {
        return config('permissions');
    }

    /**
     * Get all the permissions list from config file.
     *
     * @var array
     */

    function scopeGetAllDashboardCardPermissions()
    {
        return config('dashboard-cards-permissions');
    }


    /**
     * Get all the permissions Super Admin role id from config file.
     *
     * @var array
     */

    function scopeGetSuperAdminRole()
    {
        return config('app.super_admin_role_id');
    }


}
