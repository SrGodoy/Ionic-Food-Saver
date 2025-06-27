<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

$host = "backend-mysql-1";
$db = "produtos_db";
$user = "user";
$pass = "password";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $pdo->query("SELECT * FROM vendas ORDER BY data_venda DESC");
    $vendas = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($vendas);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["status" => "error", "message" => "Erro ao buscar vendas"]);
}