<x-activity>
    @slot('heading')
        <i class="fas fa-heart text-muted"> </i>
        <a href="{{ $activity->subject->favorited->path() }}">
            {{ $profileUser->name }} a mis en favoris une réponse.
        </a> 
        
        {{-- <a href=" {{ $activity->subject->thread->path() }} ">
            {{ $activity->subject->thread->title }}
        </a>  --}}
   @endslot 

   @slot('subheading')
        {{-- {{ ucfirst($activity->subject->created_at->diffForHumans()) }}   --}}
   @endslot

   @slot('body')
        {{ $activity->subject->favorited->body }}
   @endslot
</x-activity>