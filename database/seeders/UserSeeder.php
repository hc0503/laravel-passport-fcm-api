<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\GigItem;
use App\Models\Notification;
use App\Models\Profile;
use DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->delete();
        DB::table('gig_items')->delete();
        DB::table('notifications')->delete();
        DB::table('profiles')->delete();

        // create users
        $superAdmin = User::factory()->create([
            'name' => 'Super Admin',
            'email' => 'admin@admin.com',
        ]);
        $user = User::factory()->create([
            'name' => 'User',
            'email' => 'user@user.com'
        ]);
        
        // create gigs
        GigItem::factory()->create([
            'user_id' => $superAdmin->id
        ]);
        GigItem::factory()->create([
            'user_id' => $user->id
        ]);

        // create notifications
        Notification::factory(2)->create([
            'user_id' => $superAdmin->id,
        ]);
        Notification::factory(2)->create([
            'user_id' => $superAdmin->id,
            'type' => 'AUDIENCE',
            'notification_type' => 'CLAP'
        ]);
        Notification::factory(2)->create([
            'user_id' => $user->id,
            'type' => 'AUDIENCE',
            'notification_type' => 'INVITE'
        ]);

        // create profile
        Profile::factory()->create([
            'user_id' => $superAdmin->id,
        ]);
        Profile::factory()->create([
            'user_id' => $user->id,
        ]);
    }
}
