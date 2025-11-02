<!--
  Copyright (c) 2025 NAME.
  All rights reserved.
  Unauthorized copying, modification, distribution, or use of this is prohibited without express written permission.
-->

# 3000 Studios Theme - Auto-Deploy Setup

This document explains the GitHub Actions auto-deployment system for the 3000 Studios WordPress theme.

## Overview

Every time you push to the `main` branch, GitHub Actions will automatically:
1. Package your theme into a ZIP file
2. Upload it to your server via SCP
3. Extract and install it in the correct WordPress themes directory
4. Set proper permissions
5. Create timestamped backups of previous versions

## Initial Setup

### 1. Add GitHub Secrets

Navigate to your GitHub repository → **Settings** → **Secrets and variables** → **Actions** → **New repository secret**

Add these 4 secrets:

| Secret Name | Value |
|------------|-------|
| `WP_SSH_HOST` | `access-5018843677.webspace-host.com` |
| `WP_SSH_USER` | `a2413152` |
| `WP_SSH_PASSWORD` | `Gabby3000!!!` |
| `WP_SSH_PORT` | `22` |

**Important**: Never commit these credentials to your repository. GitHub Secrets are encrypted and only available to your workflows.

### 2. Push This Workflow

```powershell
# Add all files including the workflow
git add -A

# Commit with a descriptive message
git commit -m "Add GitHub Actions auto-deploy workflow"

# Push to GitHub
git push origin main
```

### 3. Verify Deployment

After pushing:
1. Go to your repository on GitHub
2. Click the **Actions** tab
3. You should see a workflow run in progress
4. Click on it to view live logs
5. Wait for the green checkmark (usually 1-2 minutes)

### 4. Test Your Site

Visit `https://3000studios.com` and hard refresh:
- **Chrome/Edge**: `Ctrl + Shift + R`
- **Firefox**: `Ctrl + F5`
- **Safari**: `Cmd + Shift + R`

## Daily Workflow

Once set up, deploying is automatic:

```powershell
# Make changes to any theme file
code style.css

# Stage your changes
git add style.css

# Commit with a message
git commit -m "Update perimeter border animation"

# Push to trigger auto-deploy
git push

# GitHub Actions handles the rest!
```

Within 1-2 minutes, your changes will be live on https://3000studios.com.

## Manual Deployment

If you need to deploy manually (bypass GitHub Actions):

### Option A: Use Helper Script (Windows)

```powershell
.\scripts\zip-theme.ps1
```

This creates `3000studios-clean.zip` in your theme folder. Upload via:
- WordPress Admin → Appearance → Themes → Add New → Upload Theme
- OR SFTP to `/clickandbuilds/3000Studios/wp-content/themes/`

### Option B: Use Helper Script (Linux/Mac)

```bash
chmod +x scripts/zip-theme.sh
./scripts/zip-theme.sh
```

### Option C: SFTP Upload (PowerShell)

```powershell
Import-Module Posh-SSH -Force
$hostname = 'access-5018843677.webspace-host.com'
$port = 22
$user = 'a2413152'
$pass = 'Gabby3000!!!'
$secpass = ConvertTo-SecureString $pass -AsPlainText -Force
$cred = New-Object System.Management.Automation.PSCredential($user, $secpass)
$sftp = New-SFTPSession -ComputerName $hostname -Port $port -Credential $cred -AcceptKey

# Upload a single file
Set-SFTPItem -SessionId $sftp.SessionId `
  -Path 'C:\path\to\local\file.php' `
  -Destination '/clickandbuilds/3000Studios/wp-content/themes/3000studios-clean/file.php'

Remove-SFTPSession -SessionId $sftp.SessionId
```

## Workflow Details

### Trigger Events

The workflow runs on:
- **Push to main**: Automatic deployment on every commit
- **Manual dispatch**: Click "Run workflow" in GitHub Actions tab

### Deployment Steps

1. **Checkout**: Downloads your latest code from GitHub
2. **Create ZIP**: Packages theme excluding git files, node_modules, documentation
3. **Upload**: Transfers ZIP to server `/tmp/` directory via SCP
4. **Extract**: SSHs into server, backs up old theme, extracts new one
5. **Permissions**: Sets proper file permissions (755) for WordPress
6. **Cleanup**: Removes temporary ZIP file

### Excluded Files

These files/folders are NOT included in the deployed ZIP:
- `.git/` - Git version control
- `.github/` - GitHub Actions workflows
- `node_modules/` - NPM dependencies (if any)
- `.DS_Store` - Mac system files
- `README*.md` - Documentation files
- `QUICKSTART.md` - Setup guide
- `scripts/` - Helper scripts

### Backup System

Before extracting the new theme, the workflow creates a timestamped backup:

```
3000studios-clean-backup-20251031-143022/
```

Backups are stored in `/clickandbuilds/3000Studios/wp-content/themes/`. To restore:

```bash
# SSH into server
ssh a2413152@access-5018843677.webspace-host.com

# Navigate to themes directory
cd /clickandbuilds/3000Studios/wp-content/themes

# Remove current theme
rm -rf 3000studios-clean

# Restore backup
cp -r 3000studios-clean-backup-YYYYMMDD-HHMMSS 3000studios-clean
```

## Troubleshooting

### Workflow Fails with "Permission Denied"

**Cause**: Server rejects SSH connection or wrong credentials

**Fix**:
1. Verify secrets in GitHub repository settings
2. Test SSH manually: `ssh a2413152@access-5018843677.webspace-host.com`
3. Ensure password is exactly: `Gabby3000!!!`

### Theme Not Updating on Site

**Cause**: Browser cache or WordPress object cache

**Fix**:
1. Hard refresh browser (Ctrl + Shift + R)
2. Clear WordPress cache if using caching plugin
3. Deactivate and reactivate theme in WordPress admin

### Workflow Succeeds but Changes Not Visible

**Cause**: Wrong theme activated in WordPress

**Fix**:
1. Login to WordPress admin
2. Go to Appearance → Themes
3. Activate "3000 Studios Clean" theme

### ZIP Upload Fails

**Cause**: SCP connection timeout or network issue

**Fix**:
1. Click "Re-run failed jobs" in GitHub Actions
2. Check Actions logs for specific error
3. Verify server is accessible: `ping access-5018843677.webspace-host.com`

### Extract Step Fails

**Cause**: Insufficient disk space or permissions

**Fix**:
1. SSH into server
2. Check disk space: `df -h`
3. Check permissions: `ls -la /clickandbuilds/3000Studios/wp-content/themes/`
4. Ensure user `a2413152` owns the themes directory

## Security Best Practices

### Protect Your Secrets

- ✅ **DO**: Use GitHub Secrets for credentials
- ✅ **DO**: Rotate passwords periodically
- ✅ **DO**: Use SSH keys instead of passwords (advanced)
- ❌ **DON'T**: Commit passwords to Git
- ❌ **DON'T**: Share secrets in issues or pull requests
- ❌ **DON'T**: Log secrets in workflow files

### SSH Key Authentication (Recommended)

For enhanced security, use SSH keys instead of passwords:

1. Generate SSH key pair:
   ```bash
   ssh-keygen -t ed25519 -C "github-actions@3000studios.com"
   ```

2. Add public key to server:
   ```bash
   ssh-copy-id -i ~/.ssh/id_ed25519.pub a2413152@access-5018843677.webspace-host.com
   ```

3. Update GitHub Secrets:
   - Remove `WP_SSH_PASSWORD`
   - Add `WP_SSH_PRIVATE_KEY` with contents of `~/.ssh/id_ed25519`

4. Update workflow to use `key` instead of `password`:
   ```yaml
   with:
     host: ${{ secrets.WP_SSH_HOST }}
     username: ${{ secrets.WP_SSH_USER }}
     key: ${{ secrets.WP_SSH_PRIVATE_KEY }}
     port: ${{ secrets.WP_SSH_PORT }}
   ```

## Advanced Configuration

### Deploy to Staging First

To add a staging environment:

1. Create a `staging` branch
2. Update workflow to deploy staging branch to different directory:
   ```yaml
   on:
     push:
       branches:
         - main
         - staging
   
   jobs:
     deploy:
       steps:
         # ... existing steps ...
         - name: Determine target directory
           id: target
           run: |
             if [[ "${{ github.ref }}" == "refs/heads/staging" ]]; then
               echo "dir=3000studios-staging" >> $GITHUB_OUTPUT
             else
               echo "dir=3000studios-clean" >> $GITHUB_OUTPUT
             fi
   ```

### Add Deployment Notifications

Send Slack/Discord notifications on deploy:

```yaml
- name: Notify Slack
  if: success()
  uses: slackapi/slack-github-action@v1
  with:
    webhook-url: ${{ secrets.SLACK_WEBHOOK }}
    payload: |
      {
        "text": "✓ 3000 Studios theme deployed to production"
      }
```

### Add Health Checks

Verify site is up after deployment:

```yaml
- name: Health check
  run: |
    sleep 10
    STATUS=$(curl -s -o /dev/null -w "%{http_code}" https://3000studios.com)
    if [ $STATUS -eq 200 ]; then
      echo "✓ Site is up and running"
    else
      echo "✗ Site returned status $STATUS"
      exit 1
    fi
```

## Alternative: FTP Deployment

If your host doesn't support SSH, use FTP instead:

```yaml
- name: Deploy via FTP
  uses: SamKirkland/FTP-Deploy-Action@4.3.0
  with:
    server: ${{ secrets.FTP_SERVER }}
    username: ${{ secrets.FTP_USERNAME }}
    password: ${{ secrets.FTP_PASSWORD }}
    local-dir: ./
    server-dir: /public_html/wp-content/themes/3000studios-clean/
```

## Server Requirements

Your server must have:
- ✅ SSH access enabled (port 22)
- ✅ SCP/SFTP support
- ✅ `unzip` command available
- ✅ Write permissions for user `a2413152` in themes directory
- ✅ Sufficient disk space (minimum 50MB free)

## Monitoring

### View Deployment History

1. Go to GitHub → Actions tab
2. Filter by workflow: "Deploy WordPress Theme via SFTP"
3. Click any run to see full logs

### Enable Email Notifications

GitHub → Settings → Notifications → Actions:
- ✅ Send notifications for failed workflows only
- ✅ Send notifications for successful workflows (optional)

## Support

If you encounter issues:

1. Check GitHub Actions logs for error messages
2. Test SSH connection manually
3. Verify theme directory structure
4. Ensure WordPress is installed at `/clickandbuilds/3000Studios/`
5. Contact hosting support if server configuration issues

## What's Next?

With auto-deploy working, you can focus on development:

- **Style changes**: Edit `style.css`, commit, push → live in 2 minutes
- **Template updates**: Modify PHP files, commit, push → deployed automatically
- **Asset changes**: Update CSS/JS in `assets/`, commit, push → instant deployment

No more manual FTP uploads! 🚀
