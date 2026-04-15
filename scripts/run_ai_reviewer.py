#!/usr/bin/env python3
"""
AI Reviewer Script for GitHub Actions
Calls OpenAI API to review PR diffs and generate feedback
"""

import os
import json
import sys
import urllib.request
def main():
    """Main function to run AI review"""
    
    # 1. Validate OPENAI_API_KEY
    api_key = os.environ.get("OPENAI_API_KEY")
    if not api_key:
        print("ERROR: OPENAI_API_KEY environment variable is not set!", file=sys.stderr)
        print("Please configure the OPENAI_API_KEY secret in your repository settings.", file=sys.stderr)
        sys.exit(1)
    
    if not api_key.strip():
        print("ERROR: OPENAI_API_KEY is empty!", file=sys.stderr)
        sys.exit(1)
    
    print("✓ OPENAI_API_KEY is set")
    
    # 2. Read PR diff from environment variable
    diff = os.environ.get("DIFF", "")
    if not diff:
        print("WARNING: DIFF environment variable is empty. No changes to review.", file=sys.stderr)
        with open("ai-review.txt", "w") as f:
            f.write("No changes detected in this PR.")
        sys.exit(0)
    
    # 3. Truncate diff to first 12000 characters (same as original)
    truncated_diff = diff[:12000]
    print(f"✓ Diff loaded ({len(diff)} chars, truncated to {len(truncated_diff)} chars)")
    
    # 4. Prepare the prompt
    prompt = (
        "Review this PR diff. Flag risky changes, security issues, and suggest improvements. "
        "Keep it under 300 words.\n\n"
        f"{truncated_diff}"
    )
    
    # 5. Call OpenAI API
    try:
        print("Calling OpenAI API...")
        
        request_data = {
            "model": "gpt-4o-mini",
            "messages": [
                {
                    "role": "user",
                    "content": prompt
                }
            ]
        }
        
        data = json.dumps(request_data).encode('utf-8')
        
        req = urllib.request.Request(
            "https://api.openai.com/v1/chat/completions",
            data=data,
            headers={
                "Authorization": f"Bearer {api_key}",
                "Content-Type": "application/json"
            }
        )
        
        response = urllib.request.urlopen(req)
        response_data = response.read().decode('utf-8')
        
        print("✓ OpenAI API call successful")
        
        # 6. Parse response
        result = json.loads(response_data)
        
        if "choices" not in result or len(result["choices"]) == 0:
            print("ERROR: Unexpected API response format", file=sys.stderr)
            # Only print safe metadata, not full response
            print(f"Response keys: {list(result.keys())}", file=sys.stderr)
            sys.exit(1)
        
        review_text = result["choices"][0]["message"]["content"]
        
        # 7. Write output to file
        with open("ai-review.txt", "w") as f:
            f.write(review_text)
        
        print("✓ Review written to ai-review.txt")
        print("\n--- AI Review ---")
        print(review_text)
        print("--- End of Review ---\n")
        
    except urllib.error.HTTPError as e:
        try:
            error_body = e.read().decode('utf-8')
        except Exception:
            error_body = "Unable to read error details"
        print(f"ERROR: HTTP {e.code} - {e.reason}", file=sys.stderr)
        print(f"Error details: {error_body}", file=sys.stderr)
        
        if e.code == 401:
            print("\n⚠ Authentication failed. Please check:", file=sys.stderr)
            print("  1. OPENAI_API_KEY secret is correctly set in repository settings", file=sys.stderr)
            print("  2. The API key is valid and has not expired", file=sys.stderr)
            print("  3. The API key has sufficient permissions", file=sys.stderr)
        
        sys.exit(1)
        
    except urllib.error.URLError as e:
        print(f"ERROR: Network error - {e.reason}", file=sys.stderr)
        sys.exit(1)
        
    except json.JSONDecodeError as e:
        print(f"ERROR: Failed to parse JSON response - {e}", file=sys.stderr)
        sys.exit(1)
        
    except Exception as e:
        print(f"ERROR: Unexpected error - {type(e).__name__}: {e}", file=sys.stderr)
        import traceback
        traceback.print_exc()
        sys.exit(1)


if __name__ == "__main__":
    main()
