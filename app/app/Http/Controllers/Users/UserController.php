<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Http\Traits\TraitController;
use App\User;

class UserController extends Controller
{
    use TraitController;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * @param $api_token
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function accessApiKeyCreate($api_token)
    {
        $user = User::where('api_token', '=', $api_token)->first();

        if (!$user)
            return $this->response_json([], 401, 'Access denied');

        if (!$user->api_key_expired || date('YmdHis', strtotime($user->api_key_expired)) < date('YmdHis')) {
            $user->api_key = User::createApiKey($user->id, $user->api_token);
            $user->api_key_expired = date('YmdHis', strtotime('+1 hour'));
            $user->save();
        }

        return $this->response_json([
            'api_key' => $user->api_key,
            'expired' => date('YmdHis', strtotime($user->api_key_expired))
        ]);
    }

}
