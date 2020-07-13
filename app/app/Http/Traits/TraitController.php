<?php

namespace App\Http\Traits;

trait TraitController
{
    public function response_json($data = [], $code = 200, $errors = [])
    {
        return response()->json([
            'http_code' => $code,
            'error'     => is_array($errors) || is_object($errors)? $errors : ['default' => $errors],
            'data'      => $data
        ]);
    }

    /**
     * @param $object
     *
     * @return array|null
     */
    public function objectToArray($object){
        return json_decode(json_encode($object), true);
    }

    /**
     * @param $array
     * @return object|null
     */
    public function arrayToObject($array){
        return json_decode(json_encode($array));
    }
}