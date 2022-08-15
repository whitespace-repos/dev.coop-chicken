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

        User::insert([[
                                    'phone' => 1234567890 ,
                                    'name' => 'Admin Coop' ,
                                    'email' => 'admin@coop.com',
                                    'password' => Hash::make('1234567890'),
                                    'decrypt' => '1234567890'
                ],[
                    'phone' => 1234567891 ,
                    'name' => 'Supplier 1' ,
                    'email' => 'supplier1@coop.com',
                    'password' => Hash::make('1234567890'),
                    'decrypt' => '1234567890'
                ],[
                    'phone' => 1234567892 ,
                    'name' => 'Supplier 2' ,
                    'email' => 'supplier2@coop.com',
                    'password' => Hash::make('1234567890'),
                    'decrypt' => '1234567890'
                ],[
                    'phone' => 1234567893 ,
                    'name' => 'Supplier 3' ,
                    'email' => 'supplier3@coop.com',
                    'password' => Hash::make('1234567890'),
                    'decrypt' => '1234567890'
                ],[
                    'phone' => 1234567894 ,
                    'name' => 'Supplier 4' ,
                    'email' => 'supplier4@coop.com',
                    'password' => Hash::make('1234567890'),
                    'decrypt' => '1234567890'
                ],[
                    'phone' => 1234567895 ,
                    'name' => 'Supplier 5' ,
                    'email' => 'supplier5@coop.com',
                    'password' => Hash::make('1234567890'),
                    'decrypt' => '1234567890'
                ],[
                    'phone' => 1234567896 ,
                    'name' => 'Supplier 6' ,
                    'email' => 'supplier6@coop.com',
                    'password' => Hash::make('1234567890'),
                    'decrypt' => '1234567890'
                ],[
                    'phone' => 1234567897 ,
                    'name' => 'Supplier 7' ,
                    'email' => 'supplier7@coop.com',
                    'password' => Hash::make('1234567890'),
                    'decrypt' => '1234567890'
                ],[
                    'phone' => 1234567898 ,
                    'name' => 'Supplier 8' ,
                    'email' => 'supplier8@coop.com',
                    'password' => Hash::make('1234567890'),
                    'decrypt' => '1234567890'
                ]]);
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
                for ($x = 0; $x <= 5; $x++) {
                    $user->products()->create([
                                'product_name' => 'ASP '.$user->id.'-Product-'.($x + 1),
                                'weight_unit' => 'KG',
                                'wholesale_weight' => 0,
                                'image' => '/storage/products/1655138004_hean.png',
                                'stock' => 1,
                                'supplier_id' => $user->id
                    ]);
                }
            }else{
                $user->assignRole(['Supplier']);
                for ($x = 0; $x <= 5; $x++) {
                    $user->products()->create([
                                'product_name' => 'SP '. ( $user->id - 1 ).'-Product-'.($x + 1),
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
