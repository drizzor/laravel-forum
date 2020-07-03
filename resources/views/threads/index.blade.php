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