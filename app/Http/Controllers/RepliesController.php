<?php

namespace App\Http\Controllers;

use App\User;
use App\Reply;
use App\Thread;
use App\Channel;
use App\Rules\SpamFree;
use App\Notifications\YouWereMentioned;
use Illuminate\Http\Request;

use App\Http\Requests\CreatePostRequest;

class RepliesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', ['except' => 'index']);
    }

    public function index($channelId, Thread $thread)
    {
        return $thread->replies()->paginate(10);
    }

    public function store(Channel $channel, Thread $thread, CreatePostRequest $form)
    {
        // Check anti flood system (policy) -> tout délocalisé dans $form (Request)
        // Utilisation de gate:denies plutot qu'authorize afin de pouvoir passer un msg personnalisé
        // if (Gate::denies('create', new Reply)) {
        //     return response(
        //         'Vous postez trop vite. Un instant svp !',
        //         429
        //     );
        // }
        // $this->authorize('create', new Reply);

        $reply = $thread->addReply([
            'body' => request('body'),
            'user_id' => auth()->id()
        ]);

        // Retourne format JSON afin que VUEJS puisse afficher la réponse immédiatement dans l'interface (sans refresh)
        return $reply->load('owner');
    }

    public function update(Reply $reply)
    {
        $this->authorize('update', $reply);

        $this->validate(request(), [
            'body' => ['required', new SpamFree()]
        ]);

        $reply->update(request(['body']));
    }

    public function destroy(Reply $reply)
    {
        $this->authorize('update', $reply);

        $reply->delete();

        if (request()->expectsJson()) {
            return response(['status' => 'Réponse supprimée.']);
        }

        return back();
    }

    // N'est plus exploité depuis le passage vers App/Rules
    protected function validateReply()
    {
        $this->validate(request(), [
            'body' => ['required', new SpamFree()]
        ]);

        // Resolve nous permet d'utiliser une classe sans devoir l'introduire depuis les paramètres
        // resolve(Spam::class)->detect(request('body')); -> Désormais exploité via App/rule, afin d'éviter de copier ses meme lignes de code encore et encore dans le threadscontroller
    }
}
