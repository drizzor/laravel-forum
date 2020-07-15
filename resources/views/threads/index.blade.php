<x-master>
<div class="col-md-8">
    @auth
    <div class="d-flex flex-row-reverse">
        <a href="{{ route('create_thread') }}" class="btn btn-primary mb-3">Créer un sujet</a>
    </div>
    @endauth
</div>

<div class="row justify-content-center">
    
    <div class="col-md-8">           
        @include('threads._list')
        
        <div class="d-flex justify-content-center">
            {{ $threads->links() }}
        </div>
        
    </div>

    <div class="col-md-4">
        <div class="card mb-2">
            <div class="card-header">
                Rechercher
            </div>
            <div class="card-body">
                <form method="post" action="{{ route('threads.search') }}">
                    @csrf
                    @method('GET')
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" name="q" placeholder="Votre Recherche...">
                        <div class="input-group-append">
                            <button class="btn btn-outline-secondary" type="submit" id="button-addon2"><i class="fas fa-search"></i></button>
                        </div>
                        
                    </div>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                Sujets Populaires
            </div>
            <div class="card-body">
                <ul class="list-group">
                    @forelse ($trending as $thread)
                        <li class="list-group-item">
                            {{-- {{dd($thread)}} --}}
                            <a href="{{ url($thread->path) }}">{{ $thread->title }}</a> 
                        </li>
                    @empty 
                        <p>Aucun sujet populaire pour l'instant...</p>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
</div>
</x-master>