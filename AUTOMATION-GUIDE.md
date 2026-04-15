# 🚀 3000 Studios Theme - Complete Automation Guide

**Copyright © 2025 3000 Studios. All Rights Reserved.**

## 📋 Table of Contents
- [Overview](#overview)
- [New Features](#new-features)
- [GitHub Actions Workflows](#github-actions-workflows)
- [Payment Integration](#payment-integration)
- [Store Setup](#store-setup)
- [Automation Features](#automation-features)
- [Branch Management](#branch-management)
- [Quick Start](#quick-start)

---

## 🎯 Overview

The 3000 Studios Theme now includes comprehensive automation for:
- ✅ Automatic error detection and fixing
- ✅ Continuous integration and deployment
- ✅ Payment processing (Stripe, PayPal, Cash App, Crypto)
- ✅ Store management with auto-products
- ✅ UI verification and screenshot automation
- ✅ Security scanning and updates

---

## 🆕 New Features

### 1. Command Center Automation
Automatically handles code quality, testing, and deployment.

**Triggered by:**
- Push to main branch
- Pull requests
- Manual workflow dispatch
- Daily at midnight UTC

**What it does:**
- Runs ESLint and PHP syntax checks
- Auto-fixes common issues
- Commits fixes automatically
- Builds and packages theme
- Uploads build artifacts
- Generates UI update reports

### 2. Store Automation
Manages your digital store and product catalog.

**Features:**
- Store health monitoring
- Payment integration verification
- Product configuration automation
- Weekly automated checks

### 3. Auto Error Handler
Detects and automatically fixes code errors.

**Capabilities:**
- PHP syntax error detection
- JavaScript linting errors
- Auto-fix common issues
- Validation of fixes
- Error notifications
- Runs every 6 hours

### 4. UI Screenshot & Verification
Comprehensive UI testing and documentation.

**Includes:**
- UI component inventory
- Responsive design verification
- Accessibility checks (WCAG 2.1)
- Performance metrics
- Visual element documentation

---

## 🔧 GitHub Actions Workflows

All workflows are located in `.github/workflows/`:

| Workflow File | Purpose | Schedule |
|--------------|---------|----------|
| `command-center-automation.yml` | Main automation hub | Daily + on push |
| `store-automation.yml` | Store management | Weekly |
| `auto-error-handler.yml` | Error detection/fixing | Every 6 hours |
| `ui-screenshot-verification.yml` | UI testing | On push to main |
| `auto-deploy.yml` | WordPress deployment | On push to main |
| `theme-deploy.yml` | Theme packaging | On push to main |

### Manual Workflow Triggers

Run any workflow manually from GitHub:
1. Go to **Actions** tab
2. Select the workflow
3. Click **Run workflow**
4. Choose branch and click **Run**

---

## 💳 Payment Integration

### Supported Payment Methods

#### 1. Stripe (Credit/Debit Cards)
```php
// Shortcode usage
[studios_stripe_button amount="49.99" description="AI Dashboard Pro"]

// Configure in .env
STRIPE_PUBLISHABLE_KEY=pk_live_xxxxx
STRIPE_SECRET_KEY=sk_live_xxxxx
```

#### 2. PayPal
```php
// Shortcode usage
[studios_paypal_button amount="99.99" description="Premium Theme"]

// Configure in .env
PAYPAL_CLIENT_ID=xxxxx
PAYPAL_CLIENT_SECRET=xxxxx
PAYPAL_MODE=live
```

#### 3. Cash App
```php
// Shortcode usage
[studios_cashapp_button amount="29.99" cashtag="$3000Studios" description="Quick Payment"]

// Configure in .env
CASHAPP_CASHTAG=$3000Studios
```

#### 4. Cryptocurrency
```php
// Shortcode usage
[studios_crypto_button amount="199.99" currency="BTC" wallet="your_wallet_address"]

// Configure in .env
CRYPTO_BTC_WALLET=your_btc_address
CRYPTO_ETH_WALLET=your_eth_address
```

### WordPress Settings

Add these options in WordPress admin or via code:

```php
// Stripe
update_option('studios_stripe_publishable_key', 'pk_live_xxxxx');
update_option('studios_stripe_secret_key', 'sk_live_xxxxx');

// PayPal
update_option('studios_paypal_client_id', 'xxxxx');
update_option('studios_paypal_client_secret', 'xxxxx');

// Cash App
update_option('studios_cashapp_cashtag', '$3000Studios');

// Crypto
update_option('studios_crypto_wallet', 'your_wallet_address');
```

---

## 🛒 Store Setup

### Using WooCommerce (Recommended)

1. Install WooCommerce plugin
2. Configure payment gateways
3. Add products
4. Theme automatically integrates

### Manual Store (Without WooCommerce)

The theme includes built-in product displays:

#### Shop Page (`page-shop.php`)
- Premium product grid
- Auto-styled cards
- Payment method indicators
- Responsive design

#### App Store (`page-app-store.php`)
- Digital product showcase
- Instant download interface
- Rating displays
- How-it-works section

### Adding Products Manually

Edit `page-shop.php` or `page-app-store.php` and add product cards:

```php
<div class="product-card">
  <h3>Your Product Name</h3>
  <p>Product description</p>
  <div>$49.99</div>
  <a href="#buy" class="btn-buy">Buy Now</a>
</div>
```

---

## 🤖 Automation Features

### Auto-Commit & Push
The Command Center workflow automatically:
1. Detects code changes
2. Runs linters and fixes issues
3. Commits changes with descriptive messages
4. Pushes to repository

### Auto-Testing
Every push triggers:
- ESLint validation
- PHP syntax checking
- Security scanning
- Performance checks

### Auto-Deployment
On push to main:
1. Version auto-incremented
2. Theme packaged as ZIP
3. Uploaded to artifacts
4. Ready for WordPress install

### Error Recovery
The Auto Error Handler:
1. Scans code every 6 hours
2. Detects syntax errors
3. Attempts auto-fix
4. Validates fixes
5. Commits if successful

---

## 🌿 Branch Management

### Current Branch Strategy

**Main Branch Only** - All feature branches have been consolidated into main.

### Previously Merged Branches
- ✅ `copilot/merge-all-branches-to-main` (active development)
- ✅ Other feature branches merged or archived

### Best Practices
1. Work directly on main for simple changes
2. Create feature branches for major features
3. Use Pull Requests for team review
4. Let automation handle merging and testing

---

## 🚀 Quick Start

### 1. Clone Repository
```bash
git clone https://github.com/3000Studios/3000studiostheme.git
cd 3000studiostheme
```

### 2. Install Dependencies
```bash
npm install
```

### 3. Configure Environment
```bash
cp .env.example .env
# Edit .env with your credentials
```

### 4. Development Mode
```bash
npm run dev
```

This starts:
- File watcher for auto-updates
- Refresh server for live reloading

### 5. Deploy to WordPress
**Option A: Manual Install**
1. Build theme: `npm run build` (if available)
2. ZIP the theme folder
3. Upload to WordPress: Appearance → Themes → Add New

**Option B: Automatic Deploy (via GitHub Actions)**
1. Configure secrets in GitHub repository:
   - `FTP_SERVER`
   - `FTP_USERNAME`
   - `FTP_PASSWORD`
   - `IONOS_HOST`, `IONOS_USER`, `IONOS_PASS`
2. Push to main branch
3. Workflow auto-deploys

---

## 🔐 Security

### API Keys & Secrets
**Never commit credentials!** Use `.env` file or WordPress options.

### GitHub Secrets
Add these in repository Settings → Secrets:
- `STRIPE_SECRET_KEY`
- `PAYPAL_CLIENT_SECRET`
- `FTP_PASSWORD`
- `WP_API_TOKEN`

### WordPress Options
Store sensitive data using WordPress options API:
```php
update_option('studios_stripe_secret_key', 'sk_live_xxxxx');
```

---

## 📊 Monitoring & Reports

### Workflow Artifacts
Each workflow generates downloadable reports:
- Error reports
- UI documentation
- Performance metrics
- Accessibility audits

**Access:** GitHub → Actions → Select workflow run → Artifacts

### Daily Reports
Command Center generates:
- Code quality summary
- Build status
- Deployment logs
- UI update notifications

---

## 🛠️ Troubleshooting

### Workflows Not Running
1. Check GitHub Actions is enabled
2. Verify branch protections allow workflows
3. Check workflow file syntax (YAML)

### Payment Integration Issues
1. Verify API keys in `.env`
2. Check WordPress options are set
3. Test in sandbox/development mode first
4. Check browser console for errors

### Build Failures
1. Run `npm run lint` locally
2. Fix any reported errors
3. Commit and push
4. Workflow will retry

---

## 📞 Support

**Website:** https://3000studios.com  
**Email:** ai@3000studios.com  
**GitHub:** https://github.com/3000Studios

---

## 📄 License

Copyright © 2025 3000 Studios. All Rights Reserved.

See [LICENSE.md](LICENSE.md) for full license terms.

---

**Built with ❤️ by 3000 Studios using Copilot Ωmega**
