<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Disponibilite extends Model
{
    use HasFactory;
   
    protected $fillable = ['actif', 'jour', 'heure_debut', 'heure_fin','jobber_id'];

    protected $casts = [
        'actif' => 'boolean',
        'heure_debut' => 'datetime:H:i',
        'heure_fin' => 'datetime:H:i',
        'jobber_id'=>'int'
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
   
}
