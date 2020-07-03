<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Channel extends Model
{

   public function threads()
   {
      return $this->hasMany(Thread::class);
   }

   /**
    * Détermine le slug que Laravel doit récupérer
    * Ici facultatif car je l'expoilte directement depuis le web route
    */
   public function getRouteKeyName()
   {
      return 'slug';
   }
}
