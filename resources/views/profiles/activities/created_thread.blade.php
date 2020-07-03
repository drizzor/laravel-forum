<x-activity>
   @slot('heading')
        <i class="fab fa-wpforms"> </i>
        {{ $profileUser->name }} a publié 
        <a href="{{ $activity->subject->path() }}">
            {{ $activity->subject->title }}
        </a>
   @endslot 

   @slot('subheading')
        {{ ucfirst($activity->subject->created_at->diffForHumans()) }}  
   @endslot

   @slot('body')
        {{ $activity->subject->body }}
   @endslot
</x-activity>