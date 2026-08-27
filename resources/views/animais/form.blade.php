@include('shared.form')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <title>Cadastro de Animais</title>
</head>
<body class="--bs-body-color">
    <div class="container-fluid ">
        <div class="container col-xs-12 col-sm-6 col-md-6 col-lg-3">
    <h1>Ficheiro</h1>
    <b>Nome do animal</b>
    <input class="form-control" type="text" placeholder="Nome" aria-label="default input example">
    <b>Brinco do animal</b>
    <input class="form-control" type="text" placeholder="Brinco" aria-label="default input example">
    <b>Nome do pai</b>
    <input class="form-control" type="text" placeholder="Nome do pai" aria-label="default input example">
    <b>Nome da mãe</b>
    <input class="form-control" type="text" placeholder="Nome da mãe" aria-label="default input example">
    <b>Sexo</b>



    <div class="form-check">
            <input class="form-check-input" type="radio" name="F" id="radioDefault1">
            <label class="form-check-label" for="radioDefault1">Fêmea</label>
        </div>
    <div class="form-check">
            <input class="form-check-input" type="radio" name="M" id="radioDefault2" checked>
            <label class="form-check-label" for="radioDefault2">Macho</label>
    </div>


    <div class="calendar">
    <div class="d-flex justify-content-center">
    <div
        class="border rounded"
        data-coreui-locale="pt-BR"
        data-coreui-start-date="2024/02/13"
        data-coreui-toggle="calendar"
    ></div>
    </div>
    </div>
    
    </div>
        <div class="container-fluid">
        <div class="container col-xs-12 col-sm-6 col-md-6 col-lg-3">
            <div class="d-grid gap-2 d-md-block">
                <button type="button" class="btn btn-outline-success">Cadastrar</button>
                <button type="button" class="btn btn-outline-secondary">Voltar</button>
        </div>
        </div>
        </div>
    </div>
    </div>
</body>
</html>