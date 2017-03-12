<?php

use Illuminate\Database\Seeder;

class ErrorLogsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(\App\Modules\Errors\Models\ErrorLog::class, 100)->create();
    }
}