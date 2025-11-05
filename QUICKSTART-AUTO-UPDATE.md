<!--
  Copyright (c) 2025 NAME.
  All rights reserved.
  Unauthorized copying, modification, distribution, or use of this is prohibited without express written permission.
-->

# ⚡ 3000 Studios Theme - Auto-Update Quick Start

**Version 1.3** | **Last Updated:** November 4, 2025

---

## 🚀 What's New: Black Vault SUPREME Auto-Update System

Your theme now **updates itself automatically**:
- ✅ Real-time file watching
- ✅ Instant browser refresh
- ✅ Automatic version bumping
- ✅ Auto-deployment to production
- ✅ Zero-downtime updates

---

## ⚡ Quick Start (5 seconds)

### Development Mode with Live Reload

```bash
npm run dev
```

That's it! Your browser will now auto-refresh whenever you edit files.

---

## 📖 Common Commands

| Command | What It Does |
|---------|-------------|
| `npm run dev` | Start file watcher + live reload server |
| `npm run watch` | Watch files only (no auto-refresh) |
| `npm run watch:commit` | Watch + auto-commit changes |
| `npm run watch:push` | Watch + auto-commit + auto-push |
| `npm run version:bump` | Manually bump version |
| `npm run refresh-server` | Start refresh server only |

---

## 🎯 How It Works

```
Edit file → Auto-detect → Bump version → Clear cache → Refresh browser
```

**No manual refresh needed. Ever.**

---

## 🔧 Setup for WordPress

### 1. Enable Live Reload

Add to `wp-config.php`:

```php
define('WP_DEBUG', true);
define('STUDIOS_LIVE_RELOAD', true);
```

### 2. Start Development Server

```bash
npm run dev
```

### 3. Edit & Watch

Open your site in a browser. Edit any file. Watch it update instantly.

---

## 🚀 Production Deployment

### Automatic (Recommended)

```bash
git add .
git commit -m "Your changes"
git push origin main
```

GitHub Actions automatically:
1. Bumps version
2. Packages theme
3. Deploys to production
4. Creates release

### Manual

```bash
bash scripts/zip-theme.sh
```

Upload ZIP via WordPress admin.

---

## 📊 Version Control

Versions auto-increment on every change:
- `1.2` → `1.3` → `1.4` ...

View version:
```bash
grep "Version:" style.css
```

---

## 🛡️ Security

- Live reload only works in development (`WP_DEBUG = true`)
- WebSocket server runs locally only
- No external dependencies in production
- All auto-deploy uses encrypted secrets

---

## 🔥 Pro Tips

**Tip 1:** Keep `npm run dev` running while developing  
**Tip 2:** Use `npm run watch:commit` for automatic version tracking  
**Tip 3:** Push to `main` for instant production deployment  
**Tip 4:** Check browser console for live reload status  

---

## 📁 Key Files

- `scripts/auto-update-watcher.sh` - File watcher daemon
- `scripts/refresh-server.js` - Live reload server
- `includes/live-reload-inject.php` - WordPress integration
- `.github/workflows/auto-deploy.yml` - CI/CD pipeline

---

## 🐛 Troubleshooting

### Live Reload Not Working?

```bash
# Check if server is running
curl http://localhost:3001/ping

# Restart servers
npm run dev
```

### Files Not Being Watched?

```bash
# Install dependencies
sudo apt-get install inotify-tools

# Increase watch limit
echo fs.inotify.max_user_watches=524288 | sudo tee -a /etc/sysctl.conf
sudo sysctl -p
```

---

## 📖 Full Documentation

See `AUTO-UPDATE-SYSTEM.md` for complete documentation.

---

## 🎨 Theme Features

- 🌌 Animated galaxy background
- 🎪 Interactive ball pit footer
- 🤖 AI-powered dashboard
- 💰 Built-in monetization
- 📱 Mobile-optimized
- ⚡ Lightning-fast performance

---

## 📞 Support

**3000 Studios**  
🌐 https://3000studios.com  
📧 mr.jwswain@gmail.com  
💻 https://github.com/3000Studios  

---

## 📜 License

**Copyright © 2025 Mr. jwswain & 3000 Studios. All Rights Reserved.**

---

**Built with Black Vault SUPREME Technology**  
*"The future is always up to date."*
