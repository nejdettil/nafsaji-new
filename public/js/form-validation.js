/**
 * نفسجي - التحقق من صحة النماذج
 * 
 * ملف JavaScript للتحقق من صحة المدخلات في نماذج الحجز والاتصال
 */

document.addEventListener('DOMContentLoaded', function() {
    // التحقق من نموذج الحجز
    const bookingForm = document.querySelector('form[action*="booking"]');
    if (bookingForm) {
        initBookingFormValidation(bookingForm);
    }
    
    // التحقق من نموذج الاتصال
    const contactForm = document.querySelector('form[action*="contact"]');
    if (contactForm) {
        initContactFormValidation(contactForm);
    }
});

/**
 * تهيئة التحقق من صحة نموذج الحجز
 * 
 * @param {HTMLFormElement} form نموذج الحجز
 */
function initBookingFormValidation(form) {
    form.addEventListener('submit', function(event) {
        let isValid = true;
        
        // التحقق من اختيار المتخصص
        const specialistField = form.querySelector('#specialist_id');
        if (specialistField && !specialistField.value) {
            showError(specialistField, 'يرجى اختيار المتخصص');
            isValid = false;
        } else {
            clearError(specialistField);
        }
        
        // التحقق من اختيار الخدمة
        const serviceField = form.querySelector('#service_id');
        if (serviceField && !serviceField.value) {
            showError(serviceField, 'يرجى اختيار الخدمة');
            isValid = false;
        } else {
            clearError(serviceField);
        }
        
        // التحقق من تاريخ الحجز
        const dateField = form.querySelector('#booking_date');
        if (dateField) {
            const selectedDate = new Date(dateField.value);
            const today = new Date();
            today.setHours(0, 0, 0, 0);
            
            if (!dateField.value) {
                showError(dateField, 'يرجى تحديد تاريخ الحجز');
                isValid = false;
            } else if (selectedDate < today) {
                showError(dateField, 'يجب أن يكون تاريخ الحجز اليوم أو في المستقبل');
                isValid = false;
            } else {
                clearError(dateField);
            }
        }
        
        // التحقق من وقت الحجز
        const timeField = form.querySelector('#booking_time');
        if (timeField && !timeField.value) {
            showError(timeField, 'يرجى تحديد وقت الحجز');
            isValid = false;
        } else {
            clearError(timeField);
        }
        
        if (!isValid) {
            event.preventDefault();
            // التمرير إلى أول خطأ
            const firstError = form.querySelector('.error-message');
            if (firstError) {
                firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        }
    });
}

/**
 * تهيئة التحقق من صحة نموذج الاتصال
 * 
 * @param {HTMLFormElement} form نموذج الاتصال
 */
function initContactFormValidation(form) {
    form.addEventListener('submit', function(event) {
        let isValid = true;
        
        // التحقق من حقل الاسم
        const nameField = form.querySelector('#name');
        if (nameField && !nameField.value.trim()) {
            showError(nameField, 'يرجى إدخال الاسم');
            isValid = false;
        } else {
            clearError(nameField);
        }
        
        // التحقق من حقل البريد الإلكتروني
        const emailField = form.querySelector('#email');
        if (emailField) {
            if (!emailField.value.trim()) {
                showError(emailField, 'يرجى إدخال البريد الإلكتروني');
                isValid = false;
            } else if (!isValidEmail(emailField.value)) {
                showError(emailField, 'يرجى إدخال بريد إلكتروني صحيح');
                isValid = false;
            } else {
                clearError(emailField);
            }
        }
        
        // التحقق من حقل الموضوع
        const subjectField = form.querySelector('#subject');
        if (subjectField && !subjectField.value.trim()) {
            showError(subjectField, 'يرجى إدخال الموضوع');
            isValid = false;
        } else {
            clearError(subjectField);
        }
        
        // التحقق من حقل الرسالة
        const messageField = form.querySelector('#message');
        if (messageField && !messageField.value.trim()) {
            showError(messageField, 'يرجى إدخال الرسالة');
            isValid = false;
        } else {
            clearError(messageField);
        }
        
        if (!isValid) {
            event.preventDefault();
            // التمرير إلى أول خطأ
            const firstError = form.querySelector('.error-message');
            if (firstError) {
                firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        }
    });
}

/**
 * التحقق من صحة البريد الإلكتروني
 * 
 * @param {string} email البريد الإلكتروني للتحقق منه
 * @returns {boolean} صحيح إذا كان البريد الإلكتروني صحيحًا
 */
function isValidEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
}

/**
 * إظهار رسالة خطأ لحقل معين
 * 
 * @param {HTMLElement} field الحقل لإظهار الخطأ له
 * @param {string} message رسالة الخطأ
 */
function showError(field, message) {
    clearError(field);
    
    const errorElement = document.createElement('div');
    errorElement.className = 'error-message text-red-600 text-sm mt-1';
    errorElement.textContent = message;
    
    field.classList.add('border-red-500');
    field.parentNode.appendChild(errorElement);
}

/**
 * إزالة رسالة الخطأ من حقل معين
 * 
 * @param {HTMLElement} field الحقل لإزالة الخطأ منه
 */
function clearError(field) {
    if (!field) return;
    
    const errorElement = field.parentNode.querySelector('.error-message');
    if (errorElement) {
        errorElement.parentNode.removeChild(errorElement);
    }
    
    field.classList.remove('border-red-500');
}
