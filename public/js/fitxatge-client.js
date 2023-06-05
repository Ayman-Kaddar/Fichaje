//-------------------------------------------------------CALENDARIO---------------------------------
//Arrays de datos:
meses = [
    "gener",
    "febrer",
    "març",
    "abril",
    "maig",
    "juny",
    "juliol",
    "agost",
    "setembre",
    "octubre",
    "novembre",
    "desembre",
];
lasemana = [
    "Diumenge",
    "Dilluns",
    "Dimarts",
    "Dimecres",
    "Dijous",
    "Divendres",
    "Dissabte",
];
diassemana = ["dl", "dt", "dc", "dj", "dv", "ds", "dg"];
//Tras cargarse la página ...

var calendariVoid;
var canChange = true;

window.onload = function () {
    calendariVoid = document.getElementById('dias').outerHTML;
    //fecha actual
    hoy = new Date(); //objeto fecha actual
    diasemhoy = hoy.getDay(); //dia semana actual
    diahoy = hoy.getDate(); //dia mes actual
    meshoy = hoy.getMonth(); //mes actual
    annohoy = hoy.getFullYear(); //año actual
    // Elementos del DOM: en cabecera de calendario
    tit = document.getElementById("titulos"); //cabecera del calendario
    ant = document.getElementById("anterior"); //mes anterior
    pos = document.getElementById("posterior"); //mes posterior
    // Elementos del DOM en primera fila
    f0 = document.getElementById("fila0");
    //Pie de calendario
    pie = document.getElementById("fechaactual");
    pie.innerHTML +=
        lasemana[diasemhoy] +
        ", " +
        diahoy +
        " de " +
        meses[meshoy] +
        " de " +
        annohoy;
    //formulario: datos iniciales:
    document.buscar.buscaanno.value = annohoy;
    // Definir elementos iniciales:
    mescal = meshoy; //mes principal
    annocal = annohoy; //año principal
    //iniciar calendario:
    cabecera();
    primeralinea();
    escribirdias();

};
//FUNCIONES de creación del calendario:
//cabecera del calendario
function cabecera() {
    tit.innerHTML = meses[mescal] + " de " + annocal;
    mesant = mescal - 1; //mes anterior
    mespos = mescal + 1; //mes posterior
    if (mesant < 0) {
        mesant = 11;
    }
    if (mespos > 11) {
        mespos = 0;
    }
    ant.innerHTML = meses[mesant];
    pos.innerHTML = meses[mespos];
}
//primera línea de tabla: días de la semana.
function primeralinea() {
    for (i = 0; i < 7; i++) {
        celda0 = f0.getElementsByTagName("th")[i];
        celda0.innerHTML = diassemana[i];
    }
}
//rellenar celdas con los días
async function escribirdias() {

    canChange = false;

    if (calendariVoid) {
        document.getElementById('dias').outerHTML = calendariVoid
    }

    //Buscar dia de la semana del dia 1 del mes:
    primeromes = new Date(annocal, mescal, "1"); //buscar primer día del mes
    prsem = primeromes.getDay(); //buscar día de la semana del día 1
    prsem--; //adaptar al calendario español (empezar por lunes)
    if (prsem == -1) {
        prsem = 6;
    }
    //buscar fecha para primera celda:
    diaprmes = primeromes.getDate();
    prcelda = diaprmes - prsem; //restar días que sobran de la semana
    empezar = primeromes.setDate(prcelda); //empezar= tiempo UNIX 1ª celda
    diames = new Date(); //convertir en fecha
    diames.setTime(empezar); //diames=fecha primera celda.
    //Recorrer las celdas para escribir el día:
    for (i = 1; i < 7; i++) {
        //localizar fila
        fila = document.getElementById("fila" + i);
        for (j = 0; j < 7; j++) {
            midia = diames.getDate();
            mimes = diames.getMonth();
            mianno = diames.getFullYear();
            celda = fila.getElementsByTagName("td")[j];
            var fecha = mianno + '-' + (mimes + 1 > 9 + 1 ? eval(mimes + 1) : "0" + eval(mimes + 1)) + '-' + (midia > 9 ? midia : "0" + midia);
            const data = await getdata(fecha);

            console.log(data)
                   
            // Num del dia
            celda.innerHTML = midia;

            // POSO LA QUANTITAT DE HORES D'AQUELL DIA ------------------------------------------------------------------------------------------------------
            celda.innerHTML += '<div class="hours-worked">' + Number(data.horas) + ' h</div>'

            //Recuperar estado inicial al cambiar de mes:
            celda.style.backgroundColor = "#e8e8db";
            celda.style.color = "#492736";
            //domingos en rojo
            if (j == 6 || j == 5) {
                celda.style.color = "#f11445";
                celda.innerHTML = midia;
            }
            //dias restantes del mes en gris
            if (mimes != mescal) {
                celda.style.color = "#a0babc";
                celda.innerHTML = midia;
            }
            
            if (!(j == 6 || j == 5) && !(mimes != mescal)) {
                if ((data.horas == 0 && data.minutos != 0) || (data.horas == 1)) {
                    celda.style.backgroundColor = "#f0b19e"; // rojo
                }
                if (data.horas >= 2 && data.horas < 4 ) {
                    celda.style.backgroundColor = "#edbb99"; // naranja
                }
                if (data.horas >= 4 && data.horas < 6) {
                    celda.style.backgroundColor = "#f9e79f"; // amarillo
                }
                if (data.horas >= 6) {
                    celda.style.backgroundColor = "#abebc6"; // verde
                }
            }

            //destacar la fecha actual
            if (mimes == meshoy && midia == diahoy && mianno == annohoy) {
                celda.style.backgroundColor = "#FEF5E7"; 
                celda.innerHTML = "<cite title='Fecha Actual' class='fw-bold fs-5'>" + midia + "</cite>";

                //POSO LA QUANTITAT DE HORES D'AQUELL DIA ------------------------------------------------------------------------------------------------------
                celda.innerHTML += '<div class="hours-worked">' + Number(data.horas) + ' h</div>'
            }

            //pasar al siguiente día
            midia = midia + 1;
            diames.setDate(midia);

            celda.addEventListener("click", function () {
                let textoCelda = this.innerText;
                let dia = textoCelda.split("\n")
                // SWEET ALERT ---------------------------------------------------------------------
                Swal.fire({
                    title: `<strong> Dia ${dia[0]}</strong>`,
                    icon: 'success',
                    html: `
                        <p>Hores Treballades: ${data.horas}:${data.minutos} h</p>
                        <p>Entrada: ${data.entrada}</p>
                        <p>Sortida: ${data.sortida}</p>
                    `,
                    showCloseButton: true,
                });
            })
        }
    }

    canChange = true;

}
//Ver mes anterior
function mesantes() {
    if (canChange) {
        nuevomes = new Date(); //nuevo objeto de fecha
        primeromes--; //Restamos un día al 1 del mes visualizado
        nuevomes.setTime(primeromes); //cambiamos fecha al mes anterior
        mescal = nuevomes.getMonth(); //cambiamos las variables que usarán las funciones
        annocal = nuevomes.getFullYear();
        cabecera(); //llamada a funcion de cambio de cabecera
        escribirdias(); //llamada a funcion de cambio de tabla.
    }
}
//ver mes posterior
function mesdespues() {
    if (canChange) {
        nuevomes = new Date(); //nuevo obejto fecha
        tiempounix = primeromes.getTime(); //tiempo de primero mes visible
        tiempounix = tiempounix + 45 * 24 * 60 * 60 * 1000; //le añadimos 45 días
        nuevomes.setTime(tiempounix); //fecha con mes posterior.
        mescal = nuevomes.getMonth(); //cambiamos variables
        annocal = nuevomes.getFullYear();
        cabecera(); //escribir la cabecera
        escribirdias(); //escribir la tabla
    }
}
    
//volver al mes actual
function actualizar() {
    mescal = hoy.getMonth(); //cambiar a mes actual
    annocal = hoy.getFullYear(); //cambiar a año actual
    cabecera(); //escribir la cabecera
    escribirdias(); //escribir la tabla
}
//ir al mes buscado
function mifecha() {
    //Recoger dato del año en el formulario
    if (canChange) {
        mianno = document.buscar.buscaanno.value;
        //recoger dato del mes en el formulario
        listameses = document.buscar.buscames;
        opciones = listameses.options;
        num = listameses.selectedIndex;
        mimes = opciones[num].value;
        //Comprobar si el año está bien escrito
        if (isNaN(mianno) || mianno < 1) {
            //año mal escrito: mensaje de error
            alert("El año no es válido:\n debe ser un número mayor que 0");
        } else {
            //año bien escrito: ver mes en calendario:
            mife = new Date(); //nueva fecha
            mife.setMonth(mimes); //añadir mes y año a nueva fecha
            mife.setFullYear(mianno);
            mescal = mife.getMonth(); //cambiar a mes y año indicados
            annocal = mife.getFullYear();
            cabecera(); //escribir cabecera
            escribirdias(); //escribir tabla
        }
    }
}

async function getdata(fecha) {

    var res;

    await $.get('/fitxatge/mostrar/' + fecha, function (data, status) {

        if (status == "success") {
            res = data;
        }
    });

    return res;
}

// ---- Boto info ----
    // Al donar-li click al boto de informacio apareixera un popup amb la informacio del joc
    $("#infoColors").click(function() {
        Swal.fire({
            title: '<strong>Què representa cada color?</strong>',
            icon: 'info',
            html:
              '<p style="text-align: left;">Color crema brillant <img src="' + crema_brillant + '" alt="c" style="border: 1px solid black;"> --> dia actual</p>' +
              '<p style="text-align: left;">Color beix <img src="' + baige + '" alt="c" style="border: 1px solid black;"> --> estàndard</p>' +
              '<p style="text-align: left;">Color vermell <img src="' + vermell + '" alt="c" style="border: 1px solid black;"> --> ha treballat entre 00:01 h -- 01:59 h</p>' +
              '<p style="text-align: left;">Color taronja <img src="' + taronja + '" alt="c" style="border: 1px solid black;"> --> ha treballat entre 02:00 h -- 03:59 h</p>' +
              '<p style="text-align: left;">Color groc <img src="' + groc + '" alt="c" style="border: 1px solid black;"> --> ha treballat entre 04:00 h -- 05:59 h</p>' + 
              '<p style="text-align: left;">Color verd <img src="' + verd + '" alt="c" style="border: 1px solid black;"> --> ha treballat entre 06:00 h -- 08:00 h</p>',
            showCloseButton: true,
          });

    });