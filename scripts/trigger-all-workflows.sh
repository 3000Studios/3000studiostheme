#!/bin/bash
# Copyright (c) 2025 3000 Studios. All Rights Reserved.
# Script to trigger all GitHub Actions workflows that support workflow_dispatch

set -euo pipefail

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
CYAN='\033[0;36m'
NC='\033[0m' # No Color

# Banner
echo -e "${CYAN}━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━${NC}"
echo -e "${CYAN}🚀 3000 Studios - Workflow Trigger Script${NC}"
echo -e "${CYAN}━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━${NC}"
echo ""

# Check if gh CLI is installed
if ! command -v gh &> /dev/null; then
    echo -e "${RED}❌ Error: GitHub CLI (gh) is not installed${NC}"
    echo -e "${YELLOW}Install it from: https://cli.github.com/${NC}"
    exit 1
fi

# Check if authenticated
if ! gh auth status &> /dev/null; then
    echo -e "${RED}❌ Error: Not authenticated with GitHub CLI${NC}"
    echo -e "${YELLOW}Run: gh auth login${NC}"
    exit 1
fi

# Repository
REPO="3000Studios/3000studiostheme"

# List of all workflows that support workflow_dispatch
WORKFLOWS=(
    "auto-changelog.yml"
    "auto-deploy.yml"
    "command-center-sync.yml"
    "deploy-wordpress-theme.yml"
    "deploy-wordpress.yml"
    "dev-inspector.yml"
    "drive-sync.yml"
    "github_workflows_auto-approve-and-merge_Version5.yml"
    "nightly-backup.yml"
    "repo-optimizer.yml"
    "run-all-workflows.yml"
    "security-watchdog.yml"
    "theme-package.yml"
)

# Parse arguments
SELECTED_WORKFLOWS=()
if [ $# -eq 0 ]; then
    # No arguments - run all workflows
    SELECTED_WORKFLOWS=("${WORKFLOWS[@]}")
else
    # Run specific workflows
    SELECTED_WORKFLOWS=("$@")
fi

echo -e "${CYAN}📋 Workflows to trigger (${#SELECTED_WORKFLOWS[@]})${NC}"
for workflow in "${SELECTED_WORKFLOWS[@]}"; do
    echo -e "   ${CYAN}•${NC} $workflow"
done
echo ""

# Ask for confirmation
read -p "Do you want to trigger these workflows? (y/N): " -n 1 -r
echo ""
if [[ ! $REPLY =~ ^[Yy]$ ]]; then
    echo -e "${YELLOW}❌ Cancelled${NC}"
    exit 0
fi

echo ""

# Trigger each workflow
SUCCESS_COUNT=0
FAIL_COUNT=0
FAILED_WORKFLOWS=()

for workflow in "${SELECTED_WORKFLOWS[@]}"; do
    echo -e "${CYAN}━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━${NC}"
    echo -e "${YELLOW}⚡ Triggering:${NC} $workflow"
    
    if gh workflow run "$workflow" --repo "$REPO" 2>&1; then
        echo -e "${GREEN}✅ Successfully triggered:${NC} $workflow"
        ((SUCCESS_COUNT++))
    else
        echo -e "${RED}❌ Failed to trigger:${NC} $workflow"
        FAILED_WORKFLOWS+=("$workflow")
        ((FAIL_COUNT++))
    fi
    
    # Small delay to avoid rate limiting
    sleep 2
done

echo ""
echo -e "${CYAN}━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━${NC}"
echo -e "${CYAN}📊 Summary${NC}"
echo -e "${CYAN}━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━${NC}"
echo -e "${GREEN}   ✅ Successful:${NC} $SUCCESS_COUNT"
echo -e "${RED}   ❌ Failed:${NC} $FAIL_COUNT"
echo -e "${CYAN}   📝 Total:${NC} $((SUCCESS_COUNT + FAIL_COUNT))"

if [ $FAIL_COUNT -gt 0 ]; then
    echo ""
    echo -e "${RED}Failed workflows:${NC}"
    for workflow in "${FAILED_WORKFLOWS[@]}"; do
        echo -e "   ${RED}•${NC} $workflow"
    done
fi

echo -e "${CYAN}━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━${NC}"
echo ""

# Show recent runs
echo -e "${CYAN}📋 Recent Workflow Runs:${NC}"
echo ""
gh run list --repo "$REPO" --limit 10

echo ""
echo -e "${GREEN}✅ Done!${NC}"
echo -e "${CYAN}🌐 View all runs at: https://github.com/$REPO/actions${NC}"
echo ""

# Exit with error if any failed
if [ $FAIL_COUNT -gt 0 ]; then
    exit 1
fi
