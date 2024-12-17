<?php
require_once 'config/database.php';
require_once 'includes/functions.php';

$success_message = '';
$error_message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $errors = validate_product_data($_POST);
    
    if (empty($errors)) {
        // Handle image upload
        $image_result = ["success" => true, "filename" => ""];
        if (isset($_FILES["product_image"]) && $_FILES["product_image"]["error"] == 0) {
            $image_result = upload_image($_FILES["product_image"]);
        }
        
        if ($image_result["success"]) {
            $availability = json_encode([
                "monday" => isset($_POST["availability_monday"]),
                "tuesday" => isset($_POST["availability_tuesday"]),
                "wednesday" => isset($_POST["availability_wednesday"]),
                "thursday" => isset($_POST["availability_thursday"]),
                "friday" => isset($_POST["availability_friday"]),
                "saturday" => isset($_POST["availability_saturday"]),
                "sunday" => isset($_POST["availability_sunday"])
            ]);
            
            $stmt = $conn->prepare("INSERT INTO products (name, category, description, ingredients, allergens, price, prep_time, image_url, availability, special_instructions) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            
            $name = sanitize_input($_POST["name"]);
            $category = sanitize_input($_POST["category"]);
            $description = sanitize_input($_POST["description"]);
            $ingredients = sanitize_input($_POST["ingredients"]);
            $allergens = sanitize_input($_POST["allergens"]);
            $price = format_price($_POST["price"]);
            $prep_time = (int)$_POST["prep_time"];
            $image_url = $image_result["filename"];
            $special_instructions = sanitize_input($_POST["special_instructions"]);
            
            $stmt->bind_param("sssssdisss", 
                $name, 
                $category, 
                $description, 
                $ingredients, 
                $allergens, 
                $price, 
                $prep_time, 
                $image_url, 
                $availability, 
                $special_instructions
            );
            
            if ($stmt->execute()) {
                $success_message = "Product registered successfully!";
            } else {
                $error_message = "Error: " . $stmt->error;
            }
            
            $stmt->close();
        } else {
            $error_message = $image_result["message"];
        }
    } else {
        $error_message = implode("<br>", $errors);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bakery Product Registration</title>
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>Bakery Product Registration</h1>
        </header>

        <?php if ($success_message): ?>
            <div class="alert success"><?php echo $success_message; ?></div>
        <?php endif; ?>

        <?php if ($error_message): ?>
            <div class="alert error"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="name">Product Name *</label>
                <input type="text" id="name" name="name" required>
            </div>

            <div class="form-group">
                <label for="category">Category *</label>
                <select id="category" name="category" required>
                    <option value="">Select Category</option>
                    <option value="bread">Bread</option>
                    <option value="pastry">Pastry</option>
                    <option value="cake">Cake</option>
                    <option value="cookie">Cookie</option>
                    <option value="other">Other</option>
                </select>
            </div>

            <div class="form-group">
                <label for="description">Description</label>
                <textarea id="description" name="description" rows="3"></textarea>
            </div>

            <div class="form-group">
                <label for="ingredients">Ingredients *</label>
                <textarea id="ingredients" name="ingredients" rows="3" required></textarea>
            </div>

            <div class="form-group">
                <label for="allergens">Allergens</label>
                <textarea id="allergens" name="allergens" rows="2"></textarea>
            </div>

            <div class="form-group">
                <label for="price">Price (USD) *</label>
                <input type="number" id="price" name="price" step="0.01" required>
            </div>

            <div class="form-group">
                <label for="prep_time">Preparation Time (minutes) *</label>
                <input type="number" id="prep_time" name="prep_time" required>
            </div>

            <div class="form-group">
                <label for="product_image">Product Image</label>
                <input type="file" id="product_image" name="product_image" accept="image/*">
            </div>

            <div class="form-group">
                <label>Availability</label>
                <div class="checkbox-group">
                    <label><input type="checkbox" name="availability_monday" value="1"> Monday</label>
                    <label><input type="checkbox" name="availability_tuesday" value="1"> Tuesday</label>
                    <label><input type="checkbox" name="availability_wednesday" value="1"> Wednesday</label>
                    <label><input type="checkbox" name="availability_thursday" value="1"> Thursday</label>
                    <label><input type="checkbox" name="availability_friday" value="1"> Friday</label>
                    <label><input type="checkbox" name="availability_saturday" value="1"> Saturday</label>
                    <label><input type="checkbox" name="availability_sunday" value="1"> Sunday</label>
                </div>
            </div>

            <div class="form-group">
                <label for="special_instructions">Special Instructions</label>
                <textarea id="special_instructions" name="special_instructions" rows="2"></textarea>
            </div>

            <div class="form-group">
                <button type="submit" class="btn-primary">Register Product</button>
            </div>
        </form>
    </div>

    <script src="assets/js/main.js"></script>
</body>
</html>
