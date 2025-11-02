# voice-vscode.ps1
# Safe local voice + VS Code bridge
# Prereqs:
#   - PowerShell 7+
#   - Edge/Chrome Speech API microphone access or installed Speech SDK
#   - ChatGPT VS Code extension (or REST API enabled)
#   - OPENAI_KEY environment variable set

Add-Type -AssemblyName System.Speech
$recognizer = New-Object System.Speech.Recognition.SpeechRecognitionEngine
$synth = New-Object System.Speech.Synthesis.SpeechSynthesizer
$synth.Volume = 100
$synth.Rate = 0

# Simple grammar (you can expand this later)
$choices = New-Object System.Speech.Recognition.Choices
$choices.Add("stop listening")
$grammar = New-Object System.Speech.Recognition.Grammar(
    (New-Object System.Speech.Recognition.GrammarBuilder($choices))
)
$recognizer.LoadGrammar($grammar)
$recognizer.SetInputToDefaultAudioDevice()

function Send-ToChatGPT($text) {
    $body = @{
        model    = "gpt-5"
        messages = @(@{role="user";content=$text})
    } | ConvertTo-Json -Depth 5

    try {
        $response = Invoke-RestMethod -Uri "https://api.openai.com/v1/chat/completions" `
            -Headers @{Authorization="Bearer $env:OPENAI_KEY"} `
            -Body $body -ContentType "application/json"
        return $response.choices[0].message.content
    } catch {
        return "Error contacting ChatGPT: $_"
    }
}

function Send-ToVSCode($msg) {
    # Sends text to VS Code's Chat input window using Windows APIs
    $vscode = Get-Process -Name "Code" -ErrorAction SilentlyContinue
    if ($vscode) {
        Add-Type -AssemblyName System.Windows.Forms
        [System.Windows.Forms.SendKeys]::SendWait("$msg{ENTER}")
    }
}

Write-Host "`n🎙️ Say something to ChatGPT. Say 'stop listening' to quit.`n"

while ($true) {
    $result = $recognizer.Recognize()
    if ($null -eq $result) { continue }
    $text = $result.Text
    if ($text -eq "stop listening") { break }

    Write-Host "🗣️ You said: $text"
    $reply = Send-ToChatGPT $text
    Write-Host "🤖 ChatGPT: $reply"

    # Speak it back
    $synth.Speak($reply)

    # Optionally send to VS Code
    Send-ToVSCode $reply
}

$synth.Speak("Goodbye.")
Write-Host "`n✅ Session ended."
