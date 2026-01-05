$(document).ready(function() {
    console.log("Medical Clinic Website Loaded");

    // 1. Navbar & Button Hover Effects (jQuery)
    // Adds a shadow class on hover for nav links and buttons
    $('.nav-link, .btn').hover(
        function() {
            $(this).addClass('shadow-sm');
        }, function() {
            $(this).removeClass('shadow-sm');
        }
    );

    // 2. Dynamic Form Inputs
    // Mapping of services to doctors
    const doctorsByService = {
        'general Consultation': ['Dr. Ramzi Bouden', 'Dr. Alice Williams'],
        'pediatric Consultation': ['Dr. Sarah Lee', 'Dr. Mark Davis'],
        'Cardiology': ['Dr. Youcef Soukkou', 'Dr. Yahia Bouaziz'],
        'Medical Imaging': ['Dr. Zakaria Elhess', 'Dr. Linda Taylor'],
        'Dermatology': ['Dr. Sarah Lee', 'Dr. Robert Brown'],
        'Laboratory Tests': ['Dr. Hichem Hamouda'],
        'Emergency Care': ['Dr.  Islem Boudouda', 'Dr. Ramzi Boutouil'],
        'Gynecology & Obstetrics': ['Dr. Aymen Zouaghi', 'Dr.Amira Azri']
    };

    $('#service').change(function() {
        const selectedService = $(this).val();
        
        // Show/Hide Preferred Time Slot based on 'Special Consultation'
        if (selectedService === 'special') {
            $('#timeSlotContainer').removeClass('d-none');
            $('#time').prop('required', true);
        } else {
            $('#timeSlotContainer').addClass('d-none');
            $('#time').prop('required', false);
            $('#time').val(''); // Clear value when hidden
        }

        // Update Doctor List dynamically
        const doctorSelect = $('#doctor');
        doctorSelect.empty();
        doctorSelect.append('<option value="" selected disabled>Select a doctor</option>');

        if (doctorsByService[selectedService]) {
            doctorsByService[selectedService].forEach(function(doctor) {
                doctorSelect.append(`<option value="${doctor}">${doctor}</option>`);
            });
        }
    }); 

    // 3. Age Calculation
    $('#birthdate').change(function() {
        const birthdateVal = $(this).val();
        if (!birthdateVal) return;

        const birthdate = new Date(birthdateVal);
        const today = new Date();
        let age = today.getFullYear() - birthdate.getFullYear();
        const m = today.getMonth() - birthdate.getMonth();
        
        // Adjust age if birthday hasn't occurred yet this year
        if (m < 0 || (m === 0 && today.getDate() < birthdate.getDate())) {
            age--;
        }

        if (!isNaN(age) && age >= 0) {
            $('#ageDisplay').text(`Age: ${age} years old`);
        } else {
            $('#ageDisplay').text('');
        }
    });

    // 4. Form Validation
    $('#appointmentForm').submit(function(event) {
        // Don't prevent default - allow form to submit to PHP after validation
        event.stopPropagation();

        let isValid = true;
        const form = $(this);

        // Reset previous validation states
        form.find('.form-control, .form-select').removeClass('is-invalid is-valid');

        // Helper function to validate a field
        function validateField(selector, condition) {
            const field = $(selector);
            if (condition) {
                field.addClass('is-valid');
                return true;
            } else {
                field.addClass('is-invalid');
                return false;
            }
        }

        // Validate Required Fields
        isValid &= validateField('#firstName', $('#firstName').val().trim() !== '');
        isValid &= validateField('#lastName', $('#lastName').val().trim() !== '');
        isValid &= validateField('#birthdate', $('#birthdate').val() !== '');
        isValid &= validateField('#gender', $('#gender').val() !== null);
        isValid &= validateField('#service', $('#service').val() !== null);
        isValid &= validateField('#date', $('#date').val() !== '');

        // Validate Email (Basic Regex)
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        isValid &= validateField('#email', emailRegex.test($('#email').val()));

        // Validate Phone (Exactly 10 digits)
        const phoneRegex = /^\d{10}$/;
        isValid &= validateField('#phone', phoneRegex.test($('#phone').val()));

        // Validate Time Slot if it is visible
        if (!$('#timeSlotContainer').hasClass('d-none')) {
            isValid &= validateField('#time', $('#time').val() !== '');
        }

        if (!isValid) {
            // If invalid, prevent submission and scroll to the first error
            event.preventDefault();
            $('html, body').animate({
                scrollTop: $(".is-invalid").first().offset().top - 100
            }, 500);
        }
        // If valid, form will submit naturally to process_appointment.php
    });
});
