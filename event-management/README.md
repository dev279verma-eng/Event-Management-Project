# EventPro - Premium Event Management System

A full-stack event management website built with HTML, CSS, JavaScript, and PHP. Features a stunning modern design with premium aesthetics and comprehensive event planning capabilities.

## ✨ Features

### 🎨 Premium Design
- **Modern UI/UX** - Sleek dark theme with vibrant gradients
- **Glassmorphism Effects** - Beautiful backdrop blur and transparency
- **Smooth Animations** - Micro-interactions and page transitions
- **Fully Responsive** - Works perfectly on all devices
- **Custom Color Palette** - Carefully curated HSL-based colors

### 🔐 Authentication System
- User registration with validation
- Secure login with PHP sessions
- Password hashing with bcrypt
- Session management

### 🎉 Event Types
1. **Birthday Parties** 🎂
2. **Weddings** 💒
3. **Corporate Events** 🏢
4. **Anniversaries** 💝
5. **Graduations** 🎓
6. **General Parties** 🎉

### 📋 Event Management
- Create events with detailed information
- View all your events
- Delete events
- Track event status
- Manage budgets and guest lists

## 🚀 Installation & Setup

### Prerequisites
- **XAMPP** or **WAMP** (Apache + MySQL + PHP)
- Modern web browser
- Text editor (optional, for customization)

### Step-by-Step Installation

#### 1. Install XAMPP
1. Download XAMPP from [https://www.apachefriends.org](https://www.apachefriends.org)
2. Install XAMPP to `C:\xampp` (or your preferred location)
3. Start **Apache** and **MySQL** from XAMPP Control Panel

#### 2. Setup Project Files
1. Copy the entire project folder to:
   ```
   C:\xampp\htdocs\event-management
   ```
   (Or your XAMPP htdocs directory)

#### 3. Database Configuration
The database will be created automatically on first run! Just make sure MySQL is running in XAMPP.

**Default Configuration:**
- **Host:** localhost
- **Username:** root
- **Password:** (empty)
- **Database:** event_management

If you need to change these settings, edit `config/database.php`:
```php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'event_management');
```

#### 4. Access the Application
1. Make sure Apache and MySQL are running in XAMPP
2. Open your browser and navigate to:
   ```
   http://localhost/event-management/
   ```

## 📁 Project Structure

```
event-management/
├── assets/
│   ├── css/
│   │   ├── style.css          # Main stylesheet
│   │   └── auth.css           # Authentication page styles
│   └── js/
│       └── event-templates.js # Template configuration
├── config/
│   ├── database.php           # Database configuration & setup
│   └── session.php            # Session management
├── index.html                 # Landing page
├── login.php                  # Login page
├── register.php               # Registration page
├── dashboard.php              # Main dashboard
├── my-events.php              # Event listing page
├── event-birthday.php         # Birthday event form
├── event-wedding.php          # Wedding event form
├── event-corporate.php        # Corporate event form
├── event-anniversary.php      # Anniversary event form
├── event-graduation.php       # Graduation event form
├── event-party.php            # General party form
├── delete-event.php           # Event deletion handler
├── logout.php                 # Logout handler
└── README.md                  # This file
```

## 🎯 Usage Guide

### Creating an Account
1. Go to `http://localhost/event-management/`
2. Click "Create Account"
3. Fill in your details:
   - Full Name
   - Username
   - Email
   - Password (minimum 6 characters)
4. Click "Create Account"

### Logging In
1. Navigate to the login page
2. Enter your username/email and password
3. Click "Sign In"

### Creating an Event
1. After logging in, you'll see the dashboard
2. Choose an event type (Birthday, Wedding, etc.)
3. Fill in the event details:
   - Event Name
   - Date and Time
   - Venue
   - Expected Guests
   - Budget
   - Description
4. Click "Create Event"

### Managing Events
1. Go to "My Events" from the navigation
2. View all your events with full details
3. Delete events if needed

## 🎨 Design Features

### Color Palette
- **Primary:** Purple gradient (HSL 280, 85%, 55%)
- **Secondary:** Blue (HSL 200, 100%, 55%)
- **Accent:** Pink (HSL 340, 85%, 55%)
- **Background:** Dark theme with subtle gradients

### Typography
- **Headings:** Playfair Display (serif, elegant)
- **Body:** Inter (sans-serif, modern)

### Animations
- Fade in/up animations on page load
- Hover effects on cards and buttons
- Smooth transitions throughout
- Parallax scrolling on landing page

## 🔧 Customization

### Changing Colors
Edit `assets/css/style.css` and modify CSS variables:
```css
:root {
    --primary: hsl(280, 85%, 55%);
    --secondary: hsl(200, 100%, 55%);
    --accent: hsl(340, 85%, 55%);
    /* ... more variables ... */
}
```

### Adding New Event Types
1. Create a new PHP file: `event-[type].php`
2. Copy structure from existing event pages
3. Update the icon and titles
4. Add the event card to `dashboard.php`

## 🛠️ Troubleshooting

### Database Connection Issues
- Make sure MySQL is running in XAMPP
- Check credentials in `config/database.php`
- Ensure port 3306 is not blocked

### Page Not Found (404)
- Verify files are in `htdocs` directory
- Check the URL path matches your folder name
- Ensure Apache is running

### Session Issues
- Clear browser cookies
- Check PHP session configuration
- Restart Apache server

### Styling Not Loading
- Clear browser cache
- Check file paths in HTML `<link>` tags
- Verify CSS files exist in `assets/css/`

## 📊 Database Schema

### Users Table
- `id` - Primary key
- `username` - Unique username
- `email` - Email address
- `password` - Hashed password
- `full_name` - User's full name
- `created_at` - Registration timestamp

### Events Table
- `id` - Primary key
- `user_id` - Foreign key to users
- `event_type` - Type of event
- `event_name` - Event name
- `event_date` - Event date
- `event_time` - Event time
- `venue` - Event location
- `guests` - Expected guest count
- `budget` - Event budget
- `description` - Event details
- `status` - Event status
- `created_at` - Creation timestamp

## 🔒 Security Features

- Password hashing using PHP `password_hash()`
- SQL injection prevention with prepared statements
- XSS prevention with `htmlspecialchars()`
- Session-based authentication
- User ownership verification for event operations

## 🌟 Future Enhancements

Potential features for future versions:
- Event editing functionality
- Image uploads for events
- Calendar view
- Email notifications
- Event sharing
- Guest management
- Budget tracking
- Vendor management
- Event templates
- Export to PDF

## 📝 License

This project is open source and available for educational purposes.

## 👨‍💻 Support

For issues or questions:
1. Check the troubleshooting section
2. Verify XAMPP is properly configured
3. Ensure all files are in the correct location
4. Check PHP error logs in XAMPP

## 🎉 Credits

Built with:
- HTML5
- CSS3 (with custom design system)
- JavaScript (Vanilla)
- PHP 7.4+
- MySQL
- Google Fonts (Inter, Playfair Display)

---

**Enjoy planning your events with EventPro! 🎊**
