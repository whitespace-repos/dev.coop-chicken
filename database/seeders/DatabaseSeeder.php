<?php

namespace Database\Seeders;


use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        User::insert([
                            'phone' => 1234567890 ,
                            'name' => 'Admin Coop' ,
                            'email' => 'admin@coop.com',
                            'password' => Hash::make('1234567890'),
                            'decrypt' => '1234567890'
                ]);
        //
        $users = User::all();
        //
        DB::table('roles')->insert([
            [
                'created_at' => \Carbon\Carbon::now() ,
                'updated_at' => \Carbon\Carbon::now() ,
                'guard_name' => 'web' ,
                'name' => 'Admin'
            ],[
                'created_at' => \Carbon\Carbon::now() ,
                'updated_at' => \Carbon\Carbon::now() ,
                'guard_name' => 'web' ,
                'name' => 'Supplier'
            ],[
                'created_at' => \Carbon\Carbon::now() ,
                'updated_at' => \Carbon\Carbon::now() ,
                'guard_name' => 'web' ,
                'name' => 'Employee'
            ]
        ]);

        DB::table('setting_groups')->insert([
            "group" => "Weight Unit",
            "slug"  => "weight_unit",
            'created_at' => \Carbon\Carbon::now(),
            'updated_at' => \Carbon\Carbon::now()
        ]);

        DB::table('settings')->insert([
            "setting_group_id" => 1,
            "name"  => "Killo Gram",
            "value"  => "Killo Gram",
            "key"  => "KG",
            'created_at' => \Carbon\Carbon::now(),
            'updated_at' => \Carbon\Carbon::now()
        ]);

        foreach ($users as $key => $user) {
            if($user->email == 'admin@coop.com'){
                $user->assignRole(['Admin','Supplier']);
                for ($x = 0; $x <= 3; $x++) {
                    $user->products()->create([
                                'product_name' => 'ASP '.$user->id.'-Product-'.($x + 1),
                                'weight_unit' => 'KG',
                                'wholesale_weight' => 0,
                                'image' => '/storage/products/1655138004_hean.png',
                                'stock' => 1,
                                'supplier_id' => $user->id
                    ]);
                }
            }
        }

    }
}
