<?php

namespace App\Models;
use Illuminate\Support\Str; 
use Illuminate\Database\Eloquent\Model;

class Medico extends Model{
    protected $fillable = ['MED_NOME','CRM','ATIVO'];
    CONST updated_at = null;
    CONST created_at = null;
    protected $primaryKey = 'MED_COD';
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
        return $this->hasMany(Atendimento::class,'MED_COD', 'MED_COD');
    }
    //
}
