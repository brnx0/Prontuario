<?php
use App\Http\Controllers\AtendimentoController;
use App\Http\Controllers\EnfermeiroController;
use App\Http\Controllers\EspecialidadeController;
use App\Http\Controllers\MedicoController;
use App\Http\Controllers\PacienteController;
use App\Http\Controllers\Auth\RegisteredUserController;
use Illuminate\Support\Facades\Route;






Route::get('/relatorio/{Codigo}', [AtendimentoController::class,'gerarfichaAtendimento']);

Route::middleware(['auth'])->group(function () {
Route::get('/paciente',[PacienteController::class, 'index'])->name('paciente.index');
Route::post('/paciente', [PacienteController::class, 'store'])->name('paciente.store');
Route::get('/paciente', [PacienteController::class,'filtro'])->name('filtrar.paciente');
Route::delete('/paciente/{id}',[PacienteController::class,'destroy'])->name('deletarPaciente');

Route::get('/atendimento/{ATEND_COD?}',[AtendimentoController::class,'index'])->name('atendimentoIndex');
Route::post('/atendimento', [AtendimentoController::class,'store'])->name('atendimentoPost');
Route::get('/historico{nome?}{dataAtendimento?}', [AtendimentoController::class,'historico']);
Route::get('/historico/{ATEND_COD}', [AtendimentoController::class,'registroAtendimento']);


Route::get('/medico',[MedicoController::class,'index'])->name('');
Route::get('/medico',[MedicoController::class,'filtro'])->name(name: 'filtrarMedico');
Route::post('/medico',[MedicoController::class,'store'])->name('');
Route::delete('/medico',[MedicoController::class,'destroy'])->name('');
Route::PUT('/medico',[MedicoController::class,'updateStatus']);


Route::get('/enfermeiro',[EnfermeiroController::class,'index'])->name('');
Route::get('/enfermeiro',[EnfermeiroController::class,'filtro'])->name('filtrarEnfermeiro');
Route::post('/enfermeiro',[EnfermeiroController::class,'store'])->name('');
Route::delete('/enfermeiro',[EnfermeiroController::class,'destroy'])->name('');
Route::PUT('/enfermeiro',[EnfermeiroController::class,'updateStatus']);

Route::get('/especialidade',[EspecialidadeController::class,'index'])->name('');
Route::get('/especialidade',[EspecialidadeController::class,'filtro'])->name('filtrarEspecialidade');
Route::post('/especialidade',[EspecialidadeController::class,'store'])->name('');
Route::delete('/especialidade',[EspecialidadeController::class,'destroy'])->name('');
Route::PUT('/especialidade',[EspecialidadeController::class,'updateStatus']);
});

// Route::get('/', function () {
//     return view('principal');
//  })->name('paciente');

// );

// Route::middleware([
//     'auth:sanctum',
//     config('jetstream.auth_session'),
//     'verified'
// ])->group(function () {
//     Route::get('/dashboard', function () {
//         return view('dashboard');
//     })->name('dashboard');
// });

Route::middleware(['auth'])->group(function () {
    Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('/register', [RegisteredUserController::class, 'store']);
});

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
