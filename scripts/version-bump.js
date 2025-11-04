#!/usr/bin/env node
/**
 * Copyright (c) 2025 Mr. jwswain - 3000 Studios. All Rights Reserved.
 * Automatic Version Bumper
 */

const fs = require('fs');
const path = require('path');

const STYLE_CSS = path.join(__dirname, '..', 'style.css');
const PACKAGE_JSON = path.join(__dirname, '..', 'package.json');

function bumpVersion() {
    try {
        // Read style.css
        let styleContent = fs.readFileSync(STYLE_CSS, 'utf8');
        
        // Extract current version
        const versionMatch = styleContent.match(/Version:\s*([\d.]+)/);
        if (!versionMatch) {
            console.error('❌ Could not find version in style.css');
            process.exit(1);
        }
        
        const currentVersion = versionMatch[1];
        const [major, minor] = currentVersion.split('.');
        const newMinor = parseInt(minor || 0) + 1;
        const newVersion = `${major}.${newMinor}`;
        
        // Update version
        styleContent = styleContent.replace(
            /Version:\s*[\d.]+/,
            `Version: ${newVersion}`
        );
        
        // Update build time
        const buildTime = new Date().toISOString().replace('T', ' ').slice(0, 19);
        styleContent = styleContent.replace(
            /Theme Name: 3000 Studios - Build .*/,
            `Theme Name: 3000 Studios - Build ${buildTime}`
        );
        
        // Write back
        fs.writeFileSync(STYLE_CSS, styleContent);
        
        // Update package.json
        if (fs.existsSync(PACKAGE_JSON)) {
            const pkg = JSON.parse(fs.readFileSync(PACKAGE_JSON, 'utf8'));
            pkg.version = newVersion;
            fs.writeFileSync(PACKAGE_JSON, JSON.stringify(pkg, null, 2) + '\n');
        }
        
        console.log('\x1b[32m✅ Version bumped: %s → %s\x1b[0m', currentVersion, newVersion);
        console.log('\x1b[32m✅ Build time: %s\x1b[0m', buildTime);
        
        return newVersion;
    } catch (error) {
        console.error('\x1b[31m❌ Error bumping version:\x1b[0m', error.message);
        process.exit(1);
    }
}

// Run if called directly
if (require.main === module) {
    bumpVersion();
}

module.exports = bumpVersion;
