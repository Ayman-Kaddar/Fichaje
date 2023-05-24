@extends('template.auth')

@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.17/dist/sweetalert2.min.css">
@endpush


@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.17/dist/sweetalert2.min.js"></script>
    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script> --}}

    <script>
        @if (session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: '{{ session('error') }}',
            });
        @endif
    </script>
@endpush


@section('content')
    <div id="imatgeBody">
        <div class="container">
            <div class="login-box">
                <div class="lb-header">
                    <a class="active empleado-link">Treballador</a>
                    <a class="administrador-link">Administrador</a>
                </div>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <form class="empleado-form" action="{{ route('login-google') }}" method="POST">
                    @csrf
                    <p class="title-empresa"> IdentWork <br><img src="https://logodix.com/logo/2134548.png" width="60"
                            height="60" alt="Logo"></p>
                    <p class="info-google">Inicieu sessió amb Google:</p>
                    <div class="u-form-group">
                        <div class="button-box">
                            <button class="btn-google"> <img src="https://www.google.com/favicon.ico" alt="google"><a
                                    href="{{ route('login-google') }}"> </a></button>
                        </div>
                    </div>
                </form>

                <form class="administrador-form" action="{{ route('login') }}" method="POST">
                    @csrf
                    <div class="u-form-group">
                        <input type="email" name="email" placeholder="Email" value="{{ old('email_login') }}">
                    </div>
                    <div class="u-form-group">
                        <input type="password" name="password" placeholder="Contrasenya" />
                    </div>

                    <div class="u-form-group">
                        <div class="button-box">
                            <button class="btn">Iniciar sessió</button>
                        </div>
                        <br>
                        <br>
                        <p class="or">o</p>
                        <p class="create-account">Us fa falta un compte d'administrador? <a
                                href="{{ route('register') }}">Creen un ara</a></p>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('styles')
@endpush

@push('scripts')
@endpush
