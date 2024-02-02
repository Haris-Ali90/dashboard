<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use DB;

class Ctc extends Authenticatable
{
    protected $table = 'ctc_dashboard';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'sprint_id','vendor_id','joey','store_name','customers_name','suite','weight','address','postal_code','city_name','image','dropoff_eta','tracking_id','address_line2','pickup_from_store','at_hub_processing','out_for_delivery','delivery_time','sprint_status','task_status','old_sprint','reattempts_left','hubreturned3','hubpickup3','deliver3','deliver2','hubpickup2','hubpickup','hubreturned2','hubreturned','created_at','deleted_at','updated_at','notes'
    ];

    protected $appends = ['route_id'];

    public function GetRouteIdAttribute(){

        $record = Task::select(\Illuminate\Support\Facades\DB::raw("CONCAT('R-',joey_route_locations.route_id,'-',joey_route_locations.ordinal) AS route_id"))
            ->join('joey_route_locations','joey_route_locations.task_id','=','sprint__tasks.id')
            //->where('sprint__tasks.type','=','pickup')
            ->where('sprint__tasks.sprint_id','=',$this->sprint_id)
            ->first();

        return !empty($record)?$record['route_id']:'';
    }


}
