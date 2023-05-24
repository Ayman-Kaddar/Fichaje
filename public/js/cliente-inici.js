$(document).ready(function () {

    // Animacio Imatges
    $('#carousel').slick({
        dots: false,
        infinite: true,
        speed: 500,
        fade: true,
        cssEase: 'linear',
        autoplay: true,
        autoplaySpeed: 2000 // Ajusta el tiempo de transición a 3 segundos
    });

});