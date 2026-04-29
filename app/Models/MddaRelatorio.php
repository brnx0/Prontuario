<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class MddaRelatorio extends Model
{
    protected $table = 'mdda_relatorios';
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id',
        'semana_epidemiologica',
        'ano',
        'municipio',
        'unidade_saude',
        'responsavel_nome',
        'status',
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

    public function casos()
    {
        return $this->hasMany(MddaCaso::class, 'mdda_relatorio_id', 'id')
                    ->orderBy('numero_ordem');
    }
}
