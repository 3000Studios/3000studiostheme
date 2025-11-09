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
      if (e.results && e.results[0] && e.results[0][0]) {
        const text = e.results[0][0].transcript;
        transcriptBox.textContent = text;
        sendPromptToServer(text);
      }
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
    
    // Check if the prompt is asking for images/pictures
    const imageKeywords = /\b(image|images|picture|pictures|photo|photos|pic|pics)\b/i;
    if (imageKeywords.test(prompt)) {
      // Extract search query for images
      const searchQuery = extractImageQuery(prompt);
      if (searchQuery) {
        searchImages(searchQuery);
        return;
      }
    }
    
    const data = new FormData();
    data.append('action', 'cc_call_openai');
    data.append('_ajax_nonce', ccData.nonce);
    data.append('prompt', prompt);
    fetch(ccData.ajaxUrl, { method: 'POST', body: data, credentials: 'same-origin' })
      .then(r => {
        if (!r.ok) {
          throw new Error(`Network response was not ok: ${r.status} ${r.statusText}`);
        }
        const contentType = r.headers.get('content-type');
        if (!contentType || !contentType.includes('application/json')) {
          throw new Error(`Expected JSON response but got: ${contentType}`);
        }
        return r.json();
      })
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
        responseBox.textContent = 'Network error: ' + err.message + '. Please check your internet connection and try again.';
        console.error('Command Center error:', err);
      });
  }

  function extractImageQuery(prompt) {
    // Extract what the user wants images of
    // e.g., "get pictures of cats" -> "cats"
    // e.g., "show me images of sunset" -> "sunset"
    const patterns = [
      /\b(?:image|images|picture|pictures|photo|photos|pic|pics)\s+(?:of|about|for|showing)\s+([^.?!]+)/i,
      /\b(?:show|get|find|search)\s+(?:me\s+)?(?:some\s+)?(?:image|images|picture|pictures|photo|photos)\s+(?:of|about|for)?\s*([^.?!]+)/i,
      /\b([^.?!]+)\s+(?:image|images|picture|pictures|photo|photos)/i
    ];
    
    for (const pattern of patterns) {
      const match = prompt.match(pattern);
      if (match && match[1]) {
        return match[1].trim();
      }
    }
    
    return null;
  }

  function searchImages(query) {
    responseBox.textContent = `Searching for images of "${query}"...`;
    const imagesSection = document.getElementById('cc-images-section');
    const imagesContainer = document.getElementById('cc-images');
    
    const data = new FormData();
    data.append('action', 'cc_search_images');
    data.append('_ajax_nonce', ccData.nonce);
    data.append('query', query);
    
    fetch(ccData.ajaxUrl, { method: 'POST', body: data, credentials: 'same-origin' })
      .then(r => {
        if (!r.ok) {
          throw new Error(`Network response was not ok: ${r.status} ${r.statusText}`);
        }
        const contentType = r.headers.get('content-type');
        if (!contentType || !contentType.includes('application/json')) {
          throw new Error(`Expected JSON response but got: ${contentType}`);
        }
        return r.json();
      })
      .then(json => {
        if (!json.success) {
          responseBox.textContent = 'Error searching images: ' + (json.data?.message || JSON.stringify(json.data || json));
          return;
        }
        
        const result = json.data;
        const images = result.images || result.fallback || [];
        
        if (images.length === 0) {
          responseBox.textContent = 'No images found for "' + query + '"';
          return;
        }
        
        responseBox.textContent = `Found ${images.length} images for "${query}"`;
        speakText(`Found ${images.length} images for ${query}`);
        
        // Display images
        imagesContainer.innerHTML = '';
        images.forEach(img => {
          const imgCard = document.createElement('div');
          imgCard.style.cssText = 'border: 1px solid #ddd; border-radius: 4px; overflow: hidden; background: #fff;';
          imgCard.innerHTML = `
            <img src="${img.thumbnail || img.url}" alt="${query}" style="width: 100%; height: 150px; object-fit: cover;">
            <div style="padding: 8px; font-size: 11px; color: #666;">
              ${img.photographer ? 'By: ' + img.photographer + '<br>' : ''}
              Source: ${img.source || 'Unknown'}
            </div>
          `;
          imagesContainer.appendChild(imgCard);
        });
        
        imagesSection.style.display = 'block';
      })
      .catch(err => {
        responseBox.textContent = 'Network error: ' + err.message + '. Please check your internet connection and try again.';
        console.error('Command Center image search error:', err);
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
    data.append('action', 'cc_create_draft');
    data.append('_ajax_nonce', ccData.nonce);
    data.append('title', 'AI Suggestion');
    data.append('content', content);
    fetch(ccData.ajaxUrl, { method: 'POST', body: data, credentials: 'same-origin' })
      .then(r => {
        if (!r.ok) {
          throw new Error(`Network response was not ok: ${r.status} ${r.statusText}`);
        }
        const contentType = r.headers.get('content-type');
        if (!contentType || !contentType.includes('application/json')) {
          throw new Error(`Expected JSON response but got: ${contentType}`);
        }
        return r.json();
      })
      .then(json => {
        if (json.success) {
          alert('Draft created: post id ' + json.data.id);
        } else {
          alert('Create draft failed: ' + JSON.stringify(json.data || json));
        }
      })
      .catch(err => {
        alert('Network error: ' + err.message + '. Please check your internet connection and try again.');
        console.error('Command Center draft creation error:', err);
      });
  });

})();
