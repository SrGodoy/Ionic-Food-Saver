<?php
header('Content-Type: text/html; charset=UTF-8');

$servername = "backend-mysql-1";
$username = "user";
$password = "password";
$dbname = "produtos_db";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $stmt = $conn->prepare("SELECT * FROM vendas ORDER BY data_venda DESC");
    $stmt->execute();
    $vendas = $stmt->fetchAll(PDO::FETCH_ASSOC);
    ?>
    <!DOCTYPE html>
    <html lang="pt-BR">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Histórico de Vendas</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <style>
            body { padding: 20px; }
            .table-responsive { margin-top: 20px; }
            .header { 
                margin-bottom: 30px;
                position: relative;
            }
            .btn-voltar {
                position: absolute;
                left: 15px;
                top: 50%;
                transform: translateY(-50%);
            }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="header text-center">
                <a href="index.php" class="btn btn-secondary btn-voltar">← Voltar</a>
                <h1 class="display-4">Histórico de Vendas</h1>
                <p class="lead">Total de vendas: <?= count($vendas) ?></p>
            </div>
            
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Produto ID</th>
                            <th>Nome</th>
                            <th>Quantidade</th>
                            <th>Preço Unitário</th>
                            <th>Total</th>
                            <th>Data Venda</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($vendas as $venda): ?>
                        <tr>
                            <td><?= htmlspecialchars($venda['id']) ?></td>
                            <td><?= htmlspecialchars($venda['produto_id']) ?></td>
                            <td><?= htmlspecialchars($venda['nome']) ?></td>
                            <td><?= $venda['quantidade'] ?></td>
                            <td>R$ <?= number_format($venda['preco'], 2, ',', '.') ?></td>
                            <td>R$ <?= number_format($venda['preco'] * $venda['quantidade'], 2, ',', '.') ?></td>
                            <td><?= date('d/m/Y H:i', strtotime($venda['data_venda'])) ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    </body>
    </html>
    <?php
    
} catch(PDOException $e) {
    ?>
    <!DOCTYPE html>
    <html lang="pt-BR">
    <head>
        <meta charset="UTF-8">
        <title>Erro</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    </head>
    <body>
        <div class="container mt-5">
            <div class="alert alert-danger">
                <h2>Erro ao carregar vendas</h2>
                <p><?= htmlspecialchars($e->getMessage()) ?></p>
                <a href="index.php" class="btn btn-primary">Voltar</a>
            </div>
        </div>
    </body>
    </html>
    <?php
}