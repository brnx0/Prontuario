<?php

namespace App\Http\Controllers;

use App\Models\Medico;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class MedicoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request) {
        $query = Medico::query();
        
        if ($request->nome) {
           $query->where('med_nome', 'LIKE', '%' . $request->nome . '%');
        }
        if ($request->crm) {
            $query->where('crm', '=', $request->crm);
        }
        
        $medicos = $query->orderBy('med_nome', 'asc')->paginate(10)->withQueryString();
        return \Inertia\Inertia::render('Medico/Index', ['medicos' => $medicos]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nomeMedico' => 'required',
            'crmMedico' => 'required'
        ]);

        try {
            Medico::create([
                'med_nome' => $request->nomeMedico,
                'crm' => $request->crmMedico,
            ]);
            return back()->with('success', 'Médico cadastrado com sucesso!');
        } catch (QueryException $th) {
            return back()->with('error', 'Erro ao cadastrar: ' . $th->getMessage());
        }
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'nomeMedico' => 'required',
            'crmMedico' => 'required'
        ]);

        try {
            Medico::findOrFail($id)->update([
                'med_nome' => $request->nomeMedico,
                'crm' => $request->crmMedico,
            ]);
            return back()->with('success', 'Médico atualizado com sucesso!');
        } catch (QueryException $th) {
            return back()->with('error', 'Erro ao atualizar: ' . $th->getMessage());
        }
    }
    public function updateStatus(Request $request)    {
       try{
           $med= Medico::find($request->med_cod)->update(['ativo' => $request->status]);
          
            return back()->with('success','Status atualizado com sucesso');

       }catch(QueryException $th){
            return back()->with('error','Aconteceu um erro, tente novamente em alguns instantes');
       }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request,)    {
    
        if(!$request->med_cod){
            return back()->with('error','Aconteceu um erro,  Se o erro persistir, entre em contato com o suporte e informe a seguinte mensagem: BAD REQUEST:400');
        }
        try{
            Medico::destroy($request->med_cod);
            return back()->with('success','Registro deletado.');

        }catch(QueryException $th){
            return back()->with('error', 'Não foi possivel excluir o registro, tente inativar');

        }
        //
    }
}
