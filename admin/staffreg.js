
 // Wait for DOM to fully load
document.addEventListener('DOMContentLoaded', function() {
    // Form multi-step functionality
    const formSections = document.querySelectorAll('.form-section');
    const formSteps = document.querySelectorAll('.step');
    const submitFormBtn = document.getElementById('submitForm');
    let currentStep = 0;
    
    // Create navigation buttons if they don't exist
    const actionRow = document.querySelector('.action-row');
    if (actionRow) {
        if (!document.getElementById('prevStep')) {
            const prevStepBtn = document.createElement('button');
            prevStepBtn.id = 'prevStep';
            prevStepBtn.className = 'btn btn-secondary';
            prevStepBtn.textContent = 'Previous';
            prevStepBtn.type = 'button';
            actionRow.insertBefore(prevStepBtn, actionRow.firstChild);
        }
        
        if (!document.getElementById('nextStep')) {
            const nextStepBtn = document.createElement('button');
            nextStepBtn.id = 'nextStep';
            nextStepBtn.className = 'btn btn-primary';
            nextStepBtn.textContent = 'Next';
            nextStepBtn.type = 'button';
            actionRow.insertBefore(nextStepBtn, actionRow.querySelector('div'));
        }
    }
    
    const prevStepBtn = document.getElementById('prevStep');
    const nextStepBtn = document.getElementById('nextStep');

    // Initialize form steps
    function updateFormSteps() {
        formSections.forEach((section, index) => {
            if (index < currentStep * 2 || index > currentStep * 2 + 1) {
                section.style.display = 'none';
            } else {
                section.style.display = 'block';
            }
        });
        
        // Update step indicators
        formSteps.forEach((step, index) => {
            if (index <= currentStep) {
                step.classList.add('active');
            } else {
                step.classList.remove('active');
            }
        });
        
        // Show/hide buttons
        if (currentStep === 0) {
            prevStepBtn.style.display = 'none';
        } else {
            prevStepBtn.style.display = 'inline-block';
        }
        
        if (currentStep === formSteps.length - 1) {
            nextStepBtn.style.display = 'none';
            submitFormBtn.style.display = 'inline-block';
        } else {
            nextStepBtn.style.display = 'inline-block';
            submitFormBtn.style.display = 'none';
        }
    }
    
    // Call initially to set up form
    updateFormSteps();
    
    // Add event listeners for navigation buttons
    if (prevStepBtn) {
        prevStepBtn.addEventListener('click', function() {
            if (currentStep > 0) {
                currentStep--;
                updateFormSteps();
                window.scrollTo(0, 0);
            }
        });
    }
    
    if (nextStepBtn) {
        nextStepBtn.addEventListener('click', function() {
            // Validate current section before moving on
            const currentSections = [];
            formSections.forEach((section, index) => {
                if (index >= currentStep * 2 && index <= currentStep * 2 + 1) {
                    currentSections.push(section);
                }
            });
            
            let isValid = true;
            currentSections.forEach(section => {
                const requiredFields = section.querySelectorAll('[required]');
                requiredFields.forEach(field => {
                    if (!field.value && field.type !== 'file' && field.type !== 'checkbox') {
                        field.classList.add('invalid');
                        isValid = false;
                    } else if (field.type === 'checkbox' && !field.checked) {
                        field.classList.add('invalid');
                        isValid = false;
                    } else if (field.type === 'file' && field.required && !field.files.length) {
                        field.parentElement.classList.add('invalid');
                        isValid = false;
                    } else {
                        field.classList.remove('invalid');
                        if (field.type === 'file') {
                            field.parentElement.classList.remove('invalid');
                        }
                    }
                });
            });
            
            if (isValid) {
                if (currentStep < formSteps.length - 1) {
                    currentStep++;
                    updateFormSteps();
                    window.scrollTo(0, 0);
                }
            } else {
                alert('Please fill in all required fields before proceeding.');
            }
        });
    }

    // Handle file uploads with better error handling
    function setupFileUpload(uploadEl, inputEl) {
        if (!uploadEl || !inputEl) return;
        
        uploadEl.addEventListener('click', function() {
            inputEl.click();
        });
        
        inputEl.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                const file = this.files[0];
                
                // Validate file size
                const maxSize = this.getAttribute('data-max-size') || 5 * 1024 * 1024; // Default 5MB
                if (file.size > maxSize) {
                    alert(`File is too large. Maximum size is ${maxSize / (1024 * 1024)}MB.`);
                    this.value = '';
                    return;
                }
                
                // Validate file type
                const acceptedTypes = this.accept.split(',');
                let isValidType = false;
                for (let type of acceptedTypes) {
                    if (file.type.match(type.replace('*', '.*')) || 
                        file.name.endsWith(type.replace('.', '').trim())) {
                        isValidType = true;
                        break;
                    }
                }
                
                if (!isValidType && this.accept) {
                    alert(`Invalid file type. Please upload: ${this.accept}`);
                    this.value = '';
                    return;
                }
                
                // Update UI
                const fileName = file.name;
                uploadEl.innerHTML = `
                    <div class="upload-icon">✓</div>
                    <div>${fileName}</div>
                    <div class="help-text">File selected</div>
                `;
                uploadEl.classList.add('file-selected');
                uploadEl.classList.remove('invalid');
            }
        });
    }
    
    // Initialize all file uploads
    document.querySelectorAll('.image-upload').forEach(upload => {
        const input = upload.querySelector('input[type="file"]');
        setupFileUpload(upload, input);
    });

    // Add another qualification with improved error handling
    const qualificationsContainer = document.getElementById('qualifications-container');
    const addQualificationBtn = document.getElementById('addQualification');

    if (addQualificationBtn && qualificationsContainer) {
        addQualificationBtn.addEventListener('click', function() {
            try {
                const qualificationCount = qualificationsContainer.querySelectorAll('.qualification-row').length;
                const newRow = document.createElement('div');
                newRow.className = 'qualification-row';
                
                
                
                qualificationsContainer.appendChild(newRow);
                
                // Add event listeners for the new file upload
                const newUpload = newRow.querySelector('.image-upload');
                const newInput = newRow.querySelector('input[type="file"]');
                setupFileUpload(newUpload, newInput);
                
                // Add event listener for remove button
                const removeBtn = newRow.querySelector('.remove-row');
                removeBtn.addEventListener('click', function() {
                    qualificationsContainer.removeChild(newRow);
                });
            } catch (error) {
                console.error('Error adding qualification:', error);
                alert('An error occurred while adding a new qualification.');
            }
        });
    }

    // Add employment field with proper validation
    const employmentFieldsContainer = document.getElementById('employment-additional-fields');
    const addEmploymentFieldBtn = document.getElementById('addEmploymentField');

    if (addEmploymentFieldBtn && employmentFieldsContainer) {
        addEmploymentFieldBtn.addEventListener('click', function() {
            try {
                const fieldCount = employmentFieldsContainer.querySelectorAll('.additional-field-row').length;
                
                const fieldName = prompt('Enter field name:');
                if (fieldName && fieldName.trim()) {
                    const sanitizedFieldName = fieldName.replace(/[^a-zA-Z0-9 _-]/g, '');
                    
                    const newRow = document.createElement('div');
                    newRow.className = 'additional-field-row';
                    
                    newRow.innerHTML = `
                        <div class="form-row">
                            <div class="form-group">
                                <label for="additionalField${fieldCount + 1}">${sanitizedFieldName}</label>
                                <input type="text" id="additionalField${fieldCount + 1}" name="additionalFields[${fieldCount}][value]">
                                <input type="hidden" name="additionalFields[${fieldCount}][name]" value="${sanitizedFieldName}">
                            </div>
                            <button type="button" class="remove-field-btn">Remove</button>
                        </div>
                    `;
                    
                    employmentFieldsContainer.appendChild(newRow);
                    
                    // Add event listener for remove button
                    const removeBtn = newRow.querySelector('.remove-field-btn');
                    removeBtn.addEventListener('click', function() {
                        employmentFieldsContainer.removeChild(newRow);
                    });
                } else if (fieldName !== null) {
                    // Only show error if user didn't cancel
                    alert('Please enter a valid field name.');
                }
            } catch (error) {
                console.error('Error adding employment field:', error);
                alert('An error occurred while adding a new field.');
            }
        });
    }

    // Add document upload with improved structure
    const documentsContainer = document.getElementById('documents-container');
    const addDocumentBtn = document.getElementById('addDocument');

    if (addDocumentBtn && documentsContainer) {
        addDocumentBtn.addEventListener('click', function() {
            try {
                const documentCount = documentsContainer.querySelectorAll('.document-row').length;
                
                const newRow = document.createElement('div');
                newRow.className = 'document-row';
                
                newRow.innerHTML = `
                    <button type="button" class="remove-row">×</button>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="document${documentCount + 1}" class="required">Document</label>
                            <div class="image-upload" id="documentUpload${documentCount + 1}">
                                <div class="upload-icon">📄</div>
                                <div>Click to upload document</div>
                                <div class="help-text">PDF, JPEG or PNG, max 5MB</div>
                                <input type="file" id="document${documentCount + 1}" name="documents[${documentCount}][file]" accept=".pdf,.jpg,.jpeg,.png" style="display: none;" data-max-size="5242880" required>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="documentType${documentCount + 1}" class="required">Document Type</label>
                            <select id="documentType${documentCount + 1}" name="documents[${documentCount}][type]" required>
                                <option value="">Select document type</option>
                                <option value="employment">Employment Contract</option>
                                <option value="certificate">Academic Certificate</option>
                                <option value="reference">Reference Letter</option>
                                <option value="medical">Medical Certificate</option>
                                <option value="other">Other Document</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="documentDescription${documentCount + 1}">Document Description</label>
                            <input type="text" id="documentDescription${documentCount + 1}" name="documents[${documentCount}][description]">
                        </div>
                        
                        <div class="form-group">
                            <label for="documentExpiry${documentCount + 1}">Expiry Date (if applicable)</label>
                            <input type="date" id="documentExpiry${documentCount + 1}" name="documents[${documentCount}][expiry]">
                        </div>
                    </div>
                `;
                
                documentsContainer.appendChild(newRow);
                
                // Add event listeners for the new file upload
                const newUpload = newRow.querySelector('.image-upload');
                const newInput = newRow.querySelector('input[type="file"]');
                setupFileUpload(newUpload, newInput);
                
                // Add event listener for remove button
                const removeBtn = newRow.querySelector('.remove-row');
                removeBtn.addEventListener('click', function() {
                    documentsContainer.removeChild(newRow);
                });
            } catch (error) {
                console.error('Error adding document:', error);
                alert('An error occurred while adding a new document.');
            }
        });
    }

    // Form submission with improved validation and error handling
    const staffRegistrationForm = document.getElementById('staffRegistrationForm');
    const saveAsDraftBtn = document.getElementById('saveAsDraft');

    if (staffRegistrationForm) {
        staffRegistrationForm.addEventListener('submit', function(e) {
            e.preventDefault();
            console.log("Form submission triggered");
            
            // Validate form before submission
            const requiredFields = document.querySelectorAll('[required]');
            let isValid = true;
            let firstInvalidField = null;
            
            requiredFields.forEach(field => {
                if (!field.value && field.type !== 'file' && field.type !== 'checkbox') {
                    field.classList.add('invalid');
                    isValid = false;
                    if (!firstInvalidField) firstInvalidField = field;
                    console.log("Invalid field:", field.id);
                } else if (field.type === 'checkbox' && !field.checked) {
                    field.classList.add('invalid');
                    isValid = false;
                    if (!firstInvalidField) firstInvalidField = field;
                } else if (field.type === 'file' && !field.files.length) {
                    field.parentElement.classList.add('invalid');
                    isValid = false;
                    if (!firstInvalidField) firstInvalidField = field;
                } else {
                    field.classList.remove('invalid');
                    if (field.type === 'file') {
                        field.parentElement.classList.remove('invalid');
                    }
                }
            });
            
            // Additional validation for password matching
            const password = document.getElementById('password');
            const confirmPassword = document.getElementById('confirmPassword');
            if (password && confirmPassword && password.value !== confirmPassword.value) {
                confirmPassword.classList.add('invalid');
                isValid = false;
                if (!firstInvalidField) firstInvalidField = confirmPassword;
                alert('Passwords do not match!');
            }
            
            if (isValid) {
                console.log("Form is valid, preparing to submit");
                
                // Disable submit button to prevent multiple submissions
                const submitBtn = document.getElementById('submitForm');
                if (submitBtn) {
                    submitBtn.disabled = true;
                    submitBtn.textContent = 'Submitting...';
                }
                
                // Collect form data
                const formData = new FormData(this);
                
                // Log formData keys for debugging
                for (let key of formData.keys()) {
                    console.log("FormData includes key:", key);
                }
                
                // Submit via AJAX with better error handling
                fetch('submit_staff.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => {
                    console.log("Response status:", response.status);
                    
                    if (!response.ok) {
                        throw new Error(`Server responded with status ${response.status}`);
                    }
                    
                    return response.json();
                })
                .then(data => {
                    console.log("Server response:", data);
                    if (data.success) {
                        alert('Registration submitted successfully! Staff ID: ' + data.staff_id);
                        // Redirect to confirmation page or clear form
                        window.location.href = 'confirmation.html?staff_id=' + data.staff_id;
                    } else {
                        alert('Error: ' + (data.message || 'Unknown error occurred'));
                        if (submitBtn) {
                            submitBtn.disabled = false;
                            submitBtn.textContent = 'Submit Registration';
                        }
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while submitting the form: ' + error.message);
                    
                    if (submitBtn) {
                        submitBtn.disabled = false;
                        submitBtn.textContent = 'Submit Registration';
                    }
                });
            } else {
                alert('Please fill in all required fields before submitting.');
                
                // Scroll to the first invalid field
                if (firstInvalidField) {
                    firstInvalidField.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    setTimeout(() => {
                        firstInvalidField.focus();
                    }, 500);
                }
            }
        });
    }

    // Save as draft functionality with localStorage
    if (saveAsDraftBtn) {
        saveAsDraftBtn.addEventListener('click', function() {
            try {
                const formData = new FormData(document.getElementById('staffRegistrationForm'));
                const formDataObj = {};
                
                // Convert FormData to object (excluding file inputs)
                for (let [key, value] of formData.entries()) {
                    if (typeof value !== 'object') { // Skip file inputs
                        formDataObj[key] = value;
                    }
                }
                
                // Save to localStorage with timestamp
                const timestamp = new Date().toISOString();
                const draftData = {
                    data: formDataObj,
                    timestamp: timestamp,
                    formId: 'staffRegistration'
                };
                
                localStorage.setItem('staffRegistrationDraft', JSON.stringify(draftData));
                
                alert('Your registration has been saved as draft. You can continue later.');
            } catch (error) {
                console.error('Error saving draft:', error);
                alert('An error occurred while saving your draft.');
            }
        });
        
        // Load draft data if available
        try {
            const savedDraft = localStorage.getItem('staffRegistrationDraft');
            if (savedDraft) {
                const draftData = JSON.parse(savedDraft);
                
                if (draftData && draftData.formId === 'staffRegistration') {
                    const loadDraft = confirm(`You have a saved draft from ${new Date(draftData.timestamp).toLocaleString()}. Would you like to load it?`);
                    
                    if (loadDraft) {
                        const data = draftData.data;
                        
                        // Fill form fields with saved data
                        for (let key in data) {
                            const field = document.querySelector(`[name="${key}"]`);
                            if (field) {
                                if (field.type === 'checkbox') {
                                    field.checked = data[key] === 'on';
                                } else {
                                    field.value = data[key];
                                }
                            }
                        }
                        
                        alert('Draft loaded successfully.');
                    }
                }
            }
        } catch (error) {
            console.error('Error loading draft:', error);
            // Don't alert the user - just continue with empty form
        }
    }

    // Improved event handlers for existing remove buttons
    document.querySelectorAll('.remove-row').forEach(button => {
        button.addEventListener('click', function() {
            const parentContainer = this.closest('.qualification-row, .document-row');
            const containerType = parentContainer.classList.contains('qualification-row') ? 'qualification' : 'document';
            const parentWrapper = containerType === 'qualification' ? qualificationsContainer : documentsContainer;
            
            if (parentWrapper && parentContainer) {
                const allRows = parentWrapper.querySelectorAll(`.${containerType}-row`);
                
                if (allRows.length > 1) {
                    parentWrapper.removeChild(parentContainer);
                } else {
                    alert(`At least one ${containerType} is required.`);
                }
            }
        });
    });

    // Improved password validation
    const password = document.getElementById('password');
    const confirmPassword = document.getElementById('confirmPassword');

    if (password && confirmPassword) {
        // Validate password strength
        password.addEventListener('input', function() {
            const value = this.value;
            const minLength = 8;
            const hasNumber = /\d/.test(value);
            const hasSpecial = /[!@#$%^&*(),.?":{}|<>]/.test(value);
            
            let isStrong = value.length >= minLength && hasNumber && hasSpecial;
            
            if (!isStrong && value) {
                this.classList.add('weak-password');
                let message = 'Password must ';
                let requirements = [];
                
                if (value.length < minLength) requirements.push(`be at least ${minLength} characters`);
                if (!hasNumber) requirements.push('contain at least one number');
                if (!hasSpecial) requirements.push('contain at least one special character');
                
                message += requirements.join(', ');
                this.setCustomValidity(message);
            } else {
                this.classList.remove('weak-password');
                this.setCustomValidity('');
            }
        });
        
        // Check if passwords match
        confirmPassword.addEventListener('input', function() {
            if (password.value !== this.value) {
                this.setCustomValidity('Passwords do not match');
                this.classList.add('invalid');
            } else {
                this.setCustomValidity('');
                this.classList.remove('invalid');
            }
        });
    }

    // Initialize date fields with current date
    const today = new Date().toISOString().split('T')[0];
    const dateField = document.getElementById('date');
    if (dateField) {
        dateField.value = today;
    }
    
    // Add custom validation for email fields
    const emailFields = document.querySelectorAll('input[type="email"]');
    emailFields.forEach(field => {
        field.addEventListener('input', function() {
            const value = this.value;
            const validEmailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
            
            if (value && !validEmailRegex.test(value)) {
                this.setCustomValidity('Please enter a valid email address');
                this.classList.add('invalid');
            } else {
                this.setCustomValidity('');
                this.classList.remove('invalid');
            }
        });
    });
    
    // Add custom validation for phone number fields
    const phoneFields = document.querySelectorAll('input[type="tel"]');
    phoneFields.forEach(field => {
        field.addEventListener('input', function() {
            const value = this.value;
            // Allow various phone formats with optional country codes
            const validPhoneRegex = /^(\+?\d{1,4}[\s-]?)?\(?\d{3,4}\)?[\s.-]?\d{3}[\s.-]?\d{4}$/;
            
            if (value && !validPhoneRegex.test(value)) {
                this.setCustomValidity('Please enter a valid phone number');
                this.classList.add('invalid');
            } else {
                this.setCustomValidity('');
                this.classList.remove('invalid');
            }
        });
    });
});
