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
    public function index()    {
        $enfermeiro = Enfermeiro::orderBy('enf_nome')->paginate(10);
        return view('enfermeiros.show',['enfermeiros'=> $enfermeiro]);
    }
    public function filtro (Request $request){
        if(empty($request->filtroCRE) && empty($request->filtroNome) ){
            return EnfermeiroController::index();
        }
        $enfermeiros = Enfermeiro::query();
        if($request->filtroNome){
           $enfermeiros->where('enf_nome','LIKE', '%'.$request->filtroNome.'%');
        }
        if($request->filtroCRE){
            $enfermeiros->where('cre','=', $request->filtroCRE);
         }
    
        return view('enfermeiros.show',['enfermeiros' => $enfermeiros->paginate(10)]);
   }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)    {
        if(!$request->nomeENF){

        }
        try{
            Enfermeiro::create([
                'enf_nome' => $request->nomeENF,
                'cre' =>$request->creENF
            ]);
            return back()->with('success', 'Registro criado.');
        }catch(QueryException $th){
            return back()->with('error', $th->getMessage());
        }
        //
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
    public function update(Request $request, Enfermeiro $enfermeiro)
    {
        //
    }
    public function updateStatus(Request $request)    {
        try{
            Enfermeiro::find($request->ENF_COD)->update(['ativo' => $request->STATUS]);
            return back()->with('success','Status atualizado com sucesso');
 
        }catch(QueryException $th){
            return back()->with('error','x');
        }
     }
 

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Enfermeiro $enfermeiro)    {
        if(!$request->ENF_COD){
            return back()->with('error','Aconteceu um erro,  Se o erro persistir, entre em contato com o suporte e informe a seguinte mensagem: BAD REQUEST:400');
        }
        try{
            Enfermeiro::destroy($request->ENF_COD);
            return back()->with('success','Registro deletado.');

        }catch(QueryException $th){
            return view('enfermeiros.show')->with(['error', 'Não foi possivel excluir o registro, tente inativar']) ;

        }
        //
    }
}
