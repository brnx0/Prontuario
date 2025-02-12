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
    public function index($ATEND_COD =null){
        if($ATEND_COD){
            $atendimento = Atendimento::with('paciente')->find($ATEND_COD,$columns = ['*']);
            return view("atendimento.show",['atendimento' => $atendimento, 'paciente' => $atendimento->paciente]); 
        }else{
            $pacientes = Paciente::select('PAC_COD', 'NOME')->where('ATIVO','=','S')->orderBy('NOME','ASC')->get();
            $medicos = Medico::select()->where('ATIVO','=','S')->orderBy('MED_NOME')->get();
            return view("atendimento.atendimento",[
                'pacientes' => $pacientes, 
                'Data'=> (new DateTime())->format('Y-m-d H:i'),
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
          'DT_ATENDIMENTO' => $request->dtAtendimento,
          'SITUCAO_QUEIXA' => $request->SITUACAO,
          'MMHG'  => $request->mmhg,
          'BPM' => $request->bpm,
          'SPO2'=> $request->spo2,
          'TEMP' => $request->temp,
          'RPM'=> $request->rpm,
          'KG'=> $request->kg,
          'HGT'=> $request->hgt,
          'DESC_CASO'=> $request->descricaoCaso,
          'ENF_COD'=> $request->ENF_COD,
          'ESP_COD'=> $request->ESP_COD,
          'MED_COD' => $request->MED_COD,
          'PAC_COD' => $request->PAC_COD

        ]);
        // DB::commit();  
        
        return redirect('/atendimento/'.$atend_cod->ATEND_COD);
        
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
