<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
        // $this->call(LaratrustSeeder::class);
        // $this->call(LeaveSeeder::class);
        // $this->call(PermitSeeder::class);
        // $this->call(TimeEventSeeder::class);
        $this->call(OvertimeSeeder::class);
    }
}