<?php

namespace App\Http\Controllers;

use App\Models\Enfermeiro;
use Illuminate\Database\QueryException;
use App\Models\Paciente;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

use DateTime;

class PacienteController extends Controller{
    public function index(Request $request) {
        $query = Paciente::query();
        
        if ($request->nome) {
           $query->where('nome', 'LIKE', '%' . $request->nome . '%');
        }
        if ($request->cpf) {
            $query->where('cpf', '=', $request->cpf);
        }
        if ($request->filtroData) {
            $query->where('nascimento', '=', $request->filtroData);
        }
        
        $pacientes = $query->orderBy('nome', 'asc')->paginate(10)->withQueryString();
        return \Inertia\Inertia::render('Paciente/Index', ['query' => $pacientes]);
    }
   
    public function show(string $id)
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required',
            'nascimento' => 'required|date'
        ]);

        try {
            Paciente::create([
                'nome' => $request->nome,
                'nascimento' => $request->nascimento,
                'cpf' => $request->cpf,
                'cartao_sus' => $request->cartao_sus,
                'filicao_1' => $request->filicao_1,
                'filicao_2' => $request->filicao_2,
                'cep' => $request->cep,
                'logradouro' => $request->logradouro,
                'cidade' => $request->cidade,
                'uf' => $request->uf,
                'tel_1' => $request->tel_1,
                'tel_2' => $request->tel_2,
                'email' => $request->email,
            ]);
            return back()->with('success', 'Paciente cadastrado com sucesso!');
        } catch (QueryException $th) {
            return back()->with('error', 'Erro ao cadastrar: ' . $th->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'nome' => 'required',
            'nascimento' => 'required|date'
        ]);

        try {
            Paciente::findOrFail($id)->update([
                'nome' => $request->nome,
                'nascimento' => $request->nascimento,
                'cpf' => $request->cpf,
                'cartao_sus' => $request->cartao_sus,
                'filicao_1' => $request->filicao_1,
                'filicao_2' => $request->filicao_2,
                'cep' => $request->cep,
                'logradouro' => $request->logradouro,
                'cidade' => $request->cidade,
                'uf' => $request->uf,
                'tel_1' => $request->tel_1,
                'tel_2' => $request->tel_2,
                'email' => $request->email,
            ]);
            return back()->with('success', 'Paciente atualizado com sucesso!');
        } catch (QueryException $th) {
            return back()->with('error', 'Erro ao atualizar: ' . $th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id){
        try{
            Paciente::destroy($id);
            return redirect()->back()->with('success','Paciente deletado!');
        
        }catch(QueryException $th){
              return redirect()->back()->with('error','Erro ao deletar o registro. '.$th->errorInfo[2]);
        }  
    }
    public function inativarPaciente(Request $request){
        try{
             Paciente::find($request->pac_cod)->update(['ativo' => $request->status]);
             return back()->with('success','Status atualizado com sucesso');
        }catch(QueryException $th){
             return back()->with('error','Aconteceu um erro, tente novamente em alguns instantes');
        }
    }
}
