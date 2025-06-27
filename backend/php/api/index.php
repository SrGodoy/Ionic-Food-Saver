<?php
header('Content-Type: text/html; charset=UTF-8');
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>API Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            padding-top: 2rem;
        }
        .api-card {
            transition: transform 0.3s;
            margin-bottom: 1.5rem;
        }
        .api-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        .footer {
            margin-top: 3rem;
            padding: 1rem 0;
            background-color: #f1f1f1;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="text-center mb-5">
            <h1 class="display-4">API Dashboard</h1>
            <p class="lead">Acesso às APIs de Produtos e Vendas</p>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="card api-card">
                    <div class="card-body text-center">
                        <h5 class="card-title"> API de Produtos</h5>
                        <p class="card-text">Visualize todos os produtos cadastrados no sistema</p>
                        <a href="visualizar_produto.php" class="btn btn-primary">Acessar Produtos</a>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="card api-card">
                    <div class="card-body text-center">
                        <h5 class="card-title"> API de Vendas</h5>
                        <p class="card-text">Visualize o histórico de vendas do sistema</p>
                        <a href="visualizar_venda.php" class="btn btn-success">Acessar Vendas</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        📋 Documentação da API
                    </div>
                    <div class="card-body">
                        <h5>Endpoints disponíveis:</h5>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item">
                                <strong>GET /visualizar_produto.php</strong> - Retorna todos os produtos em JSON
                            </li>
                            <li class="list-group-item">
                                <strong>GET /visualizar_venda.php</strong> - Retorna todas as vendas em JSON
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer class="footer mt-5">
        <div class="container text-center">
            <span class="text-muted">Sistema API Docker - <?php echo date('Y'); ?></span>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>