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
    public function index()    {
        $medico = Medico::all();
        return view('medicos.show',['medicos'=>$medico]);
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
    public function store(Request $request)
    {
        try{
            Medico::create([
                'med_nome' => $request->nomeMedico,
                'crm' =>$request->crmMedico
            ]);
            return back()->with('success', 'Registro criado.');
        }catch(QueryException $th){
            return back()->with('error', $th->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Medico $medico)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Medico $medico)
    {
        //
    }
    public function filtro (Request $request){

        // if(empty($request->nome) && empty($request->cpf) && empty($request->filtroData) ){
        //     return PacienteController::index();
        // }
        // $query = Paciente::query();
        // if($request->nome){
        //    $query->where('nome','LIKE', '%'.$request->nome.'%');
        // }
        // if($request->cpf){
        //     $query->where('cpf','=', $request->cpf);
        //  }
        //  if($request->filtroData){
        //     $query->where('nascimento','=', $request->filtroData,);
        //  }
    
        // return view('paciente.pacientes',['query' => $query->paginate(10)]);
   }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Medico $medico)
    {
        //
    }
    public function updateStatus(Request $request)    {
       try{
           $med= Medico::find($request->med_cod)->update(['ativo' => $request->status]);
          
            return back()->with('success','Status atualizado com sucesso');

       }catch(QueryException $th){
            return back()->with('error','x');
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
            return view('medicos.show')->with(['error', 'Não foi possivel excluir o registro, tente inativar']) ;

        }
        //
    }
}
