<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class MddaCaso extends Model
{
    protected $table = 'mdda_casos';
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'id',
        'mdda_relatorio_id',
        'atendimento_id',
        'numero_ordem',
        'data_atendimento',
        'nome_paciente',
        'procedencia',
        'faixa_etaria',
        'idade_display',
        'zona',
        'data_primeiros_sintomas',
        'plano_tratamento',
    ];

    protected static function boot(): void
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->id)) {
                $model->id = (string) Str::uuid();
            }
        });
    }

    public function relatorio()
    {
        return $this->belongsTo(MddaRelatorio::class, 'mdda_relatorio_id', 'id');
    }

    public function atendimento()
    {
        return $this->belongsTo(Atendimento::class, 'atendimento_id', 'atend_cod');
    }
}
