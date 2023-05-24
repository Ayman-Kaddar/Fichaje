<?php

use App\Http\Controllers\AfegirController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\FitxadminController;
use App\Http\Controllers\FitxatgeController;
use App\Http\Controllers\FullCalenderController;
use App\Http\Controllers\GraficosController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('auth.login');
})->middleware("guest"); // GUEST -> INVITADO

//ROUTE DE LA PARTE DE GOOGLE
Route::post('/login-google', function () {
    return Socialite::driver('google')->redirect();
})->name('login-google')->middleware("guest");

Route::get('/google-callback', function () {
    $user_google = Socialite::driver('google')->user();

    $user = User::where("email", $user_google->email)->first();

    if (!$user) {
        return redirect()->route("login")->with("error", "Aquest usuari no està registrat.");
    }

    if (!$user->external_id) {
        $user->avatar = $user_google->avatar;
        $user->external_id = $user_google->id;
        $user->external_auth = "google";
        $user->update();
    }

    Auth::login($user);

    return redirect('/inici');
})->middleware("guest");
//FIN DE ROUTE DE LA PARTE DE GOOGLE


Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {
    /* Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard'); */

    //ROUTE DE LA PARTE DE EMPLEADO
    Route::get("inici", [ClienteController::class, "inici"])->name("inici");

    Route::get('/agenda', function () {
        return view('cliente/agenda');
    })->name("agenda");

    Route::get('/agenda', [FullCalenderController::class, "index"])->name('agenda');
    Route::controller(FullCalenderController::class)->group(function () {
        Route::get('fullcalender', 'index');
        Route::post('fullcalenderAjax', 'ajax');
    });

    Route::get('/fitxatge', function () {
        return view('cliente/fitxatge');
    })->name("fitxatge");

    Route::post("fitxatge/entrada", [FitxatgeController::class, 'entrada'])->name("fitxatge.entrada"); // nombre --> controlador.funcion
    Route::post("fitxatge/pausa", [FitxatgeController::class, 'pausa'])->name("fitxatge.pausa"); // nombre --> controlador.funcion
    Route::post("fitxatge/continuacio", [FitxatgeController::class, 'continuacio'])->name("fitxatge.continuacio"); // nombre --> controlador.funcion
    Route::post("fitxatge/sortida", [FitxatgeController::class, 'sortida'])->name("fitxatge.sortida"); // nombre --> controlador.funcion
    Route::get("fitxatge/mostrar/{fecha}", [FitxatgeController::class, 'devolverfitxarge']); // nombre --> controlador.funcion

    //FIN DE ROUTE DE LA PARTE DE EMPLEADO

    //ROUTE DE LA PARTE DE ADMINISTRADOR

    Route::match(["get", "post"], "/fitxadmin", [FitxadminController::class, "fitxadmin"])->name("fitxadmin");

    Route::get("/afegir", [AfegirController::class, "afegir"])->name("afegir");
    Route::post('/import-users', [UserController::class, "importUsers"])->name('import-users');
    Route::get('/export-users', [UserController::class, "exportUsers"])->name('export-users');
    Route::delete('/delete-users', [UserController::class, "deleteUsers"])->name('delete-users');
    Route::delete('/users/{user}', [UserController::class, "destroy"])->name('users.destroy');

    Route::get("/graficos", [GraficosController::class, "graficos"])->name("graficos");
    /* Route::get("/reports", [ReportsController::class, "reports"])->name("reports"); */

    //FIN DE ROUTE DE LA PARTE DE ADMINISTRADOR

});
