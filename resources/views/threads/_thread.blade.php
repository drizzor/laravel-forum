<div class="col-md-8">
    {{-- Editing mode --}}
    <div class="card mb-3" v-if="editing">
        <div class="card-header">
            <input type="text" class="form-control form-control-lg" v-model="form.title" autofocus>                      
        </div>

        <div class="card-body">
            <textarea class="form-control" v-model="form.body"></textarea>
        </div>
        
        @can('update', $thread)

            <div class="card-footer d-flex">
                <button class="btn btn-outline-primary btn-sm mr-1" title="Editer le thread" @click="update">
                    Editer
                </button>

                <button class="btn btn-outline-secondary btn-sm mr-1" title="Editer le thread" @click="cancel">
                    Annuler
                </button>

                <div class="ml-auto">
                    <form method="POST" action="{{ $thread->path() }}">
                        @csrf
                        @method('DELETE')                    
    
                        <button type="submit" class="btn btn-danger btn-sm" title="Supprimer le thread">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>

                </div>
            </div>
            
        @endcan          

    </div>

    {{-- No editing mode --}}
    <div class="card mb-3" v-else>
        <div class="card-header">
            <div class="d-flex align-items-end">                        
                <img src="{{ $thread->creator->avatar_path }}" width="50" height="50" class="rounded-circle mr-3">   
                <h4 class="flex-grow-1 text-dark" v-text="form.title"></h4>                                
            </div>                                         
            <div class="mt-2 text-sm text-muted">
                Créé par  <a href="{{ route('profile', $thread->creator) }}">{{ $thread->creator->name }}</a>
                {{ $thread->created_at->diffForHumans() }}  
            </div>                    
        </div>

        <div class="card-body">
            <div class="body" v-text="form.body"></div>
        </div>
        
        @can('update', $thread)
            <div class="card-footer d-flex justify-content-end">
                <button class="btn btn-dark btn-sm mr-1" title="Editer le thread" @click="editing = true">
                    <i class="fas fa-pencil-alt"> </i>
                </button>
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