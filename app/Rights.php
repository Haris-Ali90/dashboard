<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Rights extends Model
{


    /**
     * Table name.
     *
     * @var array
     */
    public $table = 'rights';

    public static $portals = [
        "joeyco_dashboard"=>'Dashboard',
        "joeyco_routing"=>'Routing',
        "joeyco_admin"=>'Admin',
        "finance_dashboard"=>'Finance',
        "onboarding"=>'Onboarding',
        "attendance"=>'Attendance',
        "claim"=>'Claim',
        "fresh_desk" => 'Fresh-Desk',
        "fresh_caller" => "Fresh-Caller",
        "universal_slack_group" => "Universal-SlackGroup",
        "indeed" => "Indeed",
        "park_time" => "Park-Time",
        "email" => "Email",
        "facebook" => "Facebook",
        "linkedin" => "Linkedin",
    ];

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

    public function Permissions()
    {
        return $this->hasMany(RightPermissions::class, 'role_id','id')->where('is_delete',0);
    }

    /**
     * @return array
     */

    public function GetAttachedPlans()
    {
        return $this->hasMany(self::class, 'role_name','role_name')->where('is_delete',0);
    }







}
