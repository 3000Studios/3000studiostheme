---
# Fill in the fields below to create a basic custom agent for your repository.
# The Copilot CLI can be used for local testing: https://gh.io/customagents/cli
# To make this agent available, merge this file into the default repository branch.
# For format details, see: https://gh.io/customagents/config

name:
description:
---

# My Agent

Describe what your agent does here...
// 3000 Studios Theme Agent - agent.js
// Auto-syncs GitHub -> WordPress theme -> live push + livestream monitor
// Requires Node.js 20+ and wp-cli

import fs from "fs";
import { execSync, spawn } from "child_process";
import fetch from "node-fetch";
import chokidar from "chokidar";

const CONFIG = {
  themeDir: "C:/GPT/3000studios-theme",
  gitRepo: "https://github.com/3000Studios/3000studiostheme.git",
  wpPath: "C:/xampp/htdocs/3000studios.com", // adjust for IONOS if remote
  wpUser: "mr.jwswain@gmail.com",
  wpPass: "Gabby3000!!!",
  gitBranch: "main",
  streamUrl: "https://3000studios.com/live",
  checkInterval: 60000
};

// Helper: Run shell command safely
function run(cmd) {
  try {
    return execSync(cmd, { stdio: "pipe" }).toString().trim();
  } catch (e) {
    console.error("❌ Error:", e.message);
  }
}

// Step 1: Sync latest from GitHub
function syncRepo() {
  console.log("🔄 Pulling latest from GitHub...");
  run(`cd "${CONFIG.themeDir}" && git fetch && git reset --hard origin/${CONFIG.gitBranch}`);
  console.log("✅ Repo synced!");
}

// Step 2: Push theme live
function deployTheme() {
  console.log("🚀 Deploying 3000Studios theme...");
  run(`wp theme activate 3000studios --path="${CONFIG.wpPath}" --allow-root`);
  run(`wp cache flush --path="${CONFIG.wpPath}" --allow-root`);
  console.log("🎨 Theme active and cache cleared.");
}

// Step 3: Monitor livestream
async function checkStream() {
  try {
    const res = await fetch(CONFIG.streamUrl);
    if (res.ok) {
      console.log("📡 Livestream online ✅");
    } else {
      console.warn("⚠️ Livestream not responding:", res.status);
    }
  } catch {
    console.error("🚫 Livestream check failed.");
  }
}

// Step 4: Watch theme directory for live sync
function watchTheme() {
  console.log("👀 Watching for theme changes...");
  chokidar.watch(CONFIG.themeDir, { ignoreInitial: true }).on("all", (e, p) => {
    console.log(`📁 ${e.toUpperCase()}: ${p}`);
    syncRepo();
    deployTheme();
  });
}

// Step 5: Run continuous checks
function loop() {
  syncRepo();
  deployTheme();
  checkStream();
  setInterval(() => {
    syncRepo();
    checkStream();
  }, CONFIG.checkInterval);
  watchTheme();
}

console.log(`
🧠 3000 Studios Agent Activated
📂 Repo: ${CONFIG.gitRepo}
🎯 Auto-deploy + Livestream Monitor
`);
loop();
