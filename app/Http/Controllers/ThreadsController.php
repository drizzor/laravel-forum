<?php

namespace App\Http\Controllers;

use App\Thread;
use App\Channel;
use App\Trending;

use App\Rules\SpamFree;
use Illuminate\Http\Request;
use App\Filters\ThreadFilters;
use App\Rules\Recaptcha;

class ThreadsController extends Controller
{
    public function __construct()
    {
        //   $this->middleware('auth')->only(['store', 'create']);
        $this->middleware('auth')->except(['index', 'show']);
    }

    public function index(Channel $channel, ThreadFilters $filters, Trending $trending)
    {
        // $threads = $this->getThreads($channel); // Fonctionne, mais pas forcément très élégant et on aura d'autre filtre à faire

        $threads = $this->getThreads($channel, $filters);

        if (request()->wantsJson()) {
            return $threads;
        }

        return view('threads.index', [
            'threads' => $threads,
            'trending' => $trending->get()
        ]);
    }

    public function create()
    {
        // $channels = Channel::orderBy('name', 'asc')->get(); // gloablisée avec AppServiceProvider => plus besoin de faire la requete ici
        return view('threads.create'/*, compact('channels')*/);
    }

    public function show(Channel $channel, Thread $thread, Trending $trending)
    {
        // $thread->replies->load('owner'); // je joins chaque réponse associée au thread. De cette façon je diminue le nombre de requete
        // return $thread->append('isSubscribedTo'); Append directement géré via l'attribut dans modèle car on veut que cette valeur soit tjrs lié à notre collection thread

        // Indiquer que l'utilisateur a visité cette page (timestamp)
        if (auth()->check()) {
            auth()->user()->read($thread);
        }

        // App/Trending - incr de 1 la popularité du Thread
        $trending->push($thread);

        // Enregistre la visite du thread
        $thread->visits()->record();
        // $thread->increment('visits_count');

        return view('threads.show', [
            'thread' => $thread,
            'replies' => $thread->replies()->paginate(10) // pagination plus exploitée car effectuée via VUEJS voir controller index de Replies
        ]);
    }

    public function store(Recaptcha $recaptcha)
    {
        // Validate
        $attributes = request()->validate([
            'title' => ['required', 'max:30', 'min:5', new SpamFree()],
            'body' => ['required', 'min:10', new SpamFree()],
            'channel_id' => 'required|exists:channels,id',
            'g-recaptcha-response' => [$recaptcha]
        ]);

        // Create
        $thread = Thread::create([
            'user_id' => auth()->id(),
            'channel_id' => $attributes['channel_id'],
            'title' => $attributes['title'],
            'slug' => $attributes['title'],
            'body' => $attributes['body']
        ]);

        // Redirect
        return redirect($thread->path())
            ->with('flash', 'Votre thread a été publié.');
    }

    public function update($channel, Thread $thread)
    {
        // authorization
        $this->authorize('update', $thread);

        // validation
        $attributes = request()->validate([
            'title' => ['required', 'max:30', 'min:5', new SpamFree()],
            'body' => ['required', 'min:10', new SpamFree()],
        ]);

        // update the thread
        $thread->update($attributes);
    }

    public function destroy(Channel $channel, Thread $thread, Trending $trending)
    {
        // if($thread->user_id != auth()->id()) {
        //     abort(403, 'Vous n\'avez pas la permission de faire cette action.');
        // }             

        $this->authorize('update', $thread);

        // Suppression de la clé Redis & élément 
        $thread->visits()->reset();
        $trending->remove($thread);

        $thread->delete();

        if (request()->wantsJson()) {
            return response([], 204);
        }

        return redirect(route('threads'));
    }

    /**
     * N'est pas exploitée, on a préféré l'utilisation de filters... (cependant un peu trop complexe pour moi)
     */
    private function getThreads__old(Channel $channel)
    {
        if ($channel->exists) {
            // $channelId = Channel::where('slug', $channel->slug)->first()->id;
            // $threads = Thread::where('channel_id', $channelId)->latest()->get();
            $threads = $channel->threads()->latest(); // plus propre, en une commande et en se basant sur notre hasMany threads()
        } else $threads = Thread::latest();

        // si requete par 'by', on doit alors filtrer via le nom d'utilisateur donné
        if ($username = request('by')) {
            $user = \App\User::where('name', $username)->firstOrFail();
            $threads->where('user_id', $user->id);
        }

        return $threads = $threads->get();
    }

    /**
     * Mise en action du filtre
     */
    private function getThreads($channel, $filters)
    {
        $threads = Thread::latest()->filter($filters);

        if ($channel->exists) {
            $threads->where('channel_id', $channel->id);
        }

        // dd($threads->toSql());

        return $threads->paginate(10);
    }
}
