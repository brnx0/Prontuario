<?php

namespace App\Models;
use Illuminate\Support\Str; 
use Illuminate\Database\Eloquent\Model;

class Enfermeiro extends Model{
    protected $fillable = ['ENF_NOME','CRE','ATIVO'];
    CONST updated_at = null;
    CONST created_at = null;
    protected $primaryKey = 'ENF_COD';
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
        return $this->hasMany(Atendimento::class,'ENF_COD', 'ENF_COD');
    }
    //
}
