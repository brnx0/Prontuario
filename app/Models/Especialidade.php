<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str; 

class Especialidade extends Model{
    protected $fillable = ['escp_desc','ativo'];
    const updated_at = null;
    const created_at = null;
    protected $primaryKey = 'esp_cod';
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
        return $this->hasMany(Atendimento::class,'esp_cod', 'esp_cod');
    }
}
