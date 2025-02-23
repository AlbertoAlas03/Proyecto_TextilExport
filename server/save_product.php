<?php
include "validations.php";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $json = "../products.json";

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

    $name = $_POST["productName"];
    $price = $_POST["productPrice"];
    $description = $_POST["productDescription"];
    $stock = $_POST["productStock"];
    $category = $_POST["productCategory"];
    $code = $_POST["productCode"];

    if (empty(trim($name)) || empty(trim($price)) || empty(trim($description)) || empty($stock) || empty($category) || empty(trim($code))) {
        echo json_encode(['error' => false, 'messageError' => 'Por favor, rellene todos los campos.']);
        exit;
    } else if (!isText($name)) {
        echo json_encode(['error' => false, 'messageError' => 'El nombre del producto solo puede contener letras y espacios.']);
        exit;
    } else if (!isText($description)) {
        echo json_encode(['error' => false, 'messageError' => 'La descripción del producto solo puede contener letras y espacios.']);
        exit;
    } else if (!isCode($code)) {
        echo json_encode(['error' => false, 'messageError' => 'Formato de código incorrecto.']);
        exit;
    } else if ($price < 0) {
        echo json_encode(['error' => false, 'messageError' => 'Revise el precio ingresado.']);
        exit;
    } else if ($stock < 0) {
        echo json_encode(['error' => false, 'messageError' => 'Revise el stock ingresado.']);
        exit;
    }

    // Prosessing image
    if (isset($_FILES["productImage"]) && $_FILES["productImage"]["error"] == 0) {
        $allowedTypes = ["image/jpeg", "image/png", "image/jpg"]; // types of files allowed
        $fileType = $_FILES["productImage"]["type"];

        // validate file type
        if (in_array($fileType, $allowedTypes)) {
            $uploadDir = "../img/"; // folder to save the image
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true); // create the folder if it doesn't exist
            }

            $fileName = uniqid() . "_" . basename($_FILES["productImage"]["name"]); // name of the image
            $uploadFile = $uploadDir . $fileName;

            // moving the image to the folder "img"
            if (move_uploaded_file($_FILES["productImage"]["tmp_name"], $uploadFile)) {
                // image path
                $imagePath = $uploadFile;
            } else {
                echo json_encode(["error" => false, "massageError" => "Error al insertar la imagen"]);
                exit;
            }
        } else {
            echo json_encode(["error" => false, "messageError" => "Formato de imagen no válido. Solo se permiten JPEG, PNG y JPG"]);
            exit;
        }
    } else {
        echo json_encode(["error" => false, "messageError" => "Debes insertar una imagen"]);
        exit;
    }

    $new_product = [
        "code" => $code,
        "name" => $name,
        "description" => $description,
        "category" => $category,
        "price" => $price,
        "stock" => $stock,
        "image" => $imagePath
    ];

    //test if the file exists
    if (file_exists($json)) {
        $products = json_decode(file_get_contents($json), true);
    } else {
        $products = [];
    }

    //save product
    $products[] = $new_product;
    file_put_contents($json, json_encode($products, JSON_PRETTY_PRINT));

    echo json_encode(["success" => true, "messageSuccess" => "Producto agregado correctamente"]);
} else {
    echo json_encode(["error" => false, "MessageError" => "Error al guardar el producto"]);
}
