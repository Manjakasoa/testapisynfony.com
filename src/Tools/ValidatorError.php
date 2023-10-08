<?php 
namespace App\Tools;

class ValidatorError {
	public static function getResponseError($errors) {
		$messages = [];
        foreach($errors as $err){
            $messages[] = [
                'fields' => $err->getPropertyPath(),
                'value' => $err->getInvalidValue(),
                'message' => $err->getMessage()
            ];
        }
        return $messages;
	}
}