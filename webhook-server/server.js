// Copyright (c) 2025 NAME.
// All rights reserved.
// Unauthorized copying, modification, distribution, or use of this is prohibited without express written permission.

// Mobile-to-GitHub Webhook Service
// Accepts authenticated requests to update theme files remotely
// Secure: Uses HMAC signature verification + GitHub token

require('dotenv').config();
const express = require('express');
const bodyParser = require('body-parser');
const crypto = require('crypto');
const { Octokit } = require("@octokit/rest");

const app = express();
app.use(bodyParser.json());

// Configuration
const PORT = process.env.PORT || 3000;
const GH_TOKEN = process.env.GH_TOKEN;
const GH_OWNER = process.env.GH_OWNER || '3000Studios';
const GH_REPO = process.env.GH_REPO || '3000studiostheme';
const WEBHOOK_SECRET = process.env.WEBHOOK_SECRET;
const DEFAULT_BRANCH = process.env.DEFAULT_BRANCH || 'main';

// Validate required env vars
if (!GH_TOKEN || !WEBHOOK_SECRET) {
  console.error('❌ ERROR: Set GH_TOKEN and WEBHOOK_SECRET in .env file');
  console.error('Example:');
  console.error('  GH_TOKEN=ghp_your_token_here');
  console.error('  WEBHOOK_SECRET=your_random_secret_string');
  process.exit(1);
}

const octokit = new Octokit({ auth: GH_TOKEN });

// Verify HMAC signature
function verifySignature(payload, signature) {
  if (!signature) return false;
  const hmac = crypto.createHmac('sha256', WEBHOOK_SECRET);
  hmac.update(payload);
  const expected = hmac.digest('hex');
  return crypto.timingSafeEqual(Buffer.from(expected), Buffer.from(signature));
}

// Health check endpoint
app.get('/', (req, res) => {
  res.json({ 
    status: 'ok', 
    service: '3000 Studios Mobile Webhook',
    endpoints: {
      updateFile: 'POST /update-file',
      quickEdit: 'POST /quick-edit'
    }
  });
});

// Update any file in the theme
app.post('/update-file', async (req, res) => {
  try {
    const signature = req.headers['x-signature'];
    const payload = JSON.stringify(req.body);
    
    if (!verifySignature(payload, signature)) {
      return res.status(401).json({ error: 'Invalid signature' });
    }

    const { path, content, message, branch } = req.body;
    if (!path || typeof content === 'undefined') {
      return res.status(400).json({ error: 'Missing path or content' });
    }

    const targetBranch = branch || DEFAULT_BRANCH;
    
    // Get existing file SHA (if it exists)
    let fileSha = null;
    try {
      const getRes = await octokit.repos.getContent({
        owner: GH_OWNER,
        repo: GH_REPO,
        path,
        ref: targetBranch,
      });
      if (Array.isArray(getRes.data)) {
        return res.status(400).json({ error: 'Path is a directory' });
      }
      fileSha = getRes.data.sha;
    } catch (err) {
      if (err.status && err.status !== 404) throw err;
    }

    // Create or update file
    const commitMessage = message || `📱 Mobile update: ${path} at ${new Date().toISOString()}`;
    const encoded = Buffer.from(content).toString('base64');

    const result = await octokit.repos.createOrUpdateFileContents({
      owner: GH_OWNER,
      repo: GH_REPO,
      path,
      message: commitMessage,
      content: encoded,
      sha: fileSha || undefined,
      branch: targetBranch,
      committer: {
        name: "3000Studios Mobile Bot",
        email: "mobile-bot@3000studios.com"
      },
      author: {
        name: "3000Studios Mobile Bot",
        email: "mobile-bot@3000studios.com"
      }
    });

    console.log(`✓ Updated ${path} on ${targetBranch}`);
    return res.json({ 
      ok: true, 
      path, 
      branch: targetBranch,
      commit: result.data.commit.sha.substring(0, 7)
    });
  } catch (err) {
    console.error('Error handling update-file:', err);
    return res.status(500).json({ error: err.message || String(err) });
  }
});

// Quick edit endpoint - predefined common changes
app.post('/quick-edit', async (req, res) => {
  try {
    const signature = req.headers['x-signature'];
    const payload = JSON.stringify(req.body);
    
    if (!verifySignature(payload, signature)) {
      return res.status(401).json({ error: 'Invalid signature' });
    }

    const { action, value } = req.body;
    let path, content, message;

    // Predefined quick actions
    switch (action) {
      case 'update-border-color':
        path = 'style.css';
        // Read current file and update border color
        const styleRes = await octokit.repos.getContent({
          owner: GH_OWNER,
          repo: GH_REPO,
          path,
          ref: DEFAULT_BRANCH,
        });
        const currentStyle = Buffer.from(styleRes.data.content, 'base64').toString();
        // Simple find/replace for border color (customize as needed)
        content = currentStyle.replace(
          /border:\s*\d+px\s+solid\s+[^;]+;/g, 
          `border: 2px solid ${value};`
        );
        message = `📱 Mobile: Update border color to ${value}`;
        break;

      case 'toggle-feature':
        // Example: toggle a feature flag in functions.php
        path = 'functions.php';
        message = `📱 Mobile: Toggle feature ${value}`;
        // Implement your toggle logic here
        break;

      case 'update-announcement':
        // Update a site announcement
        path = 'header.php';
        message = `📱 Mobile: Update announcement to "${value}"`;
        // Implement announcement update logic
        break;

      default:
        return res.status(400).json({ error: `Unknown action: ${action}` });
    }

    // If content was set, commit it
    if (content) {
      const encoded = Buffer.from(content).toString('base64');
      await octokit.repos.createOrUpdateFileContents({
        owner: GH_OWNER,
        repo: GH_REPO,
        path,
        message,
        content: encoded,
        sha: styleRes.data.sha,
        branch: DEFAULT_BRANCH,
      });
      
      console.log(`✓ Quick edit: ${action}`);
      return res.json({ ok: true, action, path });
    }

    return res.status(501).json({ error: 'Action not implemented yet' });
  } catch (err) {
    console.error('Error handling quick-edit:', err);
    return res.status(500).json({ error: err.message || String(err) });
  }
});

app.listen(PORT, () => {
  console.log(`✓ 3000 Studios Webhook Server running on port ${PORT}`);
  console.log(`  GitHub: ${GH_OWNER}/${GH_REPO}`);
  console.log(`  Branch: ${DEFAULT_BRANCH}`);
  console.log(`  Endpoints:`);
  console.log(`    POST /update-file - Update any theme file`);
  console.log(`    POST /quick-edit - Predefined quick actions`);
});
