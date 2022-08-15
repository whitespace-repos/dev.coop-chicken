<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = ['name','phone','email','location','customer_id'];


    public function  purchase_history() {
        return $this
                    ->hasMany(Sale::class);
    }

}
