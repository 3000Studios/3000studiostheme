# 📱 Mobile-to-Website Auto-Deploy System

Update your WordPress theme from your phone instantly!

## Architecture Overview

```
📱 Your Phone
    ↓ (HTTPS with HMAC signature)
🌐 Webhook Server (Node.js on VPS/Cloud)
    ↓ (GitHub API with token)
📦 GitHub Repository (commits changes)
    ↓ (GitHub Actions workflow)
🚀 WordPress Server (auto-deploys theme)
```

**Result**: Text from your phone → Live on website in ~2 minutes

## Quick Start (5 Steps)

### 1. Set Up GitHub Repository

First, create a GitHub repo and push your theme:

```powershell
# Configure Git identity
git config user.name "Your Name"
git config user.email "your@email.com"

# Create initial commit
git add -A
git commit -m "Initial commit: 3000 Studios theme"

# Create GitHub repo (via web or CLI)
# Then add remote and push:
git remote add origin https://github.com/3000Studios/3000studiostheme.git
git branch -M main
git push -u origin main
```

### 2. Create GitHub Personal Access Token

1. Go to https://github.com/settings/tokens
2. Click **Generate new token (classic)**
3. Give it a name: "3000Studios Webhook"
4. Select scopes: `repo` (full control)
5. Click **Generate token**
6. **Copy the token** (starts with `ghp_`) - you won't see it again!

### 3. Add GitHub Secrets for Auto-Deploy

In your GitHub repo:

1. Go to **Settings** → **Secrets and variables** → **Actions**
2. Add these secrets:

| Secret Name | Value |
|------------|-------|
| `WP_SSH_HOST` | `access-5018843677.webspace-host.com` |
| `WP_SSH_USER` | `a2413152` |
| `WP_SSH_PASSWORD` | `Gabby3000!!!` |
| `WP_SSH_PORT` | `22` |

### 4. Deploy Webhook Server

The webhook server accepts mobile requests and commits to GitHub.

**Option A: Deploy to Railway (Easiest)**

1. Go to https://railway.app
2. Click **New Project** → **Deploy from GitHub repo**
3. Select your `3000studiostheme` repo
4. Set the **Root Directory** to `webhook-server`
5. Add environment variables:
   - `GH_TOKEN` = Your GitHub token from step 2
   - `GH_OWNER` = `3000Studios`
   - `GH_REPO` = `3000studiostheme`
   - `WEBHOOK_SECRET` = Generate with: `node -e "console.log(require('crypto').randomBytes(32).toString('hex'))"`
   - `DEFAULT_BRANCH` = `main`
   - `PORT` = `3000`
6. Deploy and copy your Railway URL (e.g., `https://your-app.railway.app`)

**Option B: Deploy to Your Own VPS**

```bash
# SSH into your VPS
ssh user@your-vps.com

# Clone repo
git clone https://github.com/3000Studios/3000studiostheme.git
cd 3000studiostheme/webhook-server

# Install dependencies
npm install

# Create .env file
cp .env.example .env
nano .env  # Fill in your values

# Install PM2 to keep server running
npm install -g pm2

# Start server
pm2 start server.js --name 3000studios-webhook

# Make it start on boot
pm2 startup
pm2 save

# Open firewall port
sudo ufw allow 3000
```

**Option C: Run Locally (Testing Only)**

```powershell
cd webhook-server
npm install
cp .env.example .env
# Edit .env with your values
npm start
```

Use ngrok to expose locally:
```powershell
ngrok http 3000
# Copy the HTTPS URL
```

### 5. Test Mobile Updates

**From PowerShell (Windows):**

```powershell
.\mobile-client\mobile-update.ps1 `
  -WebhookUrl "https://your-webhook-server.com" `
  -Secret "your_webhook_secret" `
  -Action update-file `
  -Path "style.css" `
  -Content "/* Test from phone */" `
  -Message "Test update from mobile"
```

**From Bash (Linux/Mac/Termux on Android):**

```bash
export WEBHOOK_URL="https://your-webhook-server.com"
export WEBHOOK_SECRET="your_webhook_secret"

./mobile-client/mobile-update.sh update-file \
  "style.css" \
  "/* Test from phone */" \
  "Test update"
```

**Expected result:**
1. Script sends HTTPS request to webhook server
2. Webhook server commits to GitHub
3. GitHub Actions deploys to WordPress
4. Changes live on https://3000studios.com in ~2 minutes

## Mobile Client Setup

### iOS (Shortcuts App)

1. Open **Shortcuts** app
2. Create new shortcut:
   - Add "Ask for Input" (get text to update)
   - Add "Text" action with this content:
     ```
     {"path":"header.php","content":"<?php /* Updated: [Input] */ ?>","message":"Mobile update"}
     ```
   - Add "Get Contents of URL":
     - URL: `https://your-webhook-server.com/update-file`
     - Method: POST
     - Headers: 
       - `Content-Type`: `application/json`
       - `x-signature`: (calculate HMAC - see iOS app below)
     - Request Body: Previous text
3. Add to Home Screen for quick access

**Better: Build simple iOS app** (Swift code available in `/mobile-client/ios-app/`)

### Android (Termux)

1. Install Termux from F-Droid
2. Install requirements:
   ```bash
   pkg install git curl openssl
   ```
3. Clone your scripts:
   ```bash
   git clone https://github.com/3000Studios/3000studiostheme.git
   cd 3000studiostheme/mobile-client
   chmod +x mobile-update.sh
   ```
4. Set environment variables:
   ```bash
   echo 'export WEBHOOK_URL="https://your-webhook-server.com"' >> ~/.bashrc
   echo 'export WEBHOOK_SECRET="your_secret"' >> ~/.bashrc
   source ~/.bashrc
   ```
5. Create quick commands:
   ```bash
   echo './mobile-update.sh quick-edit update-border-color "$1"' > ~/bin/border
   chmod +x ~/bin/border
   ```
6. Usage:
   ```bash
   border "#ff0000"  # Update border color
   ```

**Better: Build Android app** (Kotlin code available in `/mobile-client/android-app/`)

## Quick Edit Commands

Pre-configured commands for common changes:

### Update Border Color

```bash
./mobile-update.sh quick-edit update-border-color "#ff0000"
```

### Toggle Feature

```bash
./mobile-update.sh quick-edit toggle-feature "dark-mode"
```

### Update Announcement

```bash
./mobile-update.sh quick-edit update-announcement "New sale live!"
```

## Security Best Practices

### ✅ DO:
- Use HTTPS only (TLS 1.2+)
- Store secrets in environment variables
- Use strong HMAC secret (32+ random bytes)
- Rotate GitHub token periodically
- Use SSH keys for WordPress deploy (not passwords)
- Review commits before auto-deploy (optional)
- Set up IP whitelist on webhook server
- Enable 2FA on GitHub

### ❌ DON'T:
- Commit `.env` files with secrets
- Use HTTP (unencrypted)
- Share webhook URL publicly
- Use weak secrets (like "password123")
- Store GitHub token in code
- Deploy webhook server without authentication

## Advanced Features

### Add Telegram Bot Interface

```javascript
// In webhook-server/server.js, add:
const TelegramBot = require('node-telegram-bot-api');
const bot = new TelegramBot(process.env.TELEGRAM_BOT_TOKEN, {polling: true});

bot.onText(/\/update (.+)/, async (msg, match) => {
  const chatId = msg.chat.id;
  const content = match[1];
  
  // Commit to GitHub
  // ... (use existing update-file logic)
  
  bot.sendMessage(chatId, '✓ Updated and deploying!');
});
```

### Add Voice Commands (Siri/Google Assistant)

1. Create IFTTT webhook trigger
2. Connect to your webhook server
3. Say: "Hey Siri, update my website border to red"

### Add Approval Step (Safety)

Modify workflow to create PR instead of direct commit:

```javascript
// In server.js, replace createOrUpdateFileContents with:
await octokit.pulls.create({
  owner: GH_OWNER,
  repo: GH_REPO,
  title: `📱 Mobile update: ${path}`,
  head: `mobile-update-${Date.now()}`,
  base: DEFAULT_BRANCH,
  body: 'Review and merge to deploy'
});
```

## Troubleshooting

### Webhook Returns 401 "Invalid Signature"

**Cause**: HMAC signature mismatch

**Fix**:
1. Verify `WEBHOOK_SECRET` matches in `.env` and mobile script
2. Ensure JSON payload is exactly the same when calculating signature
3. Check for extra whitespace or encoding issues

### GitHub Actions Not Triggering

**Cause**: Workflow not watching correct files/branch

**Fix**:
1. Check `.github/workflows/deploy-wordpress.yml`
2. Ensure `branches: [main]` matches your branch
3. Check GitHub Actions tab for errors

### Changes Committed but Not Deployed

**Cause**: Workflow failed or secrets missing

**Fix**:
1. Check GitHub Actions logs
2. Verify all 4 secrets are set correctly
3. Test SSH connection manually

### Mobile Script Can't Connect

**Cause**: Webhook server down or URL wrong

**Fix**:
1. Test webhook health: `curl https://your-webhook-server.com`
2. Check server logs: `pm2 logs 3000studios-webhook`
3. Verify firewall allows port 3000

## Cost Estimate

- **GitHub**: Free (public repo)
- **Railway**: ~$5/month (webhook server)
- **WordPress hosting**: Already have
- **Total**: ~$5/month for unlimited mobile updates

## What's Next?

1. ✅ Set up GitHub repo
2. ✅ Deploy webhook server
3. ✅ Test mobile updates
4. Build native mobile app (iOS/Android)
5. Add Telegram bot interface
6. Add voice command support
7. Create approval workflow for production

**You're now live!** Edit your site from anywhere with just your phone. 🚀
