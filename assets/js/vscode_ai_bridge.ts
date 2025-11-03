/**
 * 3000 Studios AI → VS Code Bridge
 * Links ChatGPT or voice assistant commands to VS Code actions.
 * Requires: OpenAI SDK + VS Code API + Node 20+
 */

import vscode from 'vscode';
import OpenAI from 'openai';
import fs from 'fs';
import path from 'path';

const openai = new OpenAI({
  apiKey: process.env.OPENAI_API_KEY   // stored in your environment
});

export function activate(context) {
  const disposable = vscode.commands.registerCommand('3000studios.chatExec', async () => {
    const prompt = await vscode.window.showInputBox({
      prompt: 'What do you want the AI to do in this project?'
    });
    if (!prompt) return;

    vscode.window.showInformationMessage('🤖 Thinking…');

    try {
      // send the user’s command to ChatGPT
      const completion = await openai.chat.completions.create({
        model: 'gpt-5',    // or gpt-4o-mini, etc.
        messages: [
          { role: 'system', content: 'You are a VS Code automation assistant. Reply with executable code only.' },
          { role: 'user', content: prompt }
        ]
      });

      const reply = completion.choices[0].message.content.trim();
      const editor = vscode.window.activeTextEditor;

      if (editor) {
        // insert AI’s code into the current file
        await editor.edit(edit => {
          edit.insert(editor.selection.active, '\n' + reply + '\n');
        });
        vscode.window.showInformationMessage('✅ AI code inserted.');
      } else {
        // or just display in a new output channel
        const channel = vscode.window.createOutputChannel('3000 Studios AI');
        channel.appendLine(reply);
        channel.show();
      }
    } catch (err) {
      vscode.window.showErrorMessage(`AI Bridge error: ${err.message}`);
    }
  });

  context.subscriptions.push(disposable);
}

export function deactivate() {}
