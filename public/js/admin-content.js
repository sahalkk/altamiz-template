/**
 * Admin Content Management JavaScript
 * Handles loading and saving of Our Mission content
 */

(function() {
    'use strict';

    const API_URL = 'admin/api.php';
    
    // Show alert message
    function showAlert(message, type) {
        const alertContainer = document.getElementById('alertContainer');
        const alertClass = type === 'success' ? 'alert-success' : 'alert-error';
        alertContainer.innerHTML = `<div class="alert ${alertClass}">${message}</div>`;
        alertContainer.querySelector('.alert').style.display = 'block';
        
        // Auto-hide after 5 seconds
        setTimeout(() => {
            const alert = alertContainer.querySelector('.alert');
            if (alert) {
                alert.style.display = 'none';
            }
        }, 5000);
    }

    // Load content from API
    function loadContent() {
        $.ajax({
            url: API_URL,
            method: 'GET',
            dataType: 'json',
            success: function(data) {
                // Populate form fields
                document.getElementById('pageSubtitle').value = data.pageSubtitle || '';
                document.getElementById('pageTitle').value = data.pageTitle || 'Our Mission';
                document.getElementById('missionStatement').value = data.missionStatement || '';
                document.getElementById('howWeDeliverTitle').value = data.howWeDeliverTitle || '';
                document.getElementById('howWeDeliverPara1').value = data.howWeDeliverPara1 || '';
                document.getElementById('howWeDeliverPara2').value = data.howWeDeliverPara2 || '';
                document.getElementById('benefit1').value = data.benefit1 || '';
                document.getElementById('benefit2').value = data.benefit2 || '';
                document.getElementById('benefit3').value = data.benefit3 || '';
                document.getElementById('chairmanMessage').value = data.chairmanMessage || '';
                document.getElementById('chairmanMessageLabel').value = data.chairmanMessageLabel || 'Chairman\'s Message';
            },
            error: function(xhr, status, error) {
                console.error('Error loading content:', error);
                showAlert('Error loading content. Please refresh the page.', 'error');
            }
        });
    }

    // Save content to API
    function saveContent(formData) {
        const saveBtn = document.getElementById('saveBtn');
        const originalText = saveBtn.textContent;
        
        saveBtn.disabled = true;
        saveBtn.textContent = 'Saving...';
        
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

    // Handle form submission
    document.addEventListener('DOMContentLoaded', function() {
        // Load existing content
        loadContent();

        // Handle form submission
        const form = document.getElementById('missionForm');
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = {
                pageSubtitle: document.getElementById('pageSubtitle').value,
                pageTitle: document.getElementById('pageTitle').value,
                missionStatement: document.getElementById('missionStatement').value,
                howWeDeliverTitle: document.getElementById('howWeDeliverTitle').value,
                howWeDeliverPara1: document.getElementById('howWeDeliverPara1').value,
                howWeDeliverPara2: document.getElementById('howWeDeliverPara2').value,
                benefit1: document.getElementById('benefit1').value,
                benefit2: document.getElementById('benefit2').value,
                benefit3: document.getElementById('benefit3').value,
                chairmanMessage: document.getElementById('chairmanMessage').value,
                chairmanMessageLabel: document.getElementById('chairmanMessageLabel').value
            };

            saveContent(formData);
        });
    });
})();

