<x-master>

@section('header')
    <link rel="stylesheet" href="/css/vendor/jquery.atwho.css">
@endsection

<thread-view :thread="{{ $thread }}" inline-template>
    <div class="row mb-4">
        <div class="col-md-8">

            <div class="card mb-3">
                <div class="card-header">

                    <div class="d-flex align-items-end">                        

                        <img src="{{ $thread->creator->avatar_path }}" width="50" height="50" class="rounded-circle mr-3">
                        
                        <h4 class="flex-grow-1 text-dark">                            
                            {{ ucfirst($thread->title) }}
                        </h4>                                 

                    </div>
                                                            
                    <div class="mt-2 text-sm text-muted">
                        Créé par  <a href="{{ route('profile', $thread->creator) }}">{{ $thread->creator->name }}</a>
                        {{ $thread->created_at->diffForHumans() }}  
                    </div>                    

                </div>

                <div class="card-body">
                    <article>
                        <div class="body">{{ $thread->body }}</div>
                    </article>                    
                </div>
                
                @can('update', $thread)
                    <div class="card-footer d-flex justify-content-end">
                   
                        <form method="POST" action="{{ $thread->path() }}">

                            @csrf
                            @method('DELETE')

                            <button type="submit" class="btn btn-danger btn-sm" title="Supprimer le sujet">
                                <i class="fas fa-trash"></i>
                            </button>

                        </form>
                    </div>
                @endcan          

            </div>

            <replies
                @removed="repliesCount--"
                @added="repliesCount++">
            </replies>

            {{-- Element ci-dessous géré avec VUEJS --}}
            {{-- @foreach ($replies as $reply)
                @include ('threads._replies')
            @endforeach  

            {{ $replies->links() }} --}}

            {{-- Partie ci-dessous géré via VUEJS --}}
            {{-- @auth
                <form method="POST" action="{{ $thread->path() }}/replies">
                    
                    @csrf 

                    <div class="form-group">
                        <label for="body">Répondre</label>
                        <textarea name="body" id="body" cols="30" rows="5" placeholder="Dire quelque chose..." class="form-control @error('body') is-invalid @enderror" required>{{ old('body') }}</textarea>
                        @error ('body')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>                            
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary">Poster</button>

                </form>

                @else 
                    <p class="text-center"><a href="{{ route('login') }}">Connectez-vous</a> pour participer à la discussion.</p> 
            @endauth --}}
        
        </div> 

        <div class="col-md-4">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('threads') }}">Tous les sujets</a></li>
                    <li class="breadcrumb-item"><a href="{{ '/threads/' . $thread->channel->slug }}">{{ $thread->channel->name }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $thread->title }}</li>
                </ol>
            </nav>        

            <div class="card">
                <div class="card-body">
                    <p>
                        Ce sujet a été créé le {{ $thread->created_at->diffForHumans() }} par
                        <a href="#">{{ $thread->creator->name }}</a> et a 
                        un total de <span v-text="repliesCount"></span>
                        {{-- un total de {{ $thread->replies_count /*$thread->replies()->count()*/ }} --}} {{ Str::plural(' commentaire', $thread->replies_count) }}.  
                    </p>

                    <p>
                        <subscribe-button :active="{{ $thread->isSubscribedTo ? 'true' : 'false'}}" v-if="signedIn"></subscribe-button>             
                        <button class="btn btn-outline-danger btn-block" v-if="authorize('isAdmin') && !locked" @click="lock">VEROUILLER</button>  
                        <button class="btn btn-danger btn-block" v-else @click="unlock">DEVEROUILLER</button>         
                    </p>
                </div>
            </div>
        </div>
    </div>       
</thread-view>
</x-master>
