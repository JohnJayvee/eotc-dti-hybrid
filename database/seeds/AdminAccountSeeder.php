<?php

use App\Laravel\Models\User;
use Illuminate\Database\Seeder;

class AdminAccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::firstOrNew([
            'fname' => "Master Account", 
            'lname' => "",
            'status' => 'active',
            'email' => "admin@highlysucceed.com", 
            'username' => "master_admin",
            'type' => "super_user",
        ]);

        $user->password = bcrypt("admin");
        $user->save();

        echo "Successfully seeded Master Admin Account.";

    }
}
