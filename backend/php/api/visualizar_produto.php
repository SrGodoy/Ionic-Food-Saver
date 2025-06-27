<?php
header('Content-Type: text/html; charset=UTF-8');

$servername = "mysql";
$username = "user";
$password = "password";
$dbname = "produtos_db";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $stmt = $conn->prepare("SELECT * FROM produtos ORDER BY created_at DESC");
    $stmt->execute();
    $produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    ?>
    <!DOCTYPE html>
    <html lang="pt-BR">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Lista de Produtos</title>
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
            .img-thumbnail {
                max-width: 100px;
                max-height: 100px;
            }
            .badge-pending {
                background-color: #ffc107;
                color: #000;
            }
            .badge-synced {
                background-color: #28a745;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="header text-center">
                <a href="index.php" class="btn btn-secondary btn-voltar">← Voltar</a>
                <h1 class="display-4"> Lista de Produtos</h1>
                <p class="lead">Total de produtos: <?= count($produtos) ?></p>
            </div>
            
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Imagem</th>
                            <th>Nome</th>
                            <th>Descrição</th>
                            <th>Preço</th>
                            <th>Estoque</th>
                            <th>Validade</th>
                            <th>Status</th>
                            <th>Criado em</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($produtos as $produto): ?>
                        <tr>
                            <td><?= htmlspecialchars($produto['id']) ?></td>
                            <td>
                                <?php if (!empty($produto['image'])): ?>
                                    <img src="<?= htmlspecialchars($produto['image']) ?>" class="img-thumbnail" alt="Imagem do produto">
                                <?php else: ?>
                                    <span class="text-muted">Nenhuma</span>
                                <?php endif; ?>
                            </td>
                            <td><?= htmlspecialchars($produto['name']) ?></td>
                            <td><?= !empty($produto['description']) ? htmlspecialchars($produto['description']) : '<span class="text-muted">Nenhuma</span>' ?></td>
                            <td>R$ <?= number_format($produto['price'], 2, ',', '.') ?></td>
                            <td><?= $produto['quantity'] ?></td>
                            <td>
                                <?= !empty($produto['expiration_date']) ? 
                                    date('d/m/Y', strtotime($produto['expiration_date'])) : 
                                    '<span class="text-muted">Não informada</span>' ?>
                            </td>
                            <td>
                                <span class="badge <?= $produto['sync_status'] === 'pending' ? 'badge-pending' : 'badge-synced bg-success' ?>">
                                    <?= htmlspecialchars($produto['sync_status']) ?>
                                </span>
                            </td>
                            <td><?= date('d/m/Y H:i', strtotime($produto['created_at'])) ?></td>
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
                <h2>Erro ao carregar produtos</h2>
                <p><?= htmlspecialchars($e->getMessage()) ?></p>
                <a href="index.php" class="btn btn-primary">Voltar</a>
            </div>
        </div>
    </body>
    </html>
    <?php
}