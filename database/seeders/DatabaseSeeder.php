<?php

namespace Database\Seeders;


use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Shop;
use App\Models\ProductShop;
use Product;

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

        User::create([
                            'phone' => 1234567890 ,
                            'name' => 'Admin Coop' ,
                            'email' => 'admin@coop.com',
                            'password' => Hash::make('1234567890'),
                            'decrypt' => '1234567890'
        ]);

        User::create([
                        'phone' => 1234567890 ,
                        'name' => 'Supplier Asha' ,
                        'email' => 'supplier@asha.com',
                        'password' => Hash::make('1234567890'),
                        'decrypt' => '1234567890'
        ]);

        User::create([
                        'phone' => 1234567890 ,
                        'name' => 'User Asha' ,
                        'email' => 'user@asha.com',
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

        $shop = Shop::create([
                                        'shop_name' => 'Asha Chicken Corner',
                                        'shop_id' => "CPS-307025",
                                        'address' => "Near Main Bazzar, Una",
                                        'distance_from_cps' => "120",
                                        'shop_dimentions' => "1800 * 300",
                                        'stock_capacity_per_day' => "120",
                                        'max_sale_estimate_per_day' => "343",
                                        'estimated_start_date' => now(),
                                        'status' => "Active",
                                        'phone' => 9812312312,
                                        'supplier_id' => $users->where('email','supplier@asha.com')->first()->id
        ]);

        DB::table('settings')->insert([[
                "setting_group_id" => 1,
                "name"  => "Killo Gram",
                "value"  => "Killo Gram",
                "key"  => "KG",
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now()
            ],
            [
                "setting_group_id" => 1,
                "name"  => "Numbers",
                "value"  => "Nrs",
                "key"  => "Nrs",
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now()
            ]
        ]);

        foreach ($users as $key => $user) {
            if($user->email == 'admin@coop.com'){
                $user->assignRole(['Admin','Supplier']);
                for ($x = 0; $x <= 1; $x++) {
                    $user->products()->create([
                                'product_name' => 'ASP '.$user->id.'-Product-'.($x + 1),
                                'weight_unit' => 'KG',
                                'wholesale_weight' => 0,
                                'image' => '/storage/products/1655138004_hean.png',
                                'stock' => 1,
                                'supplier_id' => $user->id
                    ]);
                }
            }elseif($user->email == 'supplier@asha.com'){
                $user->assignRole(['Supplier']);
                $p1 = Product::create([
                            'product_name' => 'Eggs',
                            'weight_unit' => 'Nrs',
                            'wholesale_weight' => 0,
                            'image' => '/storage/products/1670425603_egg.png',
                            'raw_image' => 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADsAAAAfCAYAAABZGLWTAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAyBpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuMC1jMDYwIDYxLjEzNDc3NywgMjAxMC8wMi8xMi0xNzozMjowMCAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvIiB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIgeG1sbnM6c3RSZWY9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZVJlZiMiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIENTNSBXaW5kb3dzIiB4bXBNTTpJbnN0YW5jZUlEPSJ4bXAuaWlkOkI5MDRDOUFDNTc4MjExRURBNUVEQjI3QjFENzA4QTI0IiB4bXBNTTpEb2N1bWVudElEPSJ4bXAuZGlkOkI5MDRDOUFENTc4MjExRURBNUVEQjI3QjFENzA4QTI0Ij4gPHhtcE1NOkRlcml2ZWRGcm9tIHN0UmVmOmluc3RhbmNlSUQ9InhtcC5paWQ6QjkwNEM5QUE1NzgyMTFFREE1RURCMjdCMUQ3MDhBMjQiIHN0UmVmOmRvY3VtZW50SUQ9InhtcC5kaWQ6QjkwNEM5QUI1NzgyMTFFREE1RURCMjdCMUQ3MDhBMjQiLz4gPC9yZGY6RGVzY3JpcHRpb24+IDwvcmRmOlJERj4gPC94OnhtcG1ldGE+IDw/eHBhY2tldCBlbmQ9InIiPz7dWvwpAAAFNklEQVR42uRZa2gdRRSe3b3vm9xHm9gkaFDwl1iMCC36w2JBiYKKoEKlCIFSKwgS0WL9oaCUCv5S9I9oDdgqig+4VqOCiiiK9UEgrUShxbbaNDdp73vve9fvTHbjvTf7mN3ECvbA4ezNzHx7vjlnzs5MJLZOsrC0NAKzB7oNGocehx7cNDDwtUecAMwE9B7oMHQe+gF0ClittfgorRPR7TDvQ1MWzS9CJ+GoLoAzCPM5dMyieQZ6G3AW/foprwPR62CO2BAleRT6nABOGGbahigz/j5t9Lv4ZI2UexMadem6D31vdOnzDPQGlz7U/vR/Fdmd0M0i79F1/XmX9T4p+M7HzsyfG73oZJut9l6RftVag/21kLt57uSpu63a643W4zARgfex+cV8pFBWX//XCpRRgG431uUx6Fv5krq5UFK/2LQxySLhoO1YOMZyhQp/TvbHfk4n4hk8Xgn9A/ouCs7cL8d/r/XFImG02+LU6k2WPV9gmq6zcChQHx5M7zWyKk9rGThfroksSCaNNXlnT1NJrdZnsxeKN0mSxIYHUywUDKwaX1ZrbClX4s80KdFIqLeL1ta0Q2fmzz9IPzam+ll/fHWAG80WO7eY50RpQjBhVu5ScdsB0gXPZEGUis5X0K22USsiaqUKCwYUNnJZmhHxzpQ7m80xrFU2hMmIhOyjX280KT35eMIhPFMwHG05TtiBqClHobeAsOp1zb7gRJSnZSLGQqEAJ1asVLvacsUKJ0oOOhElCaM91R/n/XOFcncKAZeIUua4ECXZYvgtXqAQ1auN3ZCrDKYT3BbLVR4FM6pIc542KYd12DVx6EeJoaKYETkzqrTm+Xs2JETr0EOG/8KRfRKqiCBTylF0220NRaSxslZJUohEZ2o7Fg90Syf6+HMFE7VclBocl6LamdouQh33CZHFrFAVuc9LSacU7HRSNSwqrKdPQzwW7sIxbco9fXvlXqudllVkaSOf8IJsrkmKRAuRoDSWESpF8fYZV2SZybLEWhjfarf556YT34OQ/9tFyG71vDOBg5SurY5UjkX9bWHjxjgiSoQpvQnfh2wRITvmB9ncWJip57TRcMYJdeGEQ/5wrHhYkR3ygxwMLG8qms02twFF8eWhmfomjtVmRVCGRMgm/SAHlOVUo9QzU9vXZl3qxqF17FNWHTml5szEVbAvQ6+h3+3gSFCXFCFPdTmyQdOlsK7RwAKTtKqxKdOZriTRHmUSSJsEJE1dQJtmMVWxthRK6prOMQhrBQcYhCXJNBGygVNHh5bq+CnT27rSPNvkIIz9Cn2EyL6Dh/vZ/1/eo6kaYZeGXE5kpy4Rsgf5YkIqXwvD95PaUn431o7nmwA5Fjkk9UXnVs5uhfI2vd681fMBO6gcldOJzEpdUGujWrm620elyyoDqZeMXyeCY2/MripEtU/vOgzzgI+Zuz4ynpnpwNlpnIW9ylPAOdCBQxN/ygfONHDucPv0/OgDuGrcYKwVxzyT/rPJGM+chsn6wPlJ5Ds77QP4MzjV6nHyN5iTHnGK0G8t/n7Eh0+fuJI1nPzeI/CUx7/bydt4f30dcKh2/CB6nt3vAXgW+pFN2yvGhZjQZaXdLQMm4BubiNvJfozRhcii48cwHwqA0m5oD/prNjgXYJ4QdPAA+p9waH+Y7t4EcOje7LDXO6gJi6LTK5Nw8DvHU8x45jWYV11wKDOedcEhX3a54NBk7bCKquPtolH26Xw1atOvBNAF0bwC1hV0YrNoosJ22i47LHAGmPX/lYjgnzZrnsvfAgwAAST76A+QGDUAAAAASUVORK5CYII=',
                            'digits' => 0,
                            'stock' => 1,
                            'supplier_id' => $user->id,
                            "mask" => "#*"
                ]);
                $p2 = Product::create([
                            'product_name' => 'Broiler Live',
                            'weight_unit' => 'KG',
                            'wholesale_weight' => 0,
                            'image' => '/storage/products/1670315576_heen.png',
                            'raw_image' => 'data:image/png;base64,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',
                            'stock' => 1,
                            'supplier_id' => $user->id,
                            "mask" => "#*.##"
                ]);
                $p3 = Product::create([
                                        'product_name' => 'Layer',
                                        'weight_unit' => 'KG',
                                        'wholesale_weight' => 0,
                                        'image' => '/storage/products/1670315576_heen.png',
                                        'raw_image' => 'data:image/png;base64,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',
                                        'stock' => 1,
                                        'supplier_id' => $user->id,
                                        "mask" => "#*.##"
                            ]);

                ProductShop::create([
                    "product_id" => $p1->id,
                    "shop_id" => $shop->id,
                ]);
                ProductShop::create([
                    "product_id" => $p2->id,
                    "shop_id" => $shop->id,
                ]);
                ProductShop::create([
                    "product_id" => $p3->id,
                    "shop_id" => $shop->id,
                ]);
            }elseif($user->email == 'user@asha.com'){
                $user->shop_id = $shop->id;
                $user->save();
                $user->assignRole(['Employee']);                
            }
        }

    }
}
