<?php

namespace App\Helpers;

class ApiResponseHelper
{
    public static function formatErrorResponse(\Exception $exception, $data = null)
    {
        return response()->json([
            'errMsgFlag' => true,
            'msgFlag' => false,
            'msg' => null,
            'data' => $data,
            'errMsg' => 'Something went wrong. Please try again.',
            'errorDetails' => [
                'message' => $exception->getMessage(),
                'trace' => $exception->getTraceAsString()
            ]
        ], 500);
    }


    public static function formatSuccessResponseInsert($data = null)
    {
        return response()->json([
            'errMsgFlag' => false,
            'msgFlag' => true,
            'msg' => 'Data inserted successfully.',
            'errMsg' => null,
            'data' => $data
        ], 200);
    }

    public static function formatFailedResponseInsert($data = null)
    {
        return response()->json([
            'errMsgFlag'=>true,
            'msgFlag'=>false,
            'msg'=>null,
            'errMsg' => 'Data insert fail, Please Try again!',
         
        ], 500);
    }
    public static function formatSuccessResponseUpdate($data = null)
    {
        return response()->json([
            'errMsgFlag' => false,
            'msgFlag' => true,
            'msg' => 'Data Update successfully.',
            'errMsg' => null,
            'data' => $data
        ], 200);
    }


    public static function formatFailedResponseUpdate($data = null)
    {
        return response()->json([
            'errMsgFlag'=>true,
            'msgFlag'=>false,
            'msg'=>null,
            'errMsg' => 'Data Update fail, Please Try again!',
        
        ], 500);
    }
    public static function formatSuccessResponseDelete($data = null)
    {
        return response()->json([
            'errMsgFlag' => false,
            'msgFlag' => true,
            'msg' => 'Data Delete successfully.',
            'errMsg' => null,
            'data' => $data
        ], 200);
    }

    public static function formatFailedResponseDelete($data = null)
    {
        return response()->json([
           'errMsgFlag'=>true,
            'msgFlag'=>false,
            'msg'=>null,
            'errMsg' => 'Data deletion failed. Please try again!',
        ], 500);
    }


    public static function formatDataNotFound($data = null)
    {
        return response()->json([
              'errMsgFlag'=>true,
                'msgFlag'=>false,
                'msg'=>null,
                'errMsg'=>'Data not found!',
        ], 404);
    }
}
