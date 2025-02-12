<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str; 

class Paciente extends Model
{
    //<?php
    //
    protected $fillable = ['NOME','FILICAO_1','FILICAO_2','CEP','LOGRADOURO','NASCIMENTO','CIDADE','UF','TEL_1','TEL_2','EMAIL','CARTAO_SUS','PROF_COD'];
    CONST updated_at = null;
    CONST created_at = null;
    protected $primaryKey = 'PAC_COD';
    public $timestamps = false;
    protected $keyType = 'string';

    public $incrementing = false;

    public static function alunos(){
        return DB::select('SELECT * FROM Pacientes');
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
        return Carbon::createFromFormat('Y-m-d', $data)->format('d-m-Y');

    }
    
    public function atendimento(){
        return $this->hasMany(Atendimento::class,'PAC_COD','PAC_COD');
    }

}


