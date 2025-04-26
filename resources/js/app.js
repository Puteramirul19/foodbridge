import './bootstrap';

// Basic interactivity for FoodBridge

document.addEventListener('DOMContentLoaded', () => {
    // Flash message auto-dismiss
    const flashMessages = document.querySelectorAll('.flash-message');
    flashMessages.forEach(message => {
        setTimeout(() => {
            message.style.transition = 'opacity 0.5s';
            message.style.opacity = 0;
            setTimeout(() => message.remove(), 500);
        }, 5000);
    });

    // Donation filtering (if browse donations page exists)
    const donationFilter = document.querySelector('.donations-filter');
    if (donationFilter) {
        donationFilter.addEventListener('change', () => {
            donationFilter.closest('form').submit();
        });
    }

    // Form validation
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', (e) => {
            const requiredFields = form.querySelectorAll('[required]');
            let isValid = true;

            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    field.classList.add('error');
                    isValid = false;
                } else {
                    field.classList.remove('error');
                }
            });

            if (!isValid) {
                e.preventDefault();
                alert('Please fill all required fields');
            }
        });
    });

    // Donation reservation confirmation
    const reservationButtons = document.querySelectorAll('.reserve-donation');
    reservationButtons.forEach(button => {
        button.addEventListener('click', (e) => {
            if (!confirm('Are you sure you want to reserve this donation?')) {
                e.preventDefault();
            }
        });
    });
});