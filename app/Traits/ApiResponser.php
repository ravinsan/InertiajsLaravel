<?php

namespace App\Traits;

use Illuminate\Support\Collection;
use Illuminate\Support\MessageBag;

trait ApiResponser {

	protected function apiResponse($message = null, $messages = array(), $data = array(), $code, $status) {

		$response = $this->setResponseFormat($message, $messages, $data, $status);

		return response()->json($response, $code);
	}
	
    protected function setResponseFormat($message = null, $messages = array(), $data =  array(), $status) {

    	$messages = $this->setErrors($messages);

    	if(empty($data)) {
    		$data = new \stdClass;
    	}

    	return [
    		'status' => $status,
    		'message' => $message,
            'response' => $messages,
            'data' => $data
    	];
    }

    protected function setErrors($errors = array())
    {
    	$messages = array();
    	$errors = new MessageBag($errors);
    	/* convert to single array as required by api user */
    	foreach($errors->getMessages() as $error) {
    		
    		foreach($error as $e) {
    			$messages[] = $e;
    		}
    	}

    	return $messages;
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

    public function errorResponse($message = null, $code=422)
    {
        return response()->json([
            'status'=> false,
            'message' => $message
        ], 422);
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
        // return 200;
        return 422;
        // return 202;
    }
}