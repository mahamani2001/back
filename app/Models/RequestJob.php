<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequestJob extends Model
{
    use HasFactory;
     
    
   protected $fillable = [
       'category_id',
    'user_id',
    'job_id',
    'title',
   'description',
    'start_date',
    'end_date',
    'time',
    'location',
    'status',
    'is_client',
    'is_provider',
    'provider_id'
];
public function user()
{
    return $this->belongsTo(User::class);
}

public function job()
{
    return $this->belongsTo(Job::class);
}
public function jobRequests()
{
    if ($this->is_client) {
        return $this->hasMany(RequestJob::class, 'user_id');
    } elseif ($this->is_provider) {
        return $this->hasMany(RequestJob::class, 'provider_id');
    }
}
public function category()
{
    return $this->belongsTo(Categories::class);
}

}
