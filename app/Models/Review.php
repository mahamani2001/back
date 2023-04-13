<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
  
    
    use HasFactory;

    protected $fillable = [
         'rating',
         'comment',
         'user_id',
         'jobber_id'

    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function jobber()
    {
        return $this->belongsTo(User::class, 'jobber_id');
    }
}
