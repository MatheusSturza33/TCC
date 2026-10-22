<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AlertaController;
use App\Http\Controllers\AnimalController;
use App\Http\Controllers\AnimalPartoController;
use App\Http\Controllers\AnimalRacaController;
use App\Http\Controllers\AplicacaoVacinaController;
use App\Http\Controllers\ConfiguracaoController;
use App\Http\Controllers\DesmameController;
use App\Http\Controllers\DestinoController;
use App\Http\Controllers\EnxertoController;
use App\Http\Controllers\GrupoMontaController;
use App\Http\Controllers\LocalizacaoController;
use App\Http\Controllers\MatrizPlanoController;
use App\Http\Controllers\MovimentacaoController;
use App\Http\Controllers\PartoController;
use App\Http\Controllers\PesagemController;
use App\Http\Controllers\PlanoAlimentarController;
use App\Http\Controllers\PeriodoPlanoController;
use App\Http\Controllers\RacaController;
use App\Http\Controllers\ReproducaoController;
use App\Http\Controllers\SaidaController;
use App\Http\Controllers\ServicoReprodutivoController;
use App\Http\Controllers\VacinaController;
use App\Http\Controllers\UserController;


Route::get('/', [AuthController::class, 'loginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});


Route::resource(
    'servicos-reprodutivos',
    ServicoReprodutivoController::class
);

Route::resource('aplicacoes-vacina', AplicacaoVacinaController::class)
    ->parameters(['aplicacoes-vacina' => 'aplicacaoVacina']);

Route::resource('planos-alimentares', PlanoAlimentarController::class)
    ->parameters(['planos-alimentares' => 'planoAlimentar']);

Route::resource('periodos-plano', PeriodoPlanoController::class)
    ->parameters(['periodos-plano' => 'periodoPlano']);

Route::resource(
    'matriz-plano',
    MatrizPlanoController::class
);

Route::resource(
    'configuracoes',
    ConfiguracaoController::class
);

Route::resource('animais', AnimalController::class);
Route::resource('racas', RacaController::class);
Route::resource('animal-racas', AnimalRacaController::class);
Route::resource('localizacoes', LocalizacaoController::class)
    ->parameters(['localizacoes' => 'localizacao']);
Route::resource('movimentacoes', MovimentacaoController::class);
Route::resource('destinos', DestinoController::class);
Route::resource('grupos-monta', GrupoMontaController::class);
Route::post('reproducoes/{reproducao}/confirmar', [ReproducaoController::class, 'confirmar'])
    ->name('reproducoes.confirmar');
Route::post('reproducoes/{reproducao}/cancelar', [ReproducaoController::class, 'cancelar'])
    ->name('reproducoes.cancelar');
Route::resource('reproducoes', ReproducaoController::class)
    ->parameters(['reproducoes' => 'reproducao']);
Route::resource('partos', PartoController::class);
Route::resource('animal-partos', AnimalPartoController::class);
Route::resource('enxertos', EnxertoController::class);
Route::resource('desmames', DesmameController::class);
Route::resource('pesagens', PesagemController::class);
Route::resource('saidas', SaidaController::class);
Route::resource('vacinas', VacinaController::class);
Route::resource('alertas', AlertaController::class);
Route::resource('users', UserController::class);
