# PowerShell script to create theme ZIP for manual upload
$themeName = "3000studios-clean"
$sourceDir = Join-Path $PSScriptRoot ".."
$zipPath = Join-Path $PSScriptRoot "..\$themeName.zip"

Write-Host "Creating theme ZIP: $themeName.zip" -ForegroundColor Cyan

# Remove old ZIP if exists
if (Test-Path $zipPath) {
    Remove-Item $zipPath -Force
    Write-Host "Removed old ZIP file" -ForegroundColor Yellow
}

# Create ZIP excluding git, node_modules, etc.
$exclude = @('.git', '.github', 'node_modules', '.DS_Store', 'README*.md', 'QUICKSTART.md', 'scripts')

# Use PowerShell's Compress-Archive
$filesToZip = Get-ChildItem -Path $sourceDir -Recurse | Where-Object {
    $item = $_
    $shouldExclude = $false
    foreach ($pattern in $exclude) {
        if ($item.FullName -like "*\$pattern*" -or $item.Name -like $pattern) {
            $shouldExclude = $true
            break
        }
    }
    -not $shouldExclude
}

if ($filesToZip.Count -eq 0) {
    Write-Host "No files found to compress!" -ForegroundColor Red
    exit 1
}

# Create temp directory with theme name
$tempDir = Join-Path $env:TEMP $themeName
if (Test-Path $tempDir) {
    Remove-Item $tempDir -Recurse -Force
}
New-Item -ItemType Directory -Path $tempDir -Force | Out-Null

# Copy files to temp directory
foreach ($file in $filesToZip) {
    $relativePath = $file.FullName.Replace($sourceDir, "").TrimStart('\')
    $targetPath = Join-Path $tempDir $relativePath
    $targetDir = Split-Path $targetPath -Parent
    
    if (-not (Test-Path $targetDir)) {
        New-Item -ItemType Directory -Path $targetDir -Force | Out-Null
    }
    
    if ($file.PSIsContainer -eq $false) {
        Copy-Item $file.FullName $targetPath -Force
    }
}

# Compress from temp directory
Compress-Archive -Path "$tempDir\*" -DestinationPath $zipPath -Force

# Cleanup
Remove-Item $tempDir -Recurse -Force

Write-Host "✓ Theme ZIP created successfully: $zipPath" -ForegroundColor Green
Write-Host "  Files included: $($filesToZip.Count)" -ForegroundColor Gray
Write-Host ""
Write-Host "To upload manually:" -ForegroundColor Yellow
Write-Host "  1. Login to your WordPress admin" -ForegroundColor Gray
Write-Host "  2. Go to Appearance > Themes > Add New > Upload Theme" -ForegroundColor Gray
Write-Host "  3. Choose $themeName.zip and click Install Now" -ForegroundColor Gray
