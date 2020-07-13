<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class CreateDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $db_name = config("database.connections.mysql.database");

        config(["database.connections.mysql.database" => null]);

        DB::statement('CREATE SCHEMA IF NOT EXISTS `' . $db_name . '` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci');

        config(["database.connections.mysql.database" => $db_name]);

        print 'Created database "' . $db_name . '"';
    }
}
