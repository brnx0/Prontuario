<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prontuario Digital</title>
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>
    

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://kit.fontawesome.com/b13fe40c08.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="{{asset('css/app.css')}}">
    <link rel="shortcut icon" href="{{ asset('assets/img/ico.png') }}">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
   
    

<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container-fluid">
            <button class="btn btn-light me-2" id="toggleMenu">☰</button>
            <span class="navbar-brand">               
                
                Prontuário Digital
            </span>
            <form method="POST" action="{{ route('logout') }}" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-danger">
                    <i class="bi bi-box-arrow-right"></i> Sair
                </button>
            </form>
        </div>
    </nav>
    <div class="d-flex containerPrincipal">
        <div id="sidebar" class="bg-white p-4 border-end">
            <input type="text" class="form-control mb-3" placeholder="Buscar no menu">
            <ul class="list-group">
                <li class="list-group-item active">📂 Atendimento</li>
                <a href="/atendimento">
                    <li class="list-group-item hover">🔹 Novo Atendimento</li>
                </a>
                <a href="/historico">
                    <li class="list-group-item hover">🔹 Histórico</li>
                </a>
                <li class="list-group-item active">📅 Cadastro</li>
                <a href="/paciente">
                    <li class="list-group-item hover">
                        <i class="fa-solid fa-user-plus"style="color: #000000;"> </i> 
                    Paciente
                    </li>
                </a>
                <a href="/medico">
                    <li class="list-group-item hover">
                        <i class="fa-solid fa-user-doctor"style="color: #000000;"> </i>
                        Médicos
                    </li>
                </a>
                <a href="/enfermeiro">
                    <li class="list-group-item hover">
                        <i class="fa-solid fa-user-nurse" style="color: #000000;"> </i>
                        Enfermeiros
                    </li>
                </a>
                <a href="/especialidade">
                    <li class="list-group-item hover">🩺 Especialidade</li>
                </a>
                
                
            </ul>
        </div>
        <div id="content" class="flex-grow-1 ">
          @yield('content')
        </div>
    </div>
    <script src="{{asset('js/app.js')}}"></script>
</body>
</html>
