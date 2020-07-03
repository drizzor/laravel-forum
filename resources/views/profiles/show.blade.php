<x-master>              

    <div class="row">
        <div class="col-md-8 col-md-offset-2">

            {{-- <h1 class="display-4 text-muted">
                {{ $profileUser->name }} 
                <small class="h2 text-sm">Profil créé {{ $profileUser->created_at->diffForHumans() }}</small>
                <hr>
            </h1>   --}}

            {{-- @can('update', $profileUser)
                <form  method="POST" action="{{ route('avatar_path', $profileUser) }}" enctype="multipart/form-data">
                    @csrf
                    <input type="file" name="avatar">
                    <button type="submit" class="btn btn-primary">Ajouter Avatar</button>
                </form>
            @endcan 

            <img src="{{ $profileUser->avatar() }}" width="80" height="80" alt="User image profile" class="mb-5">  --}}

            <avatar-form :user="{{ $profileUser }}"></avatar-form>

            @forelse ($activities as $date => $activity)
                <h3 class="text-muted"> {{ $date }} </h3>
                <hr>
                @foreach ($activity as $record)
                    @if (view()->exists("profiles.activities.{$record->type}"))
                        @include("profiles.activities." . $record->type, ['activity' => $record])  
                    @endif                      
                @endforeach
                
            @empty 

                <h3 class="text-muted text-center">Aucune activité pour le moment...</h3>

            @endforelse
        </div>
    </div>             

</x-master>
