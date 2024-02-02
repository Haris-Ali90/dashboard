<?php

namespace App\Http\Controllers\Backend;


use App\Classes\RestAPI;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardPermissionController extends BackendController
{



    public function __construct()
    {

    }

    public function getDashboardPermissions(Request $request)
    {
        $records = [];
        $type = 'all';
        if (in_array($type, ['all','dashboard'])) {
            $records['dashboard'] =  config('permissions');
        }
        if (in_array($type, ['all','finance'])) {
            $records['finance'] = config('finance_permissions');
        }
        if (in_array($type, ['all','onboarding'])) {
            $records['onboarding'] = config('onboarding_permissions');
        }
        if (in_array($type, ['all','attendance'])) {
            $records['attendance'] = config('hr_permissions');
        }
        if (in_array($type, ['all','claim'])) {
            $records['claim'] = config('claim_permissions');
        }
        return RestAPI::response($records, true, "All Domain Permissions");
    }


    public function getAllDomainRights(Request $request)
    {
        DB::beginTransaction();

        try {
            $record = $this->getDomainRight('get', '/domain-rights', $data = $request->get('type'));
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            return RestAPI::response($e->getMessage(), false, 'error_exception');
        }
        return RestAPI::response($record, true, "All Domain Permissions");
    }


    public function getDomainRight($method, $uri,$data) {

        $host ='api2.joeyco.com/api';
        $data_get = $data;
        $json_data = json_encode($data);
        $headers = [
            'Accept-Encoding: utf-8',
            'Accept: application/json; charset=UTF-8',
            'Content-Type: application/json; charset=UTF-8',
            // 'Accept-Language: ' . $locale->getLangCode(),
            'User-Agent: JoeyCo',
            'Host: ' . $host,
        ];

        if (!empty($json_data) ) {
            $headers[] = 'Content-Length: ' . strlen($json_data);
        }


        $url = 'https://' . $host . $uri.'/'.$data_get;


        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($ch, CURLOPT_HEADER, true);
        //curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_ENCODING, '');
        curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
        curl_setopt($ch, CURLOPT_TIMEOUT, 0);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);


        if (strlen($json_data) > 2) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);
        }

        // $file=env('APP_ENV');
        //   dd(env('APP_ENV') === 'local');
         if (env('APP_ENV') === 'local') {
             curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
             curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
         } else {
             curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
             curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
         }

        set_time_limit(0);

        $this->originalResponse = curl_exec($ch);

        $error = curl_error($ch);


        //  dd([$this->originalResponse,$error]);
        curl_close($ch);

        if (empty($error)) {


            $this->response = explode("\n", $this->originalResponse);

            $code = explode(' ', $this->response[0]);
            $code = $code[1];

            $this->response = $this->response[count($this->response) - 1];
            $this->response = json_decode($this->response);

            if (json_last_error() != JSON_ERROR_NONE) {

                $this->response = (object) [
                    'copyright' => 'Copyright © ' . date('Y') . ' JoeyCo Inc. All rights reserved.',
                    'http' => (object) [
                        'code' => 500,
                        'message' => json_last_error_msg(),
                    ],
                    'response' => new \stdClass()
                ];
            }
        }
        else{
            dd(['error'=> $error,'responce'=>$this->originalResponse]);
        }

        return $this->response;
    }

}
