<x-master>
    @section('header')
        {{-- <script src="https://www.google.com/recaptcha/api.js" async defer></script> --}}
    @endsection
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Créer une discussion</div>
    
                    <div class="card-body">                                                    
                            
                        <form method="POST" action="{{ route('add_thread') }}">
                            @csrf

                            <div class="form-group">
                                <label for="channel_id">Choisir Un Canal</label>
                                <select class="form-control @error('channel_id') is-invalid @enderror" name="channel_id" id="channel_id" required>
                                <option value="">Votre Choix...</option>  
                                @foreach ($channels as $channel)
                                    <option value="{{ $channel->id }}" {{ old('channel_id') != $channel->id ?: 'selected' }}>{{ $channel->name }}</option>
                                @endforeach
                                </select>
                                @error ('channel_id')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="title">Titre Du Sujet</label>
                                <input type="text"  name="title" id="title" class="form-control  @error('title') is-invalid @enderror" value="{{ old('title') }}" required>
                                @error ('title')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="body">Votre Message</label>
                                <wysiwyg></wysiwyg>                                    
                                {{-- <textarea class="form-control @error('body') is-invalid @enderror" name="body" id="body" rows="5" required> {{ old('body') }} </textarea> --}}
                                @error ('body')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>                                
                                @enderror
                            </div>
                                
                            {{-- <div class="g-recaptcha" data-sitekey="6Lfi_a4ZAAAAAErt5Ts-4WmjONEjrTS9VE4Ly83L"></div> 
                            @error ('g-recaptcha-response')
                                    <p class="text-danger">{{ $message }}</p>                            
                            @enderror --}}

                            <button type="submit" class="btn btn-primary">Publier</button>

                        </form>                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-master>