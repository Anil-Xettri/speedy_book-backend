<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Vendor;
use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $superadmin = new User([
           'name' => 'SuperAdmin',
           'email' => 'sa@book.com',
           'password' => bcrypt('12345678')
        ]);

        $superadmin->save();

        $vendor = new Vendor([
            'name' => 'Vendor',
            'email' => 'vendor@book.com',
            'password' => bcrypt('12345678')
        ]);

        $vendor->save();
    }
}
