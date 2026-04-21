<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('atendimentos', function (Blueprint $table) {
            $table->string('cid10', 10)->nullable()->after('situacao_queixa');
        });
    }

    public function down(): void
    {
        Schema::table('atendimentos', function (Blueprint $table) {
            $table->dropColumn('cid10');
        });
    }
};
