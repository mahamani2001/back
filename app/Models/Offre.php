<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Offre extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'demande_service_id', 'prix', 'statut','jobber_id'];
//$offres = Offre::where('demande_service_id', $idDemande)->get();

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function RequestJob()
    {
        return $this->belongsTo(RequestJob::class);
    }
}
