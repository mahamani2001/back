<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    use HasFactory;
    protected $fillable = [
        'category_id',
        'title',
        'description',
        'price_max',
        'price_min',
        'pictureUrl',
        'jobber_id'
    ];
 
    public function user()
{
    return $this->belongsto(User::class);
}

public function category()
{
    return $this->belongsTo(Categories::class);
}

public function jobber()
{
    return $this->belongsToMany(Jobber::class)->withPivot('price')->withTimestamps();
}
}
