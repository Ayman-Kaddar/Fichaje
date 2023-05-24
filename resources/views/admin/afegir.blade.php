@extends('template.mainadmin')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/afegir-admin.css') }}">
    <link rel="stylesheet" href="{{ asset('css/barraNavegacio.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.17/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css">
@endpush

@push('scripts')
    <script src="{{ asset('js/afegir-admin.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.17/dist/sweetalert2.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>
    <script>
        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Éxito',
                text: '{{ session('success') }}',
            });
        @endif
        @if (session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: '{{ session('error') }}',
            });
        @endif

        @if (session('showSweetAlert'))
            Swal.fire({
                icon: '{{ session('showSweetAlert.type') }}',
                title: '{{ session('showSweetAlert.title') }}',
                text: '{{ session('showSweetAlert.text') }}',
            });
        @endif
    </script>
@endpush

@section('content')
    <div id="imatgeBody">
        <div class="centrar" style="text-align: center;">
            <form action="{{ route('import-users') }}" method="POST" enctype="multipart/form-data" class="form-file"
                accept-charset="UTF-8" accept-language="ca">
                @csrf
                <div class="form-group">
                    <label for="file" class="form-label">Selecciona l'arxiu excel per afegir els treballadors:</label>
                    <input type="file" id="file" name="file" class="form-input" accept=".xls, .xlsx">
                </div>
                <div style="display: flex; justify-content: space-between;">
                    <button type="submit" class="form-btn" style="margin-right: 10px;">Pujar arxiu</button>
                    <a href="{{ route('export-users') }}" class="form-btn">Descarregar empleats</a>
                </div>
            </form>
            <div class="table-responsive table-container table-users mt-5">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Nom</th>
                                <th>Correo electrónico</th>
                                <th>Accions</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($users as $user)
                                <tr>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>
                                        <form action="{{ route('users.destroy', $user) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger">Eliminar</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                </div>
                </table>
            </div>
        </div>
    </div>
@endsection
