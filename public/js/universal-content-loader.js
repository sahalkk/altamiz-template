/**
 * Universal Content Loader
 * Loads dynamic content for all pages
 */

(function() {
    'use strict';

    // Determine API URL based on current page location
    function getApiUrl() {
        const path = window.location.pathname;
        if (path.includes('/about-us/')) {
            return '../admin/api.php';
        } else if (path.includes('/services/')) {
            return '../admin/api.php';
        } else {
            return 'admin/api.php';
        }
    }
    
    const API_URL = getApiUrl();
    
    // Get page identifier from current URL
    function getPageId() {
        const path = window.location.pathname;
        const filename = path.split('/').pop().replace('.html', '');
        // Handle root path or empty filename
        if (!filename || filename === '') {
            return 'index';
        }
        return filename;
    }
    
    // Load site-wide information
    function loadSiteInfo() {
        if (typeof $ !== 'undefined') {
            $.ajax({
                url: API_URL + '?page=site-info',
                method: 'GET',
                dataType: 'json',
                success: function(data) {
                    applyContent(data);
                },
                error: function(xhr, status, error) {
                    console.warn('Could not load site info from API:', error);
                }
            });
        } else {
            // Fallback: use fetch API
            fetch(API_URL + '?page=site-info')
                .then(response => response.json())
                .then(data => {
                    applyContent(data);
                })
                .catch(error => {
                    console.warn('Could not load site info from API:', error);
                });
        }
    }

    // Load and apply content
    function loadPageContent() {
        // Always load site-wide information first
        loadSiteInfo();
        
        const pageId = getPageId();
        
        if (!pageId) {
            // Skip if no page ID found
            return;
        }
        
        // Try to load from API
        if (typeof $ !== 'undefined') {
            $.ajax({
                url: API_URL + '?page=' + encodeURIComponent(pageId),
                method: 'GET',
                dataType: 'json',
                success: function(data) {
                    applyContent(data);
                },
                error: function(xhr, status, error) {
                    console.warn('Could not load content from API:', error);
                    loadFromLocalStorage(pageId);
                }
            });
        } else {
            // Fallback: use fetch API
            fetch(API_URL + '?page=' + encodeURIComponent(pageId))
                .then(response => response.json())
                .then(data => {
                    applyContent(data);
                })
                .catch(error => {
                    console.warn('Could not load content from API:', error);
                    loadFromLocalStorage(pageId);
                });
        }
    }

    // Load from localStorage as fallback
    function loadFromLocalStorage(pageId) {
        const stored = localStorage.getItem('pageContent_' + pageId);
        if (stored) {
            try {
                const data = JSON.parse(stored);
                applyContent(data);
            } catch (e) {
                console.error('Error parsing stored content:', e);
            }
        }
    }

    // Apply content to page elements
    function applyContent(data) {
        // Find all elements with data-content attributes
        const elements = document.querySelectorAll('[data-content]');
        
        elements.forEach(element => {
            const key = element.getAttribute('data-content');
            if (data[key]) {
                // Handle different element types
                if (element.tagName === 'P' || element.tagName === 'DIV' || 
                    element.tagName === 'H1' || element.tagName === 'H2' || 
                    element.tagName === 'H3' || element.tagName === 'H4' ||
                    element.tagName === 'H5' || element.tagName === 'H6' ||
                    element.tagName === 'SPAN') {
                    element.innerText = data[key];
                } else {
                    element.textContent = data[key];
                }
            }
        });
    }

    // Load content when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', loadPageContent);
    } else {
        loadPageContent();
    }
})();


