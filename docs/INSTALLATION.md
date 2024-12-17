# 🚀 Installation Guide

## Prerequisites

### PHP Web Application
- PHP 7.4 or higher
- MySQL 5.7 or higher
- Apache/Nginx web server
- Composer
- FFmpeg (for video processing)
- ImageMagick (for image processing)

### C Console Application
- GCC compiler
- SQLite3
- Make

## Installation Steps

### 1. PHP Web Application

#### Clone the Repository
```bash
git clone https://github.com/yourusername/bakery-registration.git
cd bakery-registration/php
```

#### Install Dependencies
```bash
composer install
```

#### Configure Database
1. Copy the example configuration:
   ```bash
   cp config/database.example.php config/database.php
   ```
2. Edit `config/database.php` with your database credentials

#### Set up File Permissions
```bash
chmod -R 755 .
chmod -R 777 assets/images/products
chmod -R 777 assets/videos/products
```

#### Configure Web Server
Apache configuration example:
```apache
<VirtualHost *:80>
    ServerName bakery.local
    DocumentRoot /path/to/bakery-registration/php
    
    <Directory /path/to/bakery-registration/php>
        Options Indexes FollowSymLinks MultiViews
        AllowOverride All
        Require all granted
    </Directory>
    
    ErrorLog ${APACHE_LOG_DIR}/bakery_error.log
    CustomLog ${APACHE_LOG_DIR}/bakery_access.log combined
</VirtualHost>
```

### 2. C Console Application

#### Compile the Application
```bash
cd bakery-registration/c
make
```

#### Run the Application
```bash
./bin/bakery_manager
```

## Configuration

### Video Upload Settings
Edit `config/media.php`:
```php
define('MAX_VIDEO_SIZE', 100 * 1024 * 1024); // 100MB
define('ALLOWED_VIDEO_TYPES', ['video/mp4', 'video/webm']);
define('VIDEO_QUALITY', '720p');
```

### Image Upload Settings
Edit `config/media.php`:
```php
define('MAX_IMAGE_SIZE', 5 * 1024 * 1024); // 5MB
define('ALLOWED_IMAGE_TYPES', ['image/jpeg', 'image/png', 'image/webp']);
define('IMAGE_QUALITY', 80);
```

## Testing

### Run PHP Tests
```bash
composer test
```

### Run C Tests
```bash
make test
```

## Troubleshooting

### Common Issues

1. **Database Connection Failed**
   - Verify database credentials
   - Ensure MySQL service is running
   - Check network connectivity

2. **File Upload Issues**
   - Verify directory permissions
   - Check PHP upload limits in php.ini
   - Ensure FFmpeg is installed for video processing

3. **C Compilation Errors**
   - Install SQLite3 development libraries
   - Update GCC if needed
   - Check system dependencies

## Security Considerations

1. **File Uploads**
   - Implement virus scanning
   - Validate file types
   - Use secure file naming

2. **Database**
   - Use prepared statements
   - Implement input validation
   - Regular backups

3. **Authentication**
   - Use HTTPS
   - Implement rate limiting
   - Session management

## Performance Optimization

1. **Video Processing**
   - Implement async processing
   - Use queue system for large files
   - Configure CDN for delivery

2. **Image Optimization**
   - Implement WebP conversion
   - Use responsive images
   - Configure image caching

3. **Database**
   - Index key columns
   - Optimize queries
   - Regular maintenance

## Support

For technical support:
- Email: support@example.com
- Documentation: https://docs.example.com
- Issue Tracker: https://github.com/yourusername/bakery-registration/issues
