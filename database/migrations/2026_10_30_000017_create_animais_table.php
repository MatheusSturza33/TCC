<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('animais', function (Blueprint $table): void {
            $table->id();
            $table->string('tatuagem')->nullable();
            $table->string('mossa')->nullable();
            $table->string('brinco')->nullable();
            $table->string('chip')->nullable();
            $table->enum('sexo', ['M', 'F']);
            $table->enum('tipo', ['matriz', 'reprodutor', 'leitao']);
            $table->enum('situacao', ['ativo', 'inativo', 'vendido', 'morto'])->default('ativo');
            $table->enum('estado_reprodutivo', ['vazia', 'gestante', 'lactante', 'desmamada', 'nao_aplicavel'])->nullable();
            $table->date('data_nascimento')->nullable();
            $table->string('pai_nome')->nullable();
            $table->string('mae_nome')->nullable();
            $table->foreignId('id_pai')->nullable()->constrained('animais')->nullOnDelete();
            $table->foreignId('id_mae')->nullable()->constrained('animais')->nullOnDelete();
            $table->foreignId('id_localizacao_atual')->nullable()->constrained('localizacoes')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('animais');
    }
};
