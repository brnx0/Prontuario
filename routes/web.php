<?php
use App\Http\Controllers\AtendimentoController;
use App\Http\Controllers\MedicoController;
use App\Http\Controllers\PacienteController;
use Illuminate\Support\Facades\Route;

Route::get('/paciente',[PacienteController::class, 'index'])->name('paciente.index');
Route::post('/paciente', [PacienteController::class, 'store'])->name('paciente.store');
Route::get('/paciente', [PacienteController::class,'filtro'])->name('filtrar.paciente');
Route::delete('/paciente/{id}',[PacienteController::class,'destroy'])->name('deletarPaciente');

Route::get('/atendimento/{ATEND_COD?}',[AtendimentoController::class,'index'])->name('atendimentoIndex');
Route::post('/atendimento', [AtendimentoController::class,'store'])->name('atendimentoPost');


Route::get('/medico',[MedicoController::class,'index'])->name('');
Route::post('/medico',[MedicoController::class,'store'])->name('');
Route::delete('/medico',[MedicoController::class,'destroy'])->name('');
Route::PUT('/medico',[MedicoController::class,'updateStatus']);



Route::get('/', function () {
    return view('principal');
 })->name('paciente');



Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

