# 🏐 Volleyball Tournament 2025 - Registration System

Web system for team registration in the 2025 Volleyball Tournament. Developed with PHP, MySQL and JavaScript.

[![Español](https://img.shields.io/badge/Español-README.md-blue)](README.md)
[![Version](https://img.shields.io/badge/version-v0.1.5-green)](CHANGELOG.md)
[![Changelog](https://img.shields.io/badge/changelog-view%20history-blue)](CHANGELOG.md)

## 📋 What is it?

This is a web system that allows teams to register to participate in the 2025 Volleyball Tournament. Teams can:

- Select their grade (1st to 7th)
- Choose their division (A, B, C for basic; 1st, 2nd for advanced)
- Register team name
- Select game system (6:0, 4:2, 5:1)
- Add members with their positions
- Choose jersey color (with duplicate validation)
- View all registered teams organized by cycles

## 🚀 Installation

### Requirements
- PHP 8.2+ (tested with 8.2.12)
- MySQL 10.4+ / MariaDB 10.4+ (tested with MariaDB 10.4.32)
- Apache 2.4+ (tested with 2.4.58)
- XAMPP 3.3.0+ (recommended)

### Steps

1. **Clone the repository**
   \`\`\`bash
   git clone https://github.com/CapriaFranco/vit-gestor.git
   cd vit-gestor
   \`\`\`

2. **Configure the database**
   \`\`\`bash
   # Copy the example file
   cp php/db.example.php php/db.php
   
   # Edit with your data
   nano php/db.php
   \`\`\`

3. **Import the database**
   \`\`\`sql
   -- For local development
   mysql -u root -p < sql/db.sql
   
   -- For InfinityFree
   mysql -u user -p < sql/db-infinityfree.sql
   \`\`\`

4. **Configure the server**
   - **Local**: Place in `htdocs/vit-gestor/`
   - **InfinityFree**: Upload to `htdocs` root

## 🎯 How it works

### Registration flow

1. **Main page** → Registration form
2. **Grade selection** → Division is enabled
3. **Team name** → Game system is enabled
4. **Game system** → Members table is enabled
5. **Members** → Color selection is enabled
6. **Jersey color** → Submit button is enabled
7. **Submit** → Success page

### Validations

- ✅ Required fields
- ✅ Unique colors per grade
- ✅ Valid positions according to game system
- ✅ Duplicate prevention

### Technologies

- **Backend**: PHP 8.2.12
- **Database**: MariaDB 10.4.32
- **Frontend**: HTML5, CSS3, JavaScript ES6+
- **Server**: Apache 2.4.58 (mod_rewrite)
- **Environment**: XAMPP 3.3.0

## 📁 Structure

\`\`\`
vit-gestor/
├── index.php              # Entry point
├── .htaccess              # Route configuration
├── php/
│   ├── db.example.php     # Configuration example
│   ├── db.php             # Real configuration (DO NOT upload)
│   ├── functions.php      # Helper functions
│   ├── clear_session.php  # Session cleanup
│   └── colores_ajax.php   # Colors API
├── pages/
│   ├── register/          # Registration page
│   ├── registered/        # Success page
│   ├── registered-teams/  # Registered teams view
│   └── err/               # 404 error page
├── assets/                # Images and fonts
├── scripts/               # JavaScript
├── styles/                # CSS
└── sql/                   # Database scripts
\`\`\`

## 🔧 Configuration

### Local database (XAMPP)

\`\`\`php
// php/db.php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "vit_gestor_db";
\`\`\`

### InfinityFree database

\`\`\`php
// php/db.php
$servername = "sql309.infinityfree.com";
$username = "your_username";
$password = "your_password";
$dbname = "your_database";
\`\`\`

## 🎨 Features

- **Responsive design** for mobile and desktop
- **Modern interface** with gradients and animations
- **Real-time validation** of colors
- **Dynamic position system** according to game system
- **Custom success and error pages**
- **Friendly routing** with .htaccess
- **Smart WhatsApp links** (automatically detects mobile/desktop)
- **Cross-browser compatibility** optimized with autoprefixer
- **Pattern validations** for special characters in Spanish
- **Configured field lengths** (3-100 characters for team, 4-100 for members/color)
- **Registered teams view** organized by cycles (Basic/Advanced)
- **Visual indicators** for substitutes and captains
- **Statistics counters** for registered teams and people
- **Optimized tables** with horizontal scroll on mobile

## 📊 Versions

### 🚀 Current: v0.1.5
- ✅ Fixed WhatsApp compatibility for meta tags
- ✅ Additional Open Graph metadata (width, height, type, secure_url, alt)
- ✅ Absolute URLs instead of relative for better compatibility
- ✅ Clarification message about using full names in registration
- ✅ Fixed image extension (.jpg instead of .png)

### 📝 Complete history
See [CHANGELOG.md](CHANGELOG.md) for detailed change history.

## 📱 Usage

### Registration
1. Access the main page
2. Complete the form step by step
3. Submit the registration
4. Receive success confirmation

### View registered teams
1. Access `/teams` to see all registered teams
2. Teams are organized by cycles (Basic/Advanced)
3. View team details, members and positions
4. Use the legend to understand position indicators

## 🤝 Contribution

1. Fork the project
2. Create a branch (`git checkout -b feature/new-feature`)
3. Commit your changes (`git commit -m 'Add new feature'`)
4. Push to the branch (`git push origin feature/new-feature`)
5. Open a Pull Request

## 👥 Author

**Capria Franco** - [GitHub](https://github.com/CapriaFranco)

--- 

⭐ **Give it a star if you like the project!** ⭐
