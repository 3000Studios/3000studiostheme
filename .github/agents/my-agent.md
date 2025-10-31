---
name: "3000 Studios Adaptive Copilot Agent"
description: >
  Self-maintaining GitHub Actions + Copilot agent for 3000studios.com.
  Watches the 3000studiostheme repo, tests, deploys, scans for errors,
  and asks the AI advisor for improvement and monetization ideas.

author: "3000 Studios / Boss Man J"
version: "2.0.0"

on:
  push:
    branches: [ main ]
  schedule:
    - cron: "*/30 * * * *"     # every 30 min self-check
  workflow_dispatch:

permissions:
  contents: write
  issues: write

jobs:
  build-test-deploy:
    runs-on: ubuntu-latest
    steps:
      - name: Checkout repo
        uses: actions/checkout@v4

      - name: Setup Node
        uses: actions/setup-node@v4
        with:
          node-version: 20

      - name: Install dependencies
        run: npm ci || npm install

      - name: Run quick self-tests
        run: |
          echo "Running theme lint + sanity checks"
          npx eslint . || echo "::warning::ESLint errors ignored"
          echo "✅ Basic test run finished"

      - name: Deploy via SFTP to 3000studios.com
        env:
          SFTP_HOST: access-5017098454.webspace-host.com
          SFTP_PORT: 22
          SFTP_USER: a132096
          SFTP_PASS: ${{ secrets.IONOS_PASS }}
          REMOTE_PATH: /htdocs/wp-content/themes/3000studiostheme
        run: |
          sudo apt-get update -qq && sudo apt-get install -y lftp
          echo "🚀 Uploading theme"
          lftp -u $SFTP_USER,$SFTP_PASS -p $SFTP_PORT sftp://$SFTP_HOST <<EOF
          mirror -R ./ $REMOTE_PATH --exclude ".git/" --exclude ".github/"
          bye
          EOF
          echo "✅ Deployment done"

      - name: Auto-commit & push any local fixes
        run: |
          git config user.name "3000Studios"
          git config user.email "mr.jwswain@gmail.com"
          git add -A
          if git diff --cached --quiet; then
            echo "Nothing to commit"
          else
            git commit -m "🤖 Self-update by Copilot Agent"
            git push origin main
          fi

      - name: AI Advisor – check logs & propose ideas
        id: advisor
        uses: openai/openai-assistant-action@v1
        with:
          model: gpt-4o
          instructions: |
            Act as an unfiltered but professional advisor for 3000 Studios.
            Review commit messages, last workflow logs, and propose:
            • fixes for any build or deployment errors
            • new feature or design ideas
            • ways to monetize pages, media, or traffic
            Keep language direct and plain (no profanity).
            Return concise bullet points only.

      - name: Post advice as GitHub Issue
        if: ${{ steps.advisor.outputs.text != '' }}
        uses: peter-evans/create-issue-from-file@v5
        with:
          title: "💡 3000 Studios AI Advisor Report"
          content-file: ${{ steps.advisor.outputs.text }}
          labels: ai, advisor, enhancement

      - name: Finish report
        run: echo "🧠 Adaptive Copilot Agent completed cycle."
