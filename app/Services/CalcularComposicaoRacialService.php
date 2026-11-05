<?php

namespace App\Services;

use App\Models\Animal;

class CalcularComposicaoRacialService
{
    public function sincronizar(Animal $animal): bool
    {
        $animal->loadMissing(['mae.racas', 'pai.racas']);

        $composicao = [];

        foreach ([$animal->mae, $animal->pai] as $parent) {
            if (! $parent || $parent->racas->isEmpty()) {
                return false;
            }

            foreach ($parent->racas as $raca) {
                $composicao[$raca->id] = ($composicao[$raca->id] ?? 0) + ((float) $raca->pivot->percentual / 2);
            }
        }

        if ($composicao === []) {
            return false;
        }

        $composicao = collect($composicao)
            ->map(fn (float $percentual): float => round($percentual, 2))
            ->filter(fn (float $percentual): bool => $percentual > 0)
            ->all();

        $diferenca = round(100 - array_sum($composicao), 2);
        if ($diferenca !== 0.0) {
            $racaComMaiorPercentual = collect($composicao)->sortDesc()->keys()->first();
            $composicao[$racaComMaiorPercentual] = round(
                $composicao[$racaComMaiorPercentual] + $diferenca,
                2
            );
        }

        $animal->racas()->sync(
            collect($composicao)->mapWithKeys(fn (float $percentual, int|string $racaId): array => [
                $racaId => ['percentual' => $percentual],
            ])->all()
        );

        return true;
    }
}
