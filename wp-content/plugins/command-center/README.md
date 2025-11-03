# 3000 Studios Command Center (WP Plugin)

This plugin adds an admin-only Command Center UI that accepts voice or text prompts, proxies requests to OpenAI from the server, and allows creating drafts from AI responses. Keys must be set in server environment or wp-config.php; they are never stored in code.

## Setup

1. Add the plugin folder `wp-content/plugins/command-center` to the repo and activate it in WordPress admin.
2. Set your OpenAI key on the server (preferred) or in wp-config.php:
   - export OPENAI_API_KEY="sk-..." in the environment, or
   - define('OPENAI_API_KEY','sk-...'); in wp-config.php (less secure).
3. Visit WP Admin → Command Center and use Start Listening.

## Security

- The plugin requires administrator capability (manage_options).
- Do NOT commit API keys to the repo. Rotate any keys previously leaked.
- All API requests are proxied through the server - client never sees the API key.
- Only creates draft posts - no live edits are applied automatically.

## Testing

- In WP Admin, use the interface and speak a short command like: "Create a short blog post about neon cars." Then click Create Draft to verify the draft is created.
- Alternatively, you can type a prompt directly in the transcript box for testing without voice.

## Features

- **Voice Recognition**: Uses browser Web Speech API for hands-free input
- **AI Integration**: Server-side OpenAI API proxy (gpt-4o-mini by default)
- **Text-to-Speech**: Speaks responses back using browser Speech Synthesis API
- **Draft Creation**: Safely creates WordPress draft posts from AI responses
- **Admin-Only**: Restricted to users with manage_options capability
- **Security**: Nonce verification, capability checks, no keys in code

## Reference

This PR addresses earlier workflow failure caused by missing action openai/openai-assistant-action (see job logs ref: 196ee635f7485bed7c794a8c8f6efea608cb03e8).
