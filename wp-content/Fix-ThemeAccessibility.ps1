<#
.SYNOPSIS
  Repairs common accessibility, mixed-content, and deprecated JS issues in a WordPress theme.
#>

Set-StrictMode -Version Latest
$ErrorActionPreference = 'Stop'

$themePath = Read-Host "Enter full path to your theme folder (e.g. C:\GPT\3000studios\wp-content\themes\3000studiostheme)"
if (-not (Test-Path $themePath)) { Write-Host "Theme path not found" -ForegroundColor Red; exit 1 }

Write-Host "`n=== Backing up theme ==="
$zip = Join-Path $themePath "..\theme-backup-$(Get-Date -Format yyyyMMdd-HHmmss).zip"
Add-Type -AssemblyName System.IO.Compression.FileSystem
[System.IO.Compression.ZipFile]::CreateFromDirectory($themePath, $zip)
Write-Host "Backup created: $zip" -ForegroundColor Green

# 1️⃣ Fix form fields lacking id/name or label
Write-Host "`n=== Adding id/name and labels to form fields ==="
$files = Get-ChildItem $themePath -Recurse -Include *.php,*.html
foreach ($f in $files) {
    $content = Get-Content $f.FullName -Raw
    $changed = $false

    # Add missing name to <input>, <textarea>, <select>
    $pattern = '<(input|textarea|select)(?![^>]*(id|name)=)[^>]*>'
    $content = [regex]::Replace($content, $pattern, {
        param($m)
        $tag = $m.Groups[1].Value
        $uid = "auto_" + [guid]::NewGuid().ToString("N").Substring(0,8)
        "<$tag id='$uid' name='$uid'"
    }, [System.Text.RegularExpressions.RegexOptions]::IgnoreCase)

    # Add <label> for unlabeled inputs
    $content = [regex]::Replace($content, '(<input[^>]*id=[\"'']([^\"'']+)[\"''][^>]*>)(?![\s\S]{0,120}</label>)', {
        param($m)
        $id = $m.Groups[2].Value
        "<label for='$id'>$($id.Replace('auto_','').ToUpper())</label>`r`n$($m.Groups[1].Value)"
    }, [System.Text.RegularExpressions.RegexOptions]::IgnoreCase)
    if ($content -ne (Get-Content $f.FullName -Raw)) {
        $content | Set-Content $f.FullName -Encoding UTF8
        Write-Host "Fixed forms in $($f.Name)" -ForegroundColor Yellow
    }
}

# 2️⃣ Move @import rules to top of CSS
Write-Host "`n=== Moving @import rules to top of CSS ==="
$cssFiles = Get-ChildItem $themePath -Recurse -Include *.css
foreach ($f in $cssFiles) {
    $content = Get-Content $f.FullName -Raw
    $imports = [regex]::Matches($content, '@import[^\n;]+[;\n]')
    if ($imports.Count -gt 0) {
        $importsText = ($imports | ForEach-Object { $_.Value }) -join "`r`n"
        $noImports = [regex]::Replace($content, '@import[^\n;]+[;\n]', '')
        "$importsText`r`n$noImports" | Set-Content $f.FullName -Encoding UTF8
        Write-Host "Reordered imports in $($f.Name)" -ForegroundColor Yellow
    }
}

# 3️⃣ Wrap speechSynthesis.speak in click event
Write-Host "`n=== Wrapping speechSynthesis calls ==="
$jsFiles = Get-ChildItem $themePath -Recurse -Include *.js
foreach ($f in $jsFiles) {
    $content = Get-Content $f.FullName -Raw
    if ($content -match 'speechSynthesis\.speak') {
        $wrapped = @"
document.addEventListener('click', () => {
  try {
$([regex]::Replace($content,'speechSynthesis\.speak','    speechSynthesis.speak'))
  } catch(e) { console.warn('speechSynthesis blocked', e); }
}, { once: true });
"@
        $wrapped | Set-Content $f.FullName -Encoding UTF8
        Write-Host "Wrapped speech synthesis in $($f.Name)" -ForegroundColor Yellow
    }
}

# 4️⃣ Replace http:// with https:// in files
Write-Host "`n=== Replacing http:// with https:// ==="
$allFiles = Get-ChildItem $themePath -Recurse -Include *.php,*.css,*.js,*.html
foreach ($f in $allFiles) {
    (Get-Content $f.FullName -Raw).Replace('http://','https://') | Set-Content $f.FullName -Encoding UTF8
}
Write-Host "All links updated to HTTPS." -ForegroundColor Green

Write-Host "`n✅ Fix complete. Backup created at: $zip`n"
