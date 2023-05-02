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
    // ...

    /**
     * Scope a query to only include users within a given radius from the given latitude and longitude.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param float $latitude The latitude value to use as the center of the search radius
     * @param float $longitude The longitude value to use as the center of the search radius
     * @param int $radius The search radius in kilometers
     * @return \Illuminate\Database\Eloquent\Builder
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
       'latitude',
       'longitude'
    ];
    public function scopeWithinRadius($query, $latitude, $longitude, $radius)
    {
        // calculate the haversine formula parameters
        $haversine = "(6371 * acos(cos(radians($latitude)) * cos(radians(latitude)) * cos(radians(longitude) - radians($longitude)) + sin(radians($latitude)) * sin(radians(latitude))))";
    
        // add a select clause to the query to get the calculated distance
        $query->selectRaw("*, $haversine AS distance");
    
        // add a where clause to filter by distance
        $query->whereRaw("$haversine < ?", [$radius]);
    
        // return the query builder instance
        return $query;
    }
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
public function sentMessages()
{
    return $this->hasMany(Message::class, 'user_id');
}

public function receivedMessages()
{
    return $this->hasMany(Message::class, 'jobber_id');
}

    
}
