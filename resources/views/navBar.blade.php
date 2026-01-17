
@section('navBar')
<nav class="navbar navbar-expand-xl bg-white  py-3">
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
                @can('isStaff')
                         <li class="nav-item dropdown ms-3">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarAdminDropdown"
                            role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Dashboard
                            </a>

                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarAdminDropdown">

                                <li>
                                    <a class="dropdown-item" href="{{ route('staff.catalogo.index') }}">
                                        Catalogo
                                    </a>
                                </li>
                               
                                <li>
                                    <a class="dropdown-item" href="">
                                        Prenotazioni
                                    </a>
                                </li>
                                
                                <li>
                                    <a class="dropdown-item" href="{{ route('staff.ordini.index')}}">
                                        Ordini
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('staff.vigneti.richieste')}}">
                                        Vigneti
                                    </a>
                                </li>
                            </ul>
                        </li>
                    @endcan
                    @can('isAdmin')
                         <li class="nav-item dropdown ms-3">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarAdminDropdown"
                            role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Dashboard
                            </a>

                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarAdminDropdown">

                                <li>
                                    <a class="dropdown-item" href="{{ route('catalogo.index') }}">
                                        Catalogo
                                    </a>
                                </li>
                               
                                <li>
                                    <a class="dropdown-item" href="{{ route('dipendenti.index') }}">
                                        Dipendenti
                                    </a>
                                </li>
                                
                                <li>
                                    <a class="dropdown-item" href="{{ route('admin.rifornimenti.getRifornimenti') }}">
                                        Rifornimenti
                                    </a>
                                </li>
                                
                            </ul>
                        </li>
                    @endcan
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

                @can('isUtente')
                <li class="nav-item">
                    <a class="nav-link fs-4" href="{{ route('carrello.index') }}">🛒</a>
                </li>
                @endcan


                {{-- Area utente --}}
                @auth

                    @can('isAdmin')
                        <li class="nav-item dropdown ms-3">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarAdminDropdown"
                            role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Admin
                            </a>

                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarAdminDropdown">

                                {{-- Logout dentro al dropdown --}}
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button class="dropdown-item" type="submit">
                                            Logout
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @endcan

                    @can('isStaff')
                         <li class="nav-item dropdown ms-3">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarAdminDropdown"
                            role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Staff
                            </a>

                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarAdminDropdown">

                                {{-- Logout dentro al dropdown --}}
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button class="dropdown-item" type="submit">
                                            Logout
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @endcan

                    @can('isUtente')
                         <li class="nav-item dropdown ms-3">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarAdminDropdown"
                            role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Utente
                            </a>

                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarAdminDropdown">
                                {{-- Link alla pagina admin --}}
                                <li>
                                    <a class="dropdown-item" href="{{ route('utente.ordini') }}">
                                        Ordini
                                    </a>
                                </li>

                                <li>
                                    <a class="dropdown-item" href="{{ route('utente.vigneti.miei') }}">
                                        Miei Vigneti
                                    </a>
                                </li>

                                <li><hr class="dropdown-divider"></li>

                                {{-- Logout dentro al dropdown --}}
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button class="dropdown-item" type="submit">
                                            Logout
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @endcan

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