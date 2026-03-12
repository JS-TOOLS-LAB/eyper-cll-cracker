```markdown
# 🔥 EYPER CLL - Password Security Testing Tool

![License](https://img.shields.io/badge/license-MIT-blue.svg)
![PHP Version](https://img.shields.io/badge/PHP-7.4%2B-purple)
![Version](https://img.shields.io/badge/version-5.0-green)
![JS Intergrated Labs](https://img.shields.io/badge/developed%20by-JS%20Intergrated%20Labs-orange)
![Security Tool](https://img.shields.io/badge/purpose-security-red)
![Ethical Use](https://img.shields.io/badge/usage-educational%20only-yellow)

```

```

## ⚖️ LEGAL DISCLAIMER

> **IMPORTANT**: This tool is for **EDUCATIONAL PURPOSES** and **AUTHORIZED TESTING ONLY**.

**Users are solely responsible for compliance with all applicable laws. The developers assume no liability for misuse.**

### ✅ Allowed Use:
- Testing your own systems
- Authorized penetration testing with written permission
- Educational environments and security research
- CTF competitions and labs

### ❌ Prohibited Use:
- Unauthorized access to any system
- Cracking passwords without consent
- Illegal activities of any kind
- Malicious purposes

---

## 📋 TABLE OF CONTENTS

- [Overview](#-overview)
- [Features](#-features)
- [Installation](#-installation)
- [Usage Guide](#-usage-guide)
- [Authentication System](#-authentication-system)
- [Cracking Modes](#-cracking-modes)
- [Hash Generator](#-hash-generator)
- [Supported Hash Types](#-supported-hash-types)
- [Performance](#-performance)
- [File Structure](#-file-structure)
- [Configuration](#-configuration)
- [Troubleshooting](#-troubleshooting)
- [Contributing](#-contributing)
- [License](#-license)
- [Contact](#-contact)

---

## 📖 OVERVIEW

**EYPER CLL** is an advanced password security testing tool developed by **JS Intergrated Labs**. It features a modern console interface with real-time visual feedback, multiple cracking modes, and high-performance hash verification. The tool includes a secure authentication system and is designed for educational purposes and authorized security assessments.

---

## ✨ FEATURES

| Feature | Description |
|---------|-------------|
| 🔐 **Authentication** | Secure file-based login system |
| 🔓 **Single Hash Cracking** | Interactive mode with verbose output |
| 📚 **Multiple Hash Cracking** | Batch process many hashes from a file |
| 🔑 **Hash Generator** | Generate hashes from password lists |
| 🚀 **High Performance** | 100+ passwords/sec (bcrypt), 100,000+ (MD5) |
| 🎨 **Modern UI** | Real-time progress bars and animations |
| 🔧 **Multi-Hash Support** | MD5, SHA1, SHA256, SHA384, SHA512, bcrypt |
| 📊 **Verbose Mode** | Detailed real-time progress |
| 💾 **Smart Caching** | LRU cache for repeated verification |
| 📦 **Batch Processing** | Memory-efficient handling |

---

## 💻 INSTALLATION

### Prerequisites

- PHP 7.4 or higher
- Command line interface (CLI)
- Termux (Android) or terminal (Linux/Mac/Windows)

### Quick Install

```bash
# Clone the repository
git clone https://github.com/js-interactive-labs/eyper-cll-cracker.git

# Navigate to directory
cd eyper-cll-cracker

# Make the script executable (Linux/Mac)
chmod +x start.php login.php eyper-cll.php

# Create necessary directories
mkdir -p data passwords hashes logs

# Set proper permissions
chmod 700 data
chmod 755 passwords hashes logs

# Create a sample password file
echo "password" > passwords/pass.txt
echo "123456" >> passwords/pass.txt
echo "admin" >> passwords/pass.txt
```

Directory Setup

```bash
eyper-cll-cracker/
├── start.php              # Main entry point
├── login.php              # Login handler
├── eyper-cll.php          # Single hash cracker
├── src/                   # Source code
├── data/                  # User data (protected)
├── passwords/             # Wordlists
├── hashes/                # Hash files
└── logs/                  # Log files
```

---

🎮 USAGE GUIDE

Starting the Application

```bash
php start.php
```

Authentication Flow

1. Welcome Screen - Choose Login or Create Account
2. Login - Enter username and password
3. Main Menu - Access all features
4. Logout - Return to welcome screen

Main Menu Options

```
╔════════════════════════════════════════════════════════════╗
║                    MAIN MENU                               ║
╠════════════════════════════════════════════════════════════╣
║  1. 🔓 Crack Single Password Hash                         ║
║  2. 📚 Crack Multiple Hashes from File                     ║
║  3. 🔐 Hash Password from List                             ║
║  4. ℹ️  About Us                                           ║
║  5. 🚪 Logout                                              ║
╚════════════════════════════════════════════════════════════╝
```

---

🔐 AUTHENTICATION SYSTEM

Creating an Account

```bash
# From start.php menu
Select option 2: Create New Account

# Enter username (alphanumeric only)
# Enter password (hidden input)
# Confirm password
```

Login

```bash
# From start.php menu
Select option 1: Login

# Enter username
# Enter password (hidden)
# 3 attempts allowed before timeout
```

Session Management

· Session persists until logout
· Stored in data/session.dat
· Automatically expires after inactivity

---

🔓 CRACKING MODES

Mode 1: Single Hash Cracker

Interactive mode with verbose option:

```bash
# From main menu
Select option 1

# Enter hash to crack
# Enter wordlist path
# Choose verbose mode (y/n)

# Results show:
# - Found password
# - Total attempts
# - Time elapsed
# - Cracking speed
```

Mode 2: Multiple Hash Cracker

Batch process multiple hashes from a file:

```bash
# From main menu
Select option 2

# Enter hash filename (from hashes/ directory)
# Enter wordlist path

# Format for hash file (one per line):
5f4dcc3b5aa765d61d8327deb882cf99
5baa61e4c9b93f3f0682250b6cf8331b7ee68fd8
$2y$12$X8RluqnC.b9FT4VkPp/q0.RY1sFk5K7qX5K7q

# Results saved to hashes/cracked_YYYYMMDD_HHMMSS.txt
```

---

🔑 HASH GENERATOR

Generate hashes from a password list:

```bash
# From main menu
Select option 3

# Enter password list file
# Choose hash type:
# - md5
# - sha1
# - sha256
# - bcrypt
# - all

# Output saved to hashes/generated_YYYYMMDD_HHMMSS.txt
# Format: password:hash
```

---

🔧 SUPPORTED HASH TYPES

Type Length Example
MD5 32 5f4dcc3b5aa765d61d8327deb882cf99
SHA1 40 5baa61e4c9b93f3f0682250b6cf8331b7ee68fd8
SHA224 56 d63dc919e201d7bc4c825630d2cf25fdc93d4b2f0d46706d29038d01
SHA256 64 5e884898da28047151d0e56f8dc6292773603d0d6aabbdd62a11ef721d1542d8
SHA384 96 a8b64babd0aca91a59bdbb7761b421d4f2bb38280d3a75ba0f21f2bebc45583d
SHA512 128 b109f3bbbc244eb82441917ed06d618b9008dd09b3befd1b5e07394c706a8bb9
bcrypt 60 $2y$12$X8RluqnC.b9FT4VkPp/q0.RY1sFk5K7qX5K7q

---

⚡ PERFORMANCE

Hash Type Speed (passwords/sec)
MD5 80,000 - 100,000
SHA1 60,000 - 80,000
SHA256 30,000 - 50,000
SHA512 15,000 - 25,000
bcrypt 100 - 200

Benchmarks on Intel i7 @ 2.6GHz with 16GB RAM

---

📁 FILE STRUCTURE

```
eyper-cll-cracker/
│
├── 📄 start.php                    # Main entry point
├── 📄 login.php                     # Login handler
├── 📄 eyper-cll.php                  # Single hash cracker
├── 📄 README.md                       # Documentation
├── 📄 LICENSE                         # MIT License
├── 📄 .gitignore                      # Git ignore rules
│
├── 📁 src/                            # Source code
│   ├── 📄 Auth.php                     # Authentication
│   ├── 📄 Banner.php                    # UI banners
│   ├── 📄 Config.php                     # Configuration
│   ├── 📄 Cracker.php                     # Cracking engine
│   ├── 📄 HashVerifier.php                 # Hash verification
│   ├── 📄 PasswordLoader.php                # Wordlist loader
│   ├── 📄 ProgressTracker.php                # Progress UI
│   ├── 📄 Session.php                         # Session management
│   └── 📄 Utils.php                           # Helper functions
│
├── 📁 data/                            # User data (protected)
│   ├── 📄 user-password.pwd              # Hashed credentials
│   └── 📄 session.dat                     # Session data
│
├── 📁 passwords/                       # Wordlist directory
│   ├── 📄 pass.txt                       # Default wordlist
│   └── 📄 README.md                       # Instructions
│
├── 📁 hashes/                          # Hash files directory
│   ├── 📄 md5_samples.txt                # Sample MD5 hashes
│   ├── 📄 mixed_samples.txt               # Sample mixed hashes
│   └── 📄 README.md                        # Instructions
│
└── 📁 logs/                            # Log directory
    └── 📄 .gitkeep                        # Keep directory
```

---

⚙️ CONFIGURATION

Edit src/Config.php to adjust settings:

```php
// Performance settings
define('BATCH_SIZE', 1000);        // Passwords per batch
define('PROGRESS_INTERVAL', 0.3);   // Progress update interval
define('CACHE_SIZE', 10000);        // Cache size for verification

// Paths
define('ROOT_PATH', __DIR__);
define('PASSWORDS_PATH', ROOT_PATH . '/passwords');
define('HASHES_PATH', ROOT_PATH . '/hashes');
define('DATA_PATH', ROOT_PATH . '/data');
define('LOGS_PATH', ROOT_PATH . '/logs');
```

---

🐛 TROUBLESHOOTING

Common Issues

Problem Solution
ROOT_PATH already defined Fixed in latest version
No such file or directory Run mkdir -p data passwords hashes logs
Permission denied Run chmod 755 start.php login.php eyper-cll.php
LOCK_EX not supported Fixed for Android/Termux compatibility
Login failed Check data/user-password.pwd exists
Wordlist not found Create passwords/pass.txt

Android/Termux Specific

```bash
# Install PHP in Termux
pkg install php

# Create directories
mkdir -p ~/storage/shared/eyper-cll/{data,passwords,hashes,logs}

# Navigate
cd ~/storage/shared/eyper-cll
```

---

🤝 CONTRIBUTING

We welcome contributions from the community!

How to Contribute

1. Fork the repository
2. Create a feature branch (git checkout -b feature/amazing-feature)
3. Commit your changes (git commit -m 'Add amazing feature')
4. Push to the branch (git push origin feature/amazing-feature)
5. Open a Pull Request

Contribution Guidelines

· Follow PSR-12 coding standards
· Add comments for complex logic
· Update documentation
· Maintain backward compatibility
· Include ethical use considerations

---

📄 LICENSE

MIT License

Copyright (c) 2024 JS Intergrated Labs

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.

---

📞 CONTACT

JS Intergrated Labs

· GitHub: github.com/js-interactive-labs
· Email: contact@js-interactive-labs.com
· Twitter: @JSIntergratedLabs
· WhatsApp: JS Intergrated Labs Official Group

Project Specific

· Repository: github.com/js-interactive-labs/eyper-cll-cracker
· Issues: github.com/js-interactive-labs/eyper-cll-cracker/issues
· Discussions: github.com/js-interactive-labs/eyper-cll-cracker/discussions

---

🙏 ACKNOWLEDGMENTS

· Security research community for ethical guidelines
· PHP community for documentation and support
· Open source contributors who make tools like this possible
· Ethical hackers who make the digital world safer

---

⭐ SUPPORT US

If you find this tool useful:

· ⭐ Star the repository on GitHub
· 🍴 Fork and contribute
· 📢 Share with fellow security researchers
· 🐛 Report issues and suggest features

---

<p align="center">
  <strong>Developed with ❤️ by JS Intergrated Labs</strong><br>
  <em>"Innovation Through Integration"</em><br>
  <em>"With great power comes great responsibility"</em>
</p>

<p align="center">
  <a href="https://github.com/js-interactive-labs/eyper-cll-cracker">GitHub Repository</a> |
  <a href="https://github.com/js-interactive-labs/eyper-cll-cracker/issues">Report Issue</a> |
  <a href="https://github.com/js-interactive-labs/eyper-cll-cracker/discussions">Discussions</a>
</p>

---

📌 QUICK START

```bash
# Clone and run in 1 minute
git clone https://github.com/js-interactive-labs/eyper-cll-cracker.git
cd eyper-cll-cracker
mkdir -p data passwords hashes logs
echo "password" > passwords/pass.txt
php start.php
```

Remember: Always stay legal, stay ethical, and use this knowledge to make the digital world safer! 🔐
