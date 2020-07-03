<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Carbon\Carbon;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'avatar_path'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'email'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function threads()
    {
        return $this->hasMany(Thread::class)->latest();
    }

    /**
     * Nécessaire pour l'anti flood - Pour chercher la date de dernière réponse envoyée
     */
    public function lastReply()
    {
        return $this->hasOne(Reply::class)->latest();
    }

    public function getRouteKeyName()
    {
        return 'name';
    }

    /**
     * Sert à l'affichage de l'activité dans le profil -> hasMany()
     */
    public function activity()
    {
        return $this->hasMany(Activity::class);
    }

    /** 
     * Marque un thread comme lu (titre du thread ne sera plus en gras)
     */
    public function read($thread)
    {
        cache()->forever($this->visitedThreadCacheKey($thread), Carbon::now());
    }

    /**
     * Récupère l'avatar de l'utilisateur ou celui par défaut si aucun appliqué
     * L'attribut avatar_path sera automatiquement mis à jour
     */
    public function getAvatarPathAttribute($avatar)
    {
        if ($avatar) $avatar = "/storage/" . $avatar;
        else $avatar = '/storage/avatars/default.png';

        return $avatar;
        // if (!$this->avatar_path)
        //     return '/storage/avatars/default.jpg';

        // return "/storage/" . $this->avatar_path;
    }

    /**
     * Récupère la clé permettant d'interpreter la date en cache de chaque user 
     */
    public function visitedThreadCacheKey($thread)
    {
        return sprintf("users.%s.visits.%s", $this->id, $thread->id); //users.1.visits.5
    }
}
