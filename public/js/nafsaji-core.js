/**
 * نفسجي - ملف JavaScript الأساسي
 * Nafsaji Core JavaScript Functions
 */

const Nafsaji = {
    /**
     * الإعدادات
     */
    settings: {
        appVersion: '1.0.0',
        debug: false,
        animationSpeed: 300,
        toastDuration: 3000,
        lazyloadThreshold: 200
    },

    /**
     * تهيئة التطبيق
     */
    init: function() {
        this.lazyLoad.init();
        this.animations.init();
        this.forms.init();
        this.notifications.init();

        if (this.settings.debug) {
            console.log('Nafsaji Core Initialized - v' + this.settings.appVersion);
        }
    },

    /**
     * تحميل الصور الكسول
     */
    lazyLoad: {
        images: [],
        active: false,

        init: function() {
            if ('IntersectionObserver' in window) {
                Nafsaji.lazyLoad.images = document.querySelectorAll('[data-lazy]');
                
                if (Nafsaji.lazyLoad.images.length > 0) {
                    Nafsaji.lazyLoad.active = true;
                    const config = {
                        rootMargin: Nafsaji.settings.lazyloadThreshold + 'px 0px',
                        threshold: 0.01
                    };
                    
                    const observer = new IntersectionObserver(function(entries, self) {
                        entries.forEach(entry => {
                            if (entry.isIntersecting) {
                                Nafsaji.lazyLoad.loadImage(entry.target);
                                self.unobserve(entry.target);
                            }
                        });
                    }, config);
                    
                    Nafsaji.lazyLoad.images.forEach(image => {
                        observer.observe(image);
                    });
                    
                    if (Nafsaji.settings.debug) {
                        console.log('LazyLoad initialized for ' + Nafsaji.lazyLoad.images.length + ' images');
                    }
                }
            } else {
                // الرجوع إلى طريقة تحميل مباشرة إذا لم تكن IntersectionObserver مدعومة
                Nafsaji.lazyLoad.loadAllImages();
            }
        },

        loadImage: function(img) {
            const src = img.getAttribute('data-lazy');
            if (src) {
                if (img.tagName.toLowerCase() === 'img') {
                    img.src = src;
                } else {
                    img.style.backgroundImage = 'url(' + src + ')';
                }
                img.removeAttribute('data-lazy');
                
                img.classList.add('fade-in');
                
                // تسجيل إحصائيات التحميل
                if (Nafsaji.settings.debug) {
                    console.log('Loaded: ' + src);
                }
            }
        },

        loadAllImages: function() {
            const images = document.querySelectorAll('[data-lazy]');
            images.forEach(img => {
                Nafsaji.lazyLoad.loadImage(img);
            });
        }
    },

    /**
     * تأثيرات حركية
     */
    animations: {
        init: function() {
            // تأثيرات متنوعة عند التمرير
            if ('IntersectionObserver' in window) {
                const animatedElements = document.querySelectorAll('.animated');
                
                if (animatedElements.length > 0) {
                    const config = {
                        rootMargin: '0px',
                        threshold: 0.1
                    };
                    
                    const observer = new IntersectionObserver(function(entries) {
                        entries.forEach(entry => {
                            if (entry.isIntersecting) {
                                entry.target.classList.add('animated-visible');
                            }
                        });
                    }, config);
                    
                    animatedElements.forEach(el => {
                        observer.observe(el);
                    });
                }
            }
        }
    },

    /**
     * إدارة النماذج
     */
    forms: {
        init: function() {
            // تهيئة النماذج مع تحقق من البيانات
            const forms = document.querySelectorAll('form[data-validate]');
            
            forms.forEach(form => {
                form.addEventListener('submit', function(e) {
                    if (!Nafsaji.forms.validateForm(this)) {
                        e.preventDefault();
                        return false;
                    }
                });
            });
            
            // تهيئة عناصر الإدخال مع الماسكات
            const maskedInputs = document.querySelectorAll('[data-mask]');
            maskedInputs.forEach(input => {
                const maskType = input.getAttribute('data-mask');
                switch(maskType) {
                    case 'phone':
                        input.addEventListener('input', Nafsaji.forms.maskPhone);
                        break;
                    case 'date':
                        input.addEventListener('input', Nafsaji.forms.maskDate);
                        break;
                    // يمكن إضافة المزيد من الماسكات حسب الحاجة
                }
            });
        },
        
        validateForm: function(form) {
            let valid = true;
            const requiredFields = form.querySelectorAll('[required]');
            
            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    Nafsaji.forms.showError(field, 'هذا الحقل مطلوب');
                    valid = false;
                } else {
                    Nafsaji.forms.clearError(field);
                    
                    // التحقق من صحة البريد الإلكتروني
                    if (field.type === 'email' && !Nafsaji.forms.isValidEmail(field.value)) {
                        Nafsaji.forms.showError(field, 'يرجى إدخال بريد إلكتروني صحيح');
                        valid = false;
                    }
                    
                    // التحقق من الحد الأدنى للطول
                    if (field.getAttribute('minlength') && field.value.length < parseInt(field.getAttribute('minlength'))) {
                        Nafsaji.forms.showError(field, 'يجب أن يكون الحقل ' + field.getAttribute('minlength') + ' أحرف على الأقل');
                        valid = false;
                    }
                }
            });
            
            return valid;
        },
        
        showError: function(field, message) {
            // إزالة رسائل الخطأ السابقة
            Nafsaji.forms.clearError(field);
            
            // إضافة رسالة الخطأ الجديدة
            const errorDiv = document.createElement('div');
            errorDiv.className = 'error-message text-red-500 text-sm mt-1';
            errorDiv.textContent = message;
            
            field.classList.add('border-red-500');
            field.parentNode.appendChild(errorDiv);
        },
        
        clearError: function(field) {
            field.classList.remove('border-red-500');
            const error = field.parentNode.querySelector('.error-message');
            if (error) {
                error.remove();
            }
        },
        
        isValidEmail: function(email) {
            const re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
            return re.test(String(email).toLowerCase());
        },
        
        maskPhone: function(e) {
            let x = e.target.value.replace(/\D/g, '').match(/(\d{0,3})(\d{0,3})(\d{0,4})/);
            e.target.value = !x[2] ? x[1] : '(' + x[1] + ') ' + x[2] + (x[3] ? '-' + x[3] : '');
        },
        
        maskDate: function(e) {
            let x = e.target.value.replace(/\D/g, '').match(/(\d{0,2})(\d{0,2})(\d{0,4})/);
            e.target.value = !x[2] ? x[1] : x[1] + '/' + x[2] + (x[3] ? '/' + x[3] : '');
        }
    },

    /**
     * نظام الإشعارات
     */
    notifications: {
        init: function() {
            // إنشاء حاوية الإشعارات
            this.container = document.createElement('div');
            this.container.className = 'fixed bottom-4 right-4 z-50 flex flex-col-reverse items-end space-y-reverse space-y-2';
            document.body.appendChild(this.container);
        },
        
        show: function(message, type = 'info', duration = null) {
            duration = duration || Nafsaji.settings.toastDuration;
            
            // تعيين الألوان بناءً على النوع
            let bgClass, iconClass;
            switch(type) {
                case 'success':
                    bgClass = 'bg-green-500';
                    iconClass = 'fas fa-check-circle';
                    break;
                case 'error':
                    bgClass = 'bg-red-500';
                    iconClass = 'fas fa-exclamation-circle';
                    break;
                case 'warning':
                    bgClass = 'bg-yellow-500';
                    iconClass = 'fas fa-exclamation-triangle';
                    break;
                default:
                    bgClass = 'bg-blue-500';
                    iconClass = 'fas fa-info-circle';
            }
            
            // إنشاء عنصر الإشعار
            const toast = document.createElement('div');
            toast.className = `${bgClass} text-white p-3 rounded-lg shadow-md flex items-center min-w-[280px] max-w-xs transform transition-all duration-300 translate-x-full opacity-0`;
            toast.innerHTML = `
                <i class="${iconClass} mr-2"></i>
                <div class="flex-1">${message}</div>
                <button class="ml-2 text-white hover:text-gray-200" onclick="this.parentNode.remove();">
                    <i class="fas fa-times"></i>
                </button>
            `;
            
            // إضافة الإشعار إلى الحاوية
            this.container.appendChild(toast);
            
            // تطبيق التأثير الحركي لظهور الإشعار
            setTimeout(() => {
                toast.classList.remove('translate-x-full', 'opacity-0');
            }, 10);
            
            // إزالة الإشعار بعد المدة المحددة
            setTimeout(() => {
                toast.classList.add('translate-x-full', 'opacity-0');
                setTimeout(() => {
                    toast.remove();
                }, Nafsaji.settings.animationSpeed);
            }, duration);
            
            return toast;
        },
        
        success: function(message, duration) {
            return this.show(message, 'success', duration);
        },
        
        error: function(message, duration) {
            return this.show(message, 'error', duration);
        },
        
        warning: function(message, duration) {
            return this.show(message, 'warning', duration);
        },
        
        info: function(message, duration) {
            return this.show(message, 'info', duration);
        }
    }
};

// تهيئة التطبيق عند اكتمال تحميل الصفحة
document.addEventListener('DOMContentLoaded', function() {
    Nafsaji.init();
});
