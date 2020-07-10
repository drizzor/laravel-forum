@forelse ($threads as $thread)
    <div class="card mb-3">
        
        <div class="card-header d-flex align-items-center">
            <div class="flex-grow-1 h5 pt-1 mt-1">
                <a href="{{ $thread->path() }}">
                    @if (auth()->check() && $thread->hasUpdateFor(auth()->user()))
                        <strong>
                            {{ ucfirst($thread->title) }}
                        </strong>
                    @else 
                        {{ ucfirst($thread->title) }}
                    @endif                                
                </a>  
                <h6 class="text-muted mt-2">
                    Posté part <a href="{{ route('profile', $thread->creator) }}">{{ $thread->creator->name }}</a>
                </h6>                                 
            </div>
            
            <a href="{{ $thread->path() }}" class="btn btn-secondary btn-sm" title="Réponse(s)">
                {{-- {{ $thread->replies_count }} {{ Str::plural('réponse', $thread->replies_count) }} --}}
                <i class="far fa-comment-dots"></i> {{ $thread->replies_count }}
            </a>      
            <a href="{{ $thread->path() }}" class="btn btn-success btn-sm" title="Vue(s)">
                <i class="far fa-eye"></i> {{ $thread->visits()->count() }}  
                {{-- {{ $thread->visits_count }} --}}
            </a>                              
        </div>

        <div class="card-body">
            <div class="body">{{ $thread->body }}</div>
        </div>

        {{-- <div class="card-footer">
            
        </div> --}}
    </div>
@empty
    <p>Aucun thread trouvé pour l'instant.</p>            
@endforelse