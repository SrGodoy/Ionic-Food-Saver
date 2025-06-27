<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Conexão com o banco
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

// Recebe os dados
$body = file_get_contents("php://input");
$data = json_decode($body);

if (
    empty($data->id) || empty($data->name) || !isset($data->price)
) {
    http_response_code(400);
    echo json_encode([
        "status" => "error",
        "message" => "Campos obrigatórios faltando",
        "required_fields" => ["id", "name", "price"],
        "received" => $data
    ]);
    exit;
}

// Prepara e executa a query
try {
    $stmt = $pdo->prepare("INSERT INTO produtos (id, name, price, quantity, description, expiration_date, image, created_at, sync_status)
                           VALUES (:id, :name, :price, :quantity, :description, :expiration_date, :image, :created_at, :sync_status)
                           ON DUPLICATE KEY UPDATE 
                                name = :name, 
                                price = :price, 
                                quantity = :quantity, 
                                description = :description, 
                                expiration_date = :expiration_date, 
                                image = :image, 
                                created_at = :created_at, 
                                sync_status = :sync_status");

    $stmt->execute([
        ":id" => $data->id,
        ":name" => $data->name,
        ":price" => $data->price,
        ":quantity" => $data->quantity ?? 0,
        ":description" => $data->description ?? null,
        ":expiration_date" => $data->expiration_date ?? null,
        ":image" => $data->image ?? null,
        ":created_at" => $data->created_at ?? date('Y-m-d H:i:s'),
        ":sync_status" => $data->sync_status ?? "pending"
    ]);

    echo json_encode(["status" => "success", "message" => "Produto salvo com sucesso"]);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(["status" => "error", "message" => "Erro ao salvar produto", "erro" => $e->getMessage()]);
}
