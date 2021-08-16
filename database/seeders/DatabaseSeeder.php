<?php

namespace Database\Seeders;

use App\Models\Admin;
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
    }
    public function adminSeed()
    {
        Admin::insert([
            'name' => 'Richie Zakaria',
            'username' => 'richie',
            'password' => app('hash')->make('richie')
        ]);
    }
}
