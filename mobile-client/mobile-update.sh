#!/bin/bash
# Bash script to send updates from your phone (Termux) or Linux/Mac
# Usage: ./mobile-update.sh update-file "style.css" "new content" "Update message"

WEBHOOK_URL="${WEBHOOK_URL:-https://your-webhook-server.com}"
WEBHOOK_SECRET="${WEBHOOK_SECRET:-your_secret_here}"

ACTION="$1"
shift

if [ "$ACTION" = "update-file" ]; then
  PATH_ARG="$1"
  CONTENT="$2"
  MESSAGE="${3:-Mobile update at $(date -u +%Y-%m-%dT%H:%M:%SZ)}"
  
  if [ -z "$PATH_ARG" ] || [ -z "$CONTENT" ]; then
    echo "Usage: $0 update-file <path> <content> [message]"
    exit 1
  fi
  
  JSON=$(cat <<EOF
{
  "path": "$PATH_ARG",
  "content": "$CONTENT",
  "message": "$MESSAGE"
}
EOF
)
  ENDPOINT="/update-file"

elif [ "$ACTION" = "quick-edit" ]; then
  QUICK_ACTION="$1"
  VALUE="$2"
  
  if [ -z "$QUICK_ACTION" ]; then
    echo "Usage: $0 quick-edit <action> <value>"
    exit 1
  fi
  
  JSON=$(cat <<EOF
{
  "action": "$QUICK_ACTION",
  "value": "$VALUE"
}
EOF
)
  ENDPOINT="/quick-edit"

else
  echo "Unknown action: $ACTION"
  echo "Usage: $0 {update-file|quick-edit} ..."
  exit 1
fi

# Calculate HMAC signature
SIGNATURE=$(echo -n "$JSON" | openssl dgst -sha256 -hmac "$WEBHOOK_SECRET" | sed 's/^.* //')

# Send request
curl -X POST "$WEBHOOK_URL$ENDPOINT" \
  -H "Content-Type: application/json" \
  -H "x-signature: $SIGNATURE" \
  -d "$JSON" \
  -w "\n"
