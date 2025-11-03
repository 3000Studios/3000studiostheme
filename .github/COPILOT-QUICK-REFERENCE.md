<!-- 
Copyright (c) 2025 Mr. J.W. Swain - 3000 Studios. All Rights Reserved.
-->

# Copilot Ωmega Quick Reference 🚀

## Activation
```
"Hey Dude, engage Ωmega Quantum Protocol"
```

## Core Commands

### WordPress Development
```php
// Create WordPress page template with neon theme
// Add custom post type for [name]
// Implement WordPress REST API endpoint
// Add nonce verification and sanitization
```

### Payment Integration
```javascript
// Integrate Stripe checkout with webhooks
// Add PayPal button with transaction logging
// Create subscription management system
```

### Animation & Effects
```css
/* Neon glow button animation */
/* 3000 Studios hero section with particles */
/* Framer Motion page transition */
```

### Security
```php
// Sanitize and validate form inputs
// Add JWT authentication
// Implement rate limiting
```

## Keyboard Shortcuts

| Action | Windows/Linux | Mac |
|--------|---------------|-----|
| Accept suggestion | `Tab` | `Tab` |
| Next suggestion | `Alt + ]` | `Option + ]` |
| Previous suggestion | `Alt + [` | `Option + [` |
| Trigger inline | `Alt + \` | `Option + \` |
| Open Chat | `Ctrl + Shift + I` | `Cmd + Shift + I` |

## Chat Commands
- `/explain` - Explain code
- `/fix` - Fix bugs
- `/tests` - Generate tests
- `/doc` - Add documentation

## Brand Colors
- **Cyan**: `#00ffff`
- **Purple**: `#9d4edd`
- **Lime**: `#00ff00`

## Best Practices
1. ✅ Clear, specific comments
2. ✅ Open relevant files
3. ✅ Review before accepting
4. ✅ Test security code
5. ✅ Use env vars for secrets

## Quick Patterns

### Neon Button
```html
<button class="bg-transparent border-2 border-cyan-400 text-cyan-400 
               hover:bg-cyan-400 hover:text-black 
               transition-all duration-300 
               px-6 py-3 rounded-lg
               shadow-[0_0_10px_rgba(0,255,255,0.5)]
               hover:shadow-[0_0_20px_rgba(0,255,255,0.8)]">
  Click Me
</button>
```

### API Call Pattern
```javascript
async function fetchData() {
  try {
    const response = await fetch('/api/endpoint');
    if (!response.ok) throw new Error('Failed');
    return await response.json();
  } catch (error) {
    console.error('Error:', error);
    return null;
  }
}
```

### WordPress Security
```php
// Verify nonce
if (!wp_verify_nonce($_POST['nonce'], 'action_name')) {
    wp_die('Security check failed');
}

// Sanitize input
$clean = sanitize_text_field($_POST['data']);

// Escape output
echo esc_html($user_input);
```

---

**Status Indicator**: Cyan glow = Active ✨
**Support**: J@3000studios.com
**Docs**: [COPILOT-OMEGA-SETUP.md](../COPILOT-OMEGA-SETUP.md)
