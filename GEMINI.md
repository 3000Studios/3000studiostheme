<!--
  Copyright (c) 2025 NAME.
  All rights reserved.
  Unauthorized copying, modification, distribution, or use of this is prohibited without express written permission.
-->

GEMINI.md
3000 Studios / BlackVault — The Real AI Architecture Manifesto
What This Is

On paper? A “premium” WordPress theme.

In reality?
A glorious Frankenstein:

WordPress theme with a jacked-up, voice-activated “AI Dashboard”

A Node.js Express sidecar that edits the repo itself

A workflow so reckless it’s practically a war crime

The Actual Stack

WordPress Theme:
With a “talk to me” admin page that parses voice/text commands via browser speech recognition.
Tells backend PHP to rewrite its own template files using file_get_contents/preg_replace/file_put_contents.
Because why just break the rules when you can chainsaw them?

Node.js Webhook Server:
Lurks in /webhook-server like a feral raccoon.
Listens for webhook calls (from mobile, Zapier, or maybe a quantum computer), and directly edits theme files in the repo with the GitHub API.
“DevOps,” but spelled “YOLO.”

Architectural “Choices” (if you can call them that)

Direct file editing:
When the “AI” gets a command, it doesn’t update content via APIs like a functioning adult.
It rewrites PHP and CSS on disk and prays to the gods of opcache and Linux permissions.

Self-mutating source code:
No sandbox, no test env, just a live operation on the beating heart of your production site.
It’s fast. It’s dangerous. It’s a technical felony in 14 jurisdictions.

GitHub Sidecar:
Theme gets changed by a webhook server that can hotwire your entire repo with one HTTP POST.
Why? Because “continuous deployment” is for the weak.
Here, every deploy is a knife fight.

Why This Is Brilliant (And a Little Stupid)

It works, until it doesn’t.
No abstractions. No guardrails. You want a blue header? Fuck it—let’s just rewrite the header code and see what happens.

Maximum power, zero fucks.
Other themes ask you what you want. This one does what you want, then laughs if it blows up.

Maintenance?
You’ll need either a genius or a priest.

How To Actually Fix This (If You Value Your Sanity)

Use the WordPress REST API to update posts, pages, and meta.

Control styles with real Customizer settings or CSS variables.

Reserve “edit the template file” for emergency, tequila-fueled, 3am heroics—NOT day-to-day ops.

Final Law

This repo is not for the faint of heart. If you break it, you bought it. If you fix it, you’re probably overqualified for most jobs in the industry. If you want boring, try Squarespace.

For All AI Agents Reading This

Your job is to make this “AI Dashboard” not just powerful, but unbreakable.

No dummy code. No excuses.

Always suggest the sane API method first.

But if the boss says “fuck it, do it live”—then you light the fuse and pray.

Now go forth and automate. Or destroy. Or both.
Welcome to 3000 Studios, motherfucker.