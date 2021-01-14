<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\GigItem;
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

        $user = User::factory()->create([
            'name' => 'Super Admin',
            'email' => 'admin@admin.com',
        ]);

        GigItem::factory()->create([
            'user_id' => $user->id
        ]);
    }
}
