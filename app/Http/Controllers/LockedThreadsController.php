<?php

namespace App\Http\Controllers;

use App\Thread;
use Illuminate\Http\Request;

class LockedThreadsController extends Controller
{
    /**
     * Fixer le boolean à faux pour verouiller un thread
     */
    public function store(Thread $thread)
    {
        $thread->lock();
    }
}
