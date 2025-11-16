# 🚀 Run All Workflows

This guide explains how to trigger all GitHub Actions workflows in the 3000 Studios theme repository.

## 📋 Available Workflows

The repository contains **12 manually-triggerable workflows**:

1. **auto-changelog.yml** - Generates nightly changelogs
2. **auto-deploy.yml** - Auto-deploys theme to production
3. **command-center-sync.yml** - Syncs with WordPress Command Center
4. **deploy-wordpress-theme.yml** - Deploys theme via SFTP
5. **deploy-wordpress.yml** - Deploys theme via SSH
6. **dev-inspector.yml** - Runs linters and AI code review
7. **drive-sync.yml** - Syncs reports to Google Drive
8. **github_workflows_auto-approve-and-merge_Version5.yml** - Auto-approves PRs
9. **nightly-backup.yml** - Creates repository backups
10. **repo-optimizer.yml** - Optimizes repository (cleanup)
11. **security-watchdog.yml** - Runs security scans
12. **theme-package.yml** - Builds theme distribution package

## 🎯 Methods to Run All Workflows

### Method 1: GitHub Actions Web Interface (Recommended)

1. Navigate to: https://github.com/3000Studios/3000studiostheme/actions
2. Click on **"🚀 Run All Workflows"** in the left sidebar
3. Click **"Run workflow"** dropdown
4. Select branch (usually `main`)
5. (Optional) Specify specific workflows in the input field
6. Click **"Run workflow"** button

This will trigger all 12 workflows automatically!

### Method 2: Using npm Scripts (Easiest)

Run the workflows using npm scripts:

```bash
# Make sure you're in the repository root
cd /path/to/3000studiostheme

# Run all workflows
npm run workflows:run

# List all workflows
npm run workflows:list

# Check workflow run status
npm run workflows:status
```

**Requirements:**
- GitHub CLI (`gh`) installed: https://cli.github.com/
- Authenticated with GitHub: `gh auth login`

### Method 3: Using the Bash Script Directly

Run the provided script from your local machine:

```bash
# Make sure you're in the repository root
cd /path/to/3000studiostheme

# Run all workflows
./scripts/trigger-all-workflows.sh

# Or run specific workflows
./scripts/trigger-all-workflows.sh auto-deploy.yml theme-package.yml
```

**Requirements:**
- GitHub CLI (`gh`) installed: https://cli.github.com/
- Authenticated with GitHub: `gh auth login`

### Method 4: GitHub CLI (Manual)

Trigger workflows manually one by one:

```bash
# Set repository
REPO="3000Studios/3000studiostheme"

# Trigger individual workflows
gh workflow run "auto-changelog.yml" --repo $REPO
gh workflow run "auto-deploy.yml" --repo $REPO
gh workflow run "command-center-sync.yml" --repo $REPO
# ... and so on
```

### Method 5: Trigger via API

Using curl or any HTTP client:

```bash
# Set your GitHub token
TOKEN="your_github_personal_access_token"
REPO="3000Studios/3000studiostheme"
WORKFLOW="run-all-workflows.yml"

curl -X POST \
  -H "Accept: application/vnd.github.v3+json" \
  -H "Authorization: token $TOKEN" \
  https://api.github.com/repos/$REPO/actions/workflows/$WORKFLOW/dispatches \
  -d '{"ref":"main"}'
```

## 📊 Monitoring Workflow Runs

### Using npm Scripts (Recommended)
```bash
# Check status of recent workflow runs
npm run workflows:status

# List all available workflows
npm run workflows:list
```

### View All Runs
```bash
gh run list --repo 3000Studios/3000studiostheme --limit 20
```

### Watch a Specific Run
```bash
gh run watch <run-id> --repo 3000Studios/3000studiostheme
```

### View Run Logs
```bash
gh run view <run-id> --repo 3000Studios/3000studiostheme --log
```

## 🎮 Advanced Usage

### Run Specific Workflows Only

When using the GitHub Actions interface, you can specify which workflows to run by entering a comma-separated list in the input field:

```
auto-deploy.yml, theme-package.yml, security-watchdog.yml
```

### Schedule Regular Runs

Most workflows are already scheduled to run automatically:

- **Nightly (Midnight UTC):** `nightly-backup.yml`, `drive-sync.yml`
- **Nightly (5 AM UTC):** `auto-changelog.yml`, `command-center-sync.yml`, `security-watchdog.yml`
- **Every Hour:** `dev-inspector.yml`
- **Twice Daily:** `repo-optimizer.yml`

## ⚠️ Important Notes

1. **Rate Limiting:** GitHub has rate limits on API calls. The scripts include delays to avoid hitting these limits.

2. **Secrets Required:** Some workflows require secrets to be configured:
   - `FTP_SERVER`, `FTP_USERNAME`, `FTP_PASSWORD` (for FTP deployments)
   - `SSH_KEY`, `SSH_HOST`, `SSH_USER` (for SSH deployments)
   - `OPENAI_API_KEY` (for AI-powered features)
   - `RCLONE_CONF_BASE64` (for Google Drive sync)
   - Various SMTP settings (for email notifications)

3. **Workflow Dependencies:** Some workflows may depend on others. The `command-center-orchestrator.yml` handles these dependencies automatically.

4. **Costs:** Some workflows (like security scans) consume GitHub Actions minutes. Monitor your usage if on a limited plan.

## 🔧 Troubleshooting

### Workflow Fails to Trigger
- Ensure you have proper permissions on the repository
- Check if you're authenticated: `gh auth status`
- Verify the workflow file exists and is valid

### Workflow Runs but Fails
- Check the workflow logs in the Actions tab
- Ensure all required secrets are configured
- Verify external services (FTP, SSH) are accessible

### Script Permission Denied
```bash
chmod +x scripts/trigger-all-workflows.sh
```

## 📚 Additional Resources

- **GitHub Actions Documentation:** https://docs.github.com/en/actions
- **GitHub CLI Documentation:** https://cli.github.com/manual/
- **3000 Studios Documentation:** See other README files in this repository

---

**© 2025 3000 Studios. All Rights Reserved.**

*"The future is built in the present. Let's build it together."*
