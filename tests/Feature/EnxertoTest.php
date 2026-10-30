<?php

namespace Tests\Feature;

use App\Models\Animal;
use App\Models\Enxerto;
use App\Models\Parto;
use App\Models\Reproducao;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EnxertoTest extends TestCase
{
    use RefreshDatabase;

    public function test_transfere_leitao_para_ninhada_adotiva(): void
    {
        $origem = Animal::create(['tatuagem' => 'M001', 'sexo' => 'F', 'tipo' => 'matriz', 'situacao' => 'ativo']);
        $adotiva = Animal::create(['tatuagem' => 'M002', 'sexo' => 'F', 'tipo' => 'matriz', 'situacao' => 'ativo']);
        $leitao = Animal::create(['tatuagem' => 'L001', 'sexo' => 'M', 'tipo' => 'leitao', 'situacao' => 'ativo', 'id_mae' => $origem->id]);
        $reproducaoOrigem = Reproducao::create(['femea_id' => $origem->id, 'data_primeiro_servico' => '2026-06-30', 'situacao' => 'parto']);
        $reproducaoAdotiva = Reproducao::create(['femea_id' => $adotiva->id, 'data_primeiro_servico' => '2026-07-01', 'situacao' => 'parto']);
        $partoOrigem = Parto::create(['reproducao_id' => $reproducaoOrigem->id, 'matriz_id' => $origem->id, 'dt_inicio' => '2026-10-01 08:00:00', 'tipo' => 'normal', 'situacao' => 'realizado']);
        $partoAdotivo = Parto::create(['reproducao_id' => $reproducaoAdotiva->id, 'matriz_id' => $adotiva->id, 'dt_inicio' => '2026-10-02 08:00:00', 'tipo' => 'normal', 'situacao' => 'realizado']);
        $partoOrigem->animais()->attach($leitao->id, ['situacao_ao_nascer' => 'vivo']);

        $response = $this->withoutMiddleware(PreventRequestForgery::class)->post(route('enxertos.store'), [
            'animal_id' => $leitao->id,
            'mae_origem_id' => $origem->id,
            'parto_origem_id' => $partoOrigem->id,
            'mae_adotiva_id' => $adotiva->id,
            'parto_adotivo_id' => $partoAdotivo->id,
            'data_enxerto' => '2026-10-03',
            'motivo' => 'Recusa da mãe de origem',
        ]);

        $response->assertRedirect(route('enxertos.index'));
        $this->assertDatabaseHas('enxertos', ['animal_id' => $leitao->id, 'mae_origem_id' => $origem->id, 'mae_adotiva_id' => $adotiva->id]);
        $this->assertDatabaseHas('animais', ['id' => $leitao->id, 'id_mae' => $adotiva->id]);
        $this->assertFalse($partoOrigem->animais()->whereKey($leitao->id)->exists());
        $this->assertTrue($partoAdotivo->animais()->whereKey($leitao->id)->exists());
        $this->assertInstanceOf(Enxerto::class, Enxerto::first());
    }
}
