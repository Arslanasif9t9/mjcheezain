const heartBtn = document.getElementById('heart-btn');
const heartIcon = document.getElementById('heart-icon');
const productId = heartBtn.getAttribute('data-product-id'); // Make sure to add this attribute to your button

// Function to show notification
function showNotification(message, type = 'info') {
    // Remove existing notifications
    const existingNotifications = document.querySelectorAll('.custom-notification');
    existingNotifications.forEach(notification => notification.remove());

    const notification = document.createElement('div');
    notification.className = `custom-notification fixed top-4 right-4 p-4 rounded-lg shadow-lg bg-white border-l-4 z-50 ${
        type === 'success' ? 'border-green-500' : 
        type === 'error' ? 'border-red-500' : 'border-blue-500'
    }`;
    
    notification.innerHTML = `
        <div class="flex items-center">
            <i class="fas ${
                type === 'success' ? 'fa-check-circle text-green-500' : 
                type === 'error' ? 'fa-exclamation-circle text-red-500' : 'fa-info-circle text-blue-500'
            } mr-2"></i>
            <span class="text-sm font-medium text-gray-900">${message}</span>
            <button class="ml-4 text-gray-400 hover:text-gray-500" onclick="this.parentElement.parentElement.remove()">
                <i class="fas fa-times"></i>
            </button>
        </div>
    `;
    
    document.body.appendChild(notification);
    
    // Auto remove after 3 seconds
    setTimeout(() => {
        if (notification.parentElement) {
            notification.remove();
        }
    }, 3000);
}

// Heart icon toggle with API call
heartBtn.addEventListener('click', async () => {
    console.log('click')
    try {
        // Show loading state
        heartBtn.disabled = true;
        
        const response = await fetch('/favorites/toggle', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                product_id: productId
            })
        });

        const data = await response.json();

        if (data.success) {
            // Update UI based on favorite status
            if (data.is_favorite) {
                heartIcon.classList.replace('text-gray-700', 'text-red-500');
                heartIcon.setAttribute('fill', 'currentColor');
            } else {
                heartIcon.classList.replace('text-red-500', 'text-gray-700');
                heartIcon.setAttribute('fill', 'none');
            }
            
            showNotification(data.message, 'success');
        } else {
            showNotification(data.message, 'error');
        }

    } catch (error) {
        console.error('Error:', error);
        showNotification('Error updating favorites', 'error');
    } finally {
        heartBtn.disabled = false;
    }
});

// Check initial favorite status on page load
document.addEventListener('DOMContentLoaded', async () => {
    try {
        const response = await fetch(`/favorites/check/${productId}`);
        const data = await response.json();

        if (data.success && data.is_favorite) {
            heartIcon.classList.replace('text-gray-700', 'text-red-500');
            heartIcon.setAttribute('fill', 'currentColor');
        }

    } catch (error) {
        console.error('Error checking favorite status:', error);
    }
});