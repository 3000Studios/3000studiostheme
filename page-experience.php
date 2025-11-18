<?php
/*
 * Template Name: Enter the Experience
 * @package 3000Studios
 * @author Mr. jwswain
 * @copyright Copyright (c) 2025, Mr. jwswain & 3000 Studios
 * @license Proprietary - All Rights Reserved
 */

if (!defined('ABSPATH')) {
    exit;
}

get_header(); 

// Add custom body class for this page
add_filter('body_class', function($classes) {
    $classes[] = 'experience-page';
    return $classes;
});
?>

<!-- Animated background video -->
<video autoplay loop muted playsinline style="position:fixed;inset:0;width:100%;height:100%;object-fit:cover;z-index:-1;opacity:0.3;">
  <source src="https://3000studios.com/wp-content/uploads/2025/10/aquarium-live-wallpaper-with-sounds-Made-with-Clipchamp.mp4" type="video/mp4">
</video>

<!-- Particle background canvas -->
<canvas id="particle-bg" style="position:fixed;inset:0;z-index:0;opacity:0.6;"></canvas>

<style>
  @keyframes neon-glow {
    0%, 100% { text-shadow: 0 0 10px #00ffff, 0 0 20px #00ffff, 0 0 30px #00ffff; }
    50% { text-shadow: 0 0 20px #00ffff, 0 0 40px #9d4edd, 0 0 60px #00ff00; }
  }

  @keyframes float-up {
    0% { transform: translateY(0) rotate(0deg); opacity: 1; }
    100% { transform: translateY(-1000px) rotate(360deg); opacity: 0; }
  }

  @keyframes pulse {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.05); }
  }

  @keyframes rainbow {
    0% { filter: hue-rotate(0deg); }
    100% { filter: hue-rotate(360deg); }
  }

  .experience-hero {
    text-align: center;
    padding: 100px 20px;
    position: relative;
    z-index: 1;
  }

  .experience-hero h1 {
    font-size: 4rem;
    font-weight: 900;
    background: linear-gradient(45deg, #00ffff, #9d4edd, #00ff00, #ff6b6b);
    background-size: 300% 300%;
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    animation: rainbow 5s ease infinite, neon-glow 2s ease-in-out infinite;
    margin-bottom: 1rem;
  }

  .experience-hero p {
    font-size: 1.5rem;
    color: #fff;
    margin-bottom: 2rem;
    text-shadow: 0 0 10px rgba(0,255,255,0.5);
  }

  .floating-emojis {
    position: fixed;
    bottom: 0;
    left: 0;
    width: 100%;
    height: 100%;
    pointer-events: none;
    z-index: 0;
  }

  .emoji {
    position: absolute;
    font-size: 2rem;
    animation: float-up 10s linear infinite;
  }

  .fun-section {
    background: rgba(0,0,0,0.8);
    border-radius: 20px;
    padding: 3rem;
    margin: 2rem auto;
    max-width: 1200px;
    backdrop-filter: blur(10px);
    border: 2px solid rgba(0,255,255,0.3);
    box-shadow: 0 0 30px rgba(0,255,255,0.2);
    position: relative;
    z-index: 1;
  }

  .fun-section h2 {
    color: #00ffff;
    font-size: 2.5rem;
    margin-bottom: 1.5rem;
    animation: neon-glow 2s ease-in-out infinite;
  }

  .game-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 2rem;
    margin-top: 2rem;
  }

  .game-card {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 15px;
    padding: 2rem;
    text-align: center;
    transition: all 0.3s ease;
    cursor: pointer;
    animation: pulse 3s ease-in-out infinite;
  }

  .game-card:hover {
    transform: translateY(-10px) scale(1.05);
    box-shadow: 0 20px 40px rgba(0,255,255,0.4);
  }

  .game-card h3 {
    color: #fff;
    font-size: 1.5rem;
    margin-bottom: 1rem;
  }

  .game-card p {
    color: rgba(255,255,255,0.9);
    margin-bottom: 1rem;
  }

  .game-btn {
    background: #00ffff;
    color: #000;
    border: none;
    padding: 12px 24px;
    border-radius: 25px;
    font-weight: bold;
    cursor: pointer;
    transition: all 0.3s ease;
  }

  .game-btn:hover {
    background: #00ff00;
    transform: scale(1.1);
    box-shadow: 0 0 20px #00ff00;
  }

  .interactive-canvas {
    width: 100%;
    height: 400px;
    background: #000;
    border-radius: 15px;
    border: 3px solid #00ffff;
    cursor: crosshair;
    box-shadow: 0 0 30px rgba(0,255,255,0.5);
  }

  .color-picker {
    display: flex;
    justify-content: center;
    gap: 10px;
    margin: 1rem 0;
    flex-wrap: wrap;
  }

  .color-btn {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    border: 3px solid #fff;
    cursor: pointer;
    transition: all 0.3s ease;
  }

  .color-btn:hover {
    transform: scale(1.2);
    box-shadow: 0 0 20px currentColor;
  }

  .fun-facts {
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    padding: 2rem;
    border-radius: 15px;
    margin: 2rem 0;
  }

  .fun-facts h3 {
    color: #fff;
    font-size: 1.8rem;
    margin-bottom: 1rem;
  }

  .fact-text {
    color: #fff;
    font-size: 1.2rem;
    line-height: 1.6;
  }

  @media (max-width: 768px) {
    .experience-hero h1 {
      font-size: 2.5rem;
    }
    .fun-section {
      padding: 1.5rem;
    }
  }
</style>

<div class="floating-emojis" id="floating-emojis"></div>

<section class="experience-hero">
  <h1>🚀 WELCOME TO THE EXPERIENCE 🎮</h1>
  <p>Dive into the ultimate interactive playground of creativity, games, and endless fun!</p>
  <div style="display:flex;gap:1rem;justify-content:center;flex-wrap:wrap;">
    <button onclick="scrollToGames()" class="game-btn" style="font-size:1.2rem;padding:15px 30px;">
      🎮 PLAY GAMES
    </button>
    <button onclick="scrollToArt()" class="game-btn" style="font-size:1.2rem;padding:15px 30px;background:#9d4edd;">
      🎨 CREATE ART
    </button>
    <button onclick="randomFunFact()" class="game-btn" style="font-size:1.2rem;padding:15px 30px;background:#00ff00;">
      💡 FUN FACTS
    </button>
  </div>
</section>

<section class="fun-section" id="games-section">
  <h2>🎮 Epic Games Collection</h2>
  <div class="game-grid">
    <div class="game-card" onclick="playSpaceShooter()">
      <h3>🚀 Space Shooter</h3>
      <p>Blast asteroids and rack up points!</p>
      <button class="game-btn">PLAY NOW</button>
    </div>
    <div class="game-card" onclick="playColorMatch()">
      <h3>🎨 Color Match</h3>
      <p>Match the colors as fast as you can!</p>
      <button class="game-btn">PLAY NOW</button>
    </div>
    <div class="game-card" onclick="playMemoryGame()">
      <h3>🧠 Memory Challenge</h3>
      <p>Test your memory with this brain teaser!</p>
      <button class="game-btn">PLAY NOW</button>
    </div>
    <div class="game-card" onclick="playReactionTime()">
      <h3>⚡ Reaction Test</h3>
      <p>How fast are your reflexes?</p>
      <button class="game-btn">PLAY NOW</button>
    </div>
  </div>
</section>

<section class="fun-section" id="art-section">
  <h2>🎨 Digital Art Studio</h2>
  <p style="color:#fff;text-align:center;margin-bottom:1rem;">Click and drag to create amazing patterns!</p>
  <div class="color-picker">
    <button class="color-btn" style="background:#ff6b6b;" onclick="setColor('#ff6b6b')"></button>
    <button class="color-btn" style="background:#4ecdc4;" onclick="setColor('#4ecdc4')"></button>
    <button class="color-btn" style="background:#45b7d1;" onclick="setColor('#45b7d1')"></button>
    <button class="color-btn" style="background:#f9ca24;" onclick="setColor('#f9ca24')"></button>
    <button class="color-btn" style="background:#9d4edd;" onclick="setColor('#9d4edd')"></button>
    <button class="color-btn" style="background:#00ff00;" onclick="setColor('#00ff00')"></button>
    <button class="color-btn" style="background:#fff;" onclick="setColor('#ffffff')"></button>
  </div>
  <canvas id="art-canvas" class="interactive-canvas"></canvas>
  <div style="text-align:center;margin-top:1rem;">
    <button class="game-btn" onclick="clearCanvas()">🗑️ CLEAR</button>
    <button class="game-btn" onclick="saveArt()" style="background:#9d4edd;">💾 SAVE ART</button>
    <button class="game-btn" onclick="randomArt()" style="background:#00ff00;">✨ RANDOM ART</button>
  </div>
</section>

<section class="fun-section">
  <h2>🎲 Interactive Challenges</h2>
  <div class="game-grid">
    <div class="fun-facts">
      <h3>💡 Did You Know?</h3>
      <p class="fact-text" id="fun-fact">Click the button above to learn something awesome!</p>
    </div>
    <div class="game-card" onclick="spinWheel()">
      <h3>🎡 Wheel of Fortune</h3>
      <p>Spin the wheel and win prizes!</p>
      <div id="prize-display" style="font-size:2rem;margin:1rem 0;">🎁</div>
      <button class="game-btn">SPIN</button>
    </div>
    <div class="game-card" onclick="magicEightBall()">
      <h3>🔮 Magic 8 Ball</h3>
      <p>Ask a question and get your answer!</p>
      <div id="magic-answer" style="font-size:1.2rem;margin:1rem 0;min-height:60px;"></div>
      <button class="game-btn">ASK</button>
    </div>
  </div>
</section>

<section class="fun-section">
  <h2>🌈 More Awesome Stuff</h2>
  <div class="game-grid">
    <div class="game-card">
      <h3>📊 Your Stats</h3>
      <p>Games Played: <span id="games-played">0</span></p>
      <p>Art Created: <span id="art-created">0</span></p>
      <p>Fun Level: <span id="fun-level">100</span>%</p>
    </div>
    <div class="game-card" onclick="celebration()">
      <h3>🎉 Celebration Mode</h3>
      <p>Click for instant party vibes!</p>
      <button class="game-btn">CELEBRATE!</button>
    </div>
    <div class="game-card" onclick="window.location.href='<?php echo home_url(); ?>'">
      <h3>🏠 Back Home</h3>
      <p>Return to the main site</p>
      <button class="game-btn">GO HOME</button>
    </div>
  </div>
</section>

<script>
// Particle background
const particleBg = document.getElementById('particle-bg');
if (particleBg) {
  const ctx = particleBg.getContext('2d');
  particleBg.width = window.innerWidth;
  particleBg.height = window.innerHeight;

  const particles = [];
  for (let i = 0; i < 100; i++) {
    particles.push({
      x: Math.random() * particleBg.width,
      y: Math.random() * particleBg.height,
      dx: (Math.random() - 0.5) * 2,
      dy: (Math.random() - 0.5) * 2,
      size: Math.random() * 3 + 1
    });
  }

  function animateParticles() {
    ctx.clearRect(0, 0, particleBg.width, particleBg.height);
    ctx.fillStyle = '#00ffff';
    
    particles.forEach(p => {
      ctx.beginPath();
      ctx.arc(p.x, p.y, p.size, 0, Math.PI * 2);
      ctx.fill();
      
      p.x += p.dx;
      p.y += p.dy;
      
      if (p.x < 0 || p.x > particleBg.width) p.dx *= -1;
      if (p.y < 0 || p.y > particleBg.height) p.dy *= -1;
    });
    
    requestAnimationFrame(animateParticles);
  }
  
  animateParticles();
}

// Floating emojis
const emojis = ['🎮', '🚀', '⭐', '💎', '🎨', '🎯', '🎪', '🎭', '🎸', '🎺', '🎲', '🎰'];
const emojiContainer = document.getElementById('floating-emojis');

function createFloatingEmoji() {
  const emoji = document.createElement('div');
  emoji.className = 'emoji';
  emoji.textContent = emojis[Math.floor(Math.random() * emojis.length)];
  emoji.style.left = Math.random() * 100 + '%';
  emoji.style.animationDuration = (Math.random() * 5 + 5) + 's';
  emoji.style.animationDelay = Math.random() * 2 + 's';
  emojiContainer.appendChild(emoji);
  
  setTimeout(() => emoji.remove(), 12000);
}

setInterval(createFloatingEmoji, 500);

// Scroll functions
function scrollToGames() {
  document.getElementById('games-section').scrollIntoView({ behavior: 'smooth' });
}

function scrollToArt() {
  document.getElementById('art-section').scrollIntoView({ behavior: 'smooth' });
}

// Art canvas
const artCanvas = document.getElementById('art-canvas');
const artCtx = artCanvas.getContext('2d');
artCanvas.width = artCanvas.offsetWidth;
artCanvas.height = 400;

let currentColor = '#ff6b6b';
let isDrawing = false;
let artCreated = 0;

function setColor(color) {
  currentColor = color;
}

artCanvas.addEventListener('mousedown', (e) => {
  isDrawing = true;
  const rect = artCanvas.getBoundingClientRect();
  artCtx.beginPath();
  artCtx.moveTo(e.clientX - rect.left, e.clientY - rect.top);
});

artCanvas.addEventListener('mousemove', (e) => {
  if (!isDrawing) return;
  const rect = artCanvas.getBoundingClientRect();
  artCtx.lineTo(e.clientX - rect.left, e.clientY - rect.top);
  artCtx.strokeStyle = currentColor;
  artCtx.lineWidth = 5;
  artCtx.lineCap = 'round';
  artCtx.stroke();
});

artCanvas.addEventListener('mouseup', () => {
  isDrawing = false;
});

artCanvas.addEventListener('mouseleave', () => {
  isDrawing = false;
});

// Touch support for mobile
artCanvas.addEventListener('touchstart', (e) => {
  e.preventDefault();
  isDrawing = true;
  const rect = artCanvas.getBoundingClientRect();
  const touch = e.touches[0];
  artCtx.beginPath();
  artCtx.moveTo(touch.clientX - rect.left, touch.clientY - rect.top);
});

artCanvas.addEventListener('touchmove', (e) => {
  e.preventDefault();
  if (!isDrawing) return;
  const rect = artCanvas.getBoundingClientRect();
  const touch = e.touches[0];
  artCtx.lineTo(touch.clientX - rect.left, touch.clientY - rect.top);
  artCtx.strokeStyle = currentColor;
  artCtx.lineWidth = 5;
  artCtx.lineCap = 'round';
  artCtx.stroke();
});

artCanvas.addEventListener('touchend', () => {
  isDrawing = false;
});

function clearCanvas() {
  artCtx.clearRect(0, 0, artCanvas.width, artCanvas.height);
}

function saveArt() {
  const link = document.createElement('a');
  link.download = '3000studios-art-' + Date.now() + '.png';
  link.href = artCanvas.toDataURL();
  link.click();
  artCreated++;
  document.getElementById('art-created').textContent = artCreated;
  alert('🎨 Your masterpiece has been saved!');
}

function randomArt() {
  clearCanvas();
  for (let i = 0; i < 100; i++) {
    artCtx.beginPath();
    artCtx.arc(
      Math.random() * artCanvas.width,
      Math.random() * artCanvas.height,
      Math.random() * 30 + 10,
      0,
      Math.PI * 2
    );
    artCtx.fillStyle = `hsl(${Math.random() * 360}, 70%, 60%)`;
    artCtx.fill();
  }
  artCreated++;
  document.getElementById('art-created').textContent = artCreated;
}

// Fun facts
const funFacts = [
  "The first computer game was created in 1962 and was called 'Spacewar!'",
  "A group of flamingos is called a 'flamboyance'! 🦩",
  "Honey never spoils. Archaeologists found 3000-year-old honey in Egyptian tombs that was still edible!",
  "Octopuses have three hearts and blue blood! 🐙",
  "The world's oldest computer game company is Nintendo, founded in 1889!",
  "Bananas are berries, but strawberries aren't! 🍌",
  "A day on Venus is longer than a year on Venus!",
  "The first video game Easter egg was in the game 'Adventure' for Atari 2600!",
  "Wombats poop cubes! Yes, really! 💩",
  "The unicorn is the national animal of Scotland! 🦄",
  "3000 Studios is where creativity meets code! 🚀",
  "Your brain uses the same amount of power as a 10-watt light bulb! 💡"
];

function randomFunFact() {
  const fact = funFacts[Math.floor(Math.random() * funFacts.length)];
  document.getElementById('fun-fact').textContent = fact;
  document.getElementById('fun-fact').parentElement.style.animation = 'pulse 0.5s ease';
  setTimeout(() => {
    document.getElementById('fun-fact').parentElement.style.animation = '';
  }, 500);
}

// Wheel of Fortune
const prizes = ['🎁 Free Theme!', '💎 1000 Points!', '🎉 Confetti!', '🌟 Mystery Prize!', '🎮 New Game!', '🎨 Art Pack!'];
function spinWheel() {
  const prize = prizes[Math.floor(Math.random() * prizes.length)];
  document.getElementById('prize-display').textContent = '🎡';
  setTimeout(() => {
    document.getElementById('prize-display').textContent = prize;
    updateStats();
  }, 1000);
}

// Magic 8 Ball
const answers = [
  "Yes, definitely! ✨",
  "Ask again later 🔮",
  "Signs point to yes 👍",
  "Don't count on it 🤔",
  "Absolutely! 💯",
  "My sources say no 🚫",
  "Outlook good 😊",
  "Very doubtful 😕",
  "It is certain! ⭐",
  "Better not tell you now 🤐"
];

function magicEightBall() {
  const answer = answers[Math.floor(Math.random() * answers.length)];
  document.getElementById('magic-answer').textContent = '🔮 Thinking...';
  setTimeout(() => {
    document.getElementById('magic-answer').textContent = answer;
    updateStats();
  }, 1500);
}

// Game functions
let gamesPlayed = 0;

function playSpaceShooter() {
  alert('🚀 Space Shooter launching! (Demo mode)\n\nUse arrow keys to move and spacebar to shoot!');
  updateStats();
}

function playColorMatch() {
  const colors = ['red', 'blue', 'green', 'yellow', 'purple', 'orange'];
  const targetColor = colors[Math.floor(Math.random() * colors.length)];
  const userAnswer = prompt(`🎨 Quick! What color is this?\n\nType: ${targetColor.toUpperCase()}`);
  if (userAnswer && userAnswer.toLowerCase() === targetColor) {
    alert('🎉 Correct! You matched the color!');
  } else {
    alert('❌ Oops! The color was ' + targetColor);
  }
  updateStats();
}

function playMemoryGame() {
  const sequence = [];
  for (let i = 0; i < 5; i++) {
    sequence.push(Math.floor(Math.random() * 10));
  }
  alert('🧠 Memory Challenge!\n\nRemember this sequence:\n' + sequence.join(' - '));
  setTimeout(() => {
    const answer = prompt('What was the sequence? (separate with spaces)');
    if (answer === sequence.join(' ')) {
      alert('🎉 Perfect memory!');
    } else {
      alert('❌ The sequence was: ' + sequence.join(' - '));
    }
    updateStats();
  }, 3000);
}

function playReactionTime() {
  alert('⚡ Reaction Test Starting!\n\nClick OK and wait for the green light!');
  const delay = Math.random() * 3000 + 2000;
  const startTime = Date.now() + delay;
  
  setTimeout(() => {
    const clickTime = Date.now();
    alert('🟢 CLICK NOW!');
    const reactionTime = Date.now() - clickTime;
    alert(`⚡ Your reaction time: ${reactionTime}ms`);
    updateStats();
  }, delay);
}

function celebration() {
  for (let i = 0; i < 50; i++) {
    createFloatingEmoji();
  }
  document.body.style.animation = 'rainbow 2s ease-in-out';
  alert('🎉🎊 CELEBRATION MODE ACTIVATED! 🎊🎉');
  setTimeout(() => {
    document.body.style.animation = '';
  }, 2000);
  updateStats();
}

function updateStats() {
  gamesPlayed++;
  document.getElementById('games-played').textContent = gamesPlayed;
  const funLevel = Math.min(100 + gamesPlayed * 5, 999);
  document.getElementById('fun-level').textContent = funLevel;
}

// Window resize handler for art canvas
window.addEventListener('resize', () => {
  const tempImage = artCtx.getImageData(0, 0, artCanvas.width, artCanvas.height);
  artCanvas.width = artCanvas.offsetWidth;
  artCtx.putImageData(tempImage, 0, 0);
  
  if (particleBg) {
    particleBg.width = window.innerWidth;
    particleBg.height = window.innerHeight;
  }
});
</script>

<?php get_footer(); ?>