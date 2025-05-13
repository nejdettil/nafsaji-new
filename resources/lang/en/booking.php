<?php

return [
    'title' => 'Book an Appointment',
    'subtitle' => 'Choose the right specialist for your condition and start your mental health journey',
    'start' => [
        'title' => 'Book an Appointment',
        'subtitle' => 'Choose the booking method that suits you. You can either select the specialist or the service you are looking for. We are here to help you on your mental health journey.'
    ],
    'back_button' => 'Go Back',
    'go_back_home' => 'Back to Home',
    
    // Booking steps
    'steps' => [
        'select_specialist' => 'Select Specialist',
        'select_service' => 'Select Service',
        'select_time' => 'Select Time',
        'confirm_booking' => 'Confirm Booking',
        'payment' => 'Payment',
    ],
    
    // Specialists page
    'specialists' => [
        'title' => 'Book an Appointment with a Specialist',
        'subtitle' => 'Choose the right specialist for your condition and start your mental health journey',
        'search_placeholder' => 'Search for a specialist',
        'filter_by_specialty' => 'Filter by Specialty',
        'all_specialties' => 'All Specialties',
        'sort_by' => 'Sort by',
        'sort_options' => [
            'recommended' => 'Recommended',
            'highest_rated' => 'Highest Rated',
            'most_experienced' => 'Most Experienced',
            'lowest_price' => 'Lowest Price',
        ],
        'profile' => 'Profile',
        'experience' => 'Experience',
        'experience_years' => ':years Years',
        'specialty' => 'Specialty',
        'price' => 'Price starting from',
        'book_now' => 'Book Now',
        'available_services' => 'available services',
        'select_specialist' => 'Select Specialist',
        'no_specialists' => 'No specialists available at the moment',
        'no_specialists_message' => 'We are sorry, there are no specialists available at this time. Please try again later.',
        'go_back' => 'Go Back',
    ],
    
    // Services page
    'services' => [
        'title' => 'Select a Service',
        'subtitle' => 'Choose the service that fits your needs',
        'duration' => 'Duration',
        'minutes' => 'minutes',
        'price' => 'Price',
        'select' => 'Select',
        'no_services' => 'No services available for this specialist.',
    ],
    
    // Time slots page
    'timeslots' => [
        'title' => 'Select Appointment Time',
        'subtitle' => 'Choose a convenient date and time for your appointment',
        'available_dates' => 'Available Dates',
        'available_times' => 'Available Times',
        'morning' => 'Morning',
        'afternoon' => 'Afternoon',
        'evening' => 'Evening',
        'select_date_first' => 'Please select a date first',
        'no_slots_available' => 'No available time slots for the selected date',
        'next' => 'Next',
    ],
    
    // Confirmation page
    'confirmation' => [
        'title' => 'Confirm Your Booking',
        'subtitle' => 'Please review your booking details before confirming',
        'appointment_details' => 'Appointment Details',
        'specialist' => 'Specialist',
        'service' => 'Service',
        'date_time' => 'Date and Time',
        'price' => 'Price',
        'your_details' => 'Your Details',
        'full_name' => 'Full Name',
        'email' => 'Email Address',
        'phone' => 'Phone Number',
        'notes' => 'Additional Notes',
        'notes_placeholder' => 'Add any details that might help the specialist prepare for your session',
        'terms_agreement' => 'I agree to the terms and conditions',
        'confirm_booking' => 'Confirm Booking',
        'terms_required' => 'You must agree to the terms and conditions',
    ],
    
    // Payment page
    'payment' => [
        'title' => 'Complete Payment',
        'subtitle' => 'Choose your preferred payment method to complete your booking',
        'amount_due' => 'Amount Due',
        'payment_methods' => 'Payment Methods',
        'credit_card' => 'Credit Card',
        'debit_card' => 'Debit Card',
        'mada' => 'Mada',
        'apple_pay' => 'Apple Pay',
        'bank_transfer' => 'Bank Transfer',
        'card_number' => 'Card Number',
        'expiry_date' => 'Expiry Date',
        'cvv' => 'CVV',
        'name_on_card' => 'Name on Card',
        'pay_now' => 'Pay Now',
        'secure_payment' => 'Secure Payment',
        'secure_payment_desc' => 'All payment information is encrypted and secure',
    ],
    
    // Common buttons and messages
    'continue' => 'Continue',
    'back' => 'Back',
    'cancel' => 'Cancel',
    'success' => [
        'booking_created' => 'Your booking has been successfully created!',
        'payment_completed' => 'Payment successfully completed!',
    ],
    'error' => [
        'booking_failed' => 'There was an error creating your booking. Please try again.',
        'payment_failed' => 'Payment could not be processed. Please try again.',
    ],
];
