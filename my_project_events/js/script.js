// Add animation classes to elements when they come into view
document.addEventListener('DOMContentLoaded', function() {
    // Add fade-in class to hero content
    const heroContent = document.querySelector('.hero-content');
    if (heroContent) {
        heroContent.classList.add('fade-in');
    }
    
    // Add animation delay to event cards
    const eventCards = document.querySelectorAll('.event-card');
    eventCards.forEach((card, index) => {
        card.style.animationDelay = `${index * 0.1}s`;
        card.classList.add('fade-in');
    });
});

// Form validation for participation form
function validateForm() {
    let isValid = true;
    const firstName = document.getElementById('firstName');
    const lastName = document.getElementById('lastName');
    const email = document.getElementById('email');
    
    // Clear previous error messages
    document.querySelectorAll('.error-message').forEach(el => el.remove());
    
    // Validate first name
    if (!firstName.value.trim()) {
        displayError(firstName, 'Le prénom est requis');
        isValid = false;
    }
    
    // Validate last name
    if (!lastName.value.trim()) {
        displayError(lastName, 'Le nom est requis');
        isValid = false;
    }
    
    // Validate email
    if (!email.value.trim()) {
        displayError(email, 'L\'email est requis');
        isValid = false;
    } else if (!/\S+@\S+\.\S+/.test(email.value)) {
        displayError(email, 'Adresse email invalide');
        isValid = false;
    }
    
    return isValid;
}

// Display error message under an input
function displayError(input, message) {
    const errorElement = document.createElement('div');
    errorElement.className = 'error-message text-danger mt-1 small';
    errorElement.innerText = message;
    input.classList.add('is-invalid');
    input.parentNode.appendChild(errorElement);
}
