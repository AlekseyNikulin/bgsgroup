<?php

use Illuminate\Database\Seeder;

class UsersSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = new \App\User();
        $user->name = 'пользователь';
        $user->surname = 'Тестовый';
        $user->email = 'masterweb@e1.ru';
        $user->password = $user->createPassword('secret');
        $user->api_token = $user->createApiToken();
        $user->is_active = 1;
        $user->save();

        for ($i = 2;$i <= 22;$i++) {
            $user = new \App\User([
                'name'      => 'пользователь' . $i,
                'surname'   => 'Тестовый',
                'email'     => 'masterweb@e' . $i . '.ru',
                'is_active' => true
            ]);
            $user->password = $user->createPassword($user->generatePassword());
            $user->save();
        }
        //$this->call('UsersTableSeeder');
    }
}
