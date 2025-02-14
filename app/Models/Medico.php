<?php

namespace App\Models;
use Illuminate\Support\Str; 
use Illuminate\Database\Eloquent\Model;

class Medico extends Model{
    protected $fillable = ['med_nome','crm','ativo'];
    const updated_at = null;
    const created_at = null;
    protected $primaryKey = 'med_cod';
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
    public function atendimento(){
        return $this->hasMany(Atendimento::class,'med_cod', 'med_cod');
    }
}
