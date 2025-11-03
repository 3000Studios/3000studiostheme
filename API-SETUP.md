<!--
  Copyright (c) 2025 NAME.
  All rights reserved.
  Unauthorized copying, modification, distribution, or use of this is prohibited without express written permission.
-->

# 🚀 3000 Studios AI - API Setup Guide

## ✅ What's Installed

Your AI-powered WordPress theme now has **FULL API INTEGRATION** with:

- ✅ **OpenAI GPT-4** - Natural language AI (ALREADY CONFIGURED ✓)
- ✅ **Pexels** - 80,000+ free stock photos
- ✅ **Unsplash** - 3 million+ free high-res photos
- ✅ **Pixabay** - Free images, videos, and music

## 💰 Cost Breakdown

| API | Cost | Free Tier |
|-----|------|-----------|
| OpenAI | ~$0.002/request | No (but CHEAP) |
| Pexels | **FREE** | ∞ Unlimited requests |
| Unsplash | **FREE** | 50 requests/hour |
| Pixabay | **FREE** | ∞ Unlimited requests |

**Your OpenAI key is ALREADY SET and working! ✓**

## 🔑 Get Your FREE API Keys (5 mins)

### 1️⃣ Pexels (FREE - 2 minutes)
1. Go to https://www.pexels.com/api/
2. Click "Get Started" → Create free account
3. Copy your API key
4. WordPress Admin → Appearance → **API Settings**
5. Paste key → Save Changes

### 2️⃣ Unsplash (FREE - 2 minutes)
1. Go to https://unsplash.com/developers
2. Click "Register as a developer" → Create account
3. Create new app (name: "3000 Studios")
4. Copy "Access Key"
5. WordPress Admin → Appearance → **API Settings**
6. Paste key → Save Changes

### 3️⃣ Pixabay (FREE - 2 minutes)
1. Go to https://pixabay.com/api/docs/
2. Click "Get Started" → Create free account
3. Copy your API key from "API Key" section
4. WordPress Admin → Appearance → **API Settings**
5. Paste key → Save Changes

## 🎯 How to Use

### Access the AI Dashboard
1. Go to https://3000studios.com/wp-admin
2. Pages → Add New
3. Title: "AI Dashboard"
4. Template: Select **"AI Dashboard V2"** from right sidebar
5. Publish
6. Visit the page!

### Voice Commands Examples
🎤 Click the microphone button and say:

- **"Change the contact page title to 'Get in Touch'"**
- **"Add a glow animation to the homepage header"**
- **"Search for space images"** (uses Pexels/Unsplash)
- **"Change the blog page background to dark blue"**
- **"Add a video of nature to the originals page"**

Say **"run it"** or **"execute"** at the end to auto-execute!

### Text Commands
Type any natural language command:

```
Change the privacy page title to "Privacy Policy & Terms"
Add fade-in animation to the shop page
Search Unsplash for mountain photos
Change footer text color to cyan
```

## 🧠 AI Features

### 🎓 Learning System
- ✅ Every command is logged to MySQL database
- ✅ AI learns from successful patterns
- ✅ Confidence scores improve over time
- ✅ Smart suggestions based on your habits

### 🔍 WordPress Intelligence
- ✅ Understands Gutenberg blocks
- ✅ Detects CSS Grid and Flexbox layouts
- ✅ Analyzes page structure automatically
- ✅ Multi-page editing (any page by name/slug)

### 🛡️ Safety Features
- ✅ Auto-backup before every change (.backup.timestamp)
- ✅ Confirmation dialog before execution
- ✅ Success/failure logging
- ✅ Undo suggestions on failure

## 📊 View Statistics

The dashboard shows:
- **Commands Learned** - Total AI commands executed
- **Success Rate** - Percentage of successful changes
- **Top Patterns** - Most used command types

## 🔧 Troubleshooting

### "OpenAI API key not configured"
- Check Windows environment: Run `echo %OPENAI_API_KEY%` in PowerShell
- Should show: `sk-proj-phW9ZwNq...`
- If empty, run: `setx OPENAI_API_KEY "your-key-here"`

### "Pexels/Unsplash/Pixabay not working"
- Go to WordPress Admin → Appearance → **API Settings**
- Check if keys are saved (green checkmark = connected)
- Get keys from links above
- **All these are 100% FREE - no credit card needed!**

### Voice recognition not working
- **Requires HTTPS** (your site is already on https://3000studios.com ✓)
- Allow microphone permission when browser asks
- Only works in Chrome/Edge (not Firefox)

## 💡 Pro Tips

### For Best AI Results:
1. **Be specific**: "Change contact page title" not just "change title"
2. **Use quotes**: "Change text to 'Hello World'" 
3. **Name the page**: "on the shop page" or "blog page"
4. **Preview first**: Always preview before executing

### Image Search:
- AI automatically searches all 3 providers (Pexels, Unsplash, Pixabay)
- Falls back to placeholders if no keys configured
- Over 3 MILLION free images available!

### Save Money:
- OpenAI: Use "preview" mode to test without charges
- Each GPT-4o-mini request costs ~$0.002 (half a penny!)
- Pexels/Unsplash/Pixabay: **COMPLETELY FREE FOREVER**

## 📁 File Structure

```
3000studios/
├── includes/
│   ├── api-settings.php      ← API key management UI
│   ├── api-connector.php      ← All API calls (OpenAI, Pexels, etc)
│   ├── ai-learning.php        ← Database + pattern learning
│   └── wp-intelligence.php    ← WordPress page analysis
├── page-ai-dashboard.php      ← Main AI dashboard interface
├── functions.php              ← AJAX handlers + initialization
└── API-SETUP.md              ← This guide!
```

## 🎉 You're Ready!

**OpenAI is already configured and working!** 🎊

Just get the 3 FREE API keys above (5 mins total) and you'll have access to:
- ✅ AI-powered editing with GPT-4
- ✅ 3,000,000+ free stock photos
- ✅ Free videos and music
- ✅ Voice control with auto-execute
- ✅ Learning AI that gets smarter

---

**Questions?** The AI understands natural language - just ask!

**Want more features?** Tell the AI what you want to build next!

© 2025 Mr. jwswain & 3000 Studios - All Rights Reserved
