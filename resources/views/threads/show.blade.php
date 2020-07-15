<x-master>

@section('header')
    <link rel="stylesheet" href="/css/vendor/jquery.atwho.css">
@endsection

<thread-view :thread="{{ $thread }}" inline-template>
    <div class="row mb-4" v-cloak>
        
        @include('threads._thread')

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
                        <button class="btn btn-danger btn-block" v-else-if="authorize('isAdmin') && locked" @click="unlock">DEVEROUILLER</button>         
                    </p>
                </div>
            </div>
        </div>
    </div>       
</thread-view>
</x-master>
