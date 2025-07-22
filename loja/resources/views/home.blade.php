<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Home - Bootstrap</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
      <a class="navbar-brand" href="#">Minha Empresa</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item"><a class="nav-link active" href="#">Home</a></li>
          <li class="nav-item"><a class="nav-link" href="#">Sobre</a></li>
          <li class="nav-item"><a class="nav-link" href="#">Serviços</a></li>
          <li class="nav-item"><a class="nav-link" href="#">Contato</a></li>
        </ul>
      </div>
    </div>
  </nav>

  <!-- Banner -->
  <div class="bg-primary text-white text-center py-5">
    <div class="container">
      <h1 class="display-4">Bem-vindo à Nossa Plataforma</h1>
      <p class="lead">Soluções inovadoras para o seu negócio.</p>
      <a href="#" class="btn btn-light btn-lg">Saiba Mais</a>
    </div>
  </div>

  <!-- Cards Section -->
  <div class="container my-5">
    <div class="row text-center">
      <div class="col-md-4">
        <div class="card shadow-sm">
          <div class="card-body">
            <h5 class="card-title">Serviço 1</h5>
            <p class="card-text">Descrição breve do serviço 1.</p>
            <a href="#" class="btn btn-primary">Detalhes</a>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card shadow-sm">
          <div class="card-body">
            <h5 class="card-title">Serviço 2</h5>
            <p class="card-text">Descrição breve do serviço 2.</p>
            <a href="#" class="btn btn-primary">Detalhes</a>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card shadow-sm">
          <div class="card-body">
            <h5 class="card-title">Serviço 3</h5>
            <p class="card-text">Descrição breve do serviço 3.</p>
            <a href="#" class="btn btn-primary">Detalhes</a>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Footer -->
  <footer class="bg-dark text-white text-center py-3">
    <p class="mb-0">© 2023 Minha Empresa. Todos os direitos reservados.</p>
  </footer>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
