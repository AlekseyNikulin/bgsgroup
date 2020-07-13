<?php

namespace App\Http\Controllers\Events;

use App\Console\Commands\SendMail;
use App\Http\Controllers\Controller;
use App\Jobs\EventUserSendMailJob;
use App\Models\Events;
use App\Http\Traits\TraitController;
use App\Models\UsersEvents;
use App\User;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Queue;

class UsersEventsController extends Controller
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
     * @Description Add user to event and create is not exist user
     *
     * @param Request $request
     * @param $event_id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(Request $request, $event_id)
    {
        $validator = validator($request->post(), [
            'name'     => 'required',
            'surname'  => 'required',
            //            'password' => 'required|min:6',
            'email'    => 'required|email'
        ]);

        if ($validator->fails())
            return $this->response_json([], 406, $validator->errors());

        /**
         * Find user or create
         */
        $user = User::select('id')->where('email', '=', $request->post('email'))
            ->firstOr(
                function() use ($request){
                    $user = new User($request->post());
                    $user->password = $user->createPassword($request->post('password') ?: $user->generatePassword());
                    $user->is_active = 1;
                    $user->save();

                    return $user;
                });

        /**
         * Find from table relation event and user or write position
         */
        $users_events = UsersEvents::where([
            ['event_id', '=', $event_id],
            ['user_id', '=', $user->id]
        ])->with('events', 'users')->firstOr(function() use ($event_id, $user){
            $users_events = new UsersEvents();
            $users_events->event_id = $event_id;
            $users_events->user_id = $user->id;
            $users_events->save();

            Queue::push(EventUserSendMailJob::class, [
                $users_events->events,
                $users_events->users
            ], 'database');

            return $users_events;
        });

        return $this->response_json($users_events);
    }

    /**
     * @Description Show events and users
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show()
    {
        $result = DB::table('users_events', 'ue')
            ->join('events as e', 'e.id', '=', 'ue.event_id')
            ->join('users as u', 'u.id', '=', 'ue.user_id')
            ->leftJoin('cities as c', 'c.id', '=', 'e.city')
            ->whereRaw('e.date_begin > NOW()')
            ->select([
                'u.id as user_id',
                'u.name as user_name',
                'u.surname as user_surname',
                'e.id as event_id',
                'e.name as event_name',
                'e.city as event_city_id',
                'c.name as event_city_name',
                'e.date_begin as event_date_begin'
            ])->get()->toArray();

        if (!$result)
            return $this->response_json([], 204, 'Not found');

        $events = [];
        foreach ($result as $item) {
            $item = $this->objectToArray($item);
            if (!isset($events[$item['event_id']])) {
                $events[$item['event_id']] = array_diff_key(
                    $item, array_flip(
                        ['user_id', 'user_name', 'user_surname']
                    )
                );
            }
            $events[$item['event_id']]['users'][] = array_intersect_key(
                $item,
                array_flip(
                    ['user_id', 'user_name', 'user_surname']
                )
            );
        }

        return $this->response_json($events);
    }

    /**
     * @Description Delete user from by event
     *
     * @param $event_id
     * @param $user_id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete($event_id, $user_id)
    {
        return $this->response_json(
            [
                'is_delete' => (bool)UsersEvents::where(
                    [
                        ['event_id', '=', $event_id],
                        ['user_id', '=', $user_id]
                    ]
                )->delete()
            ]);
    }

    /**
     *
     *
     * @param Request $request
     * @param $event_id
     * @param $user_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $event_id, $user_id)
    {
        $validator = validator($request->post(),
            [
                'event_id' => 'required|integer',
            ]
        );

        if ($validator->fails())
            return $this->response_json([], 406, $validator->errors());

        $event = Events::find($request->post('event_id'));

        if (!$event)
            return $this->response_json([], 204, 'Not found event_id');

        return $this->response_json([
            'is_update' => UsersEvents::where([
                ['event_id', '=', $event_id],
                ['user_id', '=', $user_id]
            ])
                ->update(
                    [
                        'event_id' => $event->id
                    ]
                )
        ]);
    }

    /**
     * @Description Filter list user from by event
     *
     * @param $event_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function list($event_id)
    {
        $result = DB::table('users_events', 'ue')
            ->join('events as e', 'e.id', '=', 'ue.event_id')
            ->join('users as u', 'u.id', '=', 'ue.user_id')
            ->leftJoin('cities as c', 'c.id', '=', 'e.city')
            ->where('ue.event_id', '=', $event_id)
            ->select([
                'u.id as user_id',
                'u.name as user_name',
                'u.surname as user_surname',
                'e.id as event_id',
                'e.name as event_name',
                'e.city as event_city_id',
                'c.name as event_city_name',
                'e.date_begin as event_date_begin'
            ])->get()->toArray();

        if (!$result)
            return $this->response_json([], 204, 'Not found');

        $events = [];
        foreach ($result as $item) {
            $item = $this->objectToArray($item);
            if (!isset($events[$item['event_id']]))
                $events[$item['event_id']] = array_diff_key(
                    $item, array_flip(
                        ['user_id', 'user_name', 'user_surname']
                    )
                );

            $events[$item['event_id']]['users'][] = array_intersect_key(
                $item,
                array_flip(
                    ['user_id', 'user_name', 'user_surname']
                )
            );
        }

        return $this->response_json($events);
    }
}
