<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;
    protected $fillable = ['user_id','jobber_id','text_message','vu_message'];
   
   
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function jobber()
    {
        return $this->belongsTo(User::class, 'jobber_id');
    }
}
