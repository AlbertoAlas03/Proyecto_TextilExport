<?php
header('Content-Type: application/json'); //contentType JSON

$json = "../products.json";
include "validations.php";

// verify if the file exists
if (!file_exists($json)) {
    echo json_encode(['error' => false, 'messageError' => 'El archivo de productos no existe.']);
    exit;
}

$products = json_decode(file_get_contents($json), true);
if (json_last_error() !== JSON_ERROR_NONE) {
    echo json_encode(['error' => false, 'messageError' => 'Error al leer el archivo JSON: formato inválido.']);
    exit;
}

// recivy data from the form-edit
$productCode = $_POST['productCode-edit'];
$productName = $_POST['productName-edit'];
$productDescription = $_POST['productDescription-edit'];
$productCategory = $_POST['productCategory-edit'];
$productPrice = $_POST['productPrice-edit'];
$productStock = $_POST['productStock-edit'] ?? 0;

// Verify if the required fields are empty
if (empty(trim($productCode)) || empty(trim($productName)) || empty(trim($productDescription)) || empty(trim($productCategory)) || empty($productPrice) || trim($productStock) === "") {
    echo json_encode(['error' => false, 'messageError' => 'Todos los campos son obligatorios.']);
    exit;
} else if (!isText($productName)) {
    echo json_encode(['error' => false, 'messageError' => 'El nombre del producto solo puede contener letras y espacios.']);
    exit;
} else if (!isText($productCategory)) {
    echo json_encode(['error' => false, 'messageError' => 'La categoría del producto solo puede contener letras y espacios.']);
    exit;
} else if ($productPrice < 0) {
    echo json_encode(['error' => false, 'messageError' => 'Revise el precio ingresado.']);
    exit;
} else if ($productStock < 0) {
    echo json_encode(['error' => false, 'messageError' => 'Revise el stock ingresado.']);
    exit;
}

//image processing
$imagePath = null;
if (isset($_FILES['productImage-edit']) && $_FILES['productImage-edit']['error'] === UPLOAD_ERR_OK) {
    $allowedTypes = ["image/jpeg", "image/png", "image/jpg"]; // types of files allowed
    $fileType = $_FILES["productImage-edit"]["type"];

    if (in_array($fileType, $allowedTypes)) {
        $uploadDir = "../img/"; // folder to save the image

        $uploadFile = $uploadDir . basename($_FILES['productImage-edit']['name']);

        // moving the image to the folder "img"
        if (move_uploaded_file($_FILES["productImage-edit"]["tmp_name"], $uploadFile)) {
            $imagePath = $uploadFile;
        } else {
            echo json_encode(['error' => false, 'messageError' => 'Error al subir la imagen.']);
            exit;
        }
    } else {
        echo json_encode(['error' => false, 'messageError' => 'Formato de imagen no válido. Solo se permiten JPEG, PNG y JPG']);
        exit;
    }
}

// Busca el producto a actualizar
$foundIndex = -1;
foreach ($products as $key => $product) {
    if ($product['code'] === $productCode) {
        $foundIndex = $key;
        break;
    }
}

if ($foundIndex !== -1) {
    // Actualiza el producto
    $products[$foundIndex]['name'] = $productName;
    $products[$foundIndex]['description'] = $productDescription;
    $products[$foundIndex]['category'] = $productCategory;
    $products[$foundIndex]['price'] = $productPrice;
    $products[$foundIndex]['stock'] = $productStock;
    if ($imagePath) {
        $products[$foundIndex]['image'] = $imagePath;
    }

    // Guarda los datos actualizados en el archivo JSON
    if (file_put_contents($json, json_encode($products, JSON_PRETTY_PRINT))) {
        echo json_encode(['success' => true, 'messageSuccess' => 'Producto actualizado correctamente.']);
    } else {
        echo json_encode(['error' => false, 'messageError' => 'Error al guardar los cambios en el archivo.']);
    }
} else {
    echo json_encode(['error' => false, 'messageError' => 'Producto no encontrado.']);
}
