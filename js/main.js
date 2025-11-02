
/**
 * 3000 Studios Theme - Main JavaScript
 * 
 * @package     3000Studios
 * @author      Mr. jwswain
 * @copyright   Copyright (c) 2025, Mr. jwswain & 3000 Studios
 * @license     Proprietary - All Rights Reserved
 * @link        https://3000studios.com
 * @version     1.0.0
 * 
 * ⚠️ COPYRIGHT PROTECTED CODE
 * 
 * This JavaScript code is proprietary and confidential.
 * Unauthorized copying, modification, reverse engineering, or
 * distribution is STRICTLY PROHIBITED and will result in legal action.
 * 
 * © 2025 Mr. jwswain & 3000 Studios. All Rights Reserved.
 */

console.log("%c© 2025 3000 Studios - PROPRIETARY CODE", "color:lime;font-size:14px;font-weight:bold;");
console.log("%cCreated by Mr. jwswain | All Rights Reserved", "color:cyan;font-size:12px;");

// Emergency preloader hide - prevents black screen
document.addEventListener('DOMContentLoaded', () => {
  // Fallback: hide preloader after max 4 seconds no matter what
  setTimeout(() => {
    const preloader = document.querySelector('.preloader');
    if (preloader && preloader instanceof HTMLElement) {
      preloader.style.display = 'none';
      console.log('Preloader emergency hide activated');
    }
  }, 4000);
});

// ==================== OPTIMIZED CODE ====================
// Random background music (optimized with error handling)
const tracks = [
  "http://3000studios.com/wp-content/uploads/2025/10/atmospheric-tech-387712.mp3",
  "http://3000studios.com/wp-content/uploads/2025/10/smooth-coffee-254076.mp3",
  "http://3000studios.com/wp-content/uploads/2025/10/lounge-398189.mp3",
  "http://3000studios.com/wp-content/uploads/2025/10/good-vibe-398183.mp3",
  "http://3000studios.com/wp-content/uploads/2025/10/coffee-lounge-145030.mp3"
];

// Hide preloader and initialize page
window.addEventListener("load", () => {
  // Hide the preloader after page loads
  const preloader = document.querySelector('.preloader');
  if (preloader && preloader instanceof HTMLElement) {
    // Clear any CSS animation and manually control fade out
    preloader.style.animation = 'none';
    setTimeout(() => {
      preloader.classList.add('fade-out');
      setTimeout(() => {
        preloader.style.display = 'none';
      }, 500);
    }, 500); // Show for 500ms then fade out
  }
  
  // Initialize background audio
  const audio = new Audio(tracks[Math.floor(Math.random() * tracks.length)]);
  audio.volume = 0.3;
  audio.loop = true;
  audio.preload = "auto"; // Preload for faster playback
  
  // User interaction required for autoplay
  document.addEventListener('click', () => {
    audio.play().catch(err => console.warn('Audio autoplay blocked:', err));
  }, { once: true, passive: true });
}, { passive: true });

// Click sound (optimized with debouncing and caching)
(function() {
  const clickSound = new Audio("http://3000studios.com/wp-content/uploads/2025/10/click-21156.mp3");
  clickSound.preload = "auto";
  clickSound.volume = 0.5;
  
  let lastClick = 0;
  const debounceDelay = 100; // Prevent sound spam
  
  document.addEventListener("click", (e) => {
    if (e.target.tagName === 'A' && Date.now() - lastClick > debounceDelay) {
      lastClick = Date.now();
      clickSound.currentTime = 0; // Reset to start
      clickSound.play().catch(() => {}); // Ignore errors
    }
  }, { passive: true });
})();

// Particle background (optimized for 60fps)
(function() {
  const canvas = document.getElementById("particle-bg");
  if (!canvas) return;
  
  const ctx = canvas.getContext("2d", { alpha: true });
  let width, height;
  const particles = [];
  const particleCount = window.innerWidth < 768 ? 40 : 80; // Mobile optimization
  
  const resize = () => {
    width = canvas.width = window.innerWidth;
    height = canvas.height = window.innerHeight;
  };
  
  resize();
  window.addEventListener("resize", resize, { passive: true });
  
  // Initialize particles
  for (let i = 0; i < particleCount; i++) {
    particles.push({
      x: Math.random() * width,
      y: Math.random() * height,
      vx: (Math.random() - 0.5) * 0.6,
      vy: (Math.random() - 0.5) * 0.6
    });
  }
  
  // Animation loop (optimized)
  function animate() {
    ctx.clearRect(0, 0, width, height);
    ctx.fillStyle = "rgba(0, 255, 231, 0.7)";
    
    for (const particle of particles) {
      ctx.beginPath();
      ctx.arc(particle.x, particle.y, 1.8, 0, Math.PI * 2);
      ctx.fill();
      
      particle.x += particle.vx;
      particle.y += particle.vy;
      
      // Bounce off walls
      if (particle.x < 0 || particle.x > width) particle.vx *= -1;
      if (particle.y < 0 || particle.y > height) particle.vy *= -1;
    }
    
    requestAnimationFrame(animate);
  }
  
  animate();
})();

// Simple game
const g=document.getElementById("mini-game");
if(g){
  const x=g.getContext("2d");let X=320,Y=180,dx=2,dy=2;
  (function l(){
    x.fillStyle="#0a0a0a";x.fillRect(0,0,640,360);
    x.fillStyle="#00ffe7";x.beginPath();x.arc(X,Y,15,0,Math.PI*2);x.fill();
    X+=dx;Y+=dy;if(X<15||X>625)dx*=-1;if(Y<15||Y>345)dy*=-1;
    requestAnimationFrame(l);
  })();
}

// Crypto ticker (optimized with caching and error handling)
(async function() {
  const ticker = document.getElementById("crypto-ticker");
  if (!ticker) return;
  
  const CACHE_KEY = '3000_crypto_cache';
  const CACHE_DURATION = 5 * 60 * 1000; // 5 minutes
  
  async function fetchCrypto() {
    try {
      // Check cache first
      const cached = localStorage.getItem(CACHE_KEY);
      if (cached) {
        const { data, timestamp } = JSON.parse(cached);
        if (Date.now() - timestamp < CACHE_DURATION) {
          updateTicker(data);
          return;
        }
      }
      
      // Fetch fresh data
      const response = await fetch(
        "https://api.coingecko.com/api/v3/coins/markets?vs_currency=usd&order=percent_change_24h_desc&per_page=5&page=1",
        { signal: AbortSignal.timeout(5000) } // 5s timeout
      );
      
      if (!response.ok) throw new Error(`HTTP ${response.status}`);
      
      const data = await response.json();
      
      // Cache the data
      localStorage.setItem(CACHE_KEY, JSON.stringify({
        data,
        timestamp: Date.now()
      }));
      
      updateTicker(data);
    } catch (error) {
      console.warn('Crypto API error:', error);
      ticker.innerHTML = '📊 Market data temporarily unavailable';
    }
  }
  
  function updateTicker(data) {
    ticker.innerHTML = data
      .map(coin => `<span style="color:${coin.price_change_percentage_24h > 0 ? 'lime' : '#ff6666'}">${coin.name}: ${coin.price_change_percentage_24h.toFixed(2)}%</span>`)
      .join(" • ");
  }
  
  await fetchCrypto();
  
  // Refresh every 5 minutes
  setInterval(fetchCrypto, CACHE_DURATION);
})();

// Performance monitoring (production-safe)
if (console.time && performance.timing) {
  console.log('%c⚡ Performance Stats', 'color:gold;font-size:12px;font-weight:bold;');
  window.addEventListener('load', () => {
    const perf = performance.timing;
    const loadTime = perf.loadEventEnd - perf.navigationStart;
    console.log(`%cPage Load: ${loadTime}ms`, 'color:cyan;font-size:11px;');
  }, { passive: true });
}
