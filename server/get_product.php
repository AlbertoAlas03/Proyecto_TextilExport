<?php
$data = json_decode(file_get_contents("php://input"), true);
$code = $data['code'];

$jsonFile = "../products.json";
$products = json_decode(file_get_contents($jsonFile), true);

//searching for the product for the given code
$found = false;
foreach ($products as $product) {
    if ($product['code'] === $code) {
        $found = true;
        echo json_encode(['success' => true, 'product' => $product]);
        exit;
    }
}

if (!$found) {
    echo json_encode(['success' => false, 'messageError' => 'Producto no encontrado']);
}
