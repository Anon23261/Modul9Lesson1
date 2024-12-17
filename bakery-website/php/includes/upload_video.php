<?php
require_once 'VideoProcessor.php';
require_once '../config/database.php';

header('Content-Type: application/json');

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Invalid request method');
    }
    
    if (!isset($_FILES['video']) || $_FILES['video']['error'] === UPLOAD_ERR_NO_FILE) {
        throw new Exception('No video file uploaded');
    }
    
    $productId = isset($_POST['product_id']) ? intval($_POST['product_id']) : null;
    if (!$productId) {
        throw new Exception('Product ID is required');
    }
    
    // Initialize video processor
    $processor = new VideoProcessor();
    
    // Process video
    $result = $processor->processVideo($_FILES['video'], [
        'qualities' => ['720p', '1080p'],
        'generateThumbnail' => true
    ]);
    
    if (!$result['success']) {
        throw new Exception($result['error']);
    }
    
    // Store video information in database
    $stmt = $conn->prepare("
        INSERT INTO product_media 
        (product_id, media_type, file_url, file_size, mime_type, duration, sort_order) 
        VALUES (?, 'video', ?, ?, ?, ?, ?)
    ");
    
    $fileUrl = $result['filename'];
    $fileSize = $_FILES['video']['size'];
    $mimeType = $_FILES['video']['type'];
    $duration = $result['metadata']['duration'];
    $sortOrder = 0;
    
    $stmt->bind_param(
        "isssii",
        $productId,
        $fileUrl,
        $fileSize,
        $mimeType,
        $duration,
        $sortOrder
    );
    
    if (!$stmt->execute()) {
        throw new Exception('Failed to save video information to database');
    }
    
    // Store thumbnail
    if (isset($result['thumbnail'])) {
        $stmt = $conn->prepare("
            INSERT INTO product_media 
            (product_id, media_type, file_url, sort_order) 
            VALUES (?, 'thumbnail', ?, ?)
        ");
        
        $thumbnailUrl = $result['thumbnail'];
        $sortOrder = 1;
        
        $stmt->bind_param("isi", $productId, $thumbnailUrl, $sortOrder);
        $stmt->execute();
    }
    
    // Update product with video URL
    $stmt = $conn->prepare("
        UPDATE products 
        SET video_url = ?, video_thumbnail_url = ?
        WHERE id = ?
    ");
    
    $stmt->bind_param(
        "ssi",
        $result['filename'],
        $result['thumbnail'],
        $productId
    );
    
    if (!$stmt->execute()) {
        throw new Exception('Failed to update product with video information');
    }
    
    echo json_encode([
        'success' => true,
        'data' => [
            'video_url' => $result['filename'],
            'thumbnail_url' => $result['thumbnail'],
            'versions' => $result['versions'],
            'metadata' => $result['metadata']
        ]
    ]);
    
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
?>
