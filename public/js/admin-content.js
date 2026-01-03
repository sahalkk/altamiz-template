/**
 * Universal Admin Content Management JavaScript
 * Handles loading and saving of content for all pages
 */

(function() {
    'use strict';

    const API_URL = 'admin/api.php';
    
    // Page field definitions - defines what fields each page has
    const pageFields = {
        'our-mission': [
            { key: 'pageSubtitle', label: 'Page Subtitle', type: 'text' },
            { key: 'pageTitle', label: 'Page Title', type: 'text', readonly: true },
            { key: 'missionStatement', label: 'Mission Statement', type: 'textarea', rows: 4 },
            { key: 'howWeDeliverTitle', label: 'How We Deliver - Title', type: 'text' },
            { key: 'howWeDeliverPara1', label: 'How We Deliver - Paragraph 1', type: 'textarea', rows: 4 },
            { key: 'howWeDeliverPara2', label: 'How We Deliver - Paragraph 2', type: 'textarea', rows: 4 },
            { key: 'benefit1', label: 'Benefit 1', type: 'text' },
            { key: 'benefit2', label: 'Benefit 2', type: 'text' },
            { key: 'benefit3', label: 'Benefit 3', type: 'text' },
            { key: 'chairmanMessage', label: 'Chairman\'s Message', type: 'textarea', rows: 6 },
            { key: 'chairmanMessageLabel', label: 'Chairman\'s Message Label', type: 'text' }
        ],
        'our-vision': [
            { key: 'pageSubtitle', label: 'Page Subtitle', type: 'text' },
            { key: 'pageTitle', label: 'Page Title', type: 'text', readonly: true },
            { key: 'visionStatement', label: 'Vision Statement', type: 'textarea', rows: 4 },
            { key: 'sectionTitle', label: 'Section Title', type: 'text' },
            { key: 'sectionPara1', label: 'Section Paragraph 1', type: 'textarea', rows: 4 },
            { key: 'sectionPara2', label: 'Section Paragraph 2', type: 'textarea', rows: 4 }
        ],
        'our-goal': [
            { key: 'pageSubtitle', label: 'Page Subtitle', type: 'text' },
            { key: 'pageTitle', label: 'Page Title', type: 'text', readonly: true },
            { key: 'goalStatement', label: 'Goal Statement', type: 'textarea', rows: 4 },
            { key: 'sectionTitle', label: 'Section Title', type: 'text' },
            { key: 'sectionPara1', label: 'Section Paragraph 1', type: 'textarea', rows: 4 },
            { key: 'sectionPara2', label: 'Section Paragraph 2', type: 'textarea', rows: 4 }
        ],
        'overview': [
            { key: 'pageSubtitle', label: 'Page Subtitle', type: 'text' },
            { key: 'pageTitle', label: 'Page Title', type: 'text', readonly: true },
            { key: 'overviewText', label: 'Overview Text', type: 'textarea', rows: 6 }
        ],
        'civil-work': [
            { key: 'pageSubtitle', label: 'Page Subtitle', type: 'text' },
            { key: 'pageTitle', label: 'Page Title', type: 'text', readonly: true },
            { key: 'description', label: 'Description', type: 'textarea', rows: 6 },
            { key: 'sectionTitle', label: 'Section Title', type: 'text' },
            { key: 'sectionContent', label: 'Section Content', type: 'textarea', rows: 8 }
        ],
        'transportation': [
            { key: 'pageSubtitle', label: 'Page Subtitle', type: 'text' },
            { key: 'pageTitle', label: 'Page Title', type: 'text', readonly: true },
            { key: 'description', label: 'Description', type: 'textarea', rows: 6 },
            { key: 'sectionTitle', label: 'Section Title', type: 'text' },
            { key: 'sectionContent', label: 'Section Content', type: 'textarea', rows: 8 }
        ],
        'manpower-supply': [
            { key: 'pageSubtitle', label: 'Page Subtitle', type: 'text' },
            { key: 'pageTitle', label: 'Page Title', type: 'text', readonly: true },
            { key: 'description', label: 'Description', type: 'textarea', rows: 6 },
            { key: 'sectionTitle', label: 'Section Title', type: 'text' },
            { key: 'sectionContent', label: 'Section Content', type: 'textarea', rows: 8 }
        ],
        'material-supply': [
            { key: 'pageSubtitle', label: 'Page Subtitle', type: 'text' },
            { key: 'pageTitle', label: 'Page Title', type: 'text', readonly: true },
            { key: 'description', label: 'Description', type: 'textarea', rows: 6 },
            { key: 'sectionTitle', label: 'Section Title', type: 'text' },
            { key: 'sectionContent', label: 'Section Content', type: 'textarea', rows: 8 }
        ],
        'porta-cabin-supply': [
            { key: 'pageSubtitle', label: 'Page Subtitle', type: 'text' },
            { key: 'pageTitle', label: 'Page Title', type: 'text', readonly: true },
            { key: 'description', label: 'Description', type: 'textarea', rows: 6 },
            { key: 'sectionTitle', label: 'Section Title', type: 'text' },
            { key: 'sectionContent', label: 'Section Content', type: 'textarea', rows: 8 }
        ],
        'our-services': [
            { key: 'pageSubtitle', label: 'Page Subtitle', type: 'text' },
            { key: 'pageTitle', label: 'Page Title', type: 'text', readonly: true },
            { key: 'description', label: 'Description', type: 'textarea', rows: 6 },
            { key: 'sectionTitle', label: 'Section Title', type: 'text' },
            { key: 'sectionContent', label: 'Section Content', type: 'textarea', rows: 8 }
        ],
        'site-info': [
            { key: 'phone', label: 'Phone Number', type: 'text' },
            { key: 'email', label: 'Email Address', type: 'text' },
            { key: 'addressLine1', label: 'Address Line 1', type: 'text' },
            { key: 'addressLine2', label: 'Address Line 2', type: 'text' },
            { key: 'workingHours', label: 'Working Hours', type: 'text' },
            { key: 'callUsLabel', label: 'Call Us Label', type: 'text' },
            { key: 'sendMailLabel', label: 'Send Mail Label', type: 'text' }
        ],
        'mobile-cranes': [
            { key: 'pageSubtitle', label: 'Page Subtitle', type: 'text' },
            { key: 'pageTitle', label: 'Page Title', type: 'text', readonly: true },
            { key: 'description', label: 'Description', type: 'textarea', rows: 6 },
            { key: 'sectionTitle', label: 'Section Title', type: 'text' },
            { key: 'sectionContent', label: 'Section Content', type: 'textarea', rows: 8 }
        ],
        'heavy-equipment': [
            { key: 'pageSubtitle', label: 'Page Subtitle', type: 'text' },
            { key: 'pageTitle', label: 'Page Title', type: 'text', readonly: true },
            { key: 'description', label: 'Description', type: 'textarea', rows: 6 },
            { key: 'sectionTitle', label: 'Section Title', type: 'text' },
            { key: 'sectionContent', label: 'Section Content', type: 'textarea', rows: 8 }
        ],
        'light-equipment': [
            { key: 'pageSubtitle', label: 'Page Subtitle', type: 'text' },
            { key: 'pageTitle', label: 'Page Title', type: 'text', readonly: true },
            { key: 'description', label: 'Description', type: 'textarea', rows: 6 },
            { key: 'sectionTitle', label: 'Section Title', type: 'text' },
            { key: 'sectionContent', label: 'Section Content', type: 'textarea', rows: 8 }
        ],
        'earth-moving-equipment': [
            { key: 'pageSubtitle', label: 'Page Subtitle', type: 'text' },
            { key: 'pageTitle', label: 'Page Title', type: 'text', readonly: true },
            { key: 'description', label: 'Description', type: 'textarea', rows: 6 },
            { key: 'sectionTitle', label: 'Section Title', type: 'text' },
            { key: 'sectionContent', label: 'Section Content', type: 'textarea', rows: 8 }
        ],
        'industrial-equipment': [
            { key: 'pageSubtitle', label: 'Page Subtitle', type: 'text' },
            { key: 'pageTitle', label: 'Page Title', type: 'text', readonly: true },
            { key: 'description', label: 'Description', type: 'textarea', rows: 6 },
            { key: 'sectionTitle', label: 'Section Title', type: 'text' },
            { key: 'sectionContent', label: 'Section Content', type: 'textarea', rows: 8 }
        ],
        'heavy-&-light-vehicles': [
            { key: 'pageSubtitle', label: 'Page Subtitle', type: 'text' },
            { key: 'pageTitle', label: 'Page Title', type: 'text', readonly: true },
            { key: 'description', label: 'Description', type: 'textarea', rows: 6 },
            { key: 'sectionTitle', label: 'Section Title', type: 'text' },
            { key: 'sectionContent', label: 'Section Content', type: 'textarea', rows: 8 }
        ],
        'company-profile': [
            { key: 'pageSubtitle', label: 'Page Subtitle', type: 'text' },
            { key: 'pageTitle', label: 'Page Title', type: 'text', readonly: true },
            { key: 'description', label: 'Description', type: 'textarea', rows: 6 },
            { key: 'sectionTitle', label: 'Section Title', type: 'text' },
            { key: 'sectionContent', label: 'Section Content', type: 'textarea', rows: 8 }
        ],
        'index': [
            { key: 'aboutSubtitle', label: 'About Section - Subtitle', type: 'text' },
            { key: 'aboutTitle', label: 'About Section - Title', type: 'text' },
            { key: 'aboutDescription', label: 'About Section - Description', type: 'textarea', rows: 6 },
            { key: 'aboutDescription2', label: 'About Section - Additional Description', type: 'textarea', rows: 4 },
            { key: 'whyChooseUsSubtitle', label: 'Why Choose Us - Subtitle', type: 'text' },
            { key: 'whyChooseUsTitle', label: 'Why Choose Us - Title', type: 'text' },
            { key: 'whyChooseUsDescription', label: 'Why Choose Us - Description', type: 'textarea', rows: 4 },
            { key: 'servicesSubtitle', label: 'Services Section - Subtitle', type: 'text' },
            { key: 'servicesTitle', label: 'Services Section - Title', type: 'text' },
            { key: 'servicesDescription', label: 'Services Section - Description', type: 'textarea', rows: 4 }
        ],
        'contact-us': [
            { key: 'pageSubtitle', label: 'Page Subtitle', type: 'text' },
            { key: 'pageTitle', label: 'Page Title', type: 'text', readonly: true },
            { key: 'description', label: 'Page Description', type: 'textarea', rows: 4 },
            { key: 'getInTouchSubtitle', label: 'Get In Touch - Subtitle', type: 'text' },
            { key: 'getInTouchTitle', label: 'Get In Touch - Title', type: 'text' },
            { key: 'getInTouchDescription', label: 'Get In Touch - Description', type: 'textarea', rows: 4 },
            { key: 'locationsTitle', label: 'Our Locations - Title', type: 'text' },
            { key: 'locationsDescription', label: 'Our Locations - Description', type: 'textarea', rows: 4 }
        ]
    };
    
    let currentPage = '';
    
    // Show alert message
    function showAlert(message, type) {
        const alertContainer = document.getElementById('alertContainer');
        const alertClass = type === 'success' ? 'alert-success' : 'alert-error';
        alertContainer.innerHTML = `<div class="alert ${alertClass}">${message}</div>`;
        alertContainer.querySelector('.alert').style.display = 'block';
        
        setTimeout(() => {
            const alert = alertContainer.querySelector('.alert');
            if (alert) {
                alert.style.display = 'none';
            }
        }, 5000);
    }

    // Generate form fields based on page type
    function generateForm(fields, data) {
        const form = document.getElementById('contentForm');
        form.innerHTML = '';
        
        fields.forEach((field, index) => {
            const formGroup = document.createElement('div');
            formGroup.className = 'form-group';
            
            const label = document.createElement('label');
            label.setAttribute('for', field.key);
            label.textContent = field.label;
            
            let input;
            if (field.type === 'textarea') {
                input = document.createElement('textarea');
                input.rows = field.rows || 4;
            } else {
                input = document.createElement('input');
                input.type = field.type || 'text';
            }
            
            input.id = field.key;
            input.name = field.key;
            input.value = data[field.key] || '';
            
            if (field.readonly) {
                input.readOnly = true;
            }
            
            formGroup.appendChild(label);
            formGroup.appendChild(input);
            form.appendChild(formGroup);
            
            // Add section dividers for certain fields
            if (index > 0 && (field.key.includes('Title') || field.key.includes('section'))) {
                const divider = document.createElement('div');
                divider.className = 'section-divider';
                form.insertBefore(divider, formGroup);
            }
        });
        
        // Add save button
        const buttonGroup = document.createElement('div');
        buttonGroup.style.marginTop = '30px';
        const saveBtn = document.createElement('button');
        saveBtn.type = 'submit';
        saveBtn.className = 'btn-save';
        saveBtn.id = 'saveBtn';
        saveBtn.textContent = 'Save Changes';
        buttonGroup.appendChild(saveBtn);
        form.appendChild(buttonGroup);
    }

    // Load content from API
    function loadContent(page) {
        currentPage = page;
        document.getElementById('loadingContainer').style.display = 'block';
        document.getElementById('formContainer').style.display = 'none';
        
        $.ajax({
            url: API_URL + '?page=' + encodeURIComponent(page),
            method: 'GET',
            dataType: 'json',
            success: function(data) {
                const fields = pageFields[page] || [];
                generateForm(fields, data);
                document.getElementById('loadingContainer').style.display = 'none';
                document.getElementById('formContainer').style.display = 'block';
            },
            error: function(xhr, status, error) {
                console.error('Error loading content:', error);
                showAlert('Error loading content. Please try again.', 'error');
                document.getElementById('loadingContainer').style.display = 'none';
            }
        });
    }

    // Save content to API
    function saveContent(formData) {
        const saveBtn = document.getElementById('saveBtn');
        const originalText = saveBtn.textContent;
        
        saveBtn.disabled = true;
        saveBtn.textContent = 'Saving...';
        
        formData.page = currentPage;
        
        $.ajax({
            url: API_URL,
            method: 'POST',
            contentType: 'application/json',
            data: JSON.stringify(formData),
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    showAlert('Content saved successfully!', 'success');
                } else {
                    showAlert(response.message || 'Error saving content', 'error');
                }
            },
            error: function(xhr, status, error) {
                console.error('Error saving content:', error);
                let errorMessage = 'Error saving content. Please try again.';
                
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }
                
                showAlert(errorMessage, 'error');
            },
            complete: function() {
                saveBtn.disabled = false;
                saveBtn.textContent = originalText;
            }
        });
    }

    // Handle page selection
    document.addEventListener('DOMContentLoaded', function() {
        const pageSelect = document.getElementById('pageSelect');
        
        pageSelect.addEventListener('change', function() {
            const selectedPage = this.value;
            if (selectedPage) {
                loadContent(selectedPage);
            } else {
                document.getElementById('formContainer').style.display = 'none';
                document.getElementById('loadingContainer').style.display = 'none';
            }
        });

        // Handle form submission
        document.addEventListener('submit', function(e) {
            if (e.target.id === 'contentForm') {
                e.preventDefault();
                
                const formData = {};
                const form = e.target;
                const inputs = form.querySelectorAll('input, textarea');
                
                inputs.forEach(input => {
                    if (input.id && input.name) {
                        formData[input.id] = input.value;
                    }
                });

                saveContent(formData);
            }
        });
    });
})();
