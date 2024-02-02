<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use DB;
use Illuminate\Support\Facades\Mail;


class Setting extends Model
{

    /**
     * Table name.
     *
     * @var array
     */
    protected $table = 'settings';



    /**
     * The attributes that are guarded.
     *
     * @var array
     */
    protected $guarded = [
    ];

    public function HubName()
    {
        return $this->belongsTo('App\FinanceVendorCity','hub_id');
    }

    public function sendEmail($subject,$email, $full_name,$message)
    {
        $style = "font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,Helvetica,Arial,sans-serif,'Apple Color Emoji','Segoe UI Emoji','Segoe UI Symbol';color: black !important;";
        $bg_img = 'background-image:url(' . url("/images/joeyco_icon_water.png") . ');';
        $bg_img = trim($bg_img);
        $body = '<div class="row" style=" width: 32%;margin: 0 AUTO;">
                <div style="text-align: center;
    background-color: lightgrey;"><img src="' . url('/') . '/images/abc.png" alt="Web Builder" class="img-responsive" style="margin:0 auto; width:150px;" /></div>
                <div style="' . $bg_img . '
    background-size: contain;
    background-repeat: no-repeat;
    background-position: center;">
                  <h1 style="' . $style . '">Hi, ' . $full_name . '!</h1>
            
                 <p style="' . $style . '">' .$message. ' </p>               
                  <br/>
               
               
                </div>
                <div style="background-color: lightgrey;padding: 5px;">
        <p style="padding-bottom: -1px;margin: 0px;margin-left: 20px;' . $style . '">JoeyCo Inc.</p>
        <p style="margin-top: 0x;margin: 0px;margin-left: 20px;' . $style . '">16 Four Seasons Pl., Etobicoke, ON M9B 6E5</p>
        <p style="margin: 0px;margin-left: 20px;' . $style . '">+1 (855) 556-3926 · support@joeyco.com </p>   
    </div>
                </div>
                ';
       // $subject = "Sorting Time Alert";
        Mail::send(array(), array(), function ($m) use ($email, $subject, $body) {
            $m->to($email)
                ->subject($subject)
                ->from(env('MAIL_USERNAME'))
                ->setBody($body, 'text/html');
        });
    }
}