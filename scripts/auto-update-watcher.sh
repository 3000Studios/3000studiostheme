#!/bin/bash
# Copyright (c) 2025 Mr. jwswain - 3000 Studios. All Rights Reserved.
# BlackVault SUPREME Auto-Update Watcher

# Colors
CYAN='\033[0;36m'
GREEN='\033[0;32m'
GOLD='\033[0;33m'
NC='\033[0m' # No Color

echo -e "${CYAN}⚡ BlackVault SUPREME Auto-Update Watcher ACTIVATED${NC}"
echo -e "${GOLD}━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━${NC}"

# Get the directory of this script
SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
THEME_DIR="$(dirname "$SCRIPT_DIR")"

# Files to watch
WATCH_PATTERNS=(
    "*.php"
    "*.css"
    "*.js"
    "assets/**/*"
    "includes/**/*"
    "js/**/*"
)

# Version increment function
increment_version() {
    local css_file="$THEME_DIR/style.css"
    local current_version=$(grep "^Version:" "$css_file" | sed 's/Version: //')
    
    # Increment minor version
    local major=$(echo $current_version | cut -d. -f1)
    local minor=$(echo $current_version | cut -d. -f2)
    local new_minor=$((minor + 1))
    local new_version="${major}.${new_minor}"
    
    # Update version in style.css
    sed -i "s/Version: .*/Version: $new_version/" "$css_file"
    
    # Update build time
    local build_time=$(date '+%Y-%m-%d %H:%M:%S')
    sed -i "s/Theme Name: 3000 Studios - Build.*/Theme Name: 3000 Studios - Build $build_time/" "$css_file"
    
    echo -e "${GREEN}✅ Version bumped to $new_version${NC}"
    echo -e "${GREEN}✅ Build time: $build_time${NC}"
    
    return 0
}

# Git auto-commit function
auto_commit() {
    cd "$THEME_DIR"
    
    # Check if there are changes
    if [[ -n $(git status -s) ]]; then
        echo -e "${CYAN}📦 Auto-committing changes...${NC}"
        
        # Get list of changed files
        local changed_files=$(git status -s | awk '{print $2}' | tr '\n' ', ' | sed 's/,$//')
        
        git add .
        git commit -m "🚀 Auto-update: $changed_files - $(date '+%Y-%m-%d %H:%M:%S')"
        
        echo -e "${GREEN}✅ Changes committed${NC}"
        
        # Optionally push
        if [[ "$AUTO_PUSH" == "true" ]]; then
            echo -e "${CYAN}📤 Pushing to remote...${NC}"
            git push origin main
            echo -e "${GREEN}✅ Pushed to origin/main${NC}"
        fi
    fi
}

# Cache clear function
clear_caches() {
    echo -e "${CYAN}🧹 Clearing caches...${NC}"
    
    # Touch functions.php to trigger WordPress to reload
    touch "$THEME_DIR/functions.php"
    
    # Clear any local build caches
    rm -rf "$THEME_DIR/node_modules/.cache" 2>/dev/null
    
    echo -e "${GREEN}✅ Caches cleared${NC}"
}

# Trigger browser refresh via WebSocket or HTTP
trigger_refresh() {
    echo -e "${CYAN}🔄 Triggering browser refresh...${NC}"
    
    # If webhook server is running, notify it
    if curl -s http://localhost:3001/refresh >/dev/null 2>&1; then
        echo -e "${GREEN}✅ Refresh signal sent${NC}"
    else
        echo -e "${GOLD}⚠️  No refresh server running (start with npm run refresh-server)${NC}"
    fi
}

# Main watch loop
watch_theme() {
    echo -e "${CYAN}👁️  Watching theme files...${NC}"
    echo -e "${GOLD}Press Ctrl+C to stop${NC}"
    
    # Install inotify-tools if not present
    if ! command -v inotifywait &> /dev/null; then
        echo -e "${GOLD}Installing inotify-tools...${NC}"
        apt-get update && apt-get install -y inotify-tools
    fi
    
    # Watch for changes
    inotifywait -m -r -e modify,create,delete \
        --exclude '(\.git|node_modules|\.backup|\.zip)' \
        "$THEME_DIR" | while read path action file; do
        
        # Filter only relevant files
        if [[ "$file" =~ \.(php|css|js)$ ]] || [[ "$path" =~ (assets|includes|js) ]]; then
            echo -e "${CYAN}⚡ Detected change: ${file}${NC}"
            
            # Increment version
            increment_version
            
            # Clear caches
            clear_caches
            
            # Auto-commit if enabled
            if [[ "$AUTO_COMMIT" == "true" ]]; then
                auto_commit
            fi
            
            # Trigger refresh
            trigger_refresh
            
            echo -e "${GREEN}━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━${NC}"
        fi
    done
}

# Parse command line arguments
AUTO_COMMIT=${AUTO_COMMIT:-false}
AUTO_PUSH=${AUTO_PUSH:-false}

while [[ $# -gt 0 ]]; do
    case $1 in
        --auto-commit)
            AUTO_COMMIT=true
            shift
            ;;
        --auto-push)
            AUTO_PUSH=true
            AUTO_COMMIT=true
            shift
            ;;
        *)
            shift
            ;;
    esac
done

# Start watching
watch_theme
