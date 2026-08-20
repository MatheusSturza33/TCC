<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('configuracoes', function (Blueprint $table) {
            $table->id();
            $table->string('chave')->unique();
            $table->string('valor');
        });

        Schema::create('vacinas', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->enum('categoria_animal', ['matriz', 'reprodutor', 'leitao', 'todos']);
            $table->string('fase_aplicacao')->nullable();
            $table->unsignedInteger('intervalo_reforco')->default(0)->comment('Intervalo de reforço em dias');
            $table->boolean('ativa')->default(true);
        });

        Schema::create('planos_alimentares', function (Blueprint $table) {
            $table->id();
            $table->string('nome')->unique();
            $table->text('observacoes')->nullable();
            $table->boolean('ativo')->default(true);
            $table->foreignId('usuario_id')->nullable()->constrained('users')->nullOnDelete();
        });

        Schema::create('racas', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->text('observacoes')->nullable();
        });

        Schema::create('localizacoes', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->enum('tipo', ['maternidade', 'gestacao', 'creche', 'terminacao', 'quarentena', 'outro']);
            $table->unsignedInteger('capacidade')->nullable();
        });

        Schema::create('destinos', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->enum('tipo', ['abate', 'venda', 'morte', 'transferencia', 'outro']);
        });

        Schema::create('animais', function (Blueprint $table) {
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
            $table->foreignId('id_pai')->nullable()->constrained('animais')->nullOnDelete();
            $table->foreignId('id_mae')->nullable()->constrained('animais')->nullOnDelete();
            $table->foreignId('id_localizacao_atual')->nullable()->constrained('localizacoes')->nullOnDelete();
        });

        Schema::create('animal_raca', function (Blueprint $table) {
            $table->id();
            $table->foreignId('animal_id')->constrained('animais')->cascadeOnDelete();
            $table->foreignId('raca_id')->constrained('racas')->cascadeOnDelete();
            $table->decimal('percentual', 5, 2)->unsigned();
            $table->unique(['animal_id', 'raca_id']);
        });

        Schema::create('aplicacoes_vacina', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vacina_id')->constrained('vacinas')->restrictOnDelete();
            $table->foreignId('animal_id')->constrained('animais')->cascadeOnDelete();
            $table->date('data_aplicacao');
            $table->date('proxima_dose')->nullable()->comment('Calculada a partir do intervalo de reforço da vacina');
            $table->string('lote_vacina')->nullable();
            $table->foreignId('responsavel_id')->nullable()->constrained('users')->nullOnDelete();
        });

        Schema::create('alertas', function (Blueprint $table) {
            $table->id();
            $table->enum('tipo', ['parto', 'desmame', 'vacina', 'consanguinidade', 'outro']);
            $table->unsignedBigInteger('referencia_id');
            $table->date('data_prevista');
            $table->enum('situacao', ['pendente', 'concluido', 'cancelado'])->default('pendente');
            $table->foreignId('usuario_id')->nullable()->constrained('users')->nullOnDelete();
            $table->index(['tipo', 'referencia_id']);
        });

        Schema::create('periodos_plano', function (Blueprint $table) {
            $table->id();
            $table->foreignId('plano_alimentar_id')->constrained('planos_alimentares')->cascadeOnDelete();
            $table->unsignedTinyInteger('dia_inicio');
            $table->unsignedTinyInteger('dia_fim');
            $table->decimal('quantidade_kg_dia', 8, 3);
        });

        Schema::create('matriz_plano', function (Blueprint $table) {
            $table->id();
            $table->foreignId('animal_id')->constrained('animais')->cascadeOnDelete();
            $table->foreignId('plano_alimentar_id')->constrained('planos_alimentares')->cascadeOnDelete();
            $table->date('data_inicio');
            $table->date('data_fim')->nullable();
            $table->foreignId('usuario_id')->nullable()->constrained('users')->nullOnDelete();
        });

        Schema::create('grupos_monta', function (Blueprint $table) {
            $table->id();
            $table->string('identificacao')->unique();
            $table->string('semana_referencia');
            $table->enum('situacao', ['programado', 'em_servico', 'encerrado', 'cancelado'])->default('programado');
            $table->date('data_fim')->nullable();
            $table->foreignId('usuario_id')->nullable()->constrained('users')->nullOnDelete();
        });

        Schema::create('reproducoes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('femea_id')->constrained('animais')->restrictOnDelete();
            $table->foreignId('grupo_monta_id')->nullable()->constrained('grupos_monta')->nullOnDelete();
            $table->date('data_primeiro_servico');
            $table->date('previsao_parto')->nullable()->comment('Calculada conforme a data do primeiro serviço');
            $table->enum('situacao', ['aberta', 'confirmada', 'parto', 'cancelada'])->default('aberta');
            $table->foreignId('reprodutor_id')->nullable()->constrained('animais')->nullOnDelete();
            $table->foreignId('usuario_id')->nullable()->constrained('users')->nullOnDelete();
        });

        Schema::create('servicos_reprodutivos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reproducao_id')->constrained('reproducoes')->cascadeOnDelete();
            $table->enum('tipo', ['natural', 'inseminacao_artificial', 'monta']);
            $table->date('data');
            $table->foreignId('reprodutor_id')->nullable()->constrained('animais')->nullOnDelete();
            $table->unsignedInteger('numero_tentativa')->default(1);
        });

        Schema::create('partos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reproducao_id')->constrained('reproducoes')->restrictOnDelete();
            $table->foreignId('matriz_id')->constrained('animais')->restrictOnDelete();
            $table->dateTime('dt_inicio');
            $table->enum('tipo', ['normal', 'cesariana', 'aborto']);
            $table->unsignedInteger('total_vivos')->default(0);
            $table->unsignedInteger('total_natimortos')->default(0);
            $table->unsignedInteger('total_mumificados')->default(0);
            $table->enum('situacao', ['previsto', 'realizado', 'cancelado'])->default('previsto');
        });

        Schema::create('animal_parto', function (Blueprint $table) {
            $table->id();
            $table->foreignId('animal_id')->constrained('animais')->cascadeOnDelete();
            $table->foreignId('parto_id')->constrained('partos')->cascadeOnDelete();
            $table->time('hora_nascimento')->nullable();
            $table->enum('situacao_ao_nascer', ['vivo', 'natimorto', 'mumificado'])->default('vivo');
            $table->unique(['animal_id', 'parto_id']);
        });

        Schema::create('enxertos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('animal_id')->constrained('animais')->cascadeOnDelete();
            $table->foreignId('mae_origem_id')->constrained('animais')->restrictOnDelete();
            $table->foreignId('mae_adotiva_id')->constrained('animais')->restrictOnDelete();
            $table->date('data_enxerto');
            $table->string('motivo')->nullable();
            $table->foreignId('usuario_id')->nullable()->constrained('users')->nullOnDelete();
        });

        Schema::create('desmames', function (Blueprint $table) {
            $table->id();
            $table->foreignId('matriz_id')->constrained('animais')->restrictOnDelete();
            $table->date('data_realizacao');
            $table->unsignedInteger('total_leitoes_desmamados');
            $table->decimal('peso_medio_desmame_kg', 8, 3)->nullable();
            $table->foreignId('usuario_id')->nullable()->constrained('users')->nullOnDelete();
        });

        Schema::create('pesagens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('animal_id')->constrained('animais')->cascadeOnDelete();
            $table->enum('fase', ['maternidade', 'creche', 'terminacao', 'reproducao', 'outro']);
            $table->date('data');
            $table->decimal('peso_kg', 8, 3);
            $table->decimal('gp_kg', 8, 3)->nullable()->comment('Calculado a partir de pesagens anteriores');
            $table->decimal('gmd_kg', 8, 3)->nullable()->comment('Ganho médio diário calculado');
            $table->foreignId('usuario_id')->nullable()->constrained('users')->nullOnDelete();
        });

        Schema::create('saidas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('animal_id')->constrained('animais')->restrictOnDelete();
            $table->date('data');
            $table->enum('motivo', ['abate', 'venda', 'morte', 'transferencia', 'outro']);
            $table->decimal('peso_saida_kg', 8, 3)->nullable();
            $table->foreignId('destino_id')->nullable()->constrained('destinos')->nullOnDelete();
            $table->foreignId('usuario_id')->nullable()->constrained('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        foreach ([
            'saidas', 'pesagens', 'desmames', 'enxertos', 'animal_parto', 'partos',
            'servicos_reprodutivos', 'reproducoes', 'grupos_monta', 'matriz_plano',
            'periodos_plano', 'alertas', 'aplicacoes_vacina', 'animal_raca',
            'animais', 'destinos', 'localizacoes', 'racas', 'planos_alimentares',
            'vacinas', 'configuracoes',
        ] as $table) {
            Schema::dropIfExists($table);
        }
        Schema::enableForeignKeyConstraints();
    }
};
