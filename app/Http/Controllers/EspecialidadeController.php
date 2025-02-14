<?php

namespace App\Http\Controllers;

use App\Models\Especialidade;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class EspecialidadeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()    {
        
        $especialidade = Especialidade::all();
        return view('especialidade.show',['especialidades'=>$especialidade]);
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
        try{
            Especialidade::create([
                'escp_desc' => $request->descEspc
            ]);
            return back()->with('success', 'Registro criado.');
        }catch(QueryException $th){
            return back()->with('error', $th->getMessage());
        }
        //
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

    /**
     * Display the specified resource.
     */
    public function show(Especialidade $especialidade)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Especialidade $especialidade)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Especialidade $especialidade)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Especialidade $especialidade)
    {
        //
    }
}
