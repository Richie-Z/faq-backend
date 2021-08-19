<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\Plan;
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
        $this->adminSeed();
        $this->planSeed();
    }
    public function adminSeed()
    {
        Admin::create([
            'name' => 'Richie Zakaria',
            'username' => 'richie',
            'password' => app('hash')->make('richie')
        ]);
    }
    public function planSeed()
    {
        Plan::create([
            'name' => 'Free',
            'price' => 0
        ]);
    }
}
