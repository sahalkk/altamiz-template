# Universal Content Management System

## Overview
This CMS allows you to update content on multiple pages of the website without editing HTML files directly.

## Access
Navigate to `/admin.html` in your browser to access the admin panel.

## Supported Pages
- **About Us Pages:**
  - Our Mission
  - Our Vision
  - Our Goal
  - Overview

- **Service Pages:**
  - Civil Work
  - Transportation
  - Manpower Supply
  - Material Supply
  - Porta Cabin Supply
  - Our Services

## How to Use
1. Open `/admin.html` in your browser
2. Select the page you want to edit from the dropdown menu
3. The form will automatically load with the current content for that page
4. Edit the fields as needed
5. Click "Save Changes" to update the content
6. Visit the page to see your changes

## How It Works
1. Content is stored in `admin/content/[page-name].json` files
2. Each page has its own JSON file with its content
3. The admin panel dynamically generates form fields based on the selected page
4. Pages automatically load the latest content when viewed

## File Structure
```
admin/
├── api.php              # Universal API endpoint for all pages
├── content/            # Content storage directory
│   ├── our-mission.json
│   ├── our-vision.json
│   ├── our-goal.json
│   └── [other pages].json
└── README.md           # This file

admin.html              # Admin interface page
js/
├── admin-content.js    # Admin panel JavaScript
└── universal-content-loader.js  # Content loader for all pages
```

## Adding New Pages
To add content management for a new page:

1. **Add page to admin.html dropdown:**
   ```html
   <option value="new-page">New Page</option>
   ```

2. **Define fields in js/admin-content.js:**
   ```javascript
   'new-page': [
       { key: 'pageSubtitle', label: 'Page Subtitle', type: 'text' },
       { key: 'pageTitle', label: 'Page Title', type: 'text' },
       // Add more fields...
   ]
   ```

3. **Add default content in admin/api.php:**
   ```php
   'new-page' => [
       'pageSubtitle' => 'Default subtitle',
       'pageTitle' => 'Default Title',
       // Add more defaults...
   ]
   ```

4. **Add data-content attributes to HTML:**
   ```html
   <div data-content="pageSubtitle">Default text</div>
   ```

5. **Add script to page:**
   ```html
   <script src="js/universal-content-loader.js"></script>
   ```

## Security Note
**Important**: This admin panel currently has no authentication. For production use, you should:
- Add password protection
- Implement user authentication
- Restrict access to authorized users only
- Consider using `.htaccess` to protect the admin directory

## Troubleshooting
- **Content doesn't load:** Check that the JSON file exists in `admin/content/` and is readable
- **Can't save changes:** Ensure PHP is enabled and the `admin/content/` directory is writable
- **Form doesn't appear:** Check browser console for JavaScript errors
- **Page shows old content:** Clear browser cache or check that `data-content` attributes are correctly set

## API Endpoints

### GET `/admin/api.php?page=[page-name]`
Loads content for a specific page.

### POST `/admin/api.php`
Saves content for a page. Send JSON with `page` field and content fields.

Example:
```json
{
  "page": "our-mission",
  "pageSubtitle": "New subtitle",
  "missionStatement": "New statement"
}
```
