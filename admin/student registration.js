// student registration.js
document.addEventListener('DOMContentLoaded', function() {
    // Form submission handler
    const form = document.getElementById('studentRegistration');
    form.addEventListener('submit', function(e) {
        // Let the form submit naturally to your database
        // The preventDefault has been removed to allow normal form submission
        
        // Form will now submit to the action URL specified in the form: submit_student.php
    });

    // Progress bar update function
    function updateProgressBar(currentStep) {
        const steps = document.querySelectorAll('.progress-step');
        
        steps.forEach(step => {
            const stepNumber = parseInt(step.getAttribute('data-step'));
            
            if (stepNumber < currentStep) {
                step.classList.add('completed');
                step.classList.remove('active');
            } else if (stepNumber === currentStep) {
                step.classList.add('active');
                step.classList.remove('completed');
            } else {
                step.classList.remove('active', 'completed');
            }
        });
    }

    // Navigation between form sections
    document.querySelectorAll('.btn-next').forEach(button => {
        button.addEventListener('click', function() {
            const nextSection = this.getAttribute('data-next');
            navigateToSection(nextSection);
        });
    });

    document.querySelectorAll('.btn-prev').forEach(button => {
        button.addEventListener('click', function() {
            const prevSection = this.getAttribute('data-prev');
            navigateToSection(prevSection);
        });
    });

    function navigateToSection(sectionNumber) {
        // Hide all sections
        document.querySelectorAll('.section').forEach(section => {
            section.style.display = 'none';
            section.classList.remove('active');
        });
        
        // Show target section
        const targetSection = document.querySelector(`[data-section="${sectionNumber}"]`);
        targetSection.style.display = 'block';
        targetSection.classList.add('active');
        
        // Update progress bar
        updateProgressBar(sectionNumber);
    }

    // File upload preview functionality
    document.querySelectorAll('input[type="file"]').forEach(input => {
        input.addEventListener('change', function() {
            const previewId = this.id + 'Preview';
            const preview = document.getElementById(previewId);
            
            if (this.files && this.files[0]) {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    if (preview.querySelector('img')) {
                        preview.querySelector('img').src = e.target.result;
                    }
                    preview.querySelector('.file-name').textContent = input.files[0].name;
                    preview.style.display = 'flex';
                }
                
                if (this.files[0].type.startsWith('image/')) {
                    reader.readAsDataURL(this.files[0]);
                } else {
                    preview.querySelector('.file-name').textContent = input.files[0].name;
                    preview.style.display = 'flex';
                }
            }
        });
    });

    // File remove functionality
    document.querySelectorAll('.file-remove').forEach(button => {
        button.addEventListener('click', function() {
            const preview = this.closest('.file-preview');
            const inputId = preview.id.replace('Preview', '');
            const input = document.getElementById(inputId);
            
            input.value = '';
            if (preview.querySelector('img')) {
                preview.querySelector('img').src = '';
            }
            preview.querySelector('.file-name').textContent = '';
            preview.style.display = 'none';
        });
    });

    // Payment method selection handler
    document.getElementById('paymentMethod').addEventListener('change', function() {
        const method = this.value;
        
        // Hide all payment info sections
        document.getElementById('creditCardInfo').style.display = 'none';
        document.getElementById('cardDetails').style.display = 'none';
        document.getElementById('bankInfo').style.display = 'none';
        document.getElementById('scholarshipInfo').style.display = 'none';
        
        // Show relevant section based on selection
        if (method === 'credit' || method === 'debit') {
            document.getElementById('creditCardInfo').style.display = 'flex';
            document.getElementById('cardDetails').style.display = 'flex';
        } else if (method === 'bank') {
            document.getElementById('bankInfo').style.display = 'flex';
        } else if (method === 'scholarship') {
            document.getElementById('scholarshipInfo').style.display = 'block';
        }
    });

    // Billing address toggle handler
    document.getElementById('sameAddress').addEventListener('change', function() {
        document.getElementById('billingAddressSection').style.display = 
            this.checked ? 'none' : 'block';
    });
});