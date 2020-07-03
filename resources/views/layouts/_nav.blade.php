<nav class="navbar fixed-top navbar-expand-md navbar-light bg-white shadow-sm">
    <div class="container">
    <a class="navbar-brand" href="/">
            {{ config('app.name', 'Laravel') }}
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <!-- Left Side Of Navbar -->
            <ul class="navbar-nav mr-auto">
                <li class="nav-item dropdown">
                    <a id="allThreads" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" >
                        Naviguer
                    </a>

                    <div class="dropdown-menu" aria-labelledby="allThreads">
                            <a class="dropdown-item" href="{{ route('threads') }}">
                                Tous Les Sujets
                            </a>
                            <a class="dropdown-item" href="{{ '/threads?popular=1'}}">
                                Par Popularité
                            </a>
                            <a class="dropdown-item" href="{{ '/threads?unanswered=1'}}">
                                Thread sans réponse
                            </a>
                            @auth
                                <a class="dropdown-item" href="{{ '/threads?by=' . Auth::user()->name }}">
                                    Mes Sujets
                                </a>
                            @endauth
                    </div>
                </li>
                <a class="nav-link" href="{{ route('create_thread') }}">New Thread</a>
                <li class="nav-item dropdown">
                    <a id="channelDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" >
                        Channels
                    </a>

                    <div class="dropdown-menu" aria-labelledby="channelDropdown">
                        @foreach ($channels as $channel)
                            <a class="dropdown-item" href="/threads/{{ $channel->slug }}">
                                {{ $channel->name }}
                            </a>
                        @endforeach
                    </div>
                </li>

            </ul>

            <!-- Right Side Of Navbar -->
            <ul class="navbar-nav ml-auto">
                <!-- Authentication Links -->
                @guest
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                    </li>
                    @if (Route::has('register'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                        </li>
                    @endif
                @else
                
                    <user-notifications></user-notifications>

                    <li class="nav-item dropdown">
                        <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                            <img src="{{ Auth::user()->avatar_path }}" class="rounded-circle mb-1" width="25" height="25">
                            <span class="caret">{{ ucfirst(Auth::user()->name) }}</span>     
                                                                                       
                        </a>

                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                            <a href="{{ route('profile', Auth::user()) }}" class="dropdown-item">
                                Mon Profile
                            </a>

                            <a class="dropdown-item" href="{{ route('logout') }}"
                               onclick="event.preventDefault();
                                             document.getElementById('logout-form').submit();">
                                {{ __('Logout') }}
                            </a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </div>
                    </li>
                @endguest
            </ul>
        </div>
    </div>
</nav>