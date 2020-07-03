<x-activity>
    @slot('heading')
        <i class="fas fa-comment text-muted"></i> {{ $profileUser->name }} a posté une réponse sur 
        
        <a href=" {{ $activity->subject->thread->path() }} ">
            {{ $activity->subject->thread->title }}
        </a> 
   @endslot 

   @slot('subheading')
        {{ ucfirst($activity->subject->created_at->diffForHumans()) }}  
   @endslot

   @slot('body')
        {{ $activity->subject->body }}
   @endslot
</x-activity>