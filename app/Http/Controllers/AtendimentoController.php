<?php

namespace App\Http\Controllers;

use App\Models\Atendimento;
use App\Models\Enfermeiro;
use App\Models\Especialidade;
use App\Models\Medico;
use App\Models\Paciente;
use DateTime;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Reports\FichaAtendimento;
use App\Reports\Receituario;



class AtendimentoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($atend_cod =null){
        try {
        if($atend_cod){
            $atendimento = Atendimento::with(['paciente','enfermeiro','medico', 'especialidade'])->find($atend_cod,$columns = ['*']);
            return view("atendimento.show",[
                'atendimento' => $atendimento, 
                'paciente' => $atendimento->paciente,
                'medico' => $atendimento->medico,
                'enfermeiro' =>$atendimento->enfermeiro,
                'especialidade' => $atendimento->especialidade        
            ]); 
        }else{
            $enfermeiro = Enfermeiro::select ('enf_cod', 'enf_nome')->where('ativo', '=', 'S')->orderBy('enf_nome', 'asc')->get();
            $pacientes = Paciente::select('pac_cod', 'nome')->where('ativo','=','S')->orderBy('nome','asc')->get();
            $medicos = Medico::select()->where('ativo','=','S')->orderBy('med_nome')->get();
            $especialidade = Especialidade::select()->where('ativo','=','S')->orderBy('escp_desc')->get();
            

            return view("atendimento.atendimento",[
                'pacientes' => $pacientes, 
                'data'=> (new datetime())->format('y-m-d h:i'),
                'medicos' => $medicos,
                'enfermeiros' => $enfermeiro,
                'especialidades' => $especialidade
            ]);
        } 
        } catch (\Throwable $th) {
            return abort(404);
        }
    }
    public function registroAtendimento($atend_cod){
       
        try{
            $atendimento = Atendimento::select([
                'atendimentos.atend_cod',
                'atendimentos.dt_atendimento',
                'atendimentos.situacao_queixa',
                'atendimentos.mmhg',
                'atendimentos.bpm',
                'atendimentos.rpm',
                'atendimentos.spo2',
                'atendimentos.temp',
                'atendimentos.kg',
                'atendimentos.hgt',
                'pacientes.nome AS paciente_nome', // Nome do paciente
                'enfermeiros.enf_nome AS enfermeiro_nome', // Nome do enfermeiro
                'medicos.med_nome AS medico_nome', // Nome do médico
                'especialidades.escp_desc AS especialidade_desc' // Descrição da especialidade
            ])
            ->leftJoin('pacientes', 'atendimentos.pac_cod', '=', 'pacientes.pac_cod')
            ->leftJoin('enfermeiros', 'atendimentos.enf_cod', '=', 'enfermeiros.enf_cod')
            ->leftJoin('medicos', 'atendimentos.med_cod', '=', 'medicos.med_cod')
            ->leftJoin('especialidades', 'atendimentos.esp_cod', '=', 'especialidades.esp_cod')
            ->where('atendimentos.atend_cod', $atend_cod)
            ->first();
           
            return response()->json($atendimento); 
        }catch(Exception $e){
            return $e->getMessage();
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
            
            DB::beginTransaction();
            $atend_cod = Atendimento::create([
            'dt_atendimento' => $request->dtAtendimento,
            'situacao_queixa' => $request->situacao,
            'mmhg'  => $request->mmhg,
            'bpm' => $request->bpm,
            'spo2'=> $request->spo2,
            'temp' => $request->temp,
            'rpm'=> $request->rpm,
            'kg'=> $request->kg,
            'hgt'=> $request->hgt,
            'desc_caso'=> $request->descricaoCaso,
            'enf_cod'=> $request->enfermeiro,
            'esp_cod'=> $request->esp_cod,
            'med_cod' => $request->med_cod,
            'pac_cod' => $request->pac_cod,
            'receituario' => $request->receituario

        ]);
        
            DB::commit();  
        
        return redirect('/atendimento/'.$atend_cod->atend_cod);
        
        } catch (QueryException $th) {
            DB::rollBack();
            return redirect()->back()->with('error', $th->getMessage());
        }
    }
    public function historico(Request $request ){
         if(!$request->nome && !$request->dtAtendimento){
            $atendimento = Atendimento::with(['paciente','enfermeiro','medico', 'especialidade'])->paginate(10);
            return view('atendimento.historico', ['atendimentos' =>$atendimento]);
        }
    }
    public function gerarfichaAtendimento($atend_cod ){
        try{
            $dados = $this->consultaFichaAtendimento($atend_cod);
            $html = new FichaAtendimento();
            return $html->gerarHtmlAtendimento( json_encode($dados));
        }catch(Exception $th) {
            return false;
        }
        
    }
    public function gerarReceita($atend_cod ){
        try{
            ini_set('memory_limit', '512M');
            set_time_limit(120);
            $dados = $this->consultaFichaAtendimento($atend_cod);
            $html = new Receituario();
            return $html->gerarHtmlReceituario( json_encode($dados));
        }catch(Exception $th) {
            return false;
        }
        
    }
    public function consultaFichaAtendimento($atend_cod){
        $atendimento = Atendimento::select([
            'atendimentos.dt_atendimento',
            'pacientes.nome',
            DB::raw("COALESCE(CONCAT(pacientes.logradouro, ', ', pacientes.cidade, ', ', pacientes.uf), 'Não informado') AS endereco"),
            DB::raw("COALESCE(pacientes.filicao_1, pacientes.filicao_2, 'Não informado') AS filicao"),
            DB::raw("COALESCE(pacientes.cartao_sus, 'Não informado') AS cartao_sus"),
            'pacientes.nascimento',
            DB::raw("TIMESTAMPDIFF(YEAR, pacientes.nascimento, CURDATE()) AS idade"),
            DB::raw("COALESCE(pacientes.tel_1, pacientes.tel_2, 'Não informado') AS tel"),
            'atendimentos.situacao_queixa',
            'atendimentos.mmhg',
            'atendimentos.bpm',
            'atendimentos.rpm',
            'atendimentos.spo2',
            'atendimentos.temp',
            'atendimentos.kg',
            'atendimentos.hgt',
            'atendimentos.desc_caso',
            'atendimentos.receituario'
        ])
        ->leftJoin('pacientes', 'atendimentos.pac_cod', '=', 'pacientes.pac_cod')
        ->leftJoin('enfermeiros', 'atendimentos.enf_cod', '=', 'enfermeiros.enf_cod')
        ->leftJoin('medicos', 'atendimentos.med_cod', '=', 'medicos.med_cod')
        ->leftJoin('especialidades', 'atendimentos.esp_cod', '=', 'especialidades.esp_cod')
        ->where('atendimentos.atend_cod', $atend_cod)
        ->first();
        return response()->json($atendimento);
    }
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
