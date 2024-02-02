<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;

class ValidationRuleServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        // custom validation rule
        Validator::extend('check_between_date_range', function($attribute, $value, $parameters)
        {
          $from_date = $parameters[1];
          $to_date = $value;
          $range = $parameters[0];
          /*Check Validation For Date Range*/
          $interval = date_diff(date_create($from_date), date_create($to_date));

          if ($interval->days > $range) {
            return false;
          }
          return true;

        });

        // creating message
        Validator::replacer('check_between_date_range', function($message, $attribute, $rule, $parameters) {

            return 'The date range selected must be less then or equal to '.$parameters[0].' days';

        });
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }


}
