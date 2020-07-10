<?php

namespace App\Policies;

use App\Thread;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ThreadPolicy
{
    use HandlesAuthorization;

    public function before(User $user)
    {
        // L'utilisateur nommé Kevin Mary peut executer toutes les actions, peu importe les règles d'accès et d'actions liés au SI
        // Le laissé-passé est limité aux threads, pour avoir un laissé-passé global voir AuthServiceProvider et y mettre directement la règle
        if ($user->name === 'Drizz') return true;
        // if ($user->isAdmin()) return true;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\User  $user
     * @param  \App\Thread  $thread
     * @return mixed
     */
    public function update(User $user, Thread $thread)
    {
        // Si le thread user_id équivaut au auth user_id alors je peux faire l'action 
        return $thread->user_id == $user->id;
    }
}
