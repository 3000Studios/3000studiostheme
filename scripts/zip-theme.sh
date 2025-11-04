#!/bin/bash
# Bash script to create theme ZIP for manual upload

THEME_NAME="3000studios-clean"
SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
SOURCE_DIR="$(dirname "$SCRIPT_DIR")"
ZIP_PATH="$SOURCE_DIR/$THEME_NAME.zip"

echo -e "\033[36mCreating theme ZIP: $THEME_NAME.zip\033[0m"

# Remove old ZIP if exists
if [ -f "$ZIP_PATH" ]; then
    rm "$ZIP_PATH"
    echo -e "\033[33mRemoved old ZIP file\033[0m"
fi

# Create ZIP excluding git, node_modules, etc.
cd "$SOURCE_DIR/.." || exit 1
zip -r "$THEME_NAME.zip" "$(basename "$SOURCE_DIR")" \
    -x "*/\.git/*" \
    -x "*/\.github/*" \
    -x "*/node_modules/*" \
    -x "*/.DS_Store" \
    -x "*/README*.md" \
    -x "*/QUICKSTART.md" \
    -x "*/scripts/*"

mv "$THEME_NAME.zip" "$SOURCE_DIR/" 2>/dev/null

echo -e "\033[32m✓ Theme ZIP created successfully: $ZIP_PATH\033[0m"
echo ""
echo -e "\033[33mTo upload manually:\033[0m"
echo -e "\033[37m  1. Login to your WordPress admin\033[0m"
echo -e "\033[37m  2. Go to Appearance > Themes > Add New > Upload Theme\033[0m"
echo -e "\033[37m  3. Choose $THEME_NAME.zip and click Install Now\033[0m"
