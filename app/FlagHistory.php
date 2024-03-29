<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Mail;

class FlagHistory extends Model
{
    protected $table = 'flag_history';
    public $JsonValuesDecoded = [];

    /**
     * The attributes that are guarded.
     *
     * @var array
     */
    protected $guarded = [
    ];

    public function joeyName()
    {
        return $this->belongsTo(Joey::class,'joey_id','id');
    }

    public function flagByName()
    {
        return $this->belongsTo(User::class,'flaged_by','id');
    }

    public function Sprint()
    {
        return $this->belongsTo(Sprint::class,'sprint_id','id');
    }

    public function MerchantidsByTrackingID()
    {
        return $this->belongsTo(MerchantIds::class,'tracking_id','tracking_id');
    }

    public function JosnValuesDecode($return = 'all')
    {
        // checking the data is already decoded
        if(count($this->JsonValuesDecoded) <= 0)
        {
            $this->JsonValuesDecoded['finance'] = json_decode($this->finance_incident_value_applied,true);
            $this->JsonValuesDecoded['rating'] = json_decode($this->rating_value,true);
        }

        if($return != 'all')
        {
            return $this->JsonValuesDecoded[$return];
        }

        return $this->JsonValuesDecoded;

    }

    public static function unflagedBy($trackingId, $sprintId)
    {
        $joey_flags_history = FlagHistory::where('tracking_id',$trackingId)
            ->where('sprint_id', $sprintId)
            ->orderBy('id', 'DESC')
            ->where('unflaged_by','=',0)
            ->first();
        return $joey_flags_history;
    }

    //Function To Marked Un-Flag To Joeys
    public function sendUnFlagEmailToJoey($email, $joey_name ,$joey_flag)
    {
        if (empty($joey_flag['sprint_no']))
        {
            $message = 'You are receiving this email because Joeyco remove flag on you against this route number "' . $joey_flag['route_id'] . '"';
        }
        else
        {
            $message = 'You are receiving this email because Joeyco remove flag on you against this order number "' . $joey_flag['sprint_no'] . '"';
        }
        $bg_img = 'background-image:url(' . url("/images/joeyco_icon_water.png") . ');';
        $bg_img = trim($bg_img);
        $style = "font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,Helvetica,Arial,sans-serif,'Apple Color Emoji','Segoe UI Emoji','Segoe UI Symbol';color: black !important;";
        $style1 = "font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,Helvetica,Arial,sans-serif,'Apple Color Emoji','Segoe UI Emoji','Segoe UI Symbol';";
        $body = '<div class="row" style=" width: 32%;margin: 0 AUTO;">
                <div style="text-align: center;
    background-color: lightgrey;"><img src="' . url('/') . '/images/abc.png" alt="Web Builder" class="img-responsive" style="margin:0 auto; width:150px;" /></div>
                <div style="' . $bg_img . '
    background-size: contain;
    background-repeat: no-repeat;
    background-position: center;">
                  <h1 style="'.$style.'">Hi, ' . $joey_name->first_name .' '. $joey_name->last_name . '!</h1>
             <p style="'.$style.'">'.$message. '</p>
                <br/>
                 <br/>
                 
                </div>
                 <div style="background-color: lightgrey;padding: 5px;">
        <p style="padding-bottom: -1px;margin: 0px;margin-left: 20px;'.$style.'">JoeyCo Inc.</p>
        <p style="margin-top: 0x;margin: 0px;margin-left: 20px;'.$style.'">16 Four Seasons Pl., Etobicoke, ON M9B 6E5</p>
        <p style="margin: 0px;margin-left: 20px;'.$style.'">+1 (855) 556-3926 · support@joeyco.com </p>   
    </div>
                </div>
                ';
        $subject = "Order UnFlag Mail";
        $email = base64_decode($email);
        Mail::send(array(), array(), function ($m) use ($email, $subject, $body) {
            $m->to($email)
                ->subject($subject)
                ->from(env('MAIL_USERNAME'))
                ->setBody($body, 'text/html');
        });
    }
}

