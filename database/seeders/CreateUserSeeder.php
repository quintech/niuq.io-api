<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class CreateUserSeeder extends Seeder
{
    public function run()
    {
        if (User::where('account', '=', 'develop')->first() == null) {
            User::create([
                'email'        => 'kahap-develp@gmail.com',
                'account'      => 'develop',
                'password'     => Hash::make('quin-kahap-develop'),
                'owner_name'   => 'kahap-develp',
            ]);
        }
    }
}
