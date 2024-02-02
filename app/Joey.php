<?php
namespace App;

use App\JoeyPlans;
use Illuminate\Database\Eloquent\Model;


class Joey extends Model
{
    protected $table = 'joeys';


    /**
     */

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id','plan_id','first_name','last_name','nickname','display_name','email','password','address','suite','buzzer',
        'city_id','state_id','country_id','postal_code','phone','image_path','image','about_yourself','about','preferred_zone',
        'hear_from','is_newsletter','is_enabled','updated_at','created_at','deleted_at','vehicle_id','comdata_emp_num','comdata_cc_num',
        'comdata_cc_num_2','pwd_reset_token','pwd_reset_token_expiry','is_busy','current_location_id','email_verify_token','is_online','balance',
        'location_id','hst_number','rbc_deposit_number','cash_on_hand','timezone','work_type','contact_time','interview_time','has_bag','is_backcheck',
        'on_duty','preferred_zone_id','shift_amount_due','is_on_shift','api_key','is_itinerary','hub_id','hub_joey_type','can_create_order','has_route'
    ];

public function getFullNameAttribute()
    {
        $full_name = $this->first_name.' '.$this->last_name;
        return ucfirst($full_name);
    }


    public function joeyBrooker()
    {
        return $this->belongsTo(BrookerJoey::class,'id', 'joey_id');
    }

    /**
     * Get joey brooker
     */
    public function Brooker()
    {

        return $this->belongsToMany(BrookerUser::class, 'brooker_joey', 'joey_id', 'brooker_id');
    }

    /**
     * Get joey plan.
     */
    public function plan()
    {
        return $this->belongsTo(joeyPlans::class,'plan_id', 'id');
    }

}