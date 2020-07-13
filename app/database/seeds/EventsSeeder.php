<?php

use Illuminate\Database\Seeder;

class EventsSeeder extends Seeder
{
    private $data = [
        [
            'name'      => 'Концерты и шоу',
            'is_active' => true,
        ],
        [
            'name'      => 'Выставки',
            'is_active' => true,
        ],
        [
            'name'      => 'Экскурсии',
            'is_active' => true,
        ],
        [
            'name'      => 'Конференции',
            'is_active' => true,
        ],
        [
            'name'      => 'Спектакли',
            'is_active' => true,
        ],
        [
            'name'      => 'Походы',
            'is_active' => true,
        ],
        [
            'name'      => 'Кросы',
            'is_active' => true,
        ],
        [
            'name'      => 'Спортивные игры',
            'is_active' => true,
        ],
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */

    public function run()
    {
        foreach ($this->data as $key => $item) {
            $item['date_begin'] = date('YmdHis', strtotime('+' . ($key + 1) . ' month'));
            $item['city'] = $key + 1;
            (new \App\Models\Events($item))->save();
        }
    }
}
