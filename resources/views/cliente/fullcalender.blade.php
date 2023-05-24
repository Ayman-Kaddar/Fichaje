@extends('template.main')

@push('styles')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.17/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="{{ asset('css/cliente-agenda.css') }}">
    <link rel="stylesheet" href="{{ asset('css/barraNavegacio.css') }}">
@endpush

<head>
</head>

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.js"></script>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/locale/ca.js'></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.17/dist/sweetalert2.min.js"></script>


    <script type="text/javascript">
        $(document).ready(function() {

            /*Obtenir la URL del lloc*/
            var SITEURL = "{{ url('/') }}";

            /*Configuració del token CSRF*/
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            /*FullCalender JS*/
            var calendar = $('#calendar').fullCalendar({
                locale: 'ca',
                editable: true,
                events: SITEURL + "/fullcalender",
                displayEventTime: false,
                editable: true,
                eventRender: function(event, element, view) {
                    if (event.allDay === 'true') {
                        event.allDay = true;
                    } else {
                        event.allDay = false;
                    }
                },
                selectable: true,
                selectHelper: true,
                select: function(start, end, allDay) {
                    //var title = prompt('Títol de l\'esdeveniment:');
                    var title = null;

                    var start2 = start;
                    var end2 = end;

                    Swal.fire({
                        title: 'Títol de l\'esdeveniment:',
                        input: 'text',
                        showCancelButton: true,
                        confirmButtonText: 'Guardar',
                        cancelButtonText: 'Cancelar',
                        inputValidator: (value) => {
                            if (value) {
                                title = value
                            } else {
                                return 'Cal introduir un títol';
                            }
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            title = result.value;

                            var start3 = $.fullCalendar.formatDate(start2, "Y-MM-DD");
                            var end3 = $.fullCalendar.formatDate(end2, "Y-MM-DD");

                            $.ajax({
                                url: SITEURL + "/fullcalenderAjax",
                                data: {
                                    title: title,
                                    start: start3,
                                    end: end3,
                                    type: 'add'
                                },
                                type: "POST",
                                success: function(data) {
                                    Swal.fire({
                                        title: 'Esdeveniment creat amb èxit!',
                                        icon: 'success',
                                        showCancelButton: false,
                                        confirmButtonText: 'OK'
                                    });
                                    calendar.fullCalendar('renderEvent', {
                                        id: data.id,
                                        title: title,
                                        start: start,
                                        end: end,
                                        allDay: allDay
                                    }, true);
                                    calendar.fullCalendar('unselect');
                                }
                            })
                        }
                    });
                },
                eventDrop: function(event, delta) {
                    var start = $.fullCalendar.formatDate(event.start, "Y-MM-DD");
                    var end = $.fullCalendar.formatDate(event.end, "Y-MM-DD");

                    $.ajax({
                        url: SITEURL + '/fullcalenderAjax',
                        data: {
                            title: event.title,
                            start: start,
                            end: end,
                            id: event.id,
                            type: 'update'
                        },
                        type: "POST",
                        success: function(response) {
                            Swal.fire({
                                title: 'Esdeveniment actualitzat amb èxit!',
                                icon: 'success',
                                showCancelButton: false,
                                confirmButtonText: 'OK'
                            });
                        }

                    });
                },
                eventClick: function(event) {
                    Swal.fire({
                        title: 'Esteu segur que voleu eliminar l\'esdeveniment?',
                        showCancelButton: true,
                        confirmButtonText: 'Sí',
                        cancelButtonText: 'No',
                        icon: 'warning',
                        reverseButtons: true
                    }).then((result) => {
                        if (result.value) {
                            $.ajax({
                                type: "POST",
                                url: SITEURL + '/fullcalenderAjax',
                                data: {
                                    id: event.id,
                                    type: 'delete'
                                },
                                success: function(response) {
                                    calendar.fullCalendar('removeEvents', event.id);
                                    Swal.fire({
                                        title: 'Esdeveniment esborrat amb èxit!',
                                        icon: 'success',
                                        showCancelButton: false,
                                        confirmButtonText: 'OK'
                                    });
                                }
                            });
                        }
                    });
                }


            });

        });

        /*Codi d'èxit de Toastr*/
        function displayMessage(message) {
            toastr.success(message, 'Esdeveniment');
        }

        function add() {

        }
    </script>
@endpush

@section('content')
    <br>
    <div class="container">
        <div id='calendar'></div>
    </div>
    <br>
@endsection()
