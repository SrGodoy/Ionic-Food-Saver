<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

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

if (
    empty($data->produto_id) ||
    empty($data->nome) ||
    !isset($data->quantidade) ||
    !isset($data->preco)
) {
    http_response_code(400);
    echo json_encode([
        "status" => "error",
        "message" => "Campos obrigatórios faltando",
        "required_fields" => ["produto_id", "nome", "quantidade", "preco"],
        "received" => $data
    ]);
    exit;
}

try {
    $stmt = $pdo->prepare("INSERT INTO vendas (produto_id, nome, quantidade, preco) VALUES (:produto_id, :nome, :quantidade, :preco)");
    $stmt->execute([
        ":produto_id" => $data->produto_id,
        ":nome" => $data->nome,
        ":quantidade" => $data->quantidade,
        ":preco" => $data->preco
    ]);
    echo json_encode(["status" => "success", "message" => "Venda registrada com sucesso"]);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(["status" => "error", "message" => "Erro ao registrar venda", "erro" => $e->getMessage()]);
}
