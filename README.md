# Assemblies of God Church Northern Rivers District Website

A modern, fully dynamic church website with comprehensive admin panel for managing events, sermons, presbyters, ministries, and more.

## Features

### Admin Panel
- **Dashboard** - Overview with statistics and recent activity
- **Presbyters Management** - District leadership team management
- **Events Management** - Create and manage church events
- **Sermons Management** - Upload audio sermons, videos, and PDFs
- **Ministries Management** - Manage church ministries and departments
- **Prayer Requests** - View and respond to prayer requests
- **Contact Messages** - Manage contact form submissions
- **Newsletter** - Manage email subscriptions with CSV export
- **Site Settings** - Configure all website settings, social media, banking details
- **Activity Logs** - Track all admin actions (Super Admin only)
- **Admin Management** - Manage admin users (Super Admin only)

### Frontend
- **Homepage** - Hero section, featured events, featured sermons, newsletter signup
- **Events** - Browse upcoming and past events with full details
- **Sermons** - Search and filter sermons library with audio/video player
- **Presbyters** - View district leadership team with bios and contact info
- **Ministries** - Browse church ministries with meeting schedules
- **About** - Vision, mission, and core values
- **Contact** - Contact form, prayer requests, location map

## Tech Stack

- **Backend**: PHP 7.4+ with MySQLi
- **Database**: MySQL/MariaDB with InnoDB
- **Frontend**: HTML5 + Vanilla JavaScript
- **Styling**: TailwindCSS (CDN) + Font Awesome 6.4.0
- **Fonts**: Inter (body), Poppins (headings) from Google Fonts

## Installation

### Requirements
- XAMPP/LAMPP with Apache and MySQL
- PHP 7.4 or higher
- MySQL 5.7 or higher

### Setup Instructions

1. **Database Setup**
   ```bash
   cd /opt/lampp/htdocs/agcnrd
   /opt/lampp/bin/mysql -u root < database/schema.sql
   ```

2. **Configure Database Connection**
   Edit `/opt/lampp/htdocs/agcnrd/config/database.php` if needed:
   ```php
   define('DB_HOST', 'localhost');
   define('DB_USER', 'root');
   define('DB_PASS', '');
   define('DB_NAME', 'agcnrd');
   ```

3. **Set Permissions**
   ```bash
   chmod -R 777 /opt/lampp/htdocs/agcnrd/uploads
   ```

4. **Access the Website**
   - Frontend: `http://localhost/agcnrd/`
   - Admin Login: `http://localhost/agcnrd/login.php`
   - Default Credentials:
     - Username: `admin`
     - Password: `admin123`

## Directory Structure

```
/opt/lampp/htdocs/agcnrd/
├── admin/                      # Admin panel
│   ├── includes/              # Header, footer, sidebar
│   ├── ajax/                  # AJAX handlers
│   ├── index.php              # Dashboard
│   ├── presbyters.php         # Presbyters management
│   ├── events.php             # Events management
│   ├── sermons.php            # Sermons management
│   ├── ministries.php         # Ministries management
│   ├── prayer-requests.php    # Prayer requests
│   ├── contact-messages.php   # Contact messages
│   ├── newsletter.php         # Newsletter management
│   ├── settings.php           # Site settings
│   ├── admins.php            # Admin users (Super Admin)
│   └── activity-logs.php     # Activity logs (Super Admin)
├── api/                       # API endpoints
│   ├── contact.php           # Contact form handler
│   ├── prayer.php            # Prayer request handler
│   └── newsletter.php        # Newsletter subscription
├── config/                    # Configuration files
│   ├── config.php            # Main config with helpers
│   └── database.php          # Database connection
├── database/                  # Database schema
│   └── schema.sql            # Complete database schema
├── includes/                  # Frontend includes
│   ├── header.php            # Main navigation
│   └── footer.php            # Footer with social links
├── pages/                     # Frontend pages
│   ├── about.php             # About page
│   ├── events.php            # Events listing
│   ├── sermons.php           # Sermons library
│   ├── presbyters.php        # Leadership page
│   ├── ministries.php        # Ministries page
│   └── contact.php           # Contact page
├── uploads/                   # File uploads
│   ├── events/               # Event images
│   ├── sermons/              # Sermon files
│   │   ├── audio/
│   │   ├── pdf/
│   │   └── thumbnails/
│   ├── presbyters/           # Presbyter photos
│   ├── ministries/           # Ministry images
│   └── settings/             # Logo, favicon, hero
├── index.php                  # Homepage
├── login.php                  # Admin login
└── logout.php                 # Logout handler
```

## Database Schema

### Main Tables
- `admins` - Admin users with role-based access
- `presbyters` - District leadership team
- `events` - Church events
- `sermons` - Sermon library with media files
- `ministries` - Church ministries and departments
- `prayer_requests` - Prayer requests from visitors
- `contact_submissions` - Contact form messages
- `newsletter_subscriptions` - Email subscribers
- `site_settings` - All website configuration
- `activity_logs` - Admin activity tracking

## Admin Roles

- **Super Admin** - Full access to all features including admin management and activity logs
- **Admin** - Access to all content management features
- **Editor** - Access to content creation and editing

## Key Features

### Security
- Prepared statements for SQL injection prevention
- Input sanitization on all forms
- Session-based authentication
- Role-based access control
- File type validation on uploads
- Activity logging for audit trails

### File Uploads
- Events: Images (JPG, PNG, GIF, WEBP)
- Sermons: Audio (MP3, WAV), PDF, Thumbnails
- Presbyters: Photos (JPG, PNG)
- Ministries: Images (JPG, PNG, GIF, WEBP)
- Settings: Logo, Favicon, Hero Image
- Maximum file size: 10MB

### Responsive Design
- Mobile-first approach
- Breakpoints: Mobile (320px+), Tablet (768px+), Desktop (1024px+)
- Touch-friendly interface
- Optimized images and loading

## Configuration

### Site Settings
Access via Admin Panel → Settings to configure:
- Site name, tagline, logo
- Contact information (email, phone, address)
- Social media URLs (Facebook, Instagram, YouTube, Twitter, TikTok, LinkedIn)
- Live stream URL
- Hero section (title, subtitle, background image)
- Vision and mission statements
- Banking details for donations
- Payment gateway information

### Email Configuration
To enable email notifications, configure your mail server settings in the API endpoints.

## Usage

### Adding Presbyters
1. Go to Admin Panel → Presbyters
2. Click "Add New Presbyter"
3. Fill in details (name, position, email, phone, photo, bio, credentials)
4. Click "Save"

### Creating Events
1. Go to Admin Panel → Events
2. Click "Create New Event"
3. Fill in event details, upload featured image
4. Set event date, time, and location
5. Add registration link if required
6. Click "Save"

### Uploading Sermons
1. Go to Admin Panel → Sermons
2. Click "Upload New Sermon"
3. Fill in sermon details (title, speaker, date, scripture)
4. Upload audio file, thumbnail, and/or PDF
5. Or add YouTube video URL
6. Mark as featured if desired
7. Click "Save"

### Managing Site Settings
1. Go to Admin Panel → Settings
2. Update any section (General, Contact, Social Media, etc.)
3. Upload new logo, favicon, or hero image if needed
4. Click "Save Settings"

## API Endpoints

### Contact Form
- **Endpoint**: `/api/contact.php`
- **Method**: POST
- **Parameters**: name, email, phone, subject, message

### Prayer Requests
- **Endpoint**: `/api/prayer.php`
- **Method**: POST
- **Parameters**: full_name, email, phone, prayer_request, is_anonymous

### Newsletter Subscription
- **Endpoint**: `/api/newsletter.php`
- **Method**: POST
- **Parameters**: email, name

## Troubleshooting

### Upload Errors
- Check that uploads directory has write permissions (777)
- Verify file size is under 10MB
- Ensure file type is allowed

### Database Connection Errors
- Verify MySQL is running: `/opt/lampp/lampp status`
- Check database credentials in `config/database.php`
- Ensure database exists: `/opt/lampp/bin/mysql -u root -e "SHOW DATABASES;"`

### Login Issues
- Default credentials: admin / admin123
- Check that session is enabled in PHP
- Clear browser cookies and cache

## Support

For issues or questions, please contact the development team or check the activity logs in the admin panel for troubleshooting.

## License

Copyright © 2025 Assemblies of God Church Northern Rivers District. All rights reserved.

## Credits

Built with:
- TailwindCSS
- Font Awesome
- Google Fonts (Inter, Poppins)
- PHP & MySQL

---

**Version**: 1.0.0
**Last Updated**: October 21, 2025
