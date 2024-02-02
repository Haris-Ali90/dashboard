<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\JoeyPlanDetails;
use App\Joey;

class JoeyPlans extends Model
{


    const JoeysPlanTypes = [
        'per_drop_plan' => ['default|default_custom_routing|default_big_box','sub_contractor|sub_contractor_custom_routing|sub_contractor_big_box','brooker|brooker_custom_routing|brooker_big_box'],
        'by_duration'=> ['sub_hourly|downtown_hourly|sub_hourly_custom_routing|downtown_hourly_custom_routing|sub_hourly_big_box|downtown_hourly_big_box','low|high'],
        'by_area_per_drop'=> ['sub_per_drop|downtown_per_drop|sub_per_drop_custom_routing|downtown_per_drop_custom_routing|sub_per_drop_big_box|downtown_per_drop_big_box'],
        'bracket_plan'=> ['bracket_pricing|per_drop|custom_route|big_box','bracket_pricing|hourly|custom_route|big_box'],
        'dynamic_section'=>['group_zone_pricing_per_drop|custom_route|big_box'],
		'dynamic_section_zone'=>['group_zone_pricing_per_hour|custom_route|big_box'],

    ];

    /**
     * Table name.
     *
     * @var array
     */
    public $table = 'joey_plans';

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

    public $timestamps = true;

    /**
     * Get Vehicle Using Type.
     *
     * @return string
     */
    public function getVehicleUsingTypeNameAttribute()
    {
        if($this->vehicle_using_type == 0)
        {
            return 'Using Personal Vehicle';
        }
        elseif($this->vehicle_using_type == 1)
        {
            return "Using JoeyCo Vehicle";
        }

        return 'Not set yet';
    }


    /**
     * Can view_all_orders geter.
     *
     * @return string
     */
    public function getCanViewAllOrdersAttribute()
    {
        if($this->view_all_orders == 0)
        {
            return 'No';
        }
        elseif($this->view_all_orders == 1)
        {
            return "Yes";
        }

        return 'Not set yet';
    }

    /**
     * has minimum hourly rate status.
     *
     * @return string
     */
    public function getHasMinimumHourlyStatusAttribute()
    {
        if($this->has_minimum_hourly == 0)
        {
            return 'No';
        }
        elseif($this->has_minimum_hourly == 1)
        {
            return "Yes";
        }

        return 'Not set yet';
    }


    public function getPlanDetailNamesAttribute()
    {
        $data = $this->plan_type;
        $data = preg_replace('/[^a-z0-9A-Z&]+/',' ',str_replace('|',' & ',$data));
        return trim(ucwords($data));
    }

    /**
     * Get plans details
     */

    public function PlanDetails()
    {
        return $this->hasMany(JoeyPlanDetails::class,'joey_plan_id','id');
    }

    /**
     * Get plans details
     */

    public function joeys()
    {
        return $this->hasMany(Joeys::class,'plan_id','id');
    }

    public function scopeNotDefaultPlan($query)
    {
        return $query->where('plan_type', '!=','default|default_custom_routing|default_big_box');
    }



}
