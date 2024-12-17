<?php
class VideoProcessor {
    private $ffmpeg;
    private $uploadDir;
    private $maxFileSize;
    private $allowedTypes;
    private $defaultQuality;
    
    public function __construct() {
        $this->ffmpeg = '/usr/bin/ffmpeg';
        $this->uploadDir = '../assets/videos/products/';
        $this->maxFileSize = 100 * 1024 * 1024; // 100MB
        $this->allowedTypes = ['video/mp4', 'video/webm', 'video/quicktime'];
        $this->defaultQuality = '720p';
        
        if (!file_exists($this->uploadDir)) {
            mkdir($this->uploadDir, 0777, true);
        }
    }
    
    public function processVideo($file, $options = []) {
        try {
            $this->validateVideo($file);
            
            $filename = $this->generateFilename($file);
            $uploadPath = $this->uploadDir . $filename;
            
            if (!move_uploaded_file($file['tmp_name'], $uploadPath)) {
                throw new Exception('Failed to move uploaded file.');
            }
            
            // Generate thumbnail
            $thumbnailPath = $this->generateThumbnail($uploadPath);
            
            // Process video for different qualities
            $versions = $this->createVideoVersions($uploadPath, $options);
            
            // Generate metadata
            $metadata = $this->getVideoMetadata($uploadPath);
            
            return [
                'success' => true,
                'filename' => $filename,
                'thumbnail' => basename($thumbnailPath),
                'versions' => $versions,
                'metadata' => $metadata
            ];
            
        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
    
    private function validateVideo($file) {
        if ($file['error'] !== UPLOAD_ERR_OK) {
            throw new Exception('Upload error: ' . $file['error']);
        }
        
        if ($file['size'] > $this->maxFileSize) {
            throw new Exception('File too large. Maximum size is ' . ($this->maxFileSize / 1024 / 1024) . 'MB');
        }
        
        if (!in_array($file['type'], $this->allowedTypes)) {
            throw new Exception('Invalid file type. Allowed types: ' . implode(', ', $this->allowedTypes));
        }
        
        // Additional validation (file headers, etc.)
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);
        
        if (!in_array($mimeType, $this->allowedTypes)) {
            throw new Exception('Invalid file content type.');
        }
    }
    
    private function generateFilename($file) {
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        return uniqid() . '_' . time() . '.' . $extension;
    }
    
    private function generateThumbnail($videoPath) {
        $thumbnailPath = str_replace('.' . pathinfo($videoPath, PATHINFO_EXTENSION), '_thumb.jpg', $videoPath);
        
        // Extract frame at 1 second
        $command = sprintf(
            '%s -i %s -ss 00:00:01.000 -vframes 1 -f image2 %s',
            escapeshellcmd($this->ffmpeg),
            escapeshellarg($videoPath),
            escapeshellarg($thumbnailPath)
        );
        
        exec($command, $output, $returnVar);
        
        if ($returnVar !== 0) {
            throw new Exception('Failed to generate thumbnail.');
        }
        
        return $thumbnailPath;
    }
    
    private function createVideoVersions($videoPath, $options) {
        $qualities = $options['qualities'] ?? ['720p'];
        $versions = [];
        
        $qualitySettings = [
            '480p' => [
                'resolution' => '854:480',
                'bitrate' => '1000k'
            ],
            '720p' => [
                'resolution' => '1280:720',
                'bitrate' => '2500k'
            ],
            '1080p' => [
                'resolution' => '1920:1080',
                'bitrate' => '5000k'
            ]
        ];
        
        foreach ($qualities as $quality) {
            if (!isset($qualitySettings[$quality])) {
                continue;
            }
            
            $settings = $qualitySettings[$quality];
            $outputPath = str_replace(
                '.' . pathinfo($videoPath, PATHINFO_EXTENSION),
                "_{$quality}.mp4",
                $videoPath
            );
            
            $command = sprintf(
                '%s -i %s -vf scale=%s -b:v %s -c:v libx264 -preset slow -c:a aac -b:a 128k %s',
                escapeshellcmd($this->ffmpeg),
                escapeshellarg($videoPath),
                $settings['resolution'],
                $settings['bitrate'],
                escapeshellarg($outputPath)
            );
            
            exec($command, $output, $returnVar);
            
            if ($returnVar === 0) {
                $versions[$quality] = basename($outputPath);
            }
        }
        
        return $versions;
    }
    
    private function getVideoMetadata($videoPath) {
        $command = sprintf(
            '%s -i %s 2>&1',
            escapeshellcmd($this->ffmpeg),
            escapeshellarg($videoPath)
        );
        
        exec($command, $output, $returnVar);
        
        $metadata = [
            'duration' => 0,
            'resolution' => '',
            'format' => '',
            'bitrate' => ''
        ];
        
        // Parse FFmpeg output
        $output = implode("\n", $output);
        
        // Extract duration
        if (preg_match('/Duration: ([0-9]{2}):([0-9]{2}):([0-9]{2})/', $output, $matches)) {
            $hours = intval($matches[1]);
            $minutes = intval($matches[2]);
            $seconds = intval($matches[3]);
            $metadata['duration'] = $hours * 3600 + $minutes * 60 + $seconds;
        }
        
        // Extract resolution
        if (preg_match('/Stream.*Video.* ([0-9]{2,5}x[0-9]{2,5})/', $output, $matches)) {
            $metadata['resolution'] = $matches[1];
        }
        
        // Extract format
        if (preg_match('/Input #0, ([a-zA-Z0-9]+),/', $output, $matches)) {
            $metadata['format'] = $matches[1];
        }
        
        // Extract bitrate
        if (preg_match('/bitrate: ([0-9]+) kb\/s/', $output, $matches)) {
            $metadata['bitrate'] = $matches[1] . ' kb/s';
        }
        
        return $metadata;
    }
}
?>
