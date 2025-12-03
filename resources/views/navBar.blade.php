
@section('navBar')
<nav class="navbar navbar-expand-lg bg-white  py-3">
    <div class="container-fluid">

        {{-- LOGO CENTRALE (VISIBILE SOLO SU DESKTOP) --}}
        

        {{-- Mobile hamburger --}}
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#mainNavbar" aria-controls="mainNavbar"
                aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        {{-- CONTENUTO COLLASSABILE --}}
        <div class="collapse navbar-collapse" id="mainNavbar">

            {{-- SEZIONE SINISTRA (DESKTOP) --}}
            <ul class="navbar-nav me-auto mb-2 mb-lg-0  navbar-left">
                <li  class="nav-item"><a  class="nav-link" href="{{ route(name: 'vini') }}">Il Vino</a></li>
                <li  class="nav-item"><a  class="nav-link" href="{{ route('tenute') }}">Le Tenute</a></li>
            </ul>

            {{-- VERSIONE MOBILE: LOGO SPOSTATO DENTRO IL MENU 
            <a class="navbar-brand d-lg-none fw-bold fs-3" href="">
                Bellò
            </a>--}}

            <a class="navbar-brand position-absolute start-50 translate-middle-x d-none d-lg-block fw-bold fs-3" href="{{ route('home') }}">
            <img src="{{ asset('img/logo_bellò.png') }}" alt="Logo Bellò" class="logo-navbar">
            </a>


            {{-- SEZIONE DESTRA (DESKTOP) --}}
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0 navbar-right">

                <li class="nav-item">
                    <a  class="nav-link" href="{{ route('affitta') }}">Affitta un vigneto</a>
                </li>

                <li class="nav-item">
                    <a  class="nav-link" href="{{ route('shop') }}">Shop</a>
                </li>

                {{-- Icona carrello da inserire solo se autenticato--
                <li class="nav-item">
                    <a class="nav-link fs-4" href="#">🛒</a>
                </li>
                -}}
                {{-- Area utente --}}
                @auth

                    @can('isAdmin')
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('provaAdmin') }}">Admin</a>
                        </li>
                    @endcan

                    @can('isStaff')
                        <li class="nav-item">
                            <a class="nav-link" href="">Staff</a>
                        </li>
                    @endcan

                    @can('isUtente')
                        <li class="nav-item">
                            <a class="nav-link" href="">Account</a>
                        </li>
                    @endcan

                    <li class="nav-item">
                        <form action="{{ route('logout') }}" method="POST" class="d-inline">
                            @csrf
                            <button class="nav-link btn btn-link p-0 text-danger">Logout</button>
                        </form>
                    </li>

                @else
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">Login</a>
                    </li>
                @endauth

            </ul>
        </div>
    </div>
</nav>



@endsection