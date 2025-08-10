// Add to cart functionality
function addToCart(event, productId) {
    console.log('Adding to cart:', productId); // Debug log
    
    if (!event || !productId) {
        console.error('Invalid parameters:', { event, productId });
        return;
    }

    let button = event.currentTarget;
    if (!button) {
        console.error('Button element not found');
        return;
    }

    button.disabled = true;
    button.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Adding...';

    const formData = new URLSearchParams();
    formData.append('action', 'add');
    formData.append('product_id', productId);
    formData.append('quantity', 1);

    console.log('Sending request to cart_actions.php'); // Debug log

    fetch('includes/cart_actions.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: formData.toString()
    })
    .then(response => {
        console.log('Response received:', response.status); // Debug log
        return response.json();
    })
    .then(data => {
        console.log('Data received:', data); // Debug log
        
        if(data.success) {
            // Update cart count in header if it exists
            const cartCount = document.querySelector('.cart-count');
            if(cartCount) {
                cartCount.textContent = data.cartCount;
                cartCount.classList.add('cart-count'); // trigger animation
                setTimeout(() => cartCount.classList.remove('cart-count'), 500);
            }
            // Show success message
            showNotification('Product added to cart!', 'success');
            button.innerHTML = '<i class="fas fa-check"></i> Added!';
            button.classList.remove('btn-primary');
            button.classList.add('btn-success');
            setTimeout(() => {
                button.disabled = false;
                button.innerHTML = '<i class="fas fa-shopping-cart me-2"></i>Add to Cart';
                button.classList.remove('btn-success');
                button.classList.add('btn-primary');
            }, 1800);
        } else {
            console.error('Add to cart failed:', data.message); // Debug log
            button.innerHTML = '<i class="fas fa-shopping-cart me-2"></i>Add to Cart';
            button.disabled = false;
            showNotification(data.message || 'Error adding to cart', 'error');
        }
    })
    .catch(error => {
        console.error('Fetch error:', error); // Debug log
        button.innerHTML = '<i class="fas fa-shopping-cart me-2"></i>Add to Cart';
        button.disabled = false;
        showNotification('An error occurred while adding to cart', 'error');
    });
}

// Update cart badge
function updateCartBadge(count) {
    const badge = document.querySelector('.cart-badge');
    if(badge) {
        badge.textContent = count;
        badge.style.display = count > 0 ? 'inline' : 'none';
    }
}

// Show notification
function showNotification(message, type = 'success') {
    const notification = document.createElement('div');
    notification.className = `alert alert-${type} notification`;
    notification.textContent = message;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.remove();
    }, 3000);
}

// Form validation
function validateForm(formId) {
    const form = document.getElementById(formId);
    if (!form) return true;

    let isValid = true;
    const inputs = form.querySelectorAll('input[required], select[required], textarea[required]');
    
    inputs.forEach(input => {
        if (!input.value.trim()) {
            isValid = false;
            input.classList.add('is-invalid');
        } else {
            input.classList.remove('is-invalid');
        }
    });

    return isValid;
}

// Password strength checker
function checkPasswordStrength(password) {
    const strengthMeter = document.getElementById('password-strength');
    if (!strengthMeter) return;

    const strength = {
        length: password.length >= 8,
        hasUpperCase: /[A-Z]/.test(password),
        hasLowerCase: /[a-z]/.test(password),
        hasNumbers: /\d/.test(password),
        hasSpecialChar: /[!@#$%^&*]/.test(password)
    };

    const strengthScore = Object.values(strength).filter(Boolean).length;
    
    switch(strengthScore) {
        case 0:
        case 1:
            strengthMeter.className = 'progress-bar bg-danger';
            strengthMeter.style.width = '20%';
            break;
        case 2:
            strengthMeter.className = 'progress-bar bg-warning';
            strengthMeter.style.width = '40%';
            break;
        case 3:
            strengthMeter.className = 'progress-bar bg-info';
            strengthMeter.style.width = '60%';
            break;
        case 4:
            strengthMeter.className = 'progress-bar bg-primary';
            strengthMeter.style.width = '80%';
            break;
        case 5:
            strengthMeter.className = 'progress-bar bg-success';
            strengthMeter.style.width = '100%';
            break;
    }
}

// Image preview for file inputs
function previewImage(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        const preview = document.getElementById('image-preview');
        
        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.style.display = 'block';
        }
        
        reader.readAsDataURL(input.files[0]);
    }
}

// Initialize tooltips
document.addEventListener('DOMContentLoaded', function() {
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
}); 