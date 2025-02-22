<?php

$uploadFile = "../img/"; // directory to save the file

//getting the data from the form-edit
$code = $_POST['code'];
$name = $_POST['name'];
$description = $_POST['description'];
$category = $_POST['category'];
$price = $_POST['price'];
$stock = $_POST['stock'];

if(empty($code) || empty($name) || empty($description) || empty($category) || empty($price) || empty($stock)){
    echo json_encode(['success' => false, 'messageError' => 'Todos los campos son obligatorios.']);
    exit;
}

$imagePath = null;
if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) { //verify if the file was uploaded
    $imageName = basename($_FILES['image']['name']);
    $imagePath = $uploadFile . $imageName;

    // move the file to the specified directory
    if (move_uploaded_file($_FILES['image']['tmp_name'], $imagePath)) {
        // It's ok
    } else {
        echo json_encode(['success' => false, 'messageError' => 'Error al subir la imagen.']);
        exit;
    }
}

$jsonFile = "../products.json";
$products = json_decode(file_get_contents($jsonFile), true);

//searching for the product
$found = false;
foreach ($products as $key => $product) {
    if ($product['code'] === $code) {
        // update the product
        $products[$key]['name'] = $name;
        $products[$key]['description'] = $description;
        $products[$key]['category'] = $category;
        $products[$key]['price'] = $price;
        $products[$key]['stock'] = $stock;

        // update the image if it was uploaded
        if ($imagePath) {
            $products[$key]['image'] = $imagePath;
        }

        $found = true;
        break;
    }
}

file_put_contents($jsonFile, json_encode($products, JSON_PRETTY_PRINT));

if ($found) {
    echo json_encode(["success" => true, "messageSuccess" => "Producto actualizado correctamente"]);
} else {
    echo json_encode(["error" => false, "messageError" => "Producto no encontrado"]);
}
