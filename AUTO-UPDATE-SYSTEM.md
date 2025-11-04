<!--
  Copyright (c) 2025 NAME.
  All rights reserved.
  Unauthorized copying, modification, distribution, or use of this is prohibited without express written permission.
-->

# 🚀 BlackVault SUPREME Auto-Update System

**Copyright © 2025 Mr. jwswain - 3000 Studios. All Rights Reserved.**

## ⚡ Overview

The 3000 Studios theme now features a **fully autonomous auto-update system** that:

- 🔍 **Watches** all theme files for changes in real-time
- 🔢 **Auto-increments** version numbers on every update
- 🔄 **Triggers** instant browser refresh when files change
- 📦 **Auto-commits** and pushes to Git (optional)
- 🚀 **Auto-deploys** to production via GitHub Actions
- 💾 **Cache-busts** to ensure fresh content

---

## 🎯 Features

### 1. File Watcher (`auto-update-watcher.sh`)
Monitors theme files and triggers actions on change:
- PHP, CSS, JS files
- Assets directory
- Includes directory

**Actions performed:**
- ✅ Version bump
- ✅ Cache clearing
- ✅ Git commit (optional)
- ✅ Browser refresh trigger

### 2. Live Reload Server (`refresh-server.js`)
WebSocket + HTTP server for instant browser refresh:
- **HTTP Trigger:** `http://localhost:3001/refresh`
- **WebSocket:** `ws://localhost:3002`
- Broadcasts to all connected browsers

### 3. Live Reload Injection (`live-reload-inject.php`)
WordPress integration that injects refresh client:
- Auto-connects to WebSocket server
- Fallback HTTP polling
- Visual reload notifications
- Cache-busting query strings

### 4. Version Bumper (`version-bump.js`)
Automatically increments version in:
- `style.css` (WordPress theme version)
- `package.json` (npm version)
- Updates build timestamp

### 5. GitHub Actions Auto-Deploy (`.github/workflows/auto-deploy.yml`)
CI/CD pipeline that:
- ✅ Bumps version on push
- ✅ Installs & audits dependencies
- ✅ Packages theme as ZIP
- ✅ Deploys via FTP to production
- ✅ Creates GitHub releases
- ✅ Triggers WordPress refresh

---

## 🔥 Quick Start

### Local Development with Auto-Refresh

```bash
# Start file watcher + refresh server (recommended)
npm run dev

# Or start separately:
npm run watch              # File watcher only
npm run refresh-server     # Refresh server only

# With auto-commit:
npm run watch:commit       # Auto-commit on changes

# With auto-push:
npm run watch:push         # Auto-commit + auto-push
```

### Manual Version Bump

```bash
npm run version:bump
```

### Deployment

Push to `main` branch triggers automatic deployment:

```bash
git add .
git commit -m "Your changes"
git push origin main
```

GitHub Actions will:
1. Bump version
2. Package theme
3. Deploy to production
4. Create release
5. Refresh live site

---

## ⚙️ Configuration

### Enable Live Reload in WordPress

Add to `wp-config.php`:

```php
// Enable live reload (development only)
define('WP_DEBUG', true);
define('STUDIOS_LIVE_RELOAD', true);
```

### GitHub Actions Secrets

Configure in GitHub repository settings:

| Secret | Description | Required |
|--------|-------------|----------|
| `FTP_SERVER` | FTP hostname | For FTP deploy |
| `FTP_USERNAME` | FTP username | For FTP deploy |
| `FTP_PASSWORD` | FTP password | For FTP deploy |
| `SITE_REFRESH_URL` | WordPress site URL | For refresh trigger |
| `WP_API_TOKEN` | WordPress API token | For refresh trigger |

---

## 📁 File Structure

```
3000studiostheme/
├── scripts/
│   ├── auto-update-watcher.sh    # File watcher daemon
│   ├── refresh-server.js          # WebSocket refresh server
│   └── version-bump.js            # Version incrementer
├── includes/
│   └── live-reload-inject.php     # WordPress live reload client
├── .github/
│   └── workflows/
│       └── auto-deploy.yml        # CI/CD pipeline
└── package.json                   # npm scripts
```

---

## 🧠 How It Works

### Local Development Flow

```
1. Edit theme file (PHP/CSS/JS)
   ↓
2. Watcher detects change
   ↓
3. Version auto-incremented
   ↓
4. Cache cleared
   ↓
5. Refresh signal sent via WebSocket
   ↓
6. Browser reloads instantly
   ↓
7. (Optional) Git commit + push
```

### Production Deployment Flow

```
1. Push to main branch
   ↓
2. GitHub Actions triggered
   ↓
3. Version bumped
   ↓
4. Dependencies installed & audited
   ↓
5. Theme packaged as ZIP
   ↓
6. Deployed via FTP
   ↓
7. WordPress refresh triggered
   ↓
8. GitHub release created
```

---

## 🎨 Browser Client Features

When live reload is active, you'll see:

```
⚡ 3000 Studios Live Reload Active
✅ Live reload connected
```

On update:
```
🔄 Theme updated - Reloading...
```

Visual notification appears in top-right corner with neon styling.

---

## 🛡️ Security

- **Development only:** Live reload is disabled in production (checks `WP_DEBUG`)
- **WebSocket:** Localhost only (`ws://localhost:3002`)
- **No credentials:** No sensitive data in client code
- **Fail-safe:** Falls back to HTTP polling if WebSocket unavailable

---

## 🚨 Troubleshooting

### Live Reload Not Working

1. Check if refresh server is running:
   ```bash
   curl http://localhost:3001/ping
   ```

2. Check WordPress debug mode:
   ```php
   // In wp-config.php
   define('WP_DEBUG', true);
   ```

3. Check browser console for errors

### File Watcher Not Detecting Changes

1. Install inotify-tools (Linux):
   ```bash
   sudo apt-get install inotify-tools
   ```

2. Increase inotify limits:
   ```bash
   echo fs.inotify.max_user_watches=524288 | sudo tee -a /etc/sysctl.conf
   sudo sysctl -p
   ```

### Deployment Failing

1. Check GitHub Actions logs
2. Verify FTP credentials in secrets
3. Ensure FTP path is correct

---

## 💡 Pro Tips

### Speed Up Development

```bash
# Terminal 1: Watch files with auto-commit
npm run watch:commit

# Terminal 2: Run WordPress locally
# Your browser will auto-refresh on every save
```

### Prevent Auto-Push

```bash
# Watch and commit locally, but don't push
npm run watch:commit
```

### Manual Refresh Trigger

```bash
# Trigger refresh manually
curl http://localhost:3001/refresh
```

### Check Connected Clients

```bash
curl http://localhost:3001/ping
# Returns: {"status":"alive","clients":2,"uptime":123.456}
```

---

## 📊 Version History

All versions are automatically tracked in:
- Git commits
- GitHub releases
- `style.css` header
- `package.json`

View release history:
```bash
git tag -l
```

---

## 🔮 Future Enhancements

- [ ] Hot Module Replacement (HMR)
- [ ] Multi-site sync
- [ ] Mobile app integration
- [ ] A/B test auto-rollback
- [ ] Performance metrics tracking
- [ ] AI-powered update suggestions

---

## 📞 Support

**3000 Studios**  
Web: https://3000studios.com  
GitHub: https://github.com/3000Studios  

---

## 📜 License

**Copyright © 2025 Mr. jwswain & 3000 Studios. All Rights Reserved.**

Unauthorized copying, modification, distribution, or use is strictly prohibited without express written permission.

---

**Built with BlackVault SUPREME Technology**  
*"The future updates itself."*
