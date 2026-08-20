<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login | SuínosIFFar</title>
    <style>
        :root{font-family:Arial,Helvetica,sans-serif;color:#202020;background:#f4f4f4;--green:#2b7d32;--green-dark:#236b2a;--green-light:#3c9142;--border:#d7d7d7}*{box-sizing:border-box}html,body{margin:0;min-height:100%;background:#f4f4f4}body{min-height:100vh;display:flex;flex-direction:column}.topbar{height:110px;background:var(--green);display:flex;align-items:center;padding:0 16px;color:#fff;box-shadow:0 1px 3px rgba(0,0,0,.25)}.brand{display:flex;align-items:center;gap:12px;font-size:28px;font-weight:700;letter-spacing:-.7px}.brand-mark{position:relative;width:36px;height:44px;display:block}.brand-mark:before,.brand-mark:after{content:"";position:absolute;background:#10a950}.brand-mark:before{width:13px;height:44px;left:9px;top:0;box-shadow:13px 8px 0 #10a950,13px 22px 0 #10a950,13px 36px 0 #10a950}.brand-mark:after{width:11px;height:11px;left:0;top:0;background:#e32236;border-radius:50%}.page{flex:1;display:flex;align-items:center;justify-content:center;padding:26px 20px}.login-card{width:min(748px,100%);min-height:490px;background:#fff;border-radius:8px;box-shadow:0 2px 9px rgba(0,0,0,.16);display:grid;grid-template-columns:1fr 1fr;overflow:hidden}.form-panel{padding:42px 48px;display:flex;align-items:flex-start;justify-content:center}.form-content{width:100%;max-width:310px}.form-content h1{text-align:center;font-size:27px;margin:0 0 38px;color:#222}.field{margin-bottom:22px}.field label{display:block;font-size:13px;font-weight:600;margin-bottom:7px}.field input{width:100%;height:38px;border:1px solid var(--border);border-radius:4px;padding:0 11px;font-size:13px;outline:none;transition:border-color .2s,box-shadow .2s}.field input:focus{border-color:var(--green);box-shadow:0 0 0 2px rgba(43,125,50,.12)}.field input.is-invalid{border-color:#d92d20}.error{display:block;color:#b42318;font-size:11px;margin-top:5px}.feedback{padding:9px 10px;border-radius:4px;background:#fff1f0;color:#b42318;font-size:12px;margin-bottom:17px}.remember{display:flex;align-items:center;gap:7px;font-size:12px;color:#666;margin:-3px 0 20px;cursor:pointer}.remember input{accent-color:var(--green)}.btn{display:block;width:108px;height:38px;margin:0 auto;border:0;border-radius:7px;background:var(--green);color:#fff;font-size:14px;font-weight:700;cursor:pointer;transition:background .2s}.btn:hover{background:var(--green-dark)}.welcome-panel{background:var(--green);color:#fff;display:flex;flex-direction:column;align-items:center;justify-content:center;text-align:center;padding:32px}.welcome-panel h2{font-size:32px;margin:0 0 20px}.welcome-panel p{font-size:15px;margin:0;line-height:1.5}.welcome-panel strong{font-weight:700}.footer{height:44px;background:var(--green);border-top:3px solid var(--green-light);color:#e5f2e5;display:flex;align-items:center;justify-content:center;font-size:12px;position:relative}.version{position:absolute;right:18%;background:rgba(255,255,255,.15);padding:4px 9px;border-radius:3px;font-size:11px}@media(max-width:680px){.topbar{height:78px;padding-left:18px}.brand{font-size:23px}.login-card{grid-template-columns:1fr;min-height:auto;max-width:390px}.welcome-panel{min-height:175px;order:-1}.welcome-panel h2{font-size:27px;margin-bottom:10px}.form-panel{padding:34px 28px 40px}.footer{height:42px;text-align:center;padding:0 55px 0 15px}.version{right:14px}.page{padding:20px 14px}}
    </style>
</head>
<body>
<header class="topbar"><div class="brand"><span class="brand-mark" aria-hidden="true"></span><span>SuínosIFFar</span></div></header>
<main class="page">
    <section class="login-card" aria-label="Área de login">
        <div class="form-panel">
            <div class="form-content">
                <h1>Login</h1>
                @if($errors->any())<div class="feedback">Confira os dados informados e tente novamente.</div>@endif
                <form method="POST" action="{{ route('login.submit') }}">
                    @csrf
                    <div class="field">
                        <label for="matricula">Usuário:</label>
                        <input id="matricula" name="matricula" type="text" value="{{ old('matricula') }}" autocomplete="username" placeholder="CPF ou Matrícula" class="@error('matricula') is-invalid @enderror" required autofocus>
                        @error('matricula')<span class="error">{{ $message }}</span>@enderror
                    </div>
                    <div class="field">
                        <label for="senha">Senha:</label>
                        <input id="senha" name="senha" type="password" autocomplete="current-password" class="@error('senha') is-invalid @enderror" required>
                        @error('senha')<span class="error">{{ $message }}</span>@enderror
                    </div>
                    <label class="remember"><input type="checkbox" name="remember" value="1" @checked(old('remember'))> Lembrar acesso</label>
                    <button class="btn" type="submit">Entrar</button>
                </form>
            </div>
        </div>
        <div class="welcome-panel"><h2>Bem-vindo!</h2><p>Acesso ao sistema de<br><strong>Gestão de Suínos</strong></p></div>
    </section>
</main>
<footer class="footer"><span>© 2026 SuínosIFFar - Sistema de Gestão de Suínos</span><span class="version">v1.1.0</span></footer>
</body>
</html>
