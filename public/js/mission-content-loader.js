/**
 * Mission Content Loader
 * Loads dynamic content for the Our Mission page
 */

(function() {
    'use strict';

    // Determine API URL based on current page location
    const API_URL = window.location.pathname.includes('/about-us/') 
        ? '../admin/api.php' 
        : 'admin/api.php';
    
    // Load and apply content
    function loadMissionContent() {
        // Try to load from API (PHP backend)
        if (typeof $ !== 'undefined') {
            $.ajax({
                url: API_URL,
                method: 'GET',
                dataType: 'json',
                success: function(data) {
                    applyContent(data);
                },
                error: function(xhr, status, error) {
                    console.warn('Could not load content from API, using default content:', error);
                    // Fallback: try localStorage
                    loadFromLocalStorage();
                }
            });
        } else {
            // Fallback: use fetch API
            fetch(API_URL)
                .then(response => response.json())
                .then(data => {
                    applyContent(data);
                })
                .catch(error => {
                    console.warn('Could not load content from API, using default content:', error);
                    loadFromLocalStorage();
                });
        }
    }

    // Load from localStorage as fallback
    function loadFromLocalStorage() {
        const stored = localStorage.getItem('missionContent');
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
        // Map of data attributes to content keys
        const contentMap = {
            'pageSubtitle': data.pageSubtitle,
            'pageTitle': data.pageTitle,
            'missionStatement': data.missionStatement,
            'howWeDeliverTitle': data.howWeDeliverTitle,
            'howWeDeliverPara1': data.howWeDeliverPara1,
            'howWeDeliverPara2': data.howWeDeliverPara2,
            'benefit1': data.benefit1,
            'benefit2': data.benefit2,
            'benefit3': data.benefit3,
            'chairmanMessage': data.chairmanMessage ? '" ' + data.chairmanMessage + ' "' : '',
            'chairmanMessageLabel': data.chairmanMessageLabel
        };

        // Update elements with data-content attributes
        Object.keys(contentMap).forEach(key => {
            const elements = document.querySelectorAll('[data-content="' + key + '"]');
            elements.forEach(element => {
                if (contentMap[key]) {
                    // For text nodes, update innerText
                    if (element.tagName === 'P' || element.tagName === 'DIV' || element.tagName === 'H2' || element.tagName === 'H3') {
                        element.innerText = contentMap[key];
                    } else {
                        element.textContent = contentMap[key];
                    }
                }
            });
        });
    }

    // Load content when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', loadMissionContent);
    } else {
        loadMissionContent();
    }
})();

