# 🚀 Complete Setup Guide: Phone-to-Website Auto-Deploy

## What You've Got

✅ **WordPress Theme** with video backgrounds, particle effects, electric navigation  
✅ **GitHub Actions Workflow** - Auto-deploys on push to main  
✅ **Webhook Server** - Accepts mobile commands to update GitHub  
✅ **Mobile Scripts** - Send updates from any device  
✅ **Auto-commit Script** - Optional scheduled commits  

## Architecture Flow

```
📱 Your Phone (text/voice command)
    ↓ HTTPS POST with HMAC signature
🌐 Webhook Server (Node.js - Railway/Vercel/VPS)
    ↓ GitHub API (commits changes)
📦 GitHub Repository (3000Studios/3000studiostheme)
    ↓ GitHub Actions triggered on push
🖥️ WordPress Server (access-5018843677.webspace-host.com)
    ↓ Theme extracted & activated
✨ Live Website (https://3000studios.com)
```

**Total time: Text from phone → Live on site in ~90 seconds**

---

## Step-by-Step Setup (30 minutes)

### Step 1: Create GitHub Repository

**Option A: Via GitHub CLI (fastest)**

```powershell
# Install GitHub CLI if not already installed
winget install GitHub.cli

# Login to GitHub
gh auth login

# Create repository
gh repo create 3000Studios/3000studiostheme --public --source=. --remote=origin --push

# Done! Skip to Step 2
```

**Option B: Via GitHub Website**

1. Go to https://github.com/new
2. Repository name: `3000studiostheme`
3. Owner: Select your account (or create `3000Studios` org first)
4. Visibility: **Public** (or Private if you prefer)
5. **Do NOT** initialize with README (we already have files)
6. Click **Create repository**
7. Copy the remote URL shown
8. Run in PowerShell:

```powershell
git remote add origin https://github.com/3000Studios/3000studiostheme.git
git branch -M main
git push -u origin main
```

### Step 2: Create GitHub Personal Access Token

1. Go to https://github.com/settings/tokens/new
2. Note: `3000Studios Webhook Server`
3. Expiration: `No expiration` (or 1 year)
4. Select scopes:
   - ✅ `repo` (Full control of private repositories)
5. Click **Generate token**
6. **COPY THE TOKEN** (starts with `ghp_...`) - Save it somewhere safe!

### Step 3: Add GitHub Secrets (for WordPress deployment)

In your GitHub repository:

1. Go to **Settings** → **Secrets and variables** → **Actions**
2. Click **New repository secret** and add these 4 secrets:

| Secret Name | Value |
|------------|-------|
| `WP_SSH_HOST` | `access-5018843677.webspace-host.com` |
| `WP_SSH_USER` | `a2413152` |
| `WP_SSH_PASSWORD` | `Gabby3000!!!` |
| `WP_SSH_PORT` | `22` |

**Note**: For better security, use SSH keys instead of password (see Advanced section)

### Step 4: Deploy Webhook Server

The webhook accepts mobile commands and commits to GitHub. Choose one hosting option:

#### **Option A: Railway (Recommended - Easiest)**

1. Go to https://railway.app
2. Sign up with GitHub (free tier: $5 credit/month)
3. Click **New Project** → **Deploy from GitHub repo**
4. Select `3000Studios/3000studiostheme`
5. Click **Add variables** and add:

```
GH_TOKEN=ghp_your_token_from_step_2
GH_OWNER=3000Studios
GH_REPO=3000studiostheme
WEBHOOK_SECRET=generate_random_32_char_string_here
DEFAULT_BRANCH=main
PORT=3000
```

To generate `WEBHOOK_SECRET`, run:
```powershell
# In PowerShell
-join ((65..90) + (97..122) + (48..57) | Get-Random -Count 32 | ForEach-Object {[char]$_})
```

6. Set **Start Command**: `cd webhook-server && npm install && npm start`
7. Set **Root Directory**: Leave empty (Railway will detect package.json)
8. Click **Deploy**
9. Wait ~2 minutes for deployment
10. Click **Settings** → **Networking** → **Generate Domain**
11. **Copy your Railway URL** (e.g., `https://your-app.up.railway.app`)

#### **Option B: Vercel (Serverless)**

1. Go to https://vercel.com
2. Sign up with GitHub
3. Click **Import Project** → Select `3000studiostheme`
4. Framework Preset: **Other**
5. Root Directory: `webhook-server`
6. Build Command: `npm install`
7. Output Directory: Leave empty
8. Add Environment Variables (same as Railway above)
9. Deploy
10. Copy your Vercel URL

#### **Option C: Your Own VPS (Most Control)**

```bash
# SSH into your VPS
ssh root@your-vps-ip

# Install Node.js 18+
curl -fsSL https://deb.nodesource.com/setup_18.x | sudo -E bash -
sudo apt-get install -y nodejs

# Clone your repo
git clone https://github.com/3000Studios/3000studiostheme.git
cd 3000studiostheme/webhook-server

# Install dependencies
npm install

# Create .env file
nano .env
```

Paste this into `.env`:
```bash
GH_TOKEN=ghp_your_token_here
GH_OWNER=3000Studios
GH_REPO=3000studiostheme
WEBHOOK_SECRET=your_random_secret_here
DEFAULT_BRANCH=main
PORT=3000
```

Continue setup:
```bash
# Install PM2 (process manager)
sudo npm install -g pm2

# Start webhook server
pm2 start server.js --name webhook

# Make it start on boot
pm2 startup
pm2 save

# Setup Nginx reverse proxy (optional but recommended)
sudo apt install nginx
sudo nano /etc/nginx/sites-available/webhook
```

Add this Nginx config:
```nginx
server {
    listen 80;
    server_name your-domain.com;
    
    location / {
        proxy_pass http://localhost:3000;
        proxy_http_version 1.1;
        proxy_set_header Upgrade $http_upgrade;
        proxy_set_header Connection 'upgrade';
        proxy_set_header Host $host;
        proxy_cache_bypass $http_upgrade;
    }
}
```

Enable and restart:
```bash
sudo ln -s /etc/nginx/sites-available/webhook /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl restart nginx

# Get free SSL certificate
sudo apt install certbot python3-certbot-nginx
sudo certbot --nginx -d your-domain.com
```

### Step 5: Test the System

**Test 1: GitHub Actions Deployment**

```powershell
# Make a small change
echo "/* Test change */" >> style.css

# Commit and push
git add style.css
git commit -m "Test auto-deploy"
git push
```

Then:
1. Go to GitHub → **Actions** tab
2. Watch your workflow run (should complete in ~2 minutes)
3. Visit https://3000studios.com and hard refresh (`Ctrl+Shift+R`)

**Test 2: Webhook Server**

```powershell
# Test health endpoint
Invoke-RestMethod -Uri "https://your-webhook-url.railway.app"
# Should return: {"status":"ok","service":"3000 Studios Mobile Webhook"}
```

**Test 3: Mobile Update**

```powershell
# From your computer (simulating phone)
.\mobile-client\mobile-update.ps1 `
  -WebhookUrl "https://your-webhook-url.railway.app" `
  -Secret "your_webhook_secret" `
  -Action update-file `
  -Path "style.css" `
  -Content "/* Updated from script! */" `
  -Message "Test mobile update"
```

Expected output:
```json
{
  "ok": true,
  "path": "style.css",
  "branch": "main",
  "commit": "abc1234"
}
```

Then check:
1. GitHub repo - you should see new commit
2. GitHub Actions - workflow should be running
3. Wait 2 minutes and check https://3000studios.com

---

## Mobile Setup

### iOS (Shortcuts App)

1. Open **Shortcuts** app on iPhone
2. Tap **+** to create new shortcut
3. Add these actions:

**Action 1: Ask for Input**
- Prompt: "What CSS change?"
- Input Type: Text

**Action 2: Text**
- Content:
```json
{
  "path": "style.css",
  "content": "Shortcut Input",
  "message": "iOS update"
}
```

**Action 3: Set Variable**
- Variable name: `RequestBody`
- Value: Previous result

**Action 4: Get Contents of URL**
- URL: `https://your-webhook-url.railway.app/update-file`
- Method: POST
- Headers:
  - `Content-Type`: `application/json`
  - `x-signature`: (Need to calculate HMAC - see iOS app example)
- Request Body: `RequestBody`

**Action 5: Show Result**
- Show: Contents of URL

4. Tap **⋮** menu → Add to Home Screen
5. Name it "Update Website"

**Better Option**: I can create a full iOS app with proper HMAC signing.

### Android (Termux)

1. Install **Termux** from F-Droid (not Play Store)
2. Open Termux and run:

```bash
# Install requirements
pkg install git curl openssl-tool

# Clone your repo
git clone https://github.com/3000Studios/3000studiostheme.git
cd 3000studiostheme/mobile-client
chmod +x mobile-update.sh

# Set environment variables
echo 'export WEBHOOK_URL="https://your-webhook-url.railway.app"' >> ~/.bashrc
echo 'export WEBHOOK_SECRET="your_webhook_secret"' >> ~/.bashrc
source ~/.bashrc

# Create quick commands
mkdir -p ~/bin
echo '#!/bin/bash' > ~/bin/border
echo 'cd ~/3000studiostheme/mobile-client' >> ~/bin/border
echo './mobile-update.sh quick-edit update-border-color "$1"' >> ~/bin/border
chmod +x ~/bin/border

# Test it
border "#ff0000"
```

3. Add Termux widget to home screen for one-tap access

### Telegram Bot (Recommended)

Create a bot that accepts text commands:

```powershell
# Install Telegram bot server
cd webhook-server
npm install node-telegram-bot-api
```

Add to `server.js`:

```javascript
const TelegramBot = require('node-telegram-bot-api');
const bot = new TelegramBot(process.env.TELEGRAM_BOT_TOKEN, {polling: true});

// Handle /update command
bot.onText(/\/update (.+)/, async (msg, match) => {
  const chatId = msg.chat.id;
  const [path, ...contentParts] = match[1].split(' ');
  const content = contentParts.join(' ');
  
  try {
    // Use existing GitHub commit logic
    const encoded = Buffer.from(content).toString('base64');
    await octokit.repos.createOrUpdateFileContents({
      owner: GH_OWNER,
      repo: GH_REPO,
      path,
      message: `📱 Telegram update at ${new Date().toISOString()}`,
      content: encoded,
      branch: DEFAULT_BRANCH,
    });
    
    bot.sendMessage(chatId, `✅ Updated ${path}! Deploying...`);
  } catch (err) {
    bot.sendMessage(chatId, `❌ Error: ${err.message}`);
  }
});

bot.onText(/\/status/, (msg) => {
  bot.sendMessage(msg.chat.id, '✅ Bot online! Use /update <file> <content>');
});
```

Setup:
1. Create bot: Message @BotFather on Telegram
2. Send `/newbot` and follow instructions
3. Copy your bot token
4. Add to Railway/Vercel environment: `TELEGRAM_BOT_TOKEN=your_token`
5. Redeploy webhook server
6. Message your bot: `/update style.css /* New styles */`

---

## Automation Options

### Option 1: Scheduled Auto-Commits (Every 30 mins)

Already included in `.github/workflows/auto-commit.yml`

Enable by uncommenting the cron schedule in the workflow file.

### Option 2: Windows Task Scheduler (Local Auto-Commit)

```powershell
# Create scheduled task to run auto-commit every 30 minutes
$action = New-ScheduledTaskAction -Execute "PowerShell.exe" `
  -Argument "-File C:\Users\Nouna\Downloads\3000studios-theme\3000studios\scripts\local-auto-commit.ps1"

$trigger = New-ScheduledTaskTrigger -Once -At (Get-Date) -RepetitionInterval (New-TimeSpan -Minutes 30)

Register-ScheduledTask -TaskName "3000Studios Auto-Commit" `
  -Action $action `
  -Trigger $trigger `
  -Description "Auto-commit theme changes every 30 minutes"
```

### Option 3: VS Code Extension (Real-time)

Create a VS Code extension that auto-commits on file save:

```json
// In .vscode/settings.json
{
  "files.autoSave": "afterDelay",
  "files.autoSaveDelay": 1000,
  "git.enableSmartCommit": true,
  "git.postCommitCommand": "push"
}
```

---

## Security Best Practices

### ✅ Essential Security

1. **Use HTTPS only** - Never HTTP for webhook
2. **Strong secrets** - Use 32+ character random strings
3. **Verify HMAC** - Every webhook request must have valid signature
4. **Rotate tokens** - Change GitHub token every 90 days
5. **Limit scope** - Only allow specific file paths
6. **IP whitelist** - Restrict webhook to known IPs (optional)
7. **Rate limiting** - Max 10 requests/minute
8. **Audit logs** - All commits are tracked in GitHub

### 🔐 Advanced Security

**Use SSH keys instead of passwords:**

```powershell
# Generate SSH key
ssh-keygen -t ed25519 -C "github-actions@3000studios.com" -f id_3000studios

# Copy public key to server
type id_3000studios.pub | ssh a2413152@access-5018843677.webspace-host.com "cat >> ~/.ssh/authorized_keys"

# Add private key to GitHub Secrets
# Name: SSH_PRIVATE_KEY
# Value: (contents of id_3000studios file)
```

Update workflow to use key:
```yaml
- uses: appleboy/ssh-action@master
  with:
    key: ${{ secrets.SSH_PRIVATE_KEY }}
    # Remove: password: ${{ secrets.WP_SSH_PASSWORD }}
```

**Implement file path restrictions:**

In `webhook-server/server.js`, add:

```javascript
const ALLOWED_PATHS = [
  'style.css',
  'assets/css/',
  'assets/js/',
  'header.php',
  'footer.php'
];

function isPathAllowed(path) {
  return ALLOWED_PATHS.some(allowed => 
    path === allowed || path.startsWith(allowed)
  );
}

// In update-file endpoint:
if (!isPathAllowed(path)) {
  return res.status(403).json({ error: 'Path not allowed' });
}
```

---

## Quick Commands Cheat Sheet

### From Your Computer

```powershell
# Update border color
.\mobile-client\mobile-update.ps1 -WebhookUrl "..." -Secret "..." `
  -Action quick-edit -QuickAction update-border-color -Value "#ff0000"

# Update any file
.\mobile-client\mobile-update.ps1 -WebhookUrl "..." -Secret "..." `
  -Action update-file -Path "header.php" -Content "<?php /* New */ ?>"

# Deploy manually (bypass webhook)
git add -A
git commit -m "Manual update"
git push
```

### From Android/Termux

```bash
# Update border
border "#00ff00"

# Custom update
./mobile-update.sh update-file "style.css" "/* New CSS */" "Mobile update"
```

### From Telegram

```
/status
/update style.css /* New border color */
```

---

## Troubleshooting

### Issue: "Invalid signature" error

**Solution:**
```powershell
# Verify your secret matches exactly
# Test signature generation:
$secret = "your_webhook_secret"
$payload = '{"path":"test.txt","content":"test"}'
$hmac = [System.Security.Cryptography.HMACSHA256]::new(
  [System.Text.Encoding]::UTF8.GetBytes($secret)
)
$signature = [BitConverter]::ToString(
  $hmac.ComputeHash([System.Text.Encoding]::UTF8.GetBytes($payload))
).Replace('-','').ToLower()
Write-Host $signature
```

### Issue: GitHub Actions fails with "Permission denied"

**Solution:** Check secrets are correct:
```powershell
# Test SSH connection
ssh a2413152@access-5018843677.webspace-host.com
# Enter password: Gabby3000!!!
```

### Issue: Changes committed but not deployed

**Solution:** Check GitHub Actions logs:
1. Go to repo → Actions tab
2. Click failed workflow
3. Expand "Deploy on server" step
4. Look for error messages

### Issue: Webhook server won't start

**Solution:**
```bash
# Check logs on Railway
# Or if self-hosted:
pm2 logs webhook
# Look for missing env variables or port conflicts
```

---

## Cost Breakdown

| Service | Cost | Purpose |
|---------|------|---------|
| GitHub | Free | Code hosting & CI/CD |
| Railway | $5/mo | Webhook server hosting |
| WordPress hosting | Already paid | Your site |
| **Total** | **$5/mo** | Unlimited mobile updates |

**Alternative**: Self-host webhook on existing VPS = **$0/mo**

---

## What's Next?

### Immediate (Today)
1. ✅ Create GitHub repo
2. ✅ Deploy webhook server
3. ✅ Test mobile updates
4. ⬜ Add Telegram bot
5. ⬜ Setup iOS Shortcut

### This Week
- Build native mobile apps (iOS/Android)
- Add voice command support (Siri/Google Assistant)
- Implement approval workflow for critical changes
- Add syntax validation before commit

### This Month
- Add AI assistant for natural language commands
  - "Make the border red" → parses and updates CSS
  - "Add a new page for shop" → generates page template
- Create admin dashboard for monitoring
- Add rollback feature (one-tap revert)

---

## Support & Resources

📚 **Documentation:**
- Main setup: `README_MOBILE_DEPLOY.md` (this file)
- Auto-deploy: `README_AUTODEPLOY.md`
- Quick start: `QUICKSTART.md`

🔧 **Scripts:**
- Local auto-commit: `scripts/local-auto-commit.ps1`
- Zip theme: `scripts/zip-theme.ps1`
- Mobile update: `mobile-client/mobile-update.ps1`

🌐 **Webhook Server:**
- Source: `webhook-server/server.js`
- Config: `webhook-server/.env.example`

⚙️ **GitHub Actions:**
- Deploy workflow: `.github/workflows/deploy-wordpress.yml`

---

## Need Help?

Created by: 3000 Studios  
Contact: mr.jwswain@gmail.com  
Repository: https://github.com/3000Studios/3000studiostheme

**You're now ready to update your website from anywhere with just your phone!** 🚀📱✨
