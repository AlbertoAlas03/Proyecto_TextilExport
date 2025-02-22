<?php
//code product
$data = json_decode(file_get_contents("php://input"), true);
$code = $data['code'];
$jsonFile = '../products.json';
$products = json_decode(file_get_contents($jsonFile), true);

$found = false;
foreach ($products as $key => $product) { //search product
    if ($product['code'] === $code) {
        unset($products[$key]); //delete product
        $found = true;
        break;
    }
}

$products = array_values($products); //reindex array
file_put_contents($jsonFile, json_encode($products, JSON_PRETTY_PRINT)); //save the new data
if ($found) {
    echo json_encode(['success' => true, 'messageSuccess' => 'Producto eliminado correctamente']);
} else {
    echo json_encode(['error' => false, 'messageError' => 'Producto no encontrado']);
}
