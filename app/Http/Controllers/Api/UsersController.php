<?php

namespace App\Http\Controllers\Api;

use App\User;
use App\Http\Controllers\Controller;

class UsersController extends Controller
{
    // Ce controller a été créé pour la gestion de l'autocompletion nom utilisateur 
    public function index()
    {
        $search = request('name');

        return User::where('name', 'LIKE', "$search%")
            ->take(5)
            ->pluck('name'); // pluck pour retourner que le nom de l'utilisateur et rien d'autre
    }
}
