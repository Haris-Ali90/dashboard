<?php

namespace App\Helpers;

class RESTAPIHelper {

    public static function response($output = array(), $status = 'Success', $dev_message = 'Success', $format = 'json') {
               

        $response['Message']      = $dev_message;
        $response['Response']     = '2000';
            //dd($output);
        if($status == 'Success') {

            $response['Result'] = $output;
        }
        else
        {    
            
            if($output==""){
                       
                
                $response['Result']     =  new \stdClass();
            }
            else
            {     
                
                $response['Result']    =   array();

            }
             
            $output= (Array)$output;
            $output= reset($output);

            $error = $output;
            //dd($error);

//            $response['Result']     =  new \stdClass();

            if($error){
                $response['Message']    =   $error;
            }
            else
            {
                $response['Message']    =   $dev_message;
            }



            $response['Response']   =  '1000';
        }

        return response()->json($response);
    }

    public static function emptyResponse($status = true, $dev_message = '', $format = 'json') {

        $response = [
            'status' => $status ? true : false
        ];

        if (!$status) {
            $response['error_code'] = $dev_message;
        }

        return response()->json($response);
    }

}
