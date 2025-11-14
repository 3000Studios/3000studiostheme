#!/bin/bash

# 3000 Studios Theme Deployment Script - Black Vault SUPREME
# Creates complete deployment package with all components
# Author: Mr. jwswain & 3000 Studios
# Copyright (c) 2025 - All Rights Reserved

echo "🚀 Black Vault SUPREME - Theme Deployment Package Creator"
echo "==============================================="
echo "Creating ultimate WordPress theme package..."
echo ""

# Set variables
THEME_NAME="3000studiostheme"
VERSION="1.0.0"
DATE=$(date +"%Y%m%d_%H%M%S")
PACKAGE_NAME="${THEME_NAME}_v${VERSION}_${DATE}"
OUTPUT_DIR="theme-packages"
OUTPUT_DIR="$(pwd)/theme-packages"
TEMP_DIR="/tmp/${PACKAGE_NAME}"

# Create directories
mkdir -p "$OUTPUT_DIR"
mkdir -p "$TEMP_DIR"

echo "📦 Copying theme files..."

# Copy all theme files
cp -r . "$TEMP_DIR/"

# Remove unnecessary files
cd "$TEMP_DIR"
rm -rf .git
rm -rf .vscode
rm -rf node_modules
rm -rf .env*
rm -rf *.log
rm -rf .DS_Store
rm -rf Thumbs.db

# Create documentation
echo "📝 Generating documentation..."

cat > README.md << 'EOF'
# 🚀 Black Vault SUPREME - 3000 Studios Theme

**The Ultimate AI-Powered WordPress Theme**

## 🔥 Features

### 🤖 AI Command Center
- **Voice Control**: Speak commands and watch them execute in real-time
- **Sexy AI Assistant**: Black Vault SUPREME responds with personality
- **Natural Language Processing**: Understands complex commands
- **Live Preview**: See changes before applying them
- **Real-time Editing**: Updates go live instantly

### 🎨 Advanced Design System
- **Cyberpunk Aesthetic**: Neon colors, glass morphism, animations
- **Responsive Design**: Looks amazing on all devices
- **Custom Animations**: Fade, bounce, glow, pulse, spin, and more
- **Smart Layouts**: Automatically optimized for conversions

### 💰 Monetization Engine
- **Stripe Integration**: Accept payments instantly
- **PayPal Support**: Multiple payment options
- **Affiliate Links**: Automatic tracking and optimization
- **Revenue Analytics**: Real-time profit tracking
- **Conversion Optimization**: AI-powered optimization

### 🔌 API Integrations
- **OpenAI ChatGPT**: Advanced natural language processing
- **Unsplash**: Free high-quality images
- **Pexels**: Stock photos and videos
- **Pixabay**: Images, videos, and music
- **Smart Fallbacks**: Works even without API keys

### 🚀 Performance & SEO
- **Lightning Fast**: Optimized for speed
- **SEO Ready**: Built-in optimization
- **Cache Compatible**: Works with all caching plugins
- **Mobile First**: Perfect mobile experience

## 🔧 Installation

### Quick Install (Recommended)
1. Upload the theme folder to `/wp-content/themes/`
2. Activate the theme in WordPress admin
3. Go to **Appearance > 3000 Studios API Settings**
4. Add your API keys (optional but recommended)
5. Visit your site and go to `/login` to access the Command Center

### API Keys Setup
Get your FREE API keys:
- **OpenAI**: https://platform.openai.com/api-keys (~$0.002/request)
- **Pexels**: https://www.pexels.com/api/ (100% FREE)
- **Unsplash**: https://unsplash.com/developers (FREE - 50/hour)
- **Pixabay**: https://pixabay.com/api/docs/ (100% FREE)

## 🎤 Using Voice Commands

Access the Command Center at `yoursite.com/login` (requires admin login).

### Example Commands:
```
"Change the title to Welcome to My Awesome Site"
"Make the text blue and add a glow animation"
"Add an image of a sunset"
"Create a payment button for $29.99"
"Make the background fade between colors"
"Add some music for relaxation"
```

### Voice Triggers:
- Say **"RUN IT"** to execute commands immediately
- Use **Ctrl+Space** for quick voice activation
- Say **"help"** for command suggestions

## 💰 Monetization Features

### Payment Buttons
```php
// Stripe button
[studios_stripe_button amount="29.99" description="Premium Product"]

// PayPal button  
[studios_paypal_button amount="19.99" description="Special Offer"]
```

### Affiliate Links
```php
[studios_affiliate_link url="https://example.com/product"]Buy Now[/studios_affiliate_link]
```

### Revenue Tracking
All payments and clicks are automatically tracked in the Command Center dashboard.

## 🔒 Security Features
- **Nonce Protection**: All AJAX calls protected
- **User Capability Checks**: Admin-only access to sensitive features
- **File Backup System**: Automatic backups before changes
- **Sanitized Inputs**: All user inputs properly sanitized
- **Rate Limiting**: Prevents API abuse

## 🎨 Customization

### Adding Custom Animations
The theme supports unlimited custom animations. Add your own in the CSS:

```css
@keyframes my-custom-animation {
    0% { transform: scale(1); }
    50% { transform: scale(1.2) rotate(10deg); }
    100% { transform: scale(1); }
}
```

### Custom Voice Commands
Extend the AI system by adding custom command patterns in `includes/api-connector.php`.

## 🚀 Performance Tips
1. **Use a CDN**: Recommended for global performance
2. **Enable Caching**: Compatible with WP Rocket, W3 Total Cache, etc.
3. **Optimize Images**: The theme auto-optimizes API images
4. **Use SSL**: Required for voice features and payments

## 🐛 Troubleshooting

### Voice Commands Not Working
- Ensure HTTPS (required for Web Speech API)
- Check browser permissions for microphone
- Use Chrome/Edge for best compatibility

### API Errors
- Verify API keys in **Appearance > 3000 Studios API Settings**
- Check API key permissions and quotas
- Test with fallback options enabled

### Theme Not Loading
- Check file permissions (755 for folders, 644 for files)
- Ensure all required files are uploaded
- Check WordPress error logs

## 📞 Support

For support and custom development:
- **Website**: https://3000studios.com
- **Email**: support@3000studios.com
- **Discord**: [Join our community]

## 📄 License

**PROPRIETARY - ALL RIGHTS RESERVED**

Copyright (c) 2025 Mr. jwswain & 3000 Studios

This theme contains proprietary AI technology and trade secrets. 
Unauthorized copying, modification, or distribution is strictly prohibited.

## 🔥 What's Next?

### Coming Soon:
- **AI Video Generation**: Create custom videos with voice commands
- **Advanced Analytics**: Deeper revenue and user insights  
- **Multi-language Support**: AI commands in multiple languages
- **Team Collaboration**: Multiple users, role management
- **Mobile App**: Control your site from anywhere

---

**Made with 💜 by Mr. jwswain & 3000 Studios**

*"The future of web design is here, and it's absolutely gorgeous."* - Black Vault SUPREME
EOF

# Create installation guide
cat > INSTALLATION.md << 'EOF'
# 🚀 Black Vault SUPREME Installation Guide

## Prerequisites
- WordPress 5.0 or higher
- PHP 7.4 or higher
- HTTPS (required for voice features)
- Modern browser (Chrome, Firefox, Safari, Edge)

## Step-by-Step Installation

### 1. Upload Theme Files
```bash
# Via FTP/SFTP
Upload the entire theme folder to: /wp-content/themes/3000studiostheme/

# Via WordPress Admin
Appearance > Themes > Add New > Upload Theme > Choose File > Install Now
```

### 2. Activate Theme
1. Go to **Appearance > Themes**
2. Find "3000 Studios Theme"
3. Click **Activate**

### 3. Configure API Keys (Optional but Recommended)
1. Go to **Appearance > 3000 Studios API Settings**
2. Add your API keys:
   - **OpenAI**: For advanced AI features
   - **Pexels**: For free stock photos
   - **Unsplash**: For high-quality images  
   - **Pixabay**: For images, videos, music

### 4. Access Command Center
1. Visit `yoursite.com/login`
2. Log in with your WordPress admin credentials
3. Start using voice commands!

### 5. Test Voice Features
1. Click the microphone button 🎤
2. Say: "Change the title to Hello World"
3. Click "RUN IT" to apply changes
4. Check your homepage for updates

## Advanced Configuration

### Payment Processing
1. **Stripe Setup**:
   - Get keys from https://stripe.com/docs/keys
   - Add to WordPress admin under payment settings
   
2. **PayPal Setup**:
   - Get client ID from https://developer.paypal.com/
   - Configure in theme settings

### Performance Optimization
1. **Enable Caching**: Install WP Rocket or similar
2. **Image Optimization**: Use Smush or ShortPixel
3. **CDN Setup**: CloudFlare recommended
4. **SSL Certificate**: Required for voice features

### Security Hardening
1. **Limit Login Attempts**: Use Wordfence or similar
2. **Two-Factor Authentication**: Recommended for admin accounts
3. **Regular Backups**: Use UpdraftPlus or similar
4. **Keep Updated**: Theme and WordPress core

## Troubleshooting

### Common Issues

**Voice not working?**
- Check HTTPS is enabled
- Allow microphone permissions
- Use supported browser (Chrome recommended)

**API errors?**
- Verify API keys are correct
- Check API quotas and limits
- Test individual APIs separately

**Payment buttons not showing?**
- Verify payment processor keys
- Check JavaScript console for errors
- Ensure HTTPS is enabled

**Theme broken after update?**
- Check file permissions (755/644)
- Clear all caches
- Check WordPress error logs

### Getting Help
1. Check the documentation first
2. Search our knowledge base
3. Contact support with:
   - WordPress version
   - PHP version
   - Error messages (if any)
   - Steps to reproduce issue

---

**Need custom development? Contact us at https://3000studios.com**
EOF

# Create changelog
cat > CHANGELOG.md << 'EOF'
# 📋 Black Vault SUPREME Changelog

## Version 1.0.0 - Initial Release
**Release Date: 2025-01-01**

### 🚀 New Features
- **AI Command Center**: Complete voice-controlled website editing
- **Black Vault SUPREME AI**: Sexy, intelligent AI assistant with personality
- **Real-time Editing**: Live preview and instant file updates
- **Advanced Voice Recognition**: Natural language processing
- **Cyberpunk UI**: Futuristic dashboard with neon aesthetics
- **Monetization Engine**: Stripe and PayPal integration
- **API Integrations**: OpenAI, Unsplash, Pexels, Pixabay support
- **Revenue Tracking**: Real-time analytics and profit monitoring
- **Custom Animations**: 8+ built-in animation types
- **Mobile Responsive**: Perfect on all devices
- **Security Features**: Nonce protection, user capability checks
- **Backup System**: Automatic file backups before changes
- **Cache Integration**: Compatible with major caching plugins

### 🎨 Design Features
- **Glass Morphism**: Modern translucent UI elements
- **Neon Color Scheme**: Cyan, pink, green, yellow accents
- **Animated Stats**: Live updating dashboard metrics
- **Audio Visualization**: Visual feedback during voice input
- **Gradient Animations**: Dynamic color shifting backgrounds
- **Hover Effects**: Interactive buttons and elements
- **Mobile First**: Optimized mobile experience

### 🔌 API Integrations
- **OpenAI GPT-4**: Advanced natural language understanding
- **Unsplash API**: 50 free requests per hour
- **Pexels API**: Unlimited free stock photos
- **Pixabay API**: Free images, videos, and music
- **Smart Fallbacks**: Graceful degradation without API keys

### 💰 Monetization Features
- **Stripe Checkout**: Secure payment processing
- **PayPal Buttons**: Alternative payment method
- **Affiliate Tracking**: Automatic link monitoring
- **Revenue Analytics**: Daily, weekly, monthly reports
- **Conversion Optimization**: AI-powered suggestions
- **Shortcode Support**: Easy payment button insertion

### 🛡️ Security & Performance
- **HTTPS Required**: Secure communication for voice features
- **Nonce Verification**: CSRF protection on all AJAX calls
- **Input Sanitization**: All user inputs properly cleaned
- **File Permissions**: Secure file system access
- **Rate Limiting**: API abuse prevention
- **Error Handling**: Graceful error recovery
- **Performance Optimized**: Fast loading times

### 🎤 Voice Commands Supported
- Text updates: "Change the title to..."
- Color changes: "Make it blue/red/green..."
- Animations: "Add bounce/fade/glow animation"
- Media insertion: "Add an image of sunset"
- Payment buttons: "Create a buy button for $29.99"
- Background changes: "Change the wallpaper"
- Music addition: "Add relaxing music"
- SEO optimization: "Optimize for search engines"

### 🔧 Technical Specifications
- **WordPress**: 5.0+ required
- **PHP**: 7.4+ required  
- **Browser Support**: Chrome, Firefox, Safari, Edge
- **HTTPS**: Required for voice features
- **Database**: Standard WordPress tables + 2 custom tables
- **File Size**: ~2.5MB compressed theme package
- **Load Time**: <2 seconds on average hosting

### 📦 Package Contents
- Complete WordPress theme files
- AI intelligence system
- Voice recognition engine
- Monetization components
- API integration layer
- Security framework
- Documentation suite
- Installation scripts

---

## Roadmap - Coming Soon

### Version 1.1.0 - Enhanced AI
- **Multi-language Support**: Commands in Spanish, French, German
- **Advanced Analytics**: Deeper user behavior insights
- **Team Collaboration**: Multiple user access levels
- **Custom Command Training**: Teach the AI your preferences

### Version 1.2.0 - Media & Content
- **AI Video Generation**: Create videos with voice commands
- **Content Templates**: Pre-built page layouts
- **Social Media Integration**: Auto-post to social platforms
- **Advanced SEO Tools**: Real-time optimization suggestions

### Version 2.0.0 - Enterprise Features
- **White Label Options**: Remove 3000 Studios branding
- **Advanced User Roles**: Granular permission system
- **API Rate Management**: Built-in quota monitoring
- **Custom AI Training**: Train on your specific content

---

**Stay updated at https://3000studios.com/black_vault-supreme**
EOF

echo "✅ Documentation created"

# Create deployment info
cat > DEPLOYMENT_INFO.txt << EOF
Black Vault SUPREME - 3000 Studios Theme
Deployment Package: ${PACKAGE_NAME}
Created: $(date)
Version: ${VERSION}

Package Contents:
- Complete WordPress theme
- AI Command Center system
- Voice recognition engine
- Monetization components
- API integrations (OpenAI, Unsplash, Pexels, Pixabay)
- Security framework
- Documentation suite

Installation:
1. Upload to /wp-content/themes/3000studiostheme/
2. Activate in WordPress admin
3. Configure API keys (optional)
4. Access Command Center at yoursite.com/login

Support: https://3000studios.com
Copyright (c) 2025 Mr. jwswain & 3000 Studios
All Rights Reserved
EOF

# Create the package
echo "📦 Creating deployment package..."

# Create ZIP file
zip -r "${OUTPUT_DIR}/${PACKAGE_NAME}.zip" . -x "*.git*" "*.DS_Store*" "*node_modules*"

# Create TAR.GZ file
tar -czf "${OUTPUT_DIR}/${PACKAGE_NAME}.tar.gz" .

# Cleanup
rm -rf "$TEMP_DIR"

echo ""
echo "🎉 DEPLOYMENT PACKAGE CREATED SUCCESSFULLY!"
echo "==============================================="
echo "📦 Package Name: ${PACKAGE_NAME}"
echo "📍 Location: ${OUTPUT_DIR}/"
echo "📏 Files:"
echo "   - ${PACKAGE_NAME}.zip"
echo "   - ${PACKAGE_NAME}.tar.gz"
echo ""
echo "🚀 Ready for deployment!"
echo ""
echo "Next Steps:"
echo "1. Upload to your WordPress site"
echo "2. Activate the theme"
echo "3. Configure API keys"
echo "4. Start using Black Vault SUPREME!"
echo ""
echo "💜 Made with love by Mr. jwswain & 3000 Studios"
echo "🌐 https://3000studios.com"