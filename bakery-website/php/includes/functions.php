<?php
function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

function upload_image($file) {
    $target_dir = "../assets/images/products/";
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);
    }
    
    $target_file = $target_dir . basename($file["name"]);
    $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
    
    // Check if image file is actual image
    if(isset($_POST["submit"])) {
        $check = getimagesize($file["tmp_name"]);
        if($check === false) {
            return ["success" => false, "message" => "File is not an image."];
        }
    }
    
    // Check file size
    if ($file["size"] > 2000000) {
        return ["success" => false, "message" => "File is too large. Max size is 2MB."];
    }
    
    // Allow certain file formats
    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "webp") {
        return ["success" => false, "message" => "Only JPG, JPEG, PNG & WEBP files are allowed."];
    }
    
    // Generate unique filename
    $new_filename = uniqid() . '.' . $imageFileType;
    $target_file = $target_dir . $new_filename;
    
    if (move_uploaded_file($file["tmp_name"], $target_file)) {
        return ["success" => true, "filename" => $new_filename];
    } else {
        return ["success" => false, "message" => "Error uploading file."];
    }
}

function validate_product_data($data) {
    $errors = [];
    
    if (empty($data['name'])) {
        $errors[] = "Product name is required";
    }
    
    if (empty($data['category'])) {
        $errors[] = "Category is required";
    }
    
    if (empty($data['price']) || !is_numeric($data['price'])) {
        $errors[] = "Valid price is required";
    }
    
    if (empty($data['prep_time']) || !is_numeric($data['prep_time'])) {
        $errors[] = "Valid preparation time is required";
    }
    
    return $errors;
}

function format_price($price) {
    return number_format((float)$price, 2, '.', '');
}
?>
