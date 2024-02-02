<?php

namespace App\Classes;

class RestAPI
{

    private static $pagination;

    public static function response($output, $status = true, $message = '',$metaData = [] , $format = 'json')
    {
        $response = [
            'status' => $status ? true : false,
            'message' => $status ? $message : (is_array($output) ? implode("\n", $output) : $output),
            'paging' => self::$pagination ?: new \stdClass(),
        ];

        if (!$status) {
            $response['error_code'] = $message;
        } else {
            $response['body'] = $output;
            $response['metaData'] = $metaData;
        }

        return response()->json($response, 200, ['Content-type' => 'application/json; charset=utf-8'], JSON_PRESERVE_ZERO_FRACTION | JSON_UNESCAPED_UNICODE);
    }

    public static function messageResponse($output, $status = true, $message = '')
    {
        $status = (bool)$status;

        return $status ?
            self::response(new \stdClass, true, $output) :
            self::response($output, $status, $message);
    }

    public static function setPagination(\Illuminate\Pagination\LengthAwarePaginator $paginator)
    {
        self::$pagination = new \stdClass();
        self::$pagination->total_records = $paginator->total();
        self::$pagination->current_page = $paginator->currentPage();
        self::$pagination->total_pages = $paginator->lastPage();
        self::$pagination->limit = intval($paginator->perPage());

        return new static;
    }

    public static function emptyResponse($status = true, $dev_message = '', $format = 'json')
    {

        $response = [
            'status' => $status ? true : false
        ];

        if (!$status) {
            $response['error_code'] = $dev_message;
        }

        return response()->json($response);
    }

}
