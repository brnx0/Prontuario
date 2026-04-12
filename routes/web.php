<?php
use App\Http\Controllers\AtendimentoController;
use App\Http\Controllers\EnfermeiroController;
use App\Http\Controllers\EspecialidadeController;
use App\Http\Controllers\MedicoController;
use App\Http\Controllers\PacienteController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Auth\RegisteredUserController;
use Illuminate\Support\Facades\Route;






Route::get('/relatorio/{Codigo}', [AtendimentoController::class,'gerarfichaAtendimento']);

Route::middleware(['auth'])->group(function () {
    Route::get('/', function () { 
        return \Inertia\Inertia::render('Home'); 
    })->name('index');
/*Relatorios*/
Route::get('/relatorio/{Codigo}', [AtendimentoController::class,'gerarfichaAtendimento']);
Route::get('/receita/{Codigo}', [AtendimentoController::class,'gerarReceita']);

/*Pacientes*/
Route::get('/paciente/{idPaciente}', [PacienteController::class,'getPaciente']);
Route::get('/paciente',[PacienteController::class, 'index'])->name('paciente');
Route::post('/paciente', [PacienteController::class, 'store'])->name('paciente.store');
Route::put('/paciente/{id}', [PacienteController::class, 'update']);
Route::delete('/paciente/{id}',[PacienteController::class,'destroy'])->name('deletarPaciente');
Route::put('/paciente', [PacienteController::class,'inativarPaciente'])->name('inativarPaciente');


Route::get('/atendimento/{ATEND_COD?}',[AtendimentoController::class,'index'])->name('atendimento');
Route::post('/atendimento', [AtendimentoController::class,'store'])->name('atendimentoPost');
Route::get('/historico{nome?}{dataAtendimento?}', [AtendimentoController::class,'historico']);
Route::get('/historico/{ATEND_COD}', [AtendimentoController::class,'registroAtendimento']);


Route::get('/medico',[MedicoController::class,'index'])->name('medico');
Route::get('/medico/{Codigo}',[MedicoController::class,'getMedico'])->name('getMedico');
Route::post('/medico',[MedicoController::class,'store'])->name('');
Route::put('/medico/{id}',[MedicoController::class,'update']);
Route::delete('/medico',[MedicoController::class,'destroy'])->name('');
Route::put('/medico',[MedicoController::class,'updateStatus']);


Route::get('/enfermeiro',[EnfermeiroController::class,'index'])->name('enfermeiro');
Route::get('/enfermeiro/{ID}',[EnfermeiroController::class,'getEnfermeiro'])->name('getEnfermeiro');
Route::post('/enfermeiro',[EnfermeiroController::class,'store'])->name('');
Route::put('/enfermeiro/{id}', [EnfermeiroController::class, 'update']);
Route::delete('/enfermeiro',[EnfermeiroController::class,'destroy'])->name('');
Route::put('/enfermeiro',[EnfermeiroController::class,'updateStatus']);

Route::get('/especialidade',[EspecialidadeController::class,'index'])->name('especialidade');
Route::post('/especialidade',[EspecialidadeController::class,'store'])->name('');
Route::put('/especialidade/{id}',[EspecialidadeController::class,'update']);
Route::delete('/especialidade',[EspecialidadeController::class,'destroy'])->name('');
Route::PUT('/especialidade',[EspecialidadeController::class,'updateStatus']);

/*Admin - Gerenciamento de Usuários*/
Route::middleware(['admin'])->group(function () {
    Route::get('/admin/usuarios', [UserController::class, 'index'])->name('admin.usuarios');
    Route::post('/admin/usuarios', [UserController::class, 'store'])->name('admin.usuarios.store');
    Route::put('/admin/usuarios/{id}', [UserController::class, 'update'])->name('admin.usuarios.update');
    Route::delete('/admin/usuarios', [UserController::class, 'destroy'])->name('admin.usuarios.destroy');
});
});

// Route::get('/', function () {
//     return view('principal');
//  })->name('paciente');

// );

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session', 'verified')
])->group(function () {
    Route::get('/dashboard', function () {
        return \Inertia\Inertia::render('Dashboard');
    })->name('dashboard');
});




