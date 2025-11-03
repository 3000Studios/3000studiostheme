<!--
  Copyright (c) 2025 NAME.
  All rights reserved.
  Unauthorized copying, modification, distribution, or use of this is prohibited without express written permission.
-->

# Dev Tracker Reminders

## VS Code setup
```bash
npm install -g eslint prettier
pip install flake8
```

## Run periodically
```bash
npx eslint . > issues.log
flake8 . >> issues.log
php -l $(find . -name '*.php') >> issues.log
```

## Ask GPT for the next step
```bash
curl https://api.openai.com/v1/chat/completions \
 -H "Authorization: Bearer $OPENAI_KEY" \
 -H "Content-Type: application/json" \
 -d "{
  \"model\": \"gpt-4o\",\n  \"messages\": [\n    {\"role\": \"system\", \"content\": \"You are 3000 Studios' project manager.\"},\n    {\"role\": \"user\", \"content\": \"Summarize issues.log and suggest next sprint tasks.\"}\n  ]\n }" | jq -r '.choices[0].message.content'
```

Note: set `OPENAI_KEY` in your environment or as a repository secret to use the above.
