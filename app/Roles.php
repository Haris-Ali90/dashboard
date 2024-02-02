<?php

namespace App;

use App\Scopes\RoleTypeScope;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Permissions;

class Roles extends Authenticatable
{

    const ROLE_TYPE_NAME = 'joeyco_dashboard';


    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new RoleTypeScope());
    }


    /**
     * Table name.
     *
     * @var array
     */
    public $table = 'roles';

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
     * Get the users by roles.
     *
     * @return array
     */

    public function User()
    {
        return $this->hasMany(User::class, 'role_type','id');
    }

    /**
     * Get the users by roles.
     *
     * @return array
     */

    public function Permissions()
    {
        return $this->hasMany(Permissions::class, 'role_id','id')->where('is_delete', 0);
    }

    /**
     * Get the dashboard all rights as array.
     *
     * @return string
     */
    public function getDashboardCardRightAttribute()
    {
        return explode(',',$this->dashbaord_cards_rights);
    }

    /**
     * Get the dashboard all rights count.
     *
     * @return string
     */
    /*public function getDashboardCardRightCountAttribute()
    {
        $data = $this->dashbaord_cards_rights;
        if($data != null && $data != '')
        {
            return count(explode(',',$this->dashbaord_cards_rights));
        }

        return 0;

    }*/


    /**
     * Scope a query to only include not super admin role
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeNotAdminRole($query)
    {
        $admin_role_id = Permissions::SUPER_ADMIN_ROLE_ID; //config('app.super_admin_role_id');
        return $query->where('id', '!=' ,$admin_role_id);
    }




}
