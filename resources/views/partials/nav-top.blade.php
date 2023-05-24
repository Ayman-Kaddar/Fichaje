<nav class="navbar navbar-expand-lg navbar-light" id="barraNavegacio">
  <div class="container-fluid">
    <a class="navbar-brand">
            <img src="{{ asset('img/logo.png') }}" alt="Logo">
        </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria
-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link @if(strpos(Route::currentRouteAction(),'ClienteController@inici')) paginaActual barra-baja @else lletraNav-transition @endif" href="{{ route('inici') }}">Inici</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link @if(str_contains(Request::url(), '/agenda')) paginaActual barra-baja @else lletraNav-transition @endif" href="{{ route('agenda') }}">Agenda</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link @if(str_contains(Request::url(), '/fitxatge')) paginaActual barra-baja @else lletraNav-transition @endif" href="{{ route('fitxatge') }}">Fitxatge</a>
                </li>
                @auth
                    <li class="nav-item">
                        <a class="nav-link estilNav">
                            {{ Auth::user()->name }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <a class="nav-link lletraNav-transition" href="{{ route('logout') }}"
                                onclick="event.preventDefault(); this.closest('form').submit();">
                                {{ __('Sortir') }}
                            </a>
                        </form>
                    </li>
                @else
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">{{ __('Log in') }}</a>
                    </li>
                @endauth
            </ul>
        </div>
    </div>
</nav>
