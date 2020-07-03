<?php

namespace App;

use Carbon\Carbon;

use Illuminate\Database\Eloquent\Model;

class Reply extends Model
{
   use Favoritable, RecordsActivity;

   protected $guarded = [];

   // Attribut spécifique, qui nous permet de faire en sorte que notre relation owner et favorites soit tjr attaché à nos requetes reply
   protected $with = ['owner', 'favorites'];
   // protected $withCount = [''];

   // Permet de récupérer directement ses attributs dans VUEJS (et partout ailleurs) il seront lié à nos collections
   // Dans favoritable j'ai deux méthodes accessor getFooAttribute() & isBest dans cette classe
   protected $appends = ['favoritesCount', 'isFavorited', 'isBest'];

   /**
    * La méthode boot va s'executer automatiquement et réaliser les actions souhaitées
    */
   protected static function boot()
   {
      parent::boot();



      /**
       * Permet d'incrémenter le compteur du nombre de réponse après création
       */
      static::created(function ($reply) {
         $reply->thread->increment('replies_count');
      });

      /**
       * Action à exectuer dès qu'une réponse a été supprimée
       */
      static::deleted(function ($reply) {
         // Suppresion de la réf best_reply_id si celle-ci l'est - A noter que cela peut etre réalisé au niveau BDD (voir fichier thread migration)
         if ($reply->isBest) {
            $reply->thread->update(['best_reply_id' => null]);
         }

         // Permet décrémenter le compteur du nombre de réponse après suppression 
         $reply->thread->decrement('replies_count');
      });
   }

   /**
    * Liason BDD - auteur lié à réponse
    */
   public function owner()
   {
      // je dois préciser le champ user_id car le nom de la méthode n'est pas équivalente à la table ou aller chercher l'info (user) Il faut donc donner un coup de pouce à Laravel afin qu'il sache quoi prendre (par défaut il aurait cherché après owner_id)
      return $this->belongsTo(User::class, 'user_id');
   }

   /**
    * Liason BDD - Thread lié à la réponse 
    */
   public function thread()
   {
      return $this->belongsTo(Thread::class);
   }

   /**
    * Utile au système anti flood
    */
   public function wasJustPublished()
   {
      // gt = greater than et ici on compare avec 1 minute
      return $this->created_at->gt(Carbon::now()->subMinute());
   }

   /** 
    * Défini si un utilisateur a été mentionné dans une réponse (@name)
    */
   public function mentionedUsers()
   {
      // Preg_match_all -> permet de récupérer tous les nom correspondant à la regex
      preg_match_all('/@([\w\-]+)/', $this->body, $matches);
      return $matches[1]; // -> [1] afin de retourner les "name" sans le "@" au début
   }

   /**
    * Force à éditer l'attribut body de cette façon - sert quand on mentionne un user
    */
   public function setBodyAttribute($body)
   {
      $this->attributes['body'] = preg_replace('/@([\w\-]+)/', '<a href="/profiles/$1">$0</a>', $body);
   }

   /**
    * Identifier s'il sagit d'une réponse indiquée comme étant la meilleur
    */
   public function isBest()
   {
      return $this->thread->best_reply_id == $this->id;
   }

   /**
    * Accesseur à la propriété isBest
    * Permet de déterminer si la réponse cible est déterminée comme étant la meilleur 
    * isBest n'étant pas un attribut BDD nous le créons via le tableau append[] en début de ce fichier
    */
   public function getIsBestAttribute()
   {
      return $this->isBest();
   }

   /**
    * Chemin vers une réponse
    */
   public function path()
   {
      return $this->thread->path() . "#reply-{$this->id}";
   }
}
