<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable  implements JWTSubject,MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'jobber_id',
        'firstname',
        'email',
        'password',
        'lastname',
        'address',
        'phone',
        'photo',
        'competence',
        'numero_cin',
        'diplome',
        'role',
    ];
    public function getClientAttributesAttribute()
    {
        if ($this->role === 'client') {
            return $this->attributes;
        }
        return null;
    }

    public function getPrestataireAttributesAttribute()
    {
        if ($this->role === 'prestataire') {
            return $this->attributes;
        }
        return null;
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
	/**
	 * Get the identifier that will be stored in the subject claim of the JWT.
	 * @return mixed
	 */
	public function getJWTIdentifier() {
        return $this->getKey();

	}

	
	/**
	 * Return a key value array, containing any custom claims to be added to the JWT.
	 * @return array
	 */
	public function getJWTCustomClaims() {
        return [];
	}
    
    //les relations 
    public function requests()
{
    return $this->hasMany(RequestJob::class);
}

public function job()
{
    return $this->hasMany(Job::class);
}


//Relation bettween prestataire et service
public function jobs()
{
    return $this->belongsToMany(Job::class)->withPivot('price')->withTimestamps();
}
    // define any relationships to other tables, such as the "disponibilité" table
    public function disponibilites()
    {
        return $this->hasMany(Disponibilite::class);
    }
    public function category()
    {
        return $this->belongsTo(Categories::class);
    }
    public function reviews()
{
    return $this->hasMany(Review::class,'user_id');
}

   public function offers()
{
    return $this->hasMany(Offre::class,'user_id');
}

    
}
