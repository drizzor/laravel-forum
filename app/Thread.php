<?php

namespace App;

use Illuminate\Support\Str;
use App\Events\ThreadReceivedNewReply;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Thread extends Model
{
     use RecordsActivity;
     use Searchable;

     protected $guarded = [];

     protected $with = ['creator', 'channel'];

     protected $appends = ['isSubscribedTo'];

     /**
      * Toutes méthodes insérées dans le boot sont automatiquement executées
      */
     protected static function boot()
     {
          parent::boot();

          // De cette façon on rend le count() des commentaires global et accessible partout facilement depuis le Thread
          // Finalement on a décidé d'exploiter ceci directement en attribut de table en DB. On ne l'expoite plus
          // static::addGlobalScope('replyCount', function ($builder) {
          //      $builder->withCount('replies');
          // });

          static::deleting(function ($thread) {
               // $thread->replies()->delete();
               // Grâce à cette suppression each, on va supprimer toutes activités associées au Thread si celui-ci est supprimé
               $thread->replies->each->delete();
          });

          // Lorsqu'un Thread est créé on update directement le slug
          static::created(function ($thread) {
               $thread->update(['slug' => $thread->title]);
          });
     }

     public function replies()
     {
          // Le faite de compter les relations directement, va nous permettre une belle économie de requetes. Cela va automatiquement nous créé un favorites_count qui sera utilisable dans nos vues
          // On va également faire référence aux nom utiliateurs pour la meme raison, etc.
          return $this->hasMany(Reply::class);
     }

     /**
      * N'est plus exploité - Préférence pour globalScope
      */
     public function getRepliesCountAttributes()
     {
          return $this->replies()->count();
     }

     /**
      * Retourne le canal du Thread
      */
     public function channel()
     {
          return $this->belongsTo(Channel::class);
     }

     /**
      * Retourne l'auteur du thread
      */
     public function creator()
     {
          return $this->belongsTo(User::class, 'user_id');
     }

     /**
      * Chemin vers un thread
      */
     public function path()
     {
          return "/threads/" . $this->channel->slug . "/" . $this->slug;
     }

     /**
      * Execute l'ajout de la réponse en BDD & event
      */
     public function addReply($reply)
     {
          $reply = $this->replies()->create($reply);

          // Ecoute nouvelle réponse pour notif @name & notif subscribers
          event(new ThreadReceivedNewReply($reply));

          return $reply;
     }

     /**
      * N'est plus utilisé, nous exploitons listeners/events
      */
     public function notifySubcribers($reply)
     {
          $this->subscriptions
               ->where('user_id', '!=', $reply->user_id)
               ->each
               ->notify($reply); // notify est une méthode de ThreadSubscriptions, faisant le lien vers notifications/ThreadWasUpdated
     }

     public function scopeFilter($query, $filters)
     {
          return $filters->apply($query);
     }

     public function subscribe($userId = null)
     {
          $this->subscriptions()->create([
               'user_id' => $userId ?: auth()->id()
          ]);

          return $this;
     }

     public function unsubscribe($userId = null)
     {
          $this->subscriptions()->where('user_id', $userId ?: auth()->id())->delete();
     }

     public function subscriptions()
     {
          return $this->hasMany(ThreadSubscription::class);
     }

     /**
      * Avec le get on fait un accesseur qui est géré par Eloquent
      * grâce à cela on pourra faire directement appel à "isSubscribedTo" depuis notre objet thread pour savoir si oui ou non on est abonné
      * On peut utiliser append avec notre objet thread pour lier directement dans la collection ou via la propriété append si ça doit tjrs etre lié à un thread (voir attribut de cette classe)
      */
     public function getIsSubscribedToAttribute()
     {
          return $this->subscriptions()->where('user_id', auth()->id())->exists();
     }

     /**
      * Identifier les thread mis à jour - nouveau contenu à lire
      */
     public function hasUpdateFor($user)
     {
          $key = $user->visitedThreadCacheKey($this);

          return $this->updated_at > cache($key);
     }

     /**
      * Compteur de visite d'un Thread
      * Instanciation de la classe Visits afin de pouvoir faire appel à ses méthodes et je communique le thread avec $this
      */
     public function visits()
     {
          return new Visits($this);
     }

     /**
      * Indiquer à Laravel le slug
      * Facultatif, directement indiqué depuis le route
      */
     public function getRouteKeyName()
     {
          return 'slug';
     }

     /**
      * Méthode spéciale Laravel
      * Va automatiquement formater notre slug et les numéroter si titre déjà existant
      */
     public function setSlugAttribute($value)
     {
          $slug = Str::slug($value);

          while (static::whereSlug($slug)->exists()) {
               $slug = "{$slug}-" . $this->id;
          }

          $this->attributes['slug'] = $slug;
     }

     /**
      * Mise à jour du champ best_reply_id afin de marquer une réponse comme étant la meilleur 
      */
     public function markBestReply(Reply $reply)
     {
          $this->update(['best_reply_id' => $reply->id]);
          // $this->best_reply_id = $reply->id;
          // $this->save();
     }

     /**
      * Overide de la méthode du trait Searchable
      * Je suis ainsi capable d'arbitrer ce qui sera récupéré sur Algolia - Ici je garde le mécanisme par défaut, mais je demande de prendre en plus le path de chaque Thread
      */
     public function toSearchableArray()
     {
          return $this->toArray() + ['path' => $this->path()];
     }
}
