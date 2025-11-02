(function () {
  const startBtn = document.getElementById('cc-start');
  const stopBtn = document.getElementById('cc-stop');
  const transcriptBox = document.getElementById('cc-transcript');
  const responseBox = document.getElementById('cc-response');
  const draftBtn = document.getElementById('cc-create-draft');

  let recognition;
  if (!('webkitSpeechRecognition' in window) && !('SpeechRecognition' in window)) {
    transcriptBox.textContent = 'SpeechRecognition not supported in this browser.';
  } else {
    const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
    recognition = new SpeechRecognition();
    recognition.lang = 'en-US';
    recognition.interimResults = false;
    recognition.continuous = false;

    recognition.onresult = function (e) {
      const text = e.results[0][0].transcript;
      transcriptBox.textContent = text;
      sendPromptToServer(text);
    };
    recognition.onend = function() {
      startBtn.disabled = false;
      stopBtn.disabled = true;
    };
  }

  startBtn.addEventListener('click', () => {
    if (!recognition) return;
    recognition.start();
    startBtn.disabled = true;
    stopBtn.disabled = false;
  });
  stopBtn.addEventListener('click', () => {
    if (!recognition) return;
    recognition.stop();
  });

  function sendPromptToServer(prompt) {
    responseBox.textContent = 'Thinking...';
    const data = new FormData();
    data.append('action','cc_call_openai');
    data.append('_ajax_nonce', ccData.nonce);
    data.append('prompt', prompt);
    fetch(ccData.ajaxUrl, { method: 'POST', body: data, credentials: 'same-origin' })
      .then(r => r.json())
      .then(json => {
        if (!json.success) {
          responseBox.textContent = 'Error: ' + JSON.stringify(json.data || json);
          return;
        }
        // json.data.body contains raw OpenAI response body. Parse first-choice if present.
        try {
          const obj = JSON.parse(json.data.body);
          const content = obj.choices?.[0]?.message?.content || JSON.stringify(obj);
          responseBox.textContent = content;
          speakText(content);
          draftBtn.style.display = 'inline-block';
          draftBtn.dataset.content = content;
        } catch (e) {
          responseBox.textContent = json.data.body || JSON.stringify(json.data);
        }
      })
      .catch(err => {
        responseBox.textContent = 'Request failed: ' + err;
      });
  }

  function speakText(text) {
    if ('speechSynthesis' in window) {
      const u = new SpeechSynthesisUtterance(text);
      window.speechSynthesis.speak(u);
    }
  }

  draftBtn.addEventListener('click', () => {
    const content = draftBtn.dataset.content || '';
    const data = new FormData();
    data.append('action','cc_create_draft');
    data.append('_ajax_nonce', ccData.nonce);
    data.append('title','AI Suggestion');
    data.append('content', content);
    fetch(ccData.ajaxUrl, { method: 'POST', body: data, credentials: 'same-origin' })
      .then(r => r.json())
      .then(json => {
        if (json.success) {
          alert('Draft created: post id ' + json.data.id);
        } else {
          alert('Create draft failed: ' + JSON.stringify(json.data || json));
        }
      });
  });

})();
