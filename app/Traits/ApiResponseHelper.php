<?php
/**
 * File name: UserAPIController.php
 * Last modified: 2020.06.11 at 12:09:19
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2020
 */
namespace App\Traits;


trait ApiResponseHelper {

    public function successResponsePaginate($data, $message = null, $code = 200, $attr = array())
    {
        $array = [
            'status'=> true,
            'message' => $message,
            'records' => $data
        ];
        if(count($attr)){
            $array = [
                'status'=> true,
                'message' => $message,
            ];
            $array =  array_merge($array, $attr);
        }
        return response()->json(
            $array
        , $code);
    }

    public function successResponse($data, $message = null, $code = 200, $attr = array())
    {
        $array = [
            'status'=> true,
            'message' => $message,
            'data' => $data
        ];
        if(count($attr)){
            $array = [
                'status'=> true,
                'message' => $message,
            ];
            $array =  array_merge($array, $attr);
        }
        return response()->json(
            $array
        , $code);
    }

    public function successResponseFailed($data, $message = null, $code = 200, $attr = array())
    {
        $array = [
            'status'=> false,
            'message' => $message,
            'data' => $data
        ];
        if(count($attr)){
            $array = [
                'status'=> false,
                'message' => $message,
            ];
            $array =  array_merge($array, $attr);
        }
        return response()->json(
            $array
        , $code);
    }

    public function successResponseMulti($data,$subdata,$sdata, $message = null, $code = 200, $attr = array())
    {
        $array = [
            'status'=> true,
            'message' => $message,
            'data' => $data,
            'subdata' => $subdata,
            'sdata' => $sdata
        ];
        if(count($attr)){
            $array = [
                'status'=> true,
                'message' => $message,
            ];
            $array =  array_merge($array, $attr);
        }
        return response()->json(
            $array
        , $code);
    }

    public function successResponseMsgData($sdata,$data, $message = null, $code = 200, $attr = array())
    {
        $array = [
            'status'=> true,
            'message' => $message,
            'data' => $data,
            'sdata' => $sdata,
        ];
        if(count($attr)){
            $array = [
                'status'=> true,
                'message' => $message,
            ];
            $array =  array_merge($array, $attr);
        }
        return response()->json(
            $array
        , $code);
    }
    public function successResponseMsg($message = null, $code = 200, $attr = array())
    {
        $array = [
            'status'=> true,
            'message' => $message
        ];
        if(count($attr)){
            $array = [
                'status'=> true,
                'message' => $message,
            ];
            $array =  array_merge($array, $attr);
        }
        return response()->json(
            $array
        , $code);
    }
    public function successResponseMsgFailed($message = null, $code = 200, $attr = array()){
        $array = [
            'status'=> false,
            'message' => $message
        ];
        if(count($attr)){
            $array = [
                'status'=> false,
                'message' => $message,
            ];
            $array =  array_merge($array, $attr);
        }
        return response()->json(
            $array
        , $code);
    }
    public function errorResponse($message = null, $code)
    {
        return response()->json([
            'status'=> false,
            'message' => $message
        ], $code);
    }
    public function errorResponseWithData($data,$message = null, $code)
    {
        return response()->json([
            'status'=> false,
            'data' => $data,
            'message' => $message
        ], $code);
    }
    public function errorResponseWithDataResult($data,$message = null, $code)
    {
        return response()->json([
            'status'=> false,
            'message' => $message,
            'records' => $data
        ], $code);
    }
    public function validationErrorResponse($messages = null, $code)
    {
        $message = collect($messages)->values()->first();
        $key = collect($messages)->keys()->first();
        return response()->json([
            'status'=> false,
            'message' => $message[0],
            'column_name' => $key,
            'data' => null
        ], $code);
    }

    public function success() {
        return 200;
    }
    public function failed() {
        return 200;
    }
    public function invalid() {
        return 400;
    }
    public function validation() {
        return 200;
        //return 422;
        // return 202;
    }
}