<?php

namespace App\Policies;

use App\Reply;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ReplyPolicy
{
   use HandlesAuthorization;

   // Update possible uniquement si on est l'auteur
   public function update(User $user, Reply $reply)
   {
      return $reply->user_id == $user->id;
   }

   // Anti flood
   public function create(User $user)
   {
      $lastReply = $user->fresh()->lastReply; // fresh() permet de forcer le rafraichissement de données

      if (!$lastReply) return true;

      return !$lastReply->wasJustPublished();
   }
}
