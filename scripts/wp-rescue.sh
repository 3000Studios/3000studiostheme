#!/usr/bin/env bash
# 3000 Studios - WordPress Instant Rescue Script (Linux/macOS)
# Disables all plugins, switches theme, and enables debug logging safely.
# Usage: WP_ROOT=/path/to/site bash scripts/wp-rescue.sh [--disable-plugins] [--switch-theme themedir] [--enable-debug]

set -euo pipefail

WP_ROOT="${WP_ROOT:-}"
THEME_DIR="${THEME_DIR:-}" # Optional theme directory name to switch (e.g., 3000studiostheme)
ACTION_DISABLE_PLUGINS=false
ACTION_SWITCH_THEME=false
ACTION_ENABLE_DEBUG=false

for arg in "$@"; do
  case "$arg" in
    --disable-plugins) ACTION_DISABLE_PLUGINS=true ;;
    --switch-theme) ACTION_SWITCH_THEME=true ; shift ; THEME_DIR="${1:-}" ;;
    --enable-debug) ACTION_ENABLE_DEBUG=true ;;
    *) ;;
  esac
done

if [[ -z "$WP_ROOT" ]]; then
  echo "❌ Set WP_ROOT to your WordPress root (e.g., /var/www/html)"
  exit 1
fi

cd "$WP_ROOT"

backup_if_missing() {
  local file="$1"
  if [[ -f "$file" && ! -f "$file.bak" ]]; then
    cp -a "$file" "$file.bak"
  fi
}

# 1) Disable all plugins
if $ACTION_DISABLE_PLUGINS; then
  if [[ -d wp-content/plugins ]]; then
    echo "🔧 Disabling all plugins..."
    mv wp-content/plugins wp-content/plugins_off_$(date +%Y%m%d%H%M%S)
    mkdir -p wp-content/plugins
    echo "✅ Plugins disabled (renamed)."
  else
    echo "ℹ️ plugins directory already renamed or missing."
  fi
fi

# 2) Switch theme by renaming theme directory
if $ACTION_SWITCH_THEME; then
  if [[ -z "$THEME_DIR" ]]; then
    echo "❌ Provide a theme directory with --switch-theme THEME_DIR"
    exit 1
  fi
  if [[ -d "wp-content/themes/$THEME_DIR" ]]; then
    echo "🔧 Switching theme ($THEME_DIR → ${THEME_DIR}_old)..."
    mv "wp-content/themes/$THEME_DIR" "wp-content/themes/${THEME_DIR}_old_$(date +%Y%m%d%H%M%S)"
    echo "✅ Theme switched. WordPress will fall back to default."
  else
    echo "ℹ️ Theme directory not found: wp-content/themes/$THEME_DIR"
  fi
fi

# 3) Enable debug logging (to wp-content/debug.log)
if $ACTION_ENABLE_DEBUG; then
  if [[ ! -f wp-config.php ]]; then
    echo "❌ wp-config.php not found in $WP_ROOT"
    exit 1
  fi
  echo "🔧 Enabling WP_DEBUG (log to wp-content/debug.log)..."
  backup_if_missing wp-config.php

  # Insert constants above the stop-editing line if not already present
  if ! grep -q "WP_DEBUG" wp-config.php; then
    sed -i.bak "/That's all, stop editing/i \\ndefine( 'WP_DEBUG', true );\\n\
define( 'WP_DEBUG_LOG', true );\\n\
define( 'WP_DEBUG_DISPLAY', false );" wp-config.php
    echo "✅ Debug constants inserted."
  else
    sed -i.bak "s/define( *'WP_DEBUG'.*/define( 'WP_DEBUG', true );/" wp-config.php || true
    sed -i.bak "s/define( *'WP_DEBUG_LOG'.*/define( 'WP_DEBUG_LOG', true );/" wp-config.php || true
    sed -i.bak "s/define( *'WP_DEBUG_DISPLAY'.*/define( 'WP_DEBUG_DISPLAY', false );/" wp-config.php || true
    echo "✅ Debug constants updated."
  fi
fi

echo "\n🎯 Rescue actions complete. Try logging in again."
if $ACTION_ENABLE_DEBUG; then
  echo "👉 Check logs: $WP_ROOT/wp-content/debug.log"
fi
