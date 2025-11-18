<?php
/*
 *   Copyright (c) 2025 NAME.
 *   All rights reserved.
 *   Unauthorized copying, modification, distribution, or use of this is prohibited without express written permission.
 */

/*!
 * 3000 Studios Theme
 * Copyright Â© 2025 3000 Studios. All rights reserved.
 */

/* Template: Home */ if (! defined('ABSPATH')) {
  exit;
}
get_header();
// Add home class to body for JavaScript detection
add_action('wp_footer', function () {
  echo '<script>document.body.classList.add("home");</script>';
});
?>
<video autoplay loop muted playsinline style="position:fixed;inset:0;width:100%;height:100%;object-fit:cover;z-index:-3;opacity:.45;">
  <source src="https://3000studios.com/wp-content/uploads/2025/10/aquarium-live-wallpaper-with-sounds-Made-with-Clipchamp.mp4" type="video/mp4">
</video>

<section class="hero">
  <canvas id="particle-bg" style="position:absolute;inset:0;z-index:-2;"></canvas>
  <div class="container">
    <h1 style="font-size:72px;font-weight:bold;text-align:center;margin:40px 0;background:linear-gradient(45deg, #00ffff, #9d4edd, #00ff00);-webkit-background-clip:text;-webkit-text-fill-color:transparent;animation:gradient-shift 3s ease infinite;">UNDER DEVELOPMENT</h1>
    <p style="font-size:24px;text-align:center;margin:20px 0;color:#fff;">Site is currently being rebuilt with enhanced features.</p>
    <p>Code, creativity, and innovation collide here. Check back soon for the new experience.</p>
    <a class="cta" href="<?php echo home_url(); ?>/experience">Preview Experience</a>
  </div>
</section>

<style>
@keyframes gradient-shift {
  0%, 100% { filter: hue-rotate(0deg); }
  50% { filter: hue-rotate(90deg); }
}
</style>

<section class="section container">
  <h2>Featured Projects</h2>
  <div class="cards">
    <?php
    $q = new WP_Query(['posts_per_page' => 3]);
    if ($q->have_posts()):
      while ($q->have_posts()): $q->the_post(); ?>
        <div class="card">
          <h3><?php the_title(); ?></h3>
          <div style="max-height:200px;overflow:auto;"><?php the_excerpt(); ?></div>
          <a class="cta" href="<?php the_permalink(); ?>">Read More</a>
        </div>
      <?php endwhile;
      wp_reset_postdata();
    else: ?>
      <div class="card">
        <h3>Getting Started</h3>
        <p>Create your first post to see it here.</p>
      </div>
    <?php endif; ?>
  </div>
</section>

<section class="section container">
  <h2>Media Gallery</h2>
  <div class="slider" id="media-slider">
    <div class="slide"><img src="<?php echo esc_url(get_template_directory_uri() . '/assets/placeholder.png'); ?>" alt=""></div>
    <div class="slide"><img src="<?php echo esc_url(get_template_directory_uri() . '/assets/placeholder.png'); ?>" alt=""></div>
    <div class="slide"><img src="<?php echo esc_url(get_template_directory_uri() . '/assets/placeholder.png'); ?>" alt=""></div>
  </div>



<section id="game" class="section container">
  <h2>KBH Games Arcade</h2>
  <div class="kbh-game-container" style="width:100%;max-width:1000px;margin:0 auto;background:linear-gradient(135deg, #667eea 0%, #764ba2 100%);border-radius:20px;padding:2rem;position:relative;overflow:hidden;">
    <div class="game-background" style="position:absolute;top:0;left:0;right:0;bottom:0;background:radial-gradient(circle at 30% 40%, rgba(120, 119, 198, 0.4), transparent 50%), radial-gradient(circle at 70% 60%, rgba(255, 119, 198, 0.4), transparent 50%);animation:game-bg-float 15s ease-in-out infinite;"></div>

    <div style="position:relative;z-index:2;">
      <h3 style="color:#fff;text-align:center;margin-bottom:1.5rem;font-size:2rem;background:linear-gradient(45deg, #ff6b6b, #4ecdc4);-webkit-background-clip:text;-webkit-text-fill-color:transparent;font-weight:bold;">ðŸŽ® Epic Runner Game</h3>

      <canvas id="mini-game" width="800" height="400" style="width:100%;height:400px;background:#000;border-radius:15px;border:3px solid rgba(255,255,255,0.2);box-shadow:0 10px 30px rgba(0,0,0,0.3);display:block;"></canvas>

      <div class="game-controls" style="display:flex;justify-content:center;gap:1rem;margin-top:1.5rem;flex-wrap:wrap;">
        <button onclick="kbhGame.start()" style="background:rgba(255,107,107,0.8);border:none;padding:12px 24px;border-radius:25px;color:#fff;font-weight:bold;cursor:pointer;transition:all 0.3s ease;backdrop-filter:blur(10px);">ðŸŽ® START</button>
        <button onclick="kbhGame.pause()" style="background:rgba(78,205,196,0.8);border:none;padding:12px 24px;border-radius:25px;color:#fff;font-weight:bold;cursor:pointer;transition:all 0.3s ease;backdrop-filter:blur(10px);">â¸ï¸ PAUSE</button>
        <button onclick="kbhGame.reset()" style="background:rgba(69,183,209,0.8);border:none;padding:12px 24px;border-radius:25px;color:#fff;font-weight:bold;cursor:pointer;transition:all 0.3s ease;backdrop-filter:blur(10px);">ðŸ”„ RESET</button>
        <a href="<?php echo home_url(); ?>/experience" style="background:rgba(249,202,36,0.8);border:none;padding:12px 24px;border-radius:25px;color:#000;font-weight:bold;text-decoration:none;transition:all 0.3s ease;backdrop-filter:blur(10px);display:inline-block;">ðŸš€ FULL GAME</a>
      </div>

      <div class="game-instructions" style="text-align:center;margin-top:1rem;color:rgba(255,255,255,0.8);font-size:0.9rem;">
        <p style="margin:0.5rem 0;">ðŸ–±ï¸ Click or press SPACE to jump â€¢ ðŸŽ¯ Avoid obstacles â€¢ ðŸ† Beat your high score!</p>
      </div>
    </div>
  </div>
</section>

<style>
  @keyframes game-bg-float {

    0%,
    100% {
      transform: translateY(0px) rotate(0deg);
    }

    33% {
      transform: translateY(-10px) rotate(2deg);
    }

    66% {
      transform: translateY(5px) rotate(-1deg);
    }
  }

  .game-controls button:hover,
  .game-controls a:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(255, 255, 255, 0.3);
  }
</style>

<script>
  // KBH Games - Epic Runner (Mini Version)
  const kbhGame = {
    canvas: null,
    ctx: null,
    gameRunning: false,
    animationFrame: null,

    // Game objects
    player: {
      x: 80,
      y: 200,
      width: 30,
      height: 30,
      speed: 4,
      color: '#ff6b6b',
      dy: 0,
      jumping: false,
      trail: []
    },

    obstacles: [],
    particles: [],
    powerups: [],
    score: 0,
    highScore: localStorage.getItem('kbh-highscore') || 0,
    gameSpeed: 3,

    init() {
      this.canvas = document.getElementById('mini-game');
      if (!this.canvas) return;

      this.ctx = this.canvas.getContext('2d');

      // Event listeners
      this.canvas.addEventListener('click', () => this.jump());
      document.addEventListener('keydown', (e) => {
        if (e.code === 'Space' || e.code === 'ArrowUp') {
          e.preventDefault();
          this.jump();
        }
      });

      // Start game loop
      this.gameLoop();
    },

    jump() {
      if (this.gameRunning && !this.player.jumping) {
        this.player.dy = -15;
        this.player.jumping = true;
        this.createParticles(this.player.x, this.player.y + this.player.height, '#fff', 3);
      }
    },

    start() {
      this.gameRunning = true;
    },

    pause() {
      this.gameRunning = !this.gameRunning;
    },

    reset() {
      this.player.x = 80;
      this.player.y = 200;
      this.player.dy = 0;
      this.player.jumping = false;
      this.player.trail = [];
      this.obstacles = [];
      this.particles = [];
      this.powerups = [];
      this.score = 0;
      this.gameSpeed = 3;
      this.gameRunning = true;
    },

    createObstacle() {
      this.obstacles.push({
        x: this.canvas.width,
        y: Math.random() * (this.canvas.height - 150) + 100,
        width: 25,
        height: 50,
        color: '#4ecdc4',
        glow: 0
      });
    },

    createPowerup() {
      this.powerups.push({
        x: this.canvas.width,
        y: Math.random() * (this.canvas.height - 200) + 100,
        width: 20,
        height: 20,
        color: '#f9ca24',
        rotation: 0,
        pulse: 0
      });
    },

    createParticles(x, y, color, count) {
      for (let i = 0; i < count; i++) {
        this.particles.push({
          x: x + Math.random() * 20,
          y: y + Math.random() * 20,
          dx: (Math.random() - 0.5) * 8,
          dy: (Math.random() - 0.5) * 8,
          color: color,
          life: 30,
          maxLife: 30,
          size: Math.random() * 4 + 2
        });
      }
    },

    update() {
      if (!this.gameRunning) return;

      // Update player physics
      this.player.dy += 1; // gravity
      this.player.y += this.player.dy;

      // Ground collision
      const ground = this.canvas.height - 80;
      if (this.player.y + this.player.height >= ground) {
        this.player.y = ground - this.player.height;
        this.player.dy = 0;
        this.player.jumping = false;
      }

      // Player trail effect
      this.player.trail.push({
        x: this.player.x + this.player.width / 2,
        y: this.player.y + this.player.height / 2,
        life: 10
      });

      if (this.player.trail.length > 8) {
        this.player.trail.shift();
      }

      this.player.trail.forEach(t => t.life--);

      // Update obstacles
      this.obstacles.forEach((obstacle, index) => {
        obstacle.x -= this.gameSpeed;
        obstacle.glow = Math.sin(Date.now() * 0.01) * 0.5 + 0.5;

        if (obstacle.x + obstacle.width < 0) {
          this.obstacles.splice(index, 1);
          this.score += 10;

          if (this.score % 50 === 0) {
            this.gameSpeed += 0.3;
          }
        }
      });

      // Update powerups
      this.powerups.forEach((powerup, index) => {
        powerup.x -= this.gameSpeed;
        powerup.rotation += 0.1;
        powerup.pulse = Math.sin(Date.now() * 0.005) * 5;

        if (powerup.x + powerup.width < 0) {
          this.powerups.splice(index, 1);
        }

        // Collision with player
        if (this.checkCollision(this.player, powerup)) {
          this.powerups.splice(index, 1);
          this.score += 25;
          this.createParticles(powerup.x, powerup.y, powerup.color, 8);
        }
      });

      // Update particles
      this.particles.forEach((particle, index) => {
        particle.x += particle.dx;
        particle.y += particle.dy;
        particle.dx *= 0.98;
        particle.dy *= 0.98;
        particle.life--;

        if (particle.life <= 0) {
          this.particles.splice(index, 1);
        }
      });

      // Check obstacle collisions
      this.obstacles.forEach(obstacle => {
        if (this.checkCollision(this.player, obstacle)) {
          this.createParticles(this.player.x, this.player.y, '#ff6b6b', 10);
          this.gameRunning = false;

          if (this.score > this.highScore) {
            this.highScore = this.score;
            localStorage.setItem('kbh-highscore', this.highScore);
          }

          setTimeout(() => {
            if (confirm(`ðŸ’¥ Game Over!\\nðŸŽ¯ Score: ${this.score}\\nðŸ† Best: ${this.highScore}\\n\\nðŸŽ® Play again?`)) {
              this.reset();
            }
          }, 100);
        }
      });

      // Spawn obstacles and powerups
      if (Math.random() < 0.015) {
        this.createObstacle();
      }

      if (Math.random() < 0.005) {
        this.createPowerup();
      }
    },

    checkCollision(rect1, rect2) {
      return rect1.x < rect2.x + rect2.width &&
        rect1.x + rect1.width > rect2.x &&
        rect1.y < rect2.y + rect2.height &&
        rect1.y + rect1.height > rect2.y;
    },

    render() {
      // Clear with animated background
      const gradient = this.ctx.createLinearGradient(0, 0, this.canvas.width, this.canvas.height);
      gradient.addColorStop(0, '#2C5364');
      gradient.addColorStop(0.5, '#203A43');
      gradient.addColorStop(1, '#0F2027');
      this.ctx.fillStyle = gradient;
      this.ctx.fillRect(0, 0, this.canvas.width, this.canvas.height);

      // Animated grid
      this.ctx.strokeStyle = 'rgba(255,255,255,0.03)';
      this.ctx.lineWidth = 1;
      const offset = (Date.now() * 0.05) % 50;
      for (let x = -offset; x < this.canvas.width; x += 50) {
        this.ctx.beginPath();
        this.ctx.moveTo(x, 0);
        this.ctx.lineTo(x, this.canvas.height);
        this.ctx.stroke();
      }

      // Draw ground with glow
      const groundGradient = this.ctx.createLinearGradient(0, this.canvas.height - 80, 0, this.canvas.height);
      groundGradient.addColorStop(0, 'rgba(255,255,255,0.1)');
      groundGradient.addColorStop(1, 'rgba(255,255,255,0.05)');
      this.ctx.fillStyle = groundGradient;
      this.ctx.fillRect(0, this.canvas.height - 80, this.canvas.width, 80);

      // Draw player trail
      this.player.trail.forEach((trail, index) => {
        const alpha = (trail.life / 10) * 0.5;
        this.ctx.globalAlpha = alpha;
        this.ctx.fillStyle = this.player.color;
        this.ctx.fillRect(trail.x - 2, trail.y - 2, 4, 4);
      });
      this.ctx.globalAlpha = 1;

      // Draw player with glow
      this.ctx.shadowColor = this.player.color;
      this.ctx.shadowBlur = 20;
      this.ctx.fillStyle = this.player.color;
      this.ctx.fillRect(this.player.x, this.player.y, this.player.width, this.player.height);
      this.ctx.shadowBlur = 0;

      // Draw obstacles with animated glow
      this.obstacles.forEach(obstacle => {
        this.ctx.shadowColor = obstacle.color;
        this.ctx.shadowBlur = 15 + obstacle.glow * 10;
        this.ctx.fillStyle = obstacle.color;
        this.ctx.fillRect(obstacle.x, obstacle.y, obstacle.width, obstacle.height);
      });
      this.ctx.shadowBlur = 0;

      // Draw powerups with rotation and pulse
      this.powerups.forEach(powerup => {
        this.ctx.save();
        this.ctx.translate(powerup.x + powerup.width / 2, powerup.y + powerup.height / 2);
        this.ctx.rotate(powerup.rotation);
        this.ctx.shadowColor = powerup.color;
        this.ctx.shadowBlur = 20;
        this.ctx.fillStyle = powerup.color;
        this.ctx.fillRect(-powerup.width / 2 - powerup.pulse / 2, -powerup.height / 2 - powerup.pulse / 2,
          powerup.width + powerup.pulse, powerup.height + powerup.pulse);
        this.ctx.restore();
      });
      this.ctx.shadowBlur = 0;

      // Draw particles
      this.particles.forEach(particle => {
        const alpha = particle.life / particle.maxLife;
        this.ctx.globalAlpha = alpha;
        this.ctx.fillStyle = particle.color;
        this.ctx.fillRect(particle.x, particle.y, particle.size, particle.size);
      });
      this.ctx.globalAlpha = 1;

      // Draw UI
      this.ctx.fillStyle = '#fff';
      this.ctx.font = 'bold 18px Arial';
      this.ctx.fillText(`Score: ${this.score}`, 20, 30);
      this.ctx.fillText(`Best: ${this.highScore}`, 20, 55);
      this.ctx.fillText(`Speed: ${this.gameSpeed.toFixed(1)}x`, 20, 80);

      // Draw game state overlay
      if (!this.gameRunning) {
        this.ctx.fillStyle = 'rgba(0, 0, 0, 0.8)';
        this.ctx.fillRect(0, 0, this.canvas.width, this.canvas.height);

        this.ctx.fillStyle = '#fff';
        this.ctx.font = 'bold 32px Arial';
        this.ctx.textAlign = 'center';

        if (this.score === 0) {
          this.ctx.fillText('ðŸŽ® KBH GAMES', this.canvas.width / 2, this.canvas.height / 2 - 20);
          this.ctx.font = 'bold 16px Arial';
          this.ctx.fillText('Click START to begin your epic adventure!', this.canvas.width / 2, this.canvas.height / 2 + 20);
        } else {
          this.ctx.fillText('â¸ï¸ PAUSED', this.canvas.width / 2, this.canvas.height / 2);
          this.ctx.font = 'bold 16px Arial';
          this.ctx.fillText('Click START to continue', this.canvas.width / 2, this.canvas.height / 2 + 30);
        }

        this.ctx.textAlign = 'left';
      }
    },

    gameLoop() {
      this.update();
      this.render();
      this.animationFrame = requestAnimationFrame(() => this.gameLoop());
    }
  };

  // Initialize when DOM is ready
  document.addEventListener('DOMContentLoaded', () => {
    setTimeout(() => kbhGame.init(), 500);
  });
</script>

<section class="section container">
  <h2>Top Crypto Movers</h2>
  <div id="crypto-ticker" class="small">Loading dataâ€¦</div>
</section>

<?php get_footer(); ?>

