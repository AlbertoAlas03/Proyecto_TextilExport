<?php
include "validations.php";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $json = "../products.json";

    $name = $_POST["productName"];
    $price = $_POST["productPrice"];
    $description = $_POST["productDescription"];
    $stock = $_POST["productStock"];
    $category = $_POST["productCategory"];
    $code = $_POST["productCode"];

    if (empty(trim($code))) {
        echo json_encode(["error" => "El codigo del producto no puede estar vacío"]);
        exit;
    } else if (!isCode(trim($code))) {
        echo json_encode(["error" => "Formato de código incorrecto"]);
        exit;
    } else if (empty(trim($name))) {
        echo json_encode(["error" => "El nombre del producto no puede estar vacía"]);
        exit;
    } else if (!isText(trim($name))) {
        echo json_encode(["error" => "El nombre del producto solo puede contener letras"]);
        exit;
    } else if (empty(trim($description))) {
        echo json_encode(["error" => "La descripción del producto no puede estar vacía"]);
        exit;
    } else if (!isText(trim($description))) {
        echo json_encode(["error" => "La descripción del producto solo puede contener letras"]);
        exit;
    } else if (empty($category) || $category == "Seleccione una categoría") {
        echo json_encode(["error" => "Debe seleccionar una categoría"]);
        exit;
    } else if ($price <= 0) {
        echo json_encode(["error" => "Revise el precio ingresado"]);
        exit;
    } else if ($stock <= 0) {
        echo json_encode(["error" => "Revise el stock ingresado"]);
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
                echo json_encode(["error" => "Error al insertar la imagen"]);
                exit;
            }
        } else {
            echo json_encode(["error" => "Formato de imagen no válido. Solo se permiten JPEG, PNG y JPG"]);
            exit;
        }
    } else {
        echo json_encode(["error" => "Debes insertar una imagen"]);
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

    echo json_encode(["success" => "Producto agregado correctamente"]);
} else {
    echo json_encode(["error" => "Error al guardar el producto"]);
}
