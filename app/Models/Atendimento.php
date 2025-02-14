<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str; 
use App\Models\Paciente;

class Atendimento extends Model{
    protected $fillable = ['atend_cod','pac_cod','dt_atendimento','situcao_queixa','mmhg','bpm' ,'spo2','temp' ,'rpm','kg','hgt','desc_caso','enf_cod','esp_cod','med_cod'];
    const updated_at = null;
    const created_at = null;
    protected $primaryKey = 'atend_cod';
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
        return $this->belongsTo(Paciente::class,'pac_cod', 'pac_cod');
    }
    public function medico(){
        return $this->belongsTo(Medico::class,'med_cod','med_cod');
    }
    public function enfermeiro(){
        return $this->belongsTo(Enfermeiro::class,'enf_cod','enf_cod');
    }
    public function especialidade(){
        return $this->belongsTo(Especialidade::class,'esp_cod','esp_cod');
    }
    //
}
