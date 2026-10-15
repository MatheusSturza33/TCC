<?php

namespace App\Services;

use App\Models\Animal;
use App\Models\AnimalRaca;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class CalcularComposicaoRacialService
{
    public function sincronizar(Animal $animal): void
    {
        if (! $this->deveCalcular($animal)) {
            return;
        }

        $animal->loadMissing([
            'mae.racas',
            'pai.racas',
        ]);

        $composicao = $this->calcular($animal);
        $total = round($composicao->sum(), 2);

        if (abs($total - 100) > 0.01) {
            throw ValidationException::withMessages([
                'id_pai' => 'A composição racial dos pais deve totalizar 100%.',
            ]);
        }

        DB::transaction(function () use ($animal, $composicao): void {
            AnimalRaca::where('animal_id', $animal->id)->delete();

            foreach ($composicao as $racaId => $percentual) {
                AnimalRaca::create([
                    'animal_id' => $animal->id,
                    'raca_id' => $racaId,
                    'percentual' => round($percentual, 2),
                ]);
            }
        });
    }

    private function deveCalcular(Animal $animal): bool
    {
        return $animal->tipo === 'leitao'
            && filled($animal->id_pai)
            && filled($animal->id_mae);
    }

    private function calcular(Animal $animal): Collection
    {
        return collect([
            $animal->mae->racas,
            $animal->pai->racas,
        ])
            ->flatten()
            ->groupBy('pivot.raca_id')
            ->map(function (Collection $racas): float {
                return $racas->sum(function ($raca): float {
                    return (float) $raca->pivot->percentual / 2;
                });
            });
    }
}
