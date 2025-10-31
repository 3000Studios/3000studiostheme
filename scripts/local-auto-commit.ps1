# PowerShell Auto-commit Script
# Detects uncommitted changes, commits, and pushes them
# Usage: Run manually or schedule with Task Scheduler
# Configure via environment variables or edit below

param(
    [string]$RepoDir = (Get-Location).Path,
    [string]$AuthorName = "VSCode AutoCommit",
    [string]$AuthorEmail = "vscode-autocommit@3000studios.com",
    [string]$CommitPrefix = "chore(auto)",
    [switch]$DryRun = $false
)

Set-Location $RepoDir

# Ensure we're in a git repo
try {
    git rev-parse --is-inside-work-tree 2>&1 | Out-Null
} catch {
    Write-Host "Not a git repository: $RepoDir" -ForegroundColor Red
    exit 1
}

# Check if last commit was an autocommit (avoid loops)
$lastMsg = git log -1 --pretty=%B 2>$null
if ($lastMsg -match '\[autocommit\]') {
    Write-Host "Last commit is an autocommit; exiting to avoid loop." -ForegroundColor Yellow
    exit 0
}

# Detect uncommitted changes
$status = git status --porcelain
if ($status) {
    Write-Host "Detected uncommitted changes:" -ForegroundColor Cyan
    git status --porcelain

    if ($DryRun) {
        Write-Host "DRY RUN: not committing or pushing. Remove -DryRun to enable." -ForegroundColor Yellow
        exit 0
    }

    git add -A
    $timestamp = (Get-Date).ToUniversalTime().ToString("yyyy-MM-ddTHH:mm:ssZ")
    $commitMsg = "$CommitPrefix auto-commit changes from VS Code at $timestamp [autocommit]"
    
    git -c user.name="$AuthorName" -c user.email="$AuthorEmail" commit -m $commitMsg
    
    Write-Host "Pushing changes..." -ForegroundColor Green
    git push
    Write-Host "✓ Pushed changes successfully!" -ForegroundColor Green
} else {
    Write-Host "No changes detected." -ForegroundColor Gray
}
