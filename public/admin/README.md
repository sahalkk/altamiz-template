# Admin Panel - Our Mission Content Management

## Overview
This admin panel allows you to update the content on the "Our Mission" page without editing HTML files directly.

## Access
Navigate to `/admin.html` in your browser to access the admin panel.

## Features
- Edit page title and subtitle
- Update mission statement
- Modify "How We Deliver" section content
- Edit benefit items
- Update Chairman's Message

## How It Works
1. Content is stored in `admin/content.json`
2. The admin panel loads existing content when opened
3. Changes are saved via `admin/api.php`
4. The Our Mission page (`about-us/our-mission.html`) automatically loads the latest content

## Security Note
**Important**: This admin panel currently has no authentication. For production use, you should:
- Add password protection
- Implement user authentication
- Restrict access to authorized users only
- Consider using `.htaccess` to protect the admin directory

## File Structure
```
admin/
├── api.php          # API endpoint for saving/loading content
├── content.json     # JSON file storing the content
└── README.md        # This file

admin.html           # Admin interface page
js/
├── admin-content.js # Admin panel JavaScript
└── mission-content-loader.js  # Content loader for Our Mission page
```

## Troubleshooting
- If content doesn't load, check that `admin/content.json` exists and is readable
- Ensure PHP is enabled on your server
- Check browser console for JavaScript errors
- Verify file permissions (content.json should be writable by the web server)

