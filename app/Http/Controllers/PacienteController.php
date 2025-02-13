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
    //     $dataNascimento = (new DateTime($request->nascimento))->setTime(0,0,0);       
    //    if( $dataNascimento > (new DateTime())->setTime(0,0,0)){
    //         return redirect()->back()->withInput()->with(['error'=>'A data de nascimento não pode ser maior que a data atual',]);
    //    }
        try {
            DB::beginTransaction();
            Paciente::updateOrCreate([
                'pac_cod' => $request->pes_cod
            ],
            [
                'nome' => $request->nome,
                'filicao_1' => $request->filicao_1,
                'filicao_2' => $request->filicao_2,
                'cep' => $request->CEP,
                'nascimento' => $request->nascimento,
                'logradouro' => $request->logradouro,
                'cidade' => $request->cidade,
                'uf'  => $request->uf,   
                'tel_1' => $request->tel_1 ,
                'tel_2' => $request->tel_2 ,
                'email' => $request->email ,
                'cartao_sus' => $request->cartao_sus ,
                'prof_cod' => $request->prof_cod   
            ]);
            DB::commit();
            return redirect('/paciente')->with('success','Sucesso!');

        } catch (QueryException  $th) {
            DB::rollBack();
            return redirect('/paciente')->with('error','Erro ao criar o registro. '.$th ->getMessage());
        }
    }
   public function filtro (Request $request){

        if(empty($request->nome) && empty($request->cpf) && empty($request->filtroData) ){
            return PacienteController::index();
        }
        $query = Paciente::query();
        if($request->nome){
           $query->where('nome','LIKE', '%'.$request->nome.'%');
        }
        if($request->cpf){
            $query->where('cpf','=', $request->cpf);
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
