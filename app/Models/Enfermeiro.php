<?php

namespace App\Models;
use Illuminate\Support\Str; 
use Illuminate\Database\Eloquent\Model;

class Enfermeiro extends Model{
    protected $fillable = ['enf_nome','cre','ativo'];
    const updated_at = null;
    const created_at = null;
    protected $primaryKey = 'enf_cod';
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
        return $this->hasMany(Atendimento::class,'enf_cod', 'enf_cod');
    }
    //
}
