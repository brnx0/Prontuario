<?php
use App\Http\Controllers\AtendimentoController;
use App\Http\Controllers\EnfermeiroController;
use App\Http\Controllers\EspecialidadeController;
use App\Http\Controllers\MddaController;
use App\Http\Controllers\MedicoController;
use App\Http\Controllers\PacienteController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Auth\RegisteredUserController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
    Route::get('/', function () {    return \Inertia\Inertia::render('Home');  })->name('index');

    /*Relatorios*/
    Route::get('/relatorio/{Codigo}', [AtendimentoController::class,'gerarfichaAtendimento'])->name('relatorio');
    Route::get('/receita/{Codigo}', [AtendimentoController::class,'gerarReceita'])->name('receita');

    /*Pacientes*/
    Route::get('/paciente/{idPaciente}', [PacienteController::class,'getPaciente'])->name('paciente.show');
    Route::get('/paciente',[PacienteController::class, 'index'])->name('paciente');
    Route::post('/paciente', [PacienteController::class, 'store'])->name('paciente.store');
    Route::put('/paciente/{id}', [PacienteController::class, 'update'])->name('paciente.update');
    Route::delete('/paciente/{id}',[PacienteController::class,'destroy'])->name('paciente.destroy');
    Route::put('/paciente-status', [PacienteController::class,'inativarPaciente'])->name('paciente.status');

    /**Atendimento */
    Route::get('/atendimento/{ATEND_COD?}',[AtendimentoController::class,'index'])->name('atendimento');
    Route::post('/atendimento', [AtendimentoController::class,'store'])->name('atendimento.store');
    Route::get('/historico', [AtendimentoController::class,'historico'])->name('historico');
    Route::get('/historico/{ATEND_COD}', [AtendimentoController::class,'registroAtendimento'])->name('historico.show');

    /**Medico */
    Route::get('/medico',[MedicoController::class,'index'])->name('medico');
    Route::get('/medico/{Codigo}',[MedicoController::class,'getMedico'])->name('medico.show');
    Route::post('/medico',[MedicoController::class,'store'])->name('medico.store');
    Route::put('/medico/{id}',[MedicoController::class,'update'])->name('medico.update');
    Route::delete('/medico',[MedicoController::class,'destroy'])->name('medico.destroy');
    Route::put('/medico-status',[MedicoController::class,'updateStatus'])->name('medico.status');

    /**Enfermeiro */
    Route::get('/enfermeiro',[EnfermeiroController::class,'index'])->name('enfermeiro');
    Route::get('/enfermeiro/{ID}',[EnfermeiroController::class,'getEnfermeiro'])->name('enfermeiro.show');
    Route::post('/enfermeiro',[EnfermeiroController::class,'store'])->name('enfermeiro.store');
    Route::put('/enfermeiro/{id}', [EnfermeiroController::class, 'update'])->name('enfermeiro.update');
    Route::delete('/enfermeiro',[EnfermeiroController::class,'destroy'])->name('enfermeiro.destroy');
    Route::put('/enfermeiro-status',[EnfermeiroController::class,'updateStatus'])->name('enfermeiro.status');

    /**Especialidades */
    Route::get('/especialidade',[EspecialidadeController::class,'index'])->name('especialidade');
    Route::post('/especialidade',[EspecialidadeController::class,'store'])->name('especialidade.store');
    Route::put('/especialidade/{id}',[EspecialidadeController::class,'update'])->name('especialidade.update');
    Route::delete('/especialidade',[EspecialidadeController::class,'destroy'])->name('especialidade.destroy');
    Route::put('/especialidade-status',[EspecialidadeController::class,'updateStatus'])->name('especialidade.status');

    /**MDDA */
    Route::get('/mdda', [MddaController::class, 'index'])->name('mdda.index');
    Route::get('/mdda/novo', [MddaController::class, 'create'])->name('mdda.create');
    Route::post('/mdda', [MddaController::class, 'store'])->name('mdda.store');
    Route::get('/mdda/{id}/editar', [MddaController::class, 'edit'])->name('mdda.edit');
    Route::put('/mdda/{id}', [MddaController::class, 'update'])->name('mdda.update');
    Route::post('/mdda/{id}/finalizar', [MddaController::class, 'finalizar'])->name('mdda.finalizar');
    Route::get('/mdda/{id}/imprimir', [MddaController::class, 'print'])->name('mdda.print');

    /**Dashboard */
    Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');

    /*Admin - Gerenciamento de Usuarios*/
    Route::middleware(['admin'])->group(function () {
        Route::get('/admin/usuarios', [UserController::class, 'index'])->name('admin.usuarios');
        Route::post('/admin/usuarios', [UserController::class, 'store'])->name('admin.usuarios.store');
        Route::put('/admin/usuarios/{id}', [UserController::class, 'update'])->name('admin.usuarios.update');
        Route::delete('/admin/usuarios', [UserController::class, 'destroy'])->name('admin.usuarios.destroy');
    });
});
