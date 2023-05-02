<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categories extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'name',
        'image',
       'category_id'
    ];
    /*
Category
has many requests
has many services
 
    */ 
    public function jobs()
    {
        return $this->hasMany(Job::class);
    }
    
    public function requests()
    {
        return $this->hasMany(RequestJob::class);
    }

}
