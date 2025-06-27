<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

$host = "backend-mysql-1";
$db = "produtos_db";
$user = "user";
$pass = "password";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["status" => "error", "message" => "Falha na conexão com o banco"]);
    exit;
}


$body = file_get_contents("php://input");
$data = json_decode($body);

if (!is_array($data) || count($data) == 0) {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "Nenhum produto enviado"]);
    exit;
}

try {
    $pdo->beginTransaction();

    foreach ($data as $produto) {
        // Inserir na tabela vendas
        $stmt = $pdo->prepare("INSERT INTO vendas (produto_id, nome, quantidade, preco, data_venda) VALUES (:id, :nome, :quantidade, :preco, NOW())");
        $stmt->execute([
            ":id" => $produto->id,
            ":nome" => $produto->name,
            ":quantidade" => $produto->quantity,
            ":preco" => $produto->price
        ]);

        // Deletar do produtos
        $stmt = $pdo->prepare("DELETE FROM produtos WHERE id = :id");
        $stmt->execute([":id" => $produto->id]);
    }

    $pdo->commit();

    echo json_encode(["status" => "success", "message" => "Venda realizada com sucesso"]);
} catch (Throwable $e) {
    $pdo->rollBack();
    http_response_code(500);
    echo json_encode(["status" => "error", "message" => "Erro ao processar venda", "erro" => $e->getMessage()]);
}
