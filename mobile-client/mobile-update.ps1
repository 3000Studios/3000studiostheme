# PowerShell script to send updates from your phone/computer
# Usage: .\mobile-update.ps1 -Action update-file -Path "style.css" -Content "new css..."

param(
    [Parameter(Mandatory=$true)]
    [string]$WebhookUrl = "https://your-webhook-server.com",
    
    [Parameter(Mandatory=$true)]
    [string]$Secret,
    
    [Parameter(Mandatory=$true)]
    [ValidateSet("update-file", "quick-edit")]
    [string]$Action,
    
    [string]$Path,
    [string]$Content,
    [string]$Message,
    [string]$QuickAction,
    [string]$Value
)

# Prepare request body
$body = @{}
if ($Action -eq "update-file") {
    if (!$Path -or !$Content) {
        Write-Host "Error: -Path and -Content required for update-file" -ForegroundColor Red
        exit 1
    }
    $body = @{
        path = $Path
        content = $Content
        message = $Message
    }
    $endpoint = "/update-file"
} elseif ($Action -eq "quick-edit") {
    if (!$QuickAction) {
        Write-Host "Error: -QuickAction required for quick-edit" -ForegroundColor Red
        exit 1
    }
    $body = @{
        action = $QuickAction
        value = $Value
    }
    $endpoint = "/quick-edit"
}

# Convert to JSON
$jsonBody = $body | ConvertTo-Json -Compress

# Calculate HMAC signature
$hmac = New-Object System.Security.Cryptography.HMACSHA256
$hmac.Key = [System.Text.Encoding]::UTF8.GetBytes($Secret)
$signature = [System.BitConverter]::ToString(
    $hmac.ComputeHash([System.Text.Encoding]::UTF8.GetBytes($jsonBody))
).Replace('-', '').ToLower()

# Send request
try {
    $headers = @{
        'Content-Type' = 'application/json'
        'x-signature' = $signature
    }
    
    $response = Invoke-RestMethod -Uri "$WebhookUrl$endpoint" `
        -Method Post `
        -Headers $headers `
        -Body $jsonBody
    
    Write-Host "✓ Success!" -ForegroundColor Green
    Write-Host ($response | ConvertTo-Json -Depth 3)
} catch {
    Write-Host "✗ Error:" -ForegroundColor Red
    Write-Host $_.Exception.Message
    exit 1
}
