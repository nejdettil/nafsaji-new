// General variables
let selectedDate = null;
let selectedTime = null;
let autoRefreshTimer = null;
const AUTO_REFRESH_INTERVAL = 30000; // 30 seconds

// Set application locale based on HTML lang attribute or default to 'ar'
const appLocale = document.documentElement.lang || 'ar';

// Load time slots for a specific date
function loadTimeSlots(date, specialist_id, service_id) {
    console.log('Loading time slots for date:', date);
    
    // Cancel previous auto-refresh if it exists
    if (autoRefreshTimer) {
        clearTimeout(autoRefreshTimer);
    }
    
    // Show loading indicator
    document.getElementById('timeslots-loading').style.display = 'block';
    document.getElementById('timeslots-grid').style.display = 'none';
    document.getElementById('no-timeslots').style.display = 'none';
    
    // Add a dimming effect to calendar days during loading
    document.querySelectorAll('.day-card').forEach(card => {
        if (!card.classList.contains('selected')) {
            card.style.opacity = '0.6';
        }
    });
    
    // Disable continue button when reloading appointments
    document.getElementById('continueBtn').disabled = true;
    document.getElementById('continueBtn').classList.remove('ready');
    
    // Make an AJAX request to get available time slots
    const apiUrl = `/booking/api/time-slots?specialist_id=${specialist_id}&service_id=${service_id}&date=${date}`;
    console.log('API URL:', apiUrl);
    
    fetch(apiUrl)
        .then(response => {
            console.log('API response status:', response.status);
            // Check response status
            if (!response.ok) {
                throw new Error(`Error HTTP: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            console.log('API response:', data);
            console.log('Success?', data.success);
            console.log('Time slots type:', typeof data.timeSlots);
            console.log('Time slots length:', data.timeSlots ? data.timeSlots.length : 'undefined');
            
            // Hide loading indicator
            document.getElementById('timeslots-loading').style.display = 'none';
            console.log('Hide loading indicator');
            
            // Restore calendar days to normal
            document.querySelectorAll('.day-card').forEach(card => {
                card.style.opacity = '';
            });
            console.log('Restored day cards');
            
            // Create HTML elements for time slots
            const timeslotsGrid = document.getElementById('timeslots-grid');
            timeslotsGrid.innerHTML = '';
            
            // Create a container for appointments
            const slotsContainer = document.createElement('div');
            slotsContainer.className = 'timeslots-container-inner';
            slotsContainer.style.display = 'flex';
            slotsContainer.style.flexWrap = 'wrap';
            slotsContainer.style.gap = '15px';
            slotsContainer.style.justifyContent = 'center';
            slotsContainer.style.width = '100%';
            
            if (data.success && data.timeSlots && data.timeSlots.length > 0) {
                // Strict filtering of appointments - only explicitly available ones
                // with detailed logging to see how filtering works
                console.log('Filtering time slots, only showing explicitly available ones...');
                console.log('All time slots before filtering:', data.timeSlots);
                
                // DEBUG: Ensure data.timeSlots is actually an array
                if (!Array.isArray(data.timeSlots)) {
                    console.error('ERROR: data.timeSlots is not an array! Type:', typeof data.timeSlots);
                    console.log('Converting to array if possible...');
                    try {
                        // Try to convert to array if it's a JSON string
                        if (typeof data.timeSlots === 'string') {
                            data.timeSlots = JSON.parse(data.timeSlots);
                        } else {
                            // Try to force into array
                            data.timeSlots = [].concat(data.timeSlots);
                        }
                        console.log('After conversion:', data.timeSlots);
                    } catch (e) {
                        console.error('Failed to convert timeSlots to array:', e);
                    }
                }
                
                // Very strict filtering - only true is accepted
                // Anything else is considered unavailable
                const strictlyAvailableSlots = data.timeSlots.filter(slot => {
                    console.log('Checking slot:', slot);
                    // Handle undefined or null slots
                    if (!slot) {
                        console.error('ERROR: Found null or undefined slot!');
                        return false;
                    }
                    
                    const isAvailable = slot.available === true;
                    console.log(`Slot ${slot.time}: available=${slot.available}, type=${typeof slot.available}, isAvailable=${isAvailable}`);
                    return isAvailable;
                });
                
                console.log('Strictly available slots after filtering:', strictlyAvailableSlots);
                console.log(`Found ${strictlyAvailableSlots.length} available slots out of ${data.timeSlots.length} total slots`);
                
                // Check for available appointments after strict filtering
                if (strictlyAvailableSlots.length === 0) {
                    // No available appointments on this day
                    document.getElementById('no-timeslots').style.display = 'block';
                    return;
                }
                
                // Display the time slots grid
                document.getElementById('timeslots-grid').style.display = 'flex';
                
                // Display only available appointments
                // We don't display booked appointments at all
                strictlyAvailableSlots.forEach(slot => {
                    const timeSlot = document.createElement('div');
                    timeSlot.className = 'time-slot';
                    timeSlot.dataset.time = slot.time;
                    timeSlot.dataset.available = 'true'; // Mark as available
                    
                    // Improve display of available time slots
                    timeSlot.innerHTML = `
                        <div class="time-value">${slot.formattedTime}</div>
                        <div class="time-status-available"><i class="fas fa-check-circle"></i> ${appLocale === 'ar' ? 'متاح' : 'Available'}</div>
                    `;
                    
                    // Add animation effect to available appointments
                    timeSlot.style.animationDelay = `${Math.random() * 0.5}s`;
                    timeSlot.style.animation = 'fadeIn 0.5s ease-in-out forwards';
                    
                    // Add click event to available appointments
                    timeSlot.addEventListener('click', function() {
                        // Remove selected class from all time slots
                        document.querySelectorAll('.time-slot').forEach(ts => {
                            ts.classList.remove('selected');
                            // Reset text for other slots
                            const otherStatus = ts.querySelector('.time-status-available');
                            if (otherStatus && ts !== this) {
                                otherStatus.innerHTML = `<i class="fas fa-check-circle"></i> ${appLocale === 'ar' ? 'متاح' : 'Available'}`;
                                otherStatus.style.color = '';
                                otherStatus.style.backgroundColor = '';
                            }
                        });

                        // Add selected class to the chosen appointment
                        this.classList.add('selected');
                        
                        // Update text status for the chosen appointment
                        const statusDiv = this.querySelector('.time-status-available');
                        if (statusDiv) {
                            statusDiv.innerHTML = `<i class="fas fa-calendar-check"></i> ${appLocale === 'ar' ? 'تم الاختيار' : 'Selected'}`;
                            statusDiv.style.color = 'white';
                            statusDiv.style.backgroundColor = '#4CAF50';
                        }
                        
                        // Update selected time
                        selectedTime = slot.time;
                        document.getElementById('selected_time').value = selectedTime;
                        
                        // Enable continue button
                        const continueBtn = document.getElementById('continueBtn');
                        continueBtn.disabled = false;
                        continueBtn.classList.add('ready');
                    });
                    
                    // Add appointment to container
                    slotsContainer.appendChild(timeSlot);
                });
                
                // Add appointment container to display
                timeslotsGrid.appendChild(slotsContainer);
                
                // Set up auto-refresh to update appointment list every 30 seconds
                autoRefreshTimer = setTimeout(() => {
                    loadTimeSlots(date, specialist_id, service_id);
                }, AUTO_REFRESH_INTERVAL);
            } else {
                // Display message - no available appointments
                document.getElementById('no-timeslots').style.display = 'block';
            }
        })
        .catch(error => {
            console.error('Error loading time slots:', error);
            
            // Hide loading indicator and show error message
            document.getElementById('timeslots-loading').style.display = 'none';
            document.getElementById('no-timeslots').textContent = appLocale === 'ar' ? 'حدث خطأ أثناء تحميل المواعيد. يرجى المحاولة مرة أخرى.' : 'Error loading appointments. Please try again.';
            document.getElementById('no-timeslots').style.display = 'block';
            
            // Restore calendar days to normal
            document.querySelectorAll('.day-card').forEach(card => {
                card.style.opacity = '';
            });
        });
}

// Check appointment availability before submitting the form
function checkAvailabilityBeforeSubmit(specialist_id, service_id) {
    // Check if an appointment is selected
    const selectedDate = document.getElementById('selected_date').value;
    const selectedTime = document.getElementById('selected_time').value;
    
    if (!selectedTime) {
        alert(appLocale === 'ar' ? 'الرجاء اختيار موعد قبل المتابعة.' : 'Please select an appointment before continuing.');
        return;
    }
    
    // Show loading indicator
    const continueBtn = document.getElementById('continueBtn');
    const originalText = continueBtn.innerHTML;
    continueBtn.innerHTML = `<i class="fas fa-spinner fa-spin"></i> ${appLocale === 'ar' ? 'جاري التحقق...' : 'Checking...'}`;
    continueBtn.disabled = true;
    
    // Call API to check appointment availability
    fetch(`/booking/api/check-availability?specialist_id=${specialist_id}&service_id=${service_id}&date=${selectedDate}&time=${selectedTime}`)
        .then(response => {
            if (!response.ok) {
                throw new Error(`Error HTTP: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            if (data.success && data.available) {
                // الموعد لا يزال متاحًا
                document.getElementById('bookingForm').submit();
            } else {
                // الموعد لم يعد متاحًا
                const alertMsg = document.createElement('div');
                alertMsg.className = 'alert alert-danger mt-3 text-center';
                alertMsg.setAttribute('role', 'alert');
                alertMsg.innerHTML = `<i class="fas fa-exclamation-circle"></i> عذراً، هذا الموعد لم يعد متاحًا. الرجاء اختيار موعد آخر.`;
                
                // إزالة أي رسائل سابقة
                const existingAlerts = document.querySelectorAll('.alert');
                existingAlerts.forEach(alert => alert.remove());
                
                // إضافة التنبيه
                const timeslotsGrid = document.getElementById('timeslots-grid');
                timeslotsGrid.appendChild(alertMsg);
                
                // إلغاء تحديد الموعد وتعطيل زر المتابعة
                document.getElementById('selected_time').value = '';
                document.querySelectorAll('.time-slot').forEach(ts => {
                    ts.classList.remove('selected');
                });
                
                // إعادة تحميل المواعيد للحصول على القائمة المحدثة
                loadTimeSlots(selectedDate, specialist_id, service_id);
            }
            
            // إعادة زر المتابعة إلى حالته الأصلية
            continueBtn.innerHTML = originalText;
            continueBtn.disabled = false;
        })
        .catch(error => {
            console.error('Error checking time slot availability:', error);
            
            // إظهار رسالة خطأ
            const alertMsg = document.createElement('div');
            alertMsg.className = 'alert alert-danger mt-3 text-center';
            alertMsg.setAttribute('role', 'alert');
            alertMsg.innerHTML = `<i class="fas fa-exclamation-circle"></i> حدث خطأ أثناء التحقق من توفر الموعد. الرجاء المحاولة مرة أخرى.`;
            
            // إزالة أي رسائل سابقة
            const existingAlerts = document.querySelectorAll('.alert');
            existingAlerts.forEach(alert => alert.remove());
            
            // إضافة التنبيه
            const timeslotsGrid = document.getElementById('timeslots-grid');
            timeslotsGrid.appendChild(alertMsg);
            
            // إعادة زر المتابعة إلى حالته الأصلية
            continueBtn.innerHTML = originalText;
            continueBtn.disabled = false;
        });
}
