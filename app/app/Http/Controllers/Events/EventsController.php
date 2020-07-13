<?php

namespace App\Http\Controllers\Events;

use App\Http\Controllers\Controller;
use App\Models\Events;
use App\Http\Traits\TraitController;
use App\Models\UsersEvents;
use App\User;
use Illuminate\Http\Request;

class EventsController extends Controller
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
     * @return \Illuminate\Http\JsonResponse
     */
    public function showAll()
    {
        return $this->response_json(Events::with('users_events')->get());
    }
}
