// ----- FER MOSTRAR O DESAPAREIXER LA SCROLLBAR DE LA TAULA DEPENENT DE LA SEVA MIDA ----- //

// Obtenim la taula i el seu contenidor
const tabla = document.getElementById("my-table");
const contenidor = document.querySelector(".table-container");

// Verifiquem si la altura de la taula es menor que la altura del contenidor
if (tabla.clientHeight <= contenidor.clientHeight) {
    // Si es menor, ocultar el scroll lateral
    contenidor.style.overflowY = "hidden";
} else {
    // Si es major, mostrarem el scroll lateral
    contenidor.style.overflowY = "scroll";
}

$(document).ready(function() {
    $('.select2').select2();
});

$('.select2').on('select2:select', function (e) {
    var { id } = e.params.data
    if (id) {
        $("form#form-filter").submit();

    }
});