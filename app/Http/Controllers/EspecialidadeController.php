<?php

namespace App\Http\Controllers;

use App\Models\Especialidade;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class EspecialidadeController extends Controller{

    public function index()    {
        
        $especialidade = Especialidade::orderBy('escp_desc')->paginate(10);
        return view('especialidade.show',['especialidades'=>$especialidade]);
    }

    public function store(Request $request){
        try{
            Especialidade::create([
                'escp_desc' => $request->descEspc
            ]);
            return back()->with('success', 'Registro criado.');
        }catch(QueryException $th){
            return back()->with('error', $th->getMessage());
        }

    }

    public function filtro (Request $request){

        if(empty($request->escp_desc) ){
            return EspecialidadeController::index();
        }
        $query = Especialidade::query();
        if($request->escp_desc){
           $query->where('escp_desc','LIKE', '%'.$request->escp_desc.'%');
        }
       
    
        return view('especialidade.show',['especialidades' => $query->paginate(10)]);
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
