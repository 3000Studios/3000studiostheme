<!--
  Copyright (c) 2025 NAME.
  All rights reserved.
  Unauthorized copying, modification, distribution, or use of this is prohibited without express written permission.
-->

# 3000 Studios Theme - Quick Start

## One-Time Setup (5 minutes)

1. **Add GitHub Secrets** (Repository → Settings → Secrets → Actions):
   - `WP_SSH_HOST` = `access-5018843677.webspace-host.com`
   - `WP_SSH_USER` = `a2413152`
   - `WP_SSH_PASSWORD` = `Gabby3000!!!`
   - `WP_SSH_PORT` = `22`

2. **Push workflow to GitHub**:
   ```powershell
   git add -A
   git commit -m "Add auto-deploy"
   git push origin main
   ```

3. **Verify** in GitHub Actions tab → Wait for green checkmark

4. **Activate theme** in WordPress Admin → Appearance → Themes → Activate "3000 Studios Clean"

5. **Test site**: Visit https://3000studios.com and hard refresh (Ctrl+Shift+R)

## Daily Workflow

```powershell
# Edit files
code style.css

# Commit and push
git add style.css
git commit -m "Update styles"
git push

# Done! Auto-deploys in 1-2 minutes
```

## Manual Deploy (if needed)

```powershell
.\scripts\zip-theme.ps1
# Upload the generated ZIP via WordPress admin
```

## Need Help?

Read full documentation: `README_AUTODEPLOY.md`
