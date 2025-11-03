// Copyright (c) 2025 NAME.
// All rights reserved.
// Unauthorized copying, modification, distribution, or use of this is prohibited without express written permission.

/*!
 * Animated Ball Pit Footer - 3000 Studios Theme
 * Based on Matter.js Physics Ball Pit
 * Copyright © 2025 3000 Studios. All rights reserved.
 */

// Initialize Ball Pit Footer
function initBallPitFooter() {
  // Find footer element
  const footer = document.querySelector('footer');
  if (!footer) return;
  
  // Create ball pit container
  const ballPitContainer = document.createElement('div');
  ballPitContainer.id = 'footer-ball-pit';
  ballPitContainer.style.cssText = `
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    overflow: hidden;
    z-index: -1;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  `;
  
  // Make footer position relative
  footer.style.position = 'relative';
  footer.style.minHeight = '200px';
  footer.style.overflow = 'hidden';
  
  // Create canvas
  const canvas = document.createElement('canvas');
  canvas.id = 'footer-ball-canvas';
  canvas.style.cssText = `
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
  `;
  
  ballPitContainer.appendChild(canvas);
  footer.insertBefore(ballPitContainer, footer.firstChild);
  
  // Set canvas size
  const rect = footer.getBoundingClientRect();
  canvas.width = rect.width;
  canvas.height = Math.max(rect.height, 200);
  
  const ctx = canvas.getContext('2d');
  
  // Simple physics simulation (lightweight alternative to Matter.js)
  class Ball {
    constructor(x, y) {
      this.x = x || Math.random() * canvas.width;
      this.y = y || -20;
      this.radius = Math.random() * 15 + 10;
      this.vx = (Math.random() - 0.5) * 4;
      this.vy = Math.random() * 2 + 1;
      this.bounce = 0.7;
      this.friction = 0.98;
      this.gravity = 0.3;
      
      // Random colors
      const colors = [
        '#ff6b6b', '#4ecdc4', '#45b7d1', '#96ceb4', 
        '#feca57', '#ff9ff3', '#54a0ff', '#5f27cd',
        '#00d2d3', '#ff9f43', '#a55eea', '#26de81'
      ];
      this.color = colors[Math.floor(Math.random() * colors.length)];
      this.glowColor = this.color + '80'; // Add transparency for glow
    }
    
    update() {
      // Apply gravity
      this.vy += this.gravity;
      
      // Apply velocity
      this.x += this.vx;
      this.y += this.vy;
      
      // Apply friction
      this.vx *= this.friction;
      this.vy *= 0.999; // Less friction on Y for better bouncing
      
      // Collision with walls
      if (this.x + this.radius > canvas.width) {
        this.x = canvas.width - this.radius;
        this.vx *= -this.bounce;
      }
      if (this.x - this.radius < 0) {
        this.x = this.radius;
        this.vx *= -this.bounce;
      }
      
      // Collision with floor
      if (this.y + this.radius > canvas.height) {
        this.y = canvas.height - this.radius;
        this.vy *= -this.bounce;
        // Add some randomness to prevent settling
        if (Math.abs(this.vy) < 0.5) {
          this.vy = -Math.random() * 2;
        }
      }
      
      // Collision with ceiling (soft)
      if (this.y - this.radius < 0) {
        this.y = this.radius;
        this.vy *= -0.3;
      }
    }
    
    draw() {
      // Draw glow
      ctx.save();
      ctx.globalAlpha = 0.3;
      ctx.fillStyle = this.glowColor;
      ctx.beginPath();
      ctx.arc(this.x, this.y, this.radius * 1.5, 0, Math.PI * 2);
      ctx.fill();
      
      // Draw main ball
      ctx.globalAlpha = 0.9;
      ctx.fillStyle = this.color;
      ctx.beginPath();
      ctx.arc(this.x, this.y, this.radius, 0, Math.PI * 2);
      ctx.fill();
      
      // Draw highlight
      ctx.globalAlpha = 0.6;
      ctx.fillStyle = '#ffffff';
      ctx.beginPath();
      ctx.arc(this.x - this.radius * 0.3, this.y - this.radius * 0.3, this.radius * 0.2, 0, Math.PI * 2);
      ctx.fill();
      
      ctx.restore();
    }
    
    // Simple collision detection with other balls
    collideWith(other) {
      const dx = other.x - this.x;
      const dy = other.y - this.y;
      const distance = Math.sqrt(dx * dx + dy * dy);
      const minDistance = this.radius + other.radius;
      
      if (distance < minDistance) {
        // Calculate collision
        const angle = Math.atan2(dy, dx);
        const targetX = this.x + Math.cos(angle) * minDistance;
        const targetY = this.y + Math.sin(angle) * minDistance;
        
        const ax = (targetX - other.x) * 0.05;
        const ay = (targetY - other.y) * 0.05;
        
        this.vx -= ax;
        this.vy -= ay;
        other.vx += ax;
        other.vy += ay;
      }
    }
  }
  
  // Create balls
  const balls = [];
  const numBalls = Math.min(30, Math.floor(canvas.width / 30)); // Responsive ball count
  
  function createBalls() {
    balls.length = 0; // Clear existing balls
    for (let i = 0; i < numBalls; i++) {
      balls.push(new Ball());
    }
  }
  
  createBalls();
  
  // Animation loop
  function animate() {
    // Clear canvas with slight fade for trail effect
    ctx.fillStyle = 'rgba(102, 126, 234, 0.1)';
    ctx.fillRect(0, 0, canvas.width, canvas.height);
    
    // Update and draw balls
    balls.forEach((ball, i) => {
      ball.update();
      
      // Check collisions with other balls (simplified)
      if (i % 3 === 0) { // Only check every 3rd ball for performance
        balls.forEach((other, j) => {
          if (i !== j && j > i) {
            ball.collideWith(other);
          }
        });
      }
      
      ball.draw();
    });
    
    requestAnimationFrame(animate);
  }
  
  // Add balls on click/touch
  footer.addEventListener('click', (e) => {
    const rect = footer.getBoundingClientRect();
    const x = e.clientX - rect.left;
    const y = e.clientY - rect.top;
    balls.push(new Ball(x, y));
    
    // Limit total balls
    if (balls.length > numBalls * 2) {
      balls.shift();
    }
  });
  
  // Handle resize
  window.addEventListener('resize', () => {
    const rect = footer.getBoundingClientRect();
    canvas.width = rect.width;
    canvas.height = Math.max(rect.height, 200);
    createBalls();
  });
  
  // Start animation
  animate();
  
  // Periodically add new balls for movement
  setInterval(() => {
    if (balls.length < numBalls + 5) {
      balls.push(new Ball(Math.random() * canvas.width, -20));
    }
  }, 3000);
}

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
  // Small delay to ensure footer is rendered
  setTimeout(initBallPitFooter, 500);
});