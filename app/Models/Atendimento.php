<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str; 
use App\Models\Paciente;

class Atendimento extends Model{
    protected $fillable = ['ATEND_COD','PAC_COD','DT_ATENDIMENTO','SITUCAO_QUEIXA','MMHG','BPM' ,'SPO2','TEMP' ,'RPM','KG','HGT','DESC_CASO','ENF_COD','ESP_COD','MED_COD'];
    CONST updated_at = null;
    CONST created_at = null;
    protected $primaryKey = 'ATEND_COD';
    public $timestamps = false;
    protected $keyType = 'string';

    public $incrementing = false;
    protected static function boot(){
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->{$model->getKeyName()})) {
                $model->{$model->getKeyName()} = (string) Str::uuid();
            }
        });
    }

    public function paciente(){
        return $this->belongsTo(Paciente::class,'PAC_COD', 'PAC_COD');
    }
    public function medico(){
        return $this->belongsTo(Medico::class,'MED_COD','MED_COD');
    }
    //
}
