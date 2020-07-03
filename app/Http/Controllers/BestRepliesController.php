<?php

namespace App\Http\Controllers;

use App\Reply;
use Illuminate\Http\Request;

class BestRepliesController extends Controller
{
    public function store(Reply $reply)
    {
        // abort_if($reply->thread->user_id !== auth()->id(), 403); -> ceci marche, mais j'exploite la restriction dans les policies
        $this->authorize('update', $reply->thread);
        // $reply->thread->update(['best_reply_id' => $reply->id]);
        $reply->thread->markBestReply($reply);
    }
}
