<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str; 

class Paciente extends Model{
   
    protected $fillable = ['nome','filicao_1','filicao_2','cep','logradouro','nascimento','cidade','uf','tel_1','tel_2','email','cartao_sus','prof_cod'];
    const updated_at = null;
    const created_at = null;
    protected $primaryKey = 'pac_cod';
    public $timestamps = false;
    protected $keyType = 'string';

    public $incrementing = false;

    public static function alunos(){
        return DB::select('SELECT * FROM pacientes');
    }
    protected static function boot(){
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->{$model->getKeyName()})) {
                $model->{$model->getKeyName()} = (string) Str::uuid();
            }
        });
    }
    public function getNascimentoAttribute($data){
        if(!$data){
            return null;
        }else{
            return Carbon::createFromFormat('Y-m-d', $data)->format('d-m-Y');
        }
        

    }
    
    public function atendimento(){
        return $this->hasMany(Atendimento::class,'pac_cod','pac_cod');
    }

}


