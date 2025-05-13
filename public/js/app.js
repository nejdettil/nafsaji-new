/**
 * Nafsaji Main JavaScript File
 * Contains common functionality used across the website
 */

// Inicializar Alpine.js si está presente
document.addEventListener('DOMContentLoaded', function() {
    // Inicializar animaciones AOS
    if (typeof AOS !== 'undefined') {
        AOS.init({
            duration: 800,
            easing: 'ease-in-out',
            once: true
        });
    }

    // Manejo del menú móvil
    initMobileMenu();
    
    // Inicializar mensajes de alerta con auto-cierre
    initAlertMessages();
    
    // Inicializar validación de formularios
    initFormValidation();
});

/**
 * Inicializa la funcionalidad del menú móvil
 */
function initMobileMenu() {
    const mobileMenuToggle = document.getElementById('mobile-menu-toggle');
    const mobileMenu = document.getElementById('mobile-menu');
    
    if (mobileMenuToggle && mobileMenu) {
        mobileMenuToggle.addEventListener('click', function() {
            mobileMenu.classList.toggle('hidden');
            document.body.classList.toggle('overflow-hidden');
        });
        
        // Cerrar el menú al hacer clic en un enlace
        const mobileLinks = mobileMenu.querySelectorAll('a');
        mobileLinks.forEach(link => {
            link.addEventListener('click', function() {
                mobileMenu.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
            });
        });
    }
}

/**
 * Inicializa los mensajes de alerta con auto-cierre
 */
function initAlertMessages() {
    const alerts = document.querySelectorAll('.alert');
    
    alerts.forEach(alert => {
        // Agregar botón de cierre si no existe
        if (!alert.querySelector('.alert-close')) {
            const closeButton = document.createElement('button');
            closeButton.className = 'alert-close';
            closeButton.innerHTML = '&times;';
            closeButton.addEventListener('click', function() {
                alert.remove();
            });
            alert.appendChild(closeButton);
        }
        
        // Auto-cierre después de 5 segundos
        if (alert.classList.contains('alert-auto-close')) {
            setTimeout(function() {
                alert.style.opacity = '0';
                setTimeout(function() {
                    alert.remove();
                }, 300);
            }, 5000);
        }
    });
}

/**
 * Inicializa la validación básica de formularios
 */
function initFormValidation() {
    const forms = document.querySelectorAll('form[data-validate="true"]');
    
    forms.forEach(form => {
        form.addEventListener('submit', function(event) {
            let isValid = true;
            
            // Validar campos requeridos
            const requiredFields = form.querySelectorAll('[required]');
            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    isValid = false;
                    showFieldError(field, 'Este campo es requerido');
                } else {
                    removeFieldError(field);
                }
            });
            
            // Validar campos de email
            const emailFields = form.querySelectorAll('input[type="email"]');
            emailFields.forEach(field => {
                if (field.value && !isValidEmail(field.value)) {
                    isValid = false;
                    showFieldError(field, 'Por favor, ingrese un email válido');
                }
            });
            
            if (!isValid) {
                event.preventDefault();
            }
        });
    });
}

/**
 * Muestra un mensaje de error para un campo de formulario
 */
function showFieldError(field, message) {
    removeFieldError(field); // Eliminar errores existentes
    
    field.classList.add('border-red-500');
    
    const errorElement = document.createElement('p');
    errorElement.className = 'text-red-500 text-sm mt-1 error-message';
    errorElement.textContent = message;
    
    field.parentNode.appendChild(errorElement);
}

/**
 * Elimina los mensajes de error de un campo
 */
function removeFieldError(field) {
    field.classList.remove('border-red-500');
    
    const errorMessages = field.parentNode.querySelectorAll('.error-message');
    errorMessages.forEach(el => el.remove());
}

/**
 * Valida un formato de email
 */
function isValidEmail(email) {
    const re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(String(email).toLowerCase());
}

/**
 * Función para cambiar entre idiomas
 */
function switchLanguage(lang) {
    // Crear un formulario para enviar la solicitud POST al controlador de idioma
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = `/language/${lang}`;
    
    // Agregar token CSRF
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const csrfInput = document.createElement('input');
    csrfInput.type = 'hidden';
    csrfInput.name = '_token';
    csrfInput.value = csrfToken;
    
    form.appendChild(csrfInput);
    document.body.appendChild(form);
    form.submit();
}
