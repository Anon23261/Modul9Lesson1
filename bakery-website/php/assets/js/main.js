document.addEventListener('DOMContentLoaded', function() {
    // Form validation
    const form = document.querySelector('form');
    const requiredFields = form.querySelectorAll('[required]');
    
    form.addEventListener('submit', function(e) {
        let isValid = true;
        
        requiredFields.forEach(field => {
            if (!field.value.trim()) {
                isValid = false;
                field.classList.add('error');
                
                // Create or update error message
                let errorMsg = field.parentElement.querySelector('.error-message');
                if (!errorMsg) {
                    errorMsg = document.createElement('div');
                    errorMsg.className = 'error-message';
                    field.parentElement.appendChild(errorMsg);
                }
                errorMsg.textContent = `${field.getAttribute('name')} is required`;
            } else {
                field.classList.remove('error');
                const errorMsg = field.parentElement.querySelector('.error-message');
                if (errorMsg) {
                    errorMsg.remove();
                }
            }
        });
        
        if (!isValid) {
            e.preventDefault();
        }
    });
    
    // Price input formatting
    const priceInput = document.getElementById('price');
    if (priceInput) {
        priceInput.addEventListener('input', function(e) {
            let value = e.target.value;
            
            // Remove non-numeric characters except decimal point
            value = value.replace(/[^\d.]/g, '');
            
            // Ensure only one decimal point
            const parts = value.split('.');
            if (parts.length > 2) {
                value = parts[0] + '.' + parts.slice(1).join('');
            }
            
            // Limit to two decimal places
            if (parts.length === 2 && parts[1].length > 2) {
                value = parseFloat(value).toFixed(2);
            }
            
            e.target.value = value;
        });
    }
    
    // Image preview
    const imageInput = document.getElementById('product_image');
    if (imageInput) {
        imageInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                // Check file size
                if (file.size > 2000000) {
                    alert('File is too large. Maximum size is 2MB.');
                    e.target.value = '';
                    return;
                }
                
                // Check file type
                const validTypes = ['image/jpeg', 'image/png', 'image/webp'];
                if (!validTypes.includes(file.type)) {
                    alert('Invalid file type. Please upload JPG, PNG, or WebP images only.');
                    e.target.value = '';
                    return;
                }
                
                // Create preview
                const reader = new FileReader();
                reader.onload = function(e) {
                    let preview = document.getElementById('image-preview');
                    if (!preview) {
                        preview = document.createElement('img');
                        preview.id = 'image-preview';
                        preview.style.maxWidth = '200px';
                        preview.style.marginTop = '10px';
                        imageInput.parentElement.appendChild(preview);
                    }
                    preview.src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        });
    }
    
    // Availability checkboxes
    const checkboxGroup = document.querySelector('.checkbox-group');
    if (checkboxGroup) {
        const checkboxes = checkboxGroup.querySelectorAll('input[type="checkbox"]');
        checkboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const checked = Array.from(checkboxes).filter(cb => cb.checked).length;
                if (checked === 0) {
                    alert('Please select at least one day of availability.');
                    this.checked = true;
                }
            });
        });
    }
});
