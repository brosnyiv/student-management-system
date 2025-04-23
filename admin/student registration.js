// DOM Elements
        const form = document.getElementById('studentRegistration');
        const sections = document.querySelectorAll('.section');
        const progressSteps = document.querySelectorAll('.progress-step');
        const nextButtons = document.querySelectorAll('.btn-next');
        const prevButtons = document.querySelectorAll('.btn-prev');
        const progressBar = document.getElementById('progressBar');
        const toast = document.getElementById('toast');
        const toastClose = document.querySelector('.toast-close');
        const autosaveIndicator = document.getElementById('autosaveIndicator');
        
        // Progress bar functionality
        function updateProgressBar(step) {
            // Reset all steps
            progressSteps.forEach((stepEl) => {
                stepEl.classList.remove('active', 'completed');
            });
            
            // Update current and previous steps
            for (let i = 0; i < progressSteps.length; i++) {
                if (i < step) {
                    progressSteps[i].classList.add('completed');
                } else if (i === step - 1) {
                    progressSteps[i].classList.add('active');
                }
            }
            
            // Update progress bar fill
            const progressPercentage = ((step - 1) / (progressSteps.length - 1)) * 100;
            progressBar.style.setProperty('--progress', `${progressPercentage}%`);
            document.querySelector('.progress-bar').style.setProperty('width', `${progressPercentage}%`);
        }
        
        // Navigation between sections
        function showSection(sectionNumber) {
            sections.forEach(section => {
                section.classList.remove('active');
            });
            
            const targetSection = document.querySelector(`.section[data-section="${sectionNumber}"]`);
            if (targetSection) {
                targetSection.classList.add('active');
                updateProgressBar(sectionNumber);
                window.scrollTo(0, 0);
            }
        }
        
        // Event listeners for next/prev buttons
        nextButtons.forEach(button => {
            button.addEventListener('click', () => {
                const currentSection = parseInt(button.closest('.section').dataset.section);
                const nextSection = parseInt(button.dataset.next);
                
                // Validate current section before proceeding
                if (validateSection(currentSection)) {
                    showSection(nextSection);
                }
            });
        });
        
        prevButtons.forEach(button => {
            button.addEventListener('click', () => {
                const prevSection = parseInt(button.dataset.prev);
                showSection(prevSection);
            });
        });
        
        // Form validation
        function validateSection(sectionNumber) {
            const currentSection = document.querySelector(`.section[data-section="${sectionNumber}"]`);
            const requiredFields = currentSection.querySelectorAll('[required]');
            let isValid = true;
            
            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    showError(field);
                    isValid = false;
                } else {
                    hideError(field);
                }
                
                // Email validation
                if (field.type === 'email' && field.value.trim()) {
                    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                    if (!emailRegex.test(field.value)) {
                        showError(field);
                        isValid = false;
                    }
                }
                
                // Phone validation (simple check)
                if (field.type === 'tel' && field.value.trim()) {
                    const phoneRegex = /^[\d\s\+\-\(\)]{10,20}$/;
                    if (!phoneRegex.test(field.value)) {
                        showError(field);
                        isValid = false;
                    }
                }
            });
            
            if (!isValid) {
                showToast('Please fill in all required fields correctly', 'error');
            }
            
            return isValid;
        }
        
        function showError(field) {
            field.classList.add('error');
            const errorElement = document.getElementById(field.id + 'Error');
            if (errorElement) {
                errorElement.style.display = 'block';
            }
        }
        
        function hideError(field) {
            field.classList.remove('error');
            field.classList.add('valid');
            const errorElement = document.getElementById(field.id + 'Error');
            if (errorElement) {
                errorElement.style.display = 'none';
            }
        }
        
        // Toast notification
        function showToast(message, type = 'info') {
            const toastMessage = toast.querySelector('.toast-message');
            const toastIcon = toast.querySelector('.toast-icon');
            
            toast.className = 'toast show ' + type;
            toastMessage.textContent = message;
            
            // Set icon based on type
            if (type === 'success') {
                toastIcon.textContent = '✓';
            } else if (type === 'error') {
                toastIcon.textContent = '⚠';
            } else {
                toastIcon.textContent = 'ℹ';
            }
            
            // Auto-hide after 5 seconds
            setTimeout(() => {
                toast.classList.remove('show');
            }, 5000);
        }
        
        toastClose.addEventListener('click', () => {
            toast.classList.remove('show');
        });
        
        // File upload preview
        document.querySelectorAll('input[type="file"]').forEach(fileInput => {
            fileInput.addEventListener('change', function() {
                const previewId = this.id + 'Preview';
                const previewContainer = document.getElementById(previewId);
                
                if (this.files && this.files[0]) {
                    const file = this.files[0];
                    const reader = new FileReader();
                    
                    reader.onload = function(e) {
                        const previewImg = previewContainer.querySelector('img');
                        const fileName = previewContainer.querySelector('.file-name');
                        
                        if (file.type.startsWith('image/')) {
                            previewImg.src = e.target.result;
                            previewImg.style.display = 'block';
                        } else {
                            previewImg.style.display = 'none';
                        }
                        
                        fileName.textContent = file.name;
                        previewContainer.style.display = 'block';
                    };
                    
                    reader.readAsDataURL(file);
                }
            });
            
            const previewContainer = document.getElementById(fileInput.id + 'Preview');
            if (previewContainer) {
                const removeButton = previewContainer.querySelector('.file-remove');
                if (removeButton) {
                    removeButton.addEventListener('click', function() {
                        fileInput.value = '';
                                    // Continue from where the code was cut off

                        fileInput.value = '';
                        previewContainer.style.display = 'none';
                        const previewImg = previewContainer.querySelector('img');
                        if (previewImg) {
                            previewImg.src = '';
                        }
                        const fileName = previewContainer.querySelector('.file-name');
                        if (fileName) {
                            fileName.textContent = '';
                        }
                    });
                }
            }
        });
        
        // Payment method dependent fields
        const paymentMethodSelect = document.getElementById('paymentMethod');
        if (paymentMethodSelect) {
            paymentMethodSelect.addEventListener('change', function() {
                // Hide all payment specific sections first
                document.getElementById('creditCardInfo').style.display = 'none';
                document.getElementById('cardDetails').style.display = 'none';
                document.getElementById('bankInfo').style.display = 'none';
                document.getElementById('scholarshipInfo').style.display = 'none';
                
                // Show relevant section based on selection
                const selectedMethod = this.value;
                if (selectedMethod === 'credit' || selectedMethod === 'debit') {
                    document.getElementById('creditCardInfo').style.display = 'flex';
                    document.getElementById('cardDetails').style.display = 'flex';
                } else if (selectedMethod === 'bank') {
                    document.getElementById('bankInfo').style.display = 'flex';
                } else if (selectedMethod === 'scholarship') {
                    document.getElementById('scholarshipInfo').style.display = 'block';
                }
            });
        }
        
        // Billing address same as contact address toggle
        const sameAddressCheckbox = document.getElementById('sameAddress');
        if (sameAddressCheckbox) {
            sameAddressCheckbox.addEventListener('change', function() {
                const billingAddressSection = document.getElementById('billingAddressSection');
                if (this.checked) {
                    billingAddressSection.style.display = 'none';
                    // Clear validation requirements when using same address
                    const billingFields = billingAddressSection.querySelectorAll('input, textarea');
                    billingFields.forEach(field => {
                        field.removeAttribute('required');
                    });
                } else {
                    billingAddressSection.style.display = 'block';
                    // Add validation requirements when using different address
                    const billingFields = billingAddressSection.querySelectorAll('input, textarea');
                    billingFields.forEach(field => {
                        if (field.classList.contains('required')) {
                            field.setAttribute('required', '');
                        }
                    });
                }
            });
        }
        
        // Form submission
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Validate all sections before submission
            let isValid = true;
            for (let i = 1; i <= sections.length; i++) {
                if (!validateSection(i)) {
                    isValid = false;
                    showSection(i); // Go to the first invalid section
                    break;
                }
            }
            
            if (isValid) {
                // Simulate form submission (in a real app, this would send data to a server)
                simulateFormSubmission();
            }
        });
        
        function simulateFormSubmission() {
            const submitButton = document.getElementById('submitButton');
            submitButton.disabled = true;
            submitButton.textContent = 'Submitting...';
            
            // Show loading state
            showAutosaveIndicator('Submitting...');
            
            // Simulate server delay
            setTimeout(() => {
                hideAutosaveIndicator();
                submitButton.disabled = false;
                submitButton.textContent = 'Submit Registration';
                
                // Show success message
                showToast('Registration submitted successfully!', 'success');
                
                // In a real app, you would redirect to a confirmation page or clear the form
                // For demo purposes, just reset the form and go to first section
                // form.reset();
                // showSection(1);
            }, 2000);
        }
        
        // Form reset confirmation
        const resetButton = document.getElementById('resetButton');
        if (resetButton) {
            resetButton.addEventListener('click', function(e) {
                e.preventDefault();
                if (confirm('Are you sure you want to clear all form data? This action cannot be undone.')) {
                    form.reset();
                    // Reset all file upload previews
                    document.querySelectorAll('.file-preview').forEach(preview => {
                        preview.style.display = 'none';
                    });
                    // Go back to first section
                    showSection(1);
                    showToast('Form has been cleared', 'info');
                }
            });
        }
        
        // Autosave functionality
        let autosaveTimeout;
        const AUTOSAVE_DELAY = 3000; // 3 seconds
        
        function setupAutosave() {
            const formInputs = form.querySelectorAll('input, select, textarea');
            formInputs.forEach(input => {
                input.addEventListener('change', triggerAutosave);
                if (input.type !== 'file') {
                    input.addEventListener('input', triggerAutosave);
                }
            });
        }
        
        function triggerAutosave() {
            clearTimeout(autosaveTimeout);
            showAutosaveIndicator();
            
            autosaveTimeout = setTimeout(() => {
                saveFormData();
                hideAutosaveIndicator();
            }, AUTOSAVE_DELAY);
        }
        
        function showAutosaveIndicator(text = 'Saving...') {
            const indicator = document.getElementById('autosaveIndicator');
            const indicatorText = indicator.querySelector('.autosave-text');
            indicatorText.textContent = text;
            indicator.classList.add('show');
        }
        
        function hideAutosaveIndicator() {
            const indicator = document.getElementById('autosaveIndicator');
            indicator.classList.remove('show');
        }
        
        function saveFormData() {
            // In a real application, this would send the data to the server
            // For demo purposes, we'll just save to localStorage
            const formData = new FormData(form);
            const formDataObj = {};
            
            formData.forEach((value, key) => {
                // Skip file inputs for localStorage (they can't be serialized this way)
                if (!(value instanceof File)) {
                    formDataObj[key] = value;
                }
            });
            
            try {
                localStorage.setItem('studentRegistrationData', JSON.stringify(formDataObj));
            } catch (e) {
                console.error('Error saving form data', e);
            }
        }
        
        function loadFormData() {
            try {
                const savedData = localStorage.getItem('studentRegistrationData');
                if (savedData) {
                    const formDataObj = JSON.parse(savedData);
                    
                    // Populate form fields
                    Object.keys(formDataObj).forEach(key => {
                        const field = form.elements[key];
                        if (field) {
                            if (field.type === 'checkbox') {
                                field.checked = formDataObj[key] === 'on';
                            } else {
                                field.value = formDataObj[key];
                            }
                        }
                    });
                    
                    // Trigger change events for select fields with dependencies
                    const paymentMethod = document.getElementById('paymentMethod');
                    if (paymentMethod && paymentMethod.value) {
                        paymentMethod.dispatchEvent(new Event('change'));
                    }
                    
                    const sameAddress = document.getElementById('sameAddress');
                    if (sameAddress) {
                        sameAddress.dispatchEvent(new Event('change'));
                    }
                    
                    showToast('Restored your previously saved information', 'info');
                }
            } catch (e) {
                console.error('Error loading form data', e);
            }
        }
        
        // Form input validation on typing/change
        function setupFormValidation() {
            const allInputs = form.querySelectorAll('input, select, textarea');
            
            allInputs.forEach(input => {
                if (input.hasAttribute('required')) {
                    input.addEventListener('blur', function() {
                        if (!this.value.trim()) {
                            showError(this);
                        } else {
                            hideError(this);
                        }
                        
                        // Special validation for email
                        if (this.type === 'email' && this.value.trim()) {
                            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                            if (!emailRegex.test(this.value)) {
                                showError(this);
                            }
                        }
                        
                        // Special validation for phone
                        if (this.type === 'tel' && this.value.trim()) {
                            const phoneRegex = /^[\d\s\+\-\(\)]{10,20}$/;
                            if (!phoneRegex.test(this.value)) {
                                showError(this);
                            }
                        }
                    });
                }
            });
        }
        
        // Format credit card number with spaces
        const cardNumberInput = document.getElementById('cardNumber');
        if (cardNumberInput) {
            cardNumberInput.addEventListener('input', function() {
                let value = this.value.replace(/\D/g, '');
                let formattedValue = '';
                
                for (let i = 0; i < value.length; i++) {
                    if (i > 0 && i % 4 === 0) {
                        formattedValue += ' ';
                    }
                    formattedValue += value[i];
                }
                
                this.value = formattedValue;
            });
        }
        
        // Format expiry date as MM/YY
        const expiryDateInput = document.getElementById('expiryDate');
        if (expiryDateInput) {
            expiryDateInput.addEventListener('input', function() {
                let value = this.value.replace(/\D/g, '');
                
                if (value.length > 2) {
                    this.value = value.substring(0, 2) + '/' + value.substring(2, 4);
                } else {
                    this.value = value;
                }
            });
        }
        
        // Initialize form
        function initForm() {
            setupAutosave();
            setupFormValidation();
            loadFormData();
            
            // Start at section 1
            showSection(1);
        }
        
        // Start the application when DOM is fully loaded
        document.addEventListener('DOMContentLoaded', initForm);