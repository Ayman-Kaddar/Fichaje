@extends('template.mainadmin')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/fitxatge-admin.css') }}">
    <link rel="stylesheet" href="{{ asset('css/barraNavegacio.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endpush

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
    <script src="{{ asset('js/fitxatge-admin.js') }}"></script>
@endpush

@section('content')
    <div id="imatgeBody">
        <div id="centrar">
            <form action="{{ route('fitxadmin') }}" method="POST" id="form-filter" class="mb-3">
                @csrf
                <select name="user_filter" class="select2">
                    <option value="" disabled selected>Selecciona un usuari</option>
                    @foreach ($users as $user)
                        <option value="{{ $user->id }}">{{ $user->name }} - {{ $user->email }}</option>
                    @endforeach
                </select>
            </form>

            <p class="user_name">{{ $user_name }}</p>

            <div id="tablaFichaje" class="col-md-7 table-responsive table-container">
                <table class="table table-dark table-striped" id="my-table">
                    <thead>
                        <tr>
                            <th scope="col">Data</th>
                            <th scope="col">Entrada</th>
                            {{-- <th scope="col" class="pausa-continuitat">Pausa</th>
                            <th scope="col" class="pausa-continuitat">Continuïtat</th> --}}
                            <th scope="col">Descans</th>
                            <th scope="col">Sortida</th>
                            <th scope="col">Temps Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($fitxatges)
                            @foreach ($fitxatges as $fitxatge)
                                <tr>
                                    <td scope="col">{{ $fitxatge->data }}</td>
                                    <td scope="col">{{ $fitxatge->entrada }}</td>
                                    {{-- <td scope="col"
                                        class="pausa-continuitat @if ($fitxatge->pausa == '-') text-center @endif">
                                        {{ $fitxatge->pausa }}</td>

                                    <td scope="col"
                                        class="pausa-continuitat @if ($fitxatge->continuitat == '-') text-center @endif">
                                        {{ $fitxatge->continuitat }}</td> --}}
                                    <td scope="col">{{ $fitxatge->descans }}</td>
                                    <td scope="col">{{ $fitxatge->sortida }}</td>
                                    <td scope="col">{{ $fitxatge->hores_totals }}</td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>

    </div>
@endsection
