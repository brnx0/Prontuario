<?php

namespace App\Http\Controllers;

use App\Models\Enfermeiro;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;

class EnfermeiroController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request) {
        $enfermeiros = Enfermeiro::query();
        
        if ($request->filtroNome) {
           $enfermeiros->where('enf_nome', 'LIKE', '%' . $request->filtroNome . '%');
        }
        if ($request->filtroCRE) {
            $enfermeiros->where('cre', '=', $request->filtroCRE);
        }
        
        return \Inertia\Inertia::render('Enfermeiro/Index', [
            'enfermeiros' => $enfermeiros->orderBy('enf_nome', 'asc')->paginate(10)->withQueryString()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function getEnfermeiro($request)    {
        try{
            $enfermeiro = Enfermeiro::find($request, $columns = ['*']);
         
            return json_encode($enfermeiro);
        }catch(QueryException $th){
            return json_encode($th);
        }
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nomeEnf' => 'required',
            'creEnf' => 'required'
        ]);

        try {
            Enfermeiro::create([
                'enf_nome' => $request->nomeEnf,
                'cre' => $request->creEnf
            ]);
            return back()->with('success', 'Enfermeiro(a) cadastrado(a) com sucesso!');
        } catch (QueryException $th) {
            return back()->with('error', 'Erro ao cadastrar: ' . $th->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Enfermeiro $enfermeiro)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Enfermeiro $enfermeiro)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'nomeEnf' => 'required',
            'creEnf' => 'required'
        ]);

        try {
            Enfermeiro::findOrFail($id)->update([
                'enf_nome' => $request->nomeEnf,
                'cre' => $request->creEnf
            ]);
            return back()->with('success', 'Enfermeiro(a) atualizado(a) com sucesso!');
        } catch (QueryException $th) {
            return back()->with('error', 'Erro ao atualizar: ' . $th->getMessage());
        }
    }
    public function updateStatus(Request $request)    {
        try{
            Enfermeiro::find($request->enf_cod)->update(['ativo' => $request->status]);
            return back()->with('success','Status atualizado com sucesso');
 
        }catch(QueryException $th){
            return back()->with('error','x');
        }
     }
 

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Enfermeiro $enfermeiro)    {
        if(!$request->enf_cod){
            return back()->with('error','Aconteceu um erro,  Se o erro persistir, entre em contato com o suporte e informe a seguinte mensagem: BAD REQUEST:400');
        }
        try{
            Enfermeiro::destroy($request->enf_cod);
            return back()->with('success','Registro deletado.');

        }catch(QueryException $th){
            return back()->with('error', 'Não foi possivel excluir o registro, tente inativar');

        }
        //
    }
}
