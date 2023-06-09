@extends('template.main')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.1/dist/sweetalert2.min.css" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/fitxatge-client.css') }}">
<link rel="stylesheet" href="{{ asset('css/barraNavegacio.css') }}">
@endpush

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.1/dist/sweetalert2.all.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick.min.js"></script>
<script src="{{ asset('js/fitxatge-client.js') }}"></script>
@endpush

@section('content')

<script>
    var crema_brillant = "{{ asset('img/crema-brillant.png') }}";
    var baige = "{{ asset('img/baige.png') }}";
    var vermell = "{{ asset('img/vermell.png') }}";
    var taronja = "{{ asset('img/taronja.png') }}";    
    var groc = "{{ asset('img/groc.png') }}";
    var verd = "{{ asset('img/verd.png') }}";

</script>
<div id="informacio">
    <a> <img src="{{ asset('img/acerca-de.png') }}" alt="informacio" id="infoColors"></a>
</div>

<div id="calendarioContenedor" class="mt-3">

    <!-- CALENDARI -->
    <div id="calendario">
        <div id="anterior" onclick="mesantes()"></div>
        <div id="posterior" onclick="mesdespues()"></div>
        <h2 id="titulos"></h2>
        <table id="diasc">
            <thead>
                <tr id="fila0">
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                </tr>
            </thead>
            <tbody id="dias">
                <tr id="fila1">
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr id="fila2">
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr id="fila3">
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr id="fila4">
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr id="fila5">
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr id="fila6">
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            </tbody>
        </table>
        <div id="fechaactual"><i onclick="actualizar()">AVUI: </i></div>
        <div id="buscafecha">
            <form action="#" name="buscar">
                <p class="textoCalendario">Buscar ... MES
                    <select name="buscames">
                        <option value="0">gener</option>
                        <option value="1">febrer</option>
                        <option value="2">març</option>
                        <option value="3">abril</option>
                        <option value="4">maig</option>
                        <option value="5">juny</option>
                        <option value="6">juliol</option>
                        <option value="7">agost</option>
                        <option value="8">setembre</option>
                        <option value="9">octubre</option>
                        <option value="10">novembre</option>
                        <option value="11">desembre</option>
                    </select>
                    ... ANY ...
                    <input type="text" name="buscaanno" maxlength="4" size="4" />
                    ...
                    <input type="button" value="BUSCAR" onclick="mifecha()" />
                </p>
            </form>
        </div>
    </div>
</div>
@endsection
