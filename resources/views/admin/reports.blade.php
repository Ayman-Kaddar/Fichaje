@extends('template.mainadmin')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/afegir-admin.css') }}">
    <!-- CARRUSEL -->
    <link rel="stylesheet" type="text/css"
        href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick-theme.min.css" />
@endpush

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick.min.js"></script>
    <script src="{{ asset('js/afegir-admin.js') }}"></script>
@endpush

@section('content')
@endsection
