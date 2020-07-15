<reply :attributes="{{ $reply }}" inline-template v-cloak>

<div class="card mb-3">

    <div class="card-header" id="reply-{{ $reply->id }}">
        <div class="d-flex justify-content-start align-items-center">
            <div class="flex-grow-1">
                <a href="{{ route('profile', $reply->owner) }}">{{ $reply->owner->name }}</a> 
                {{ $reply->created_at->diffForHumans() }}.
            </div>            

            @auth
                <favorite :reply="{{ $reply }}"></favorite>
            @endauth                            

            {{-- <form method="POST" action="{{ route('fav_reply', $reply->id) }}">
                @csrf
                <button type="submit" class="btn {{ $reply->isFavorited() ? 'btn-success' : 'btn-secondary' }}">
                    <i class="fas fa-thumbs-up"></i>
                    {{ $reply->favorites_count }} {{ Str::plural('Like', $reply->favorites_count) }}
                </button>   
            </form> --}}
        </div>
    </div>    

    <div class="card-body">
        <div v-if="editing">
            <div class="form-group">
                <textarea class="form-control" v-model="body"></textarea>
            </div>
            <button class="btn btn-sm btn-outline-primary" @click="update">
                <i class="fas fa-check"></i> Valider
            </button>
            <button class="btn btn-sm btn-outline-secondary" @click="editing = false">
                <i class="fas fa-undo"></i> Annuler
            </button>
        </div>

        <div v-else v-text="body"></div>                                    
    </div>

    @can('update', $reply)
        <div class="card-footer d-flex">
            <button class="btn btn-warning btn-sm mr-2" @click="editing = true" v-if="!editing">
                <i class="fas fa-pencil-alt pr-1"></i> Editer
            </button>
            <button class="btn btn-danger btn-sm" @click="destroy" v-if="!editing">
                <i class="fas fa-trash pr-1"></i> Supprimer
            </button>
            {{-- <form action="{{ route('delete_reply', $reply->id) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger btn-sm">
                    <i class="fas fa-trash"></i> Supprimer
                </button>                
            </form>   --}}
        </div> 
    @endcan   

</div>

</reply>   