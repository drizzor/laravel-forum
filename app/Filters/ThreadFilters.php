<?php

namespace App\Filters;

use App\User;
use Illuminate\Http\Request;

class ThreadFilters extends Filters
{  
    protected $filters = ['by', 'popular', 'unanswered'];

    protected function by($username)
    {
        $user = User::where('name', $username)->firstOrFail();
        return $this->builder->where('user_id', $user->id);
    }

    protected function popular()
    {
        return $this->builder->reorder('replies_count', 'desc'); // avec reorder() on annuler toutes requete initialisée auparavant comme le fait d'ornonné nos requetes par date, ce qui faussait le mécanisme popular qui souhaite nous retourner par ordre de threads ayant le + de réponses
    }

    protected function unanswered()
    {
        return $this->builder->where('replies_count', 0);
    }
}