<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// Conexão (mesma do salvar.php)
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

try {
    $stmt = $pdo->query("SELECT * FROM produtos");
    $produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    foreach ($produtos as &$produto) {
    if (!empty($produto['image']) && strpos($produto['image'], 'data:image') !== 0) {
        $produto['image'] = 'data:image/jpeg;base64,' . $produto['image'];
    }
}
    echo json_encode(["status" => "success", "produtos" => $produtos]);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(["status" => "error", "message" => "Erro ao buscar produtos"]);
}
?>
