<?php

namespace App\Http\Controllers;
use Illuminate\Database\QueryException;
use App\Models\Paciente;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

use DateTime;

class PacienteController extends Controller{
    public function index()    {
        $paciente = Paciente::where('ativo' , '=','S')->orderBy('nome','asc')->paginate(10);
        return view('paciente.pacientes', ['query' => $paciente]);
    }

    public function create(Request $request)
    {   
       

        
        
    }
    public function store(Request $request)    {
        $dataNascimento = (new DateTime($request->nascimento))->setTime(0,0,0);       
       if( $dataNascimento > (new DateTime())->setTime(0,0,0)){
            return redirect()->back()->withInput()->with(['error'=>'A data de nascimento não pode ser maior que a data atual',]);
       }
        try {
            DB::beginTransaction();
            Paciente::updateOrCreate([
                'pac_cod' => $request->pes_cod
            ],
            [
                'nome' => $request->NOME,
                'filicao_1' => $request->FILICAO_1,
                'filicao_2' => $request->FILICAO_2,
                'cep' => $request->CEP,
                'nascimento' => $dataNascimento,
                'logradouro' => $request->LOGRADOURO,
                'cidade' => $request->CIDADE,
                'uf'  => $request->UF,   
                'tel_1' => $request->TEL_1 ,
                'tel_2' => $request->TEL_2 ,
                'email' => $request->EMAIL ,
                'cartao_sus' => $request->CARTAO_SUS ,
                'prof_cod' => $request->PROF_COD   
            ]);
            DB::commit();
            return redirect('/paciente')->with('success','Sucesso!');

        } catch (QueryException  $th) {
            DB::rollBack();
            return redirect('/paciente')->with('error','Erro ao criar o registro. '.$th->errorInfo[2]);
        }
    }
   public function filtro (Request $request){

        if(empty($request->NOME) && empty($request->CPF) && empty($request->filtroData) ){
            return PacienteController::index();
        }
        $query = Paciente::query();
        if($request->NOME){
           $query->where('nome','LIKE', '%'.$request->NOME.'%');
        }
        if($request->CPF){
            $query->where('cpf','=', $request->CPF);
         }
         if($request->filtroData){
            $query->where('nascimento','=', $request->filtroData,);
         }
    
        return view('paciente.pacientes',['query' => $query->paginate(10)]);
   }
   
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
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
}
