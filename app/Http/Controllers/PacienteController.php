<?php

namespace App\Http\Controllers;
use Illuminate\Database\QueryException;
use App\Models\Paciente;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

use DateTime;

class PacienteController extends Controller{
    public function index()    {
        $paciente = Paciente::where('ATIVO' , '=','S')->orderBy('NOME','asc')->paginate(10);
        return view('paciente.pacientes', ['query' => $paciente]);
    }

    public function create(Request $request)
    {   
       

        
        
    }
    public function store(Request $request)    {
        $dataNascimento = (new DateTime($request->NASCIMENTO))->setTime(0,0,0);       
       if( $dataNascimento > (new DateTime())->setTime(0,0,0)){
            return redirect()->back()->withInput()->with(['error'=>'A data de nascimento não pode ser maior que a data atual',]);
       }
        try {
            DB::beginTransaction();
            Paciente::updateOrCreate([
                'PAC_COD' => $request->pes_cod
            ],
            [
                'NOME' => $request->NOME,
                'FILICAO_1' => $request->FILICAO_1,
                'FILICAO_2' => $request->FILICAO_2,
                'CEP' => $request->CEP,
                'NASCIMENTO' => $dataNascimento,
                'LOGRADOURO' => $request->LOGRADOURO,
                'CIDADE' => $request->CIDADE,
                'UF'  => $request->UF,   
                'TEL_1' => $request->TEL_1 ,
                'TEL_2' => $request->TEL_2 ,
                'EMAIL' => $request->EMAIL ,
                'CARTAO_SUS' => $request->CARTAO_SUS ,
                'PROF_COD' => $request->PROF_COD   
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
           $query->where('NOME','LIKE', '%'.$request->NOME.'%');
        }
        if($request->CPF){
            $query->where('CPF','=', $request->CPF);
         }
         if($request->filtroData){
            $query->where('NASCIMENTO','=', $request->filtroData,);
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
