<?php

namespace App\Http\Controllers;

use App\Models\Especialidade;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class EspecialidadeController extends Controller{

    public function index(Request $request) {
        $query = Especialidade::query();
        
        if ($request->escp_desc) {
           $query->where('escp_desc', 'LIKE', '%' . $request->escp_desc . '%');
        }
        
        $especialidades = $query->orderBy('escp_desc', 'asc')->paginate(10)->withQueryString();
        return \Inertia\Inertia::render('Especialidade/Index', ['especialidades' => $especialidades]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'descEspc' => 'required'
        ]);

        try {
            Especialidade::create([
                'escp_desc' => $request->descEspc
            ]);
            return back()->with('success', 'Especialidade cadastrada com sucesso!');
        } catch (QueryException $th) {
            return back()->with('error', 'Erro ao cadastrar: ' . $th->getMessage());
        }
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'descEspc' => 'required'
        ]);

        try {
            Especialidade::findOrFail($id)->update([
                'escp_desc' => $request->descEspc
            ]);
            return back()->with('success', 'Especialidade atualizada com sucesso!');
        } catch (QueryException $th) {
            return back()->with('error', 'Erro ao atualizar: ' . $th->getMessage());
        }
    }

    public function updateStatus(Request $request)    {
        try{
             Especialidade::find($request->espc_cod)->update(['ativo' => $request->status]);
             return back()->with('success','Status atualizado com sucesso');
 
        }catch(QueryException $th){
             return back()->with('error','Aconteceu um erro, tente novamente em alguns instantes');
        }
     }

     public function destroy(Request $request){
        try {
            Especialidade::destroy($request->espc_cod);
            return back()->with('success', 'Registro Excluído com sucesso!');
        } catch (QueryException $th) {
            return back()->with('error', $th->getMessage());
        }
     }
}
