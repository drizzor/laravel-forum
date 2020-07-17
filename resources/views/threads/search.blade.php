<x-master>
    <div class="col-md-8">
        {{-- @auth
        <div class="d-flex flex-row-reverse">
            <a href="{{ route('create_thread') }}" class="btn btn-primary mb-3">Créer un sujet</a>
        </div>
        @endauth --}}
    </div>
    
    <ais-index app-id="{{ config('scout.algolia.id') }}" api-key="{{ config('scout.algolia.public') }}" index-name="threads" query="{{ request('q') }}">
        <div class="row justify-content-center">    

            <div class="col-md-8">           
                <ais-results>
                    <template scope="{ result }">
                        <li>
                            <a :href="result.path">
                                <ais-highlight :result="result" attribute-name="title"></ais-highlight>
                            </a>
                        </li>
                    </template>
                </ais-results>
            </div>
        
            <div class="col-md-4">
                <div class="card mb-2">
                    <div class="card-header">
                        Rechercher
                    </div>
                    <div class="card-body">
                        <ais-search-box>
                            <div class="input-group mb-3">
                                <ais-input placeholder="Votre Recherche..." :autofocus="true" class="form-control"></ais-input>      
                                <div class="input-group-append"> 
                                    <ais-clear class="btn btn-outline-secondary"><i class="fas fa-undo"></i></ais-clear>          
                                </div> 
                            </div>             
                        </ais-search-box>
                       
                    </div>                   
                </div>

                <div class="card mb-2">
                    <div class="card-header">
                        Filtrer Par Channel
                    </div>
                    <div class="card-body">
                        <ais-refinement-list attribute-name="channel.name"></ais-refinement-list>
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
    </ais-index>
</x-master>