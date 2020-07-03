<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserNotificationsController extends Controller
{
    public function __construct() 
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return auth()->user()->unreadNotifications;
    }

    public function destroy($user, $notificationId)
    {
        // On utilise l'utilisateur connecté afin d'éviter qui puisse supprimer une notification n'étant pas la notre
        // Si je trouve la notification X dans la liste depuis mon ID user alors marquer lu    
        auth()->user()->notifications()->findOrFail($notificationId)->markAsRead();
    }
}
