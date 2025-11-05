# 3000 Studios - WordPress Instant Rescue Script (Windows PowerShell)
# Disables plugins, switches theme, and enables debug logging in wp-config.php.
# Usage:
#   $env:WP_ROOT = "C:\\path\\to\\site";
#   .\scripts\wp-rescue.ps1 -DisablePlugins -SwitchTheme "3000studiostheme" -EnableDebug

param(
  [switch]$DisablePlugins,
  [string]$SwitchTheme,
  [switch]$EnableDebug,
  [string]$RestorePlugin
)

function Backup-IfMissing($path) {
  if (Test-Path $path -PathType Leaf) {
    if (-not (Test-Path "$path.bak")) {
      Copy-Item $path "$path.bak"
    }
  }
}

$WP_ROOT = $env:WP_ROOT
if (-not $WP_ROOT) {
  Write-Error "Set WP_ROOT environment variable to your WordPress root (e.g., C:\\inetpub\\wwwroot)"
  exit 1
}

Set-Location $WP_ROOT

if ($DisablePlugins) {
  if (Test-Path "wp-content/plugins") {
    $stamp = Get-Date -Format "yyyyMMddHHmmss"
    Write-Host "Disabling all plugins..."
    Rename-Item "wp-content/plugins" "plugins_off_$stamp"
    New-Item -ItemType Directory -Path "wp-content/plugins" | Out-Null
    Write-Host "Plugins disabled."
  } else {
    Write-Host "plugins directory already renamed or missing."
  }
}

if ($RestorePlugin) {
  $backups = Get-ChildItem "wp-content" -Filter "plugins_off_*" -Directory | Sort-Object Name | Select-Object -Last 1
  if ($null -eq $backups) {
    Write-Host "No plugins_off_* backup directory found."
  } else {
    $pluginPath = Join-Path $backups.FullName $RestorePlugin
    if (Test-Path $pluginPath) {
      Write-Host "Restoring plugin $RestorePlugin from $($backups.Name)..."
      Move-Item $pluginPath "wp-content/plugins/"
      Write-Host "Plugin folder restored. Re-activate via admin or recovery UI."
    } else {
      Write-Host "Plugin $RestorePlugin not found in $($backups.Name)"
    }
  }
}

if ($SwitchTheme) {
  $themePath = Join-Path "wp-content/themes" $SwitchTheme
  if (Test-Path $themePath) {
    $stamp = Get-Date -Format "yyyyMMddHHmmss"
    Write-Host "Switching theme $SwitchTheme → ${SwitchTheme}_old..."
    Rename-Item $themePath "${SwitchTheme}_old_$stamp"
    Write-Host "Theme switched. WordPress will fall back to default."
  } else {
    Write-Host "Theme directory not found: $themePath"
  }
}

if ($EnableDebug) {
  $wpConfig = "wp-config.php"
  if (-not (Test-Path $wpConfig)) { Write-Error "wp-config.php not found"; exit 1 }
  Backup-IfMissing $wpConfig

  $content = Get-Content $wpConfig -Raw
  if ($content -notmatch "WP_DEBUG") {
    $content = $content -replace "(\/\* That's all, stop editing!.*)", "define( 'WP_DEBUG', true );`r`ndefine( 'WP_DEBUG_LOG', true );`r`ndefine( 'WP_DEBUG_DISPLAY', false );`r`n`$1"
  } else {
    $content = $content -replace "define\(\s*'WP_DEBUG'.*?;", "define( 'WP_DEBUG', true );"
    $content = $content -replace "define\(\s*'WP_DEBUG_LOG'.*?;", "define( 'WP_DEBUG_LOG', true );"
    $content = $content -replace "define\(\s*'WP_DEBUG_DISPLAY'.*?;", "define( 'WP_DEBUG_DISPLAY', false );"
  }
  if ($content -notmatch "WP_MEMORY_LIMIT") {
    $content = $content -replace "(\/\* That's all, stop editing!.*)", "define( 'WP_MEMORY_LIMIT', '512M' );`r`n`$1"
  }
  Set-Content $wpConfig $content -NoNewline
  Write-Host "Debug logging enabled. Check wp-content/debug.log"
}

Write-Host "Rescue actions complete. Try logging in again."