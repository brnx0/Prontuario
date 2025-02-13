<?php

namespace App\Http\Controllers;

use App\Models\Atendimento;
use App\Models\Medico;
use App\Models\Paciente;
use DateTime;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AtendimentoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($atend_cod =null){
        if($atend_cod){
            $atendimento = Atendimento::with('paciente')->find($atend_cod,$columns = ['*']);
            return view("atendimento.show",['atendimento' => $atendimento, 'paciente' => $atendimento->paciente]); 
        }else{
            $pacientes = Paciente::select('pac_cod', 'nome')->where('ativo','=','S')->orderBy('nome','asc')->get();
            $medicos = Medico::select()->where('ativo','=','S')->orderBy('med_nome')->get();
            return view("atendimento.atendimento",[
                'pacientes' => $pacientes, 
                'data'=> (new datetime())->format('y-m-d h:i'),
                'medicos' => $medicos
            ]);
        } 
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
    public function store(Request $request){

        if(!$request){
            return redirect()->back()->with("error","");
        }
        try {
        //   DB::beginTransaction();
           $atend_cod = Atendimento::create([
          'dt_atendimento' => $request->dtAtendimento,
          'situcao_queixa' => $request->situacao,
          'mmhg'  => $request->mmhg,
          'bpm' => $request->bpm,
          'spo2'=> $request->spo2,
          'temp' => $request->temp,
          'rpm'=> $request->rpm,
          'kg'=> $request->kg,
          'hgt'=> $request->hgt,
          'desc_caso'=> $request->descricaoCaso,
          'enf_cod'=> $request->enf_cod,
          'esp_cod'=> $request->esp_cod,
          'med_cod' => $request->med_cod,
          'pac_cod' => $request->pac_cod

        ]);
        // DB::commit();  
        
        return redirect('/atendimento/'.$atend_cod->atend_cod);
        
        } catch (QueryException $th) {
            DB::rollBack();
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Atendimento $atendimento)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Atendimento $atendimento)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Atendimento $atendimento)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Atendimento $atendimento)
    {
        //
    }
}
