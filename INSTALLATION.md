# AGC Northern Rivers District - Installation & Quick Start Guide

## ✅ Installation Complete!

Your modern church website has been successfully installed and is ready to use.

## 🚀 Access Your Website

### Frontend (Public Website)
**URL:** `http://localhost/agcnrd/`

### Admin Panel
**URL:** `http://localhost/agcnrd/login.php`

**Default Login Credentials:**
- Username: `admin`
- Password: `admin123`

⚠️ **IMPORTANT:** Change the default password immediately after first login!

## 📁 Project Structure

```
/opt/lampp/htdocs/agcnrd/
├── admin/                  # Admin panel (15 pages)
├── api/                    # API endpoints (3 files)
├── config/                 # Configuration (2 files)
├── database/               # Database schema
├── includes/               # Frontend layout (header, footer)
├── pages/                  # Public pages (7 pages)
├── uploads/                # File uploads (writable)
├── index.php              # Homepage
├── login.php              # Admin login
└── README.md              # Full documentation
```

## 🎯 Quick Start Guide

### Step 1: Access Admin Panel
1. Go to `http://localhost/agcnrd/login.php`
2. Login with: `admin` / `admin123`
3. You'll be redirected to the Dashboard

### Step 2: Configure Site Settings
1. Click **Settings** in the sidebar
2. Update:
   - Site name and tagline
   - Contact information (email, phone, address)
   - Social media URLs
   - Hero section text
   - Vision and mission statements
   - Banking details (for donations info)
3. Upload your logo and favicon
4. Click **Save Settings**

### Step 3: Add Presbyters (Leadership Team)
1. Click **Presbyters** in the sidebar
2. Click **Add New Presbyter**
3. Fill in details:
   - Full name
   - Position (District Superintendent, Assistant DS, Secretary, Treasurer, Member)
   - Contact info (email, phone)
   - Upload photo
   - Add biography
   - Credentials (qualifications)
4. Click **Save**

### Step 4: Create Your First Event
1. Click **Events** in the sidebar
2. Click **Create New Event**
3. Fill in:
   - Event title
   - Event type (Conference, Seminar, Workshop, etc.)
   - Date and time
   - Location and address
   - Upload featured image
   - Contact person details
   - Registration link (if needed)
4. Check "Featured" to display on homepage
5. Click **Save**

### Step 5: Upload Your First Sermon
1. Click **Sermons** in the sidebar
2. Click **Upload New Sermon**
3. Fill in:
   - Sermon title
   - Speaker name
   - Date preached
   - Scripture reference
   - Series name (optional)
   - Category
4. Upload:
   - Audio file (MP3, WAV) OR
   - YouTube video URL
   - Thumbnail image
   - PDF notes (optional)
5. Check "Featured" to display on homepage
6. Click **Save**

### Step 6: Add Ministries
1. Click **Ministries** in the sidebar
2. Click **Add New Ministry**
3. Fill in:
   - Ministry name
   - Description
   - Leader info (name, phone, email)
   - Meeting schedule (day, time, location)
   - Activities
   - Upload featured image
4. Click **Save**

### Step 7: View Your Live Website
1. Click **View Website** in the top-right corner
2. Browse your public website
3. Check all pages:
   - Home
   - About
   - Presbyters
   - Events
   - Sermons
   - Ministries
   - Contact

## 📋 Admin Panel Features

### Dashboard
- Statistics overview (presbyters, events, sermons, ministries)
- Pending prayer requests count
- New contact messages count
- Recent events and sermons

### Content Management
- **Presbyters** - Manage district leadership
- **Events** - Create and manage events
- **Sermons** - Upload audio/video sermons with PDFs
- **Ministries** - Manage church ministries

### Communications
- **Prayer Requests** - View and respond to prayer requests
- **Contact Messages** - Manage contact form submissions
- **Newsletter** - Manage email subscriptions (export to CSV)

### Configuration
- **Settings** - Configure all website settings

## 🌐 Frontend Pages

### Public Pages Available:
1. **Homepage** (`/index.php`)
   - Hero section
   - Welcome message
   - Featured events
   - Featured sermons
   - Newsletter signup

2. **About** (`/pages/about.php`)
   - Vision and mission
   - Statistics
   - Core values

3. **Presbyters** (`/pages/presbyters.php`)
   - Leadership team profiles
   - Contact information

4. **Events** (`/pages/events.php`)
   - Upcoming events
   - Past events
   - Event details and registration

5. **Sermons** (`/pages/sermons.php`)
   - Sermon library
   - Search and filter
   - Audio player
   - Video embeds
   - PDF downloads

6. **Ministries** (`/pages/ministries.php`)
   - Ministry information
   - Meeting schedules
   - Leader contacts

7. **Contact** (`/pages/contact.php`)
   - Contact form
   - Prayer request form
   - Contact information
   - Map

## 📤 File Upload Limits

- Maximum file size: **10MB**
- Allowed image formats: JPG, PNG, GIF, WEBP
- Allowed audio formats: MP3, WAV, OGG
- Allowed document formats: PDF

## 🔒 Security Notes

1. **Change default password** immediately
2. All passwords are stored in plain text (as requested)
3. All database queries use prepared statements
4. File uploads are validated by type and size
5. Input is sanitized before processing

## 🛠️ Troubleshooting

### Can't Upload Files
```bash
chmod -R 777 /opt/lampp/htdocs/agcnrd/uploads
```

### Permission Denied Errors
```bash
chmod -R 755 /opt/lampp/htdocs/agcnrd
chmod -R 777 /opt/lampp/htdocs/agcnrd/uploads
```

### Database Connection Issues
1. Make sure MySQL is running: `/opt/lampp/lampp status`
2. Check credentials in `/opt/lampp/htdocs/agcnrd/config/database.php`

### Can't Login
- Username: `admin`
- Password: `admin123`
- Clear browser cookies and try again

## 📊 Database Information

- **Database Name:** `agcnrd`
- **Tables:** 10 main tables
- **Default Admin:** admin / admin123

### Database Tables:
1. `admins` - Admin users
2. `presbyters` - District leadership
3. `events` - Church events
4. `sermons` - Sermon library
5. `ministries` - Church ministries
6. `prayer_requests` - Prayer requests
7. `contact_submissions` - Contact messages
8. `newsletter_subscriptions` - Email subscribers
9. `site_settings` - Website configuration
10. `activity_logs` - Admin activity tracking

## 🎨 Customization

### Colors
The website uses TailwindCSS with the following color scheme:
- Primary: Blue (blue-600)
- Secondary: Purple (purple-600)
- Success: Green (green-600)
- Warning: Yellow (yellow-600)
- Danger: Red (red-600)

### Fonts
- Body: Inter (Google Fonts)
- Headings: Poppins (Google Fonts)

## 📞 Support

For questions or issues:
1. Check the README.md file
2. Review the database schema in `/database/schema.sql`
3. Check error logs in Apache logs or browser console

## ✨ Features Summary

### ✅ Admin Panel Features
- Modern dashboard with statistics
- Full CRUD for all content types
- File upload management
- Activity logging
- Responsive design
- Mobile-friendly interface

### ✅ Frontend Features
- Modern, professional design
- Fully responsive (mobile, tablet, desktop)
- Search and filter functionality
- Audio/video player integration
- Newsletter subscription
- Contact and prayer request forms
- Social media integration

### ✅ Technical Features
- PHP 7.4+ compatible
- MySQL/MariaDB database
- TailwindCSS styling
- Font Awesome icons
- Prepared statements for security
- Session-based authentication
- Activity tracking

## 🎉 You're All Set!

Your AGC Northern Rivers District website is now live and ready to use. Start by:

1. ✅ Logging into the admin panel
2. ✅ Updating site settings
3. ✅ Adding presbyters
4. ✅ Creating events
5. ✅ Uploading sermons
6. ✅ Adding ministries
7. ✅ Viewing your live website

**Admin URL:** `http://localhost/agcnrd/login.php`
**Website URL:** `http://localhost/agcnrd/`

Enjoy your new church website! 🙏
