// Copyright (c) 2025 NAME.
// All rights reserved.
// Unauthorized copying, modification, distribution, or use of this is prohibited without express written permission.

/*!
 * 3D Galaxy Background - 3000 Studios Theme
 * Based on Three.js Interactive 3D Galaxy
 * Copyright © 2025 3000 Studios. All rights reserved.
 */

// Initialize Galaxy Background
function initGalaxyBackground() {
  // Check if we're on the homepage
  if (!document.body.classList.contains('home')) return;
  
  // Create container for galaxy
  const galaxyContainer = document.createElement('div');
  galaxyContainer.id = 'galaxy-container';
  galaxyContainer.style.cssText = `
    position: fixed;
    top: 0;
    left: 0;
    width: 100vw;
    height: 100vh;
    z-index: -10;
    background: radial-gradient(circle at center, #000010 0%, #000000 100%);
    overflow: hidden;
  `;
  
  // Insert at the beginning of body
  document.body.insertBefore(galaxyContainer, document.body.firstChild);
  
  // Create canvas for particles
  const canvas = document.createElement('canvas');
  canvas.id = 'galaxy-canvas';
  canvas.style.cssText = `
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    pointer-events: none;
  `;
  galaxyContainer.appendChild(canvas);
  
  // Set canvas size
  canvas.width = window.innerWidth;
  canvas.height = window.innerHeight;
  
  const ctx = canvas.getContext('2d');
  
  // Galaxy parameters
  const particles = [];
  const numParticles = 1500;
  let mouseX = 0;
  let mouseY = 0;
  let time = 0;
  
  // Particle class
  class GalaxyParticle {
    constructor() {
      this.reset();
      this.life = Math.random() * 100;
    }
    
    reset() {
      // Create spiral galaxy pattern
      const angle = Math.random() * Math.PI * 2;
      const radius = Math.random() * Math.min(canvas.width, canvas.height) * 0.4;
      const spiralOffset = radius * 0.02;
      
      this.x = canvas.width / 2 + Math.cos(angle + spiralOffset) * radius;
      this.y = canvas.height / 2 + Math.sin(angle + spiralOffset) * radius;
      this.z = Math.random() * 1000 - 500;
      
      this.vx = Math.cos(angle + Math.PI/2) * 0.2;
      this.vy = Math.sin(angle + Math.PI/2) * 0.2;
      this.vz = (Math.random() - 0.5) * 0.1;
      
      this.size = Math.random() * 2 + 0.5;
      this.life = 100;
      this.decay = Math.random() * 0.02 + 0.005;
      
      // Color based on distance from center
      const distanceFromCenter = Math.sqrt(Math.pow(this.x - canvas.width/2, 2) + Math.pow(this.y - canvas.height/2, 2));
      const maxDistance = Math.min(canvas.width, canvas.height) * 0.4;
      const colorIntensity = 1 - (distanceFromCenter / maxDistance);
      
      this.color = {
        r: Math.floor(100 + colorIntensity * 155),
        g: Math.floor(50 + colorIntensity * 205),
        b: Math.floor(200 + colorIntensity * 55),
        a: colorIntensity
      };
    }
    
    update() {
      // Orbital motion
      const centerX = canvas.width / 2;
      const centerY = canvas.height / 2;
      const dx = this.x - centerX;
      const dy = this.y - centerY;
      const distance = Math.sqrt(dx * dx + dy * dy);
      
      // Apply orbital velocity
      const orbitalSpeed = 0.001;
      this.x += this.vx + Math.cos(time * orbitalSpeed + Math.atan2(dy, dx)) * 0.1;
      this.y += this.vy + Math.sin(time * orbitalSpeed + Math.atan2(dy, dx)) * 0.1;
      this.z += this.vz;
      
      // Mouse interaction
      const mouseDistance = Math.sqrt(Math.pow(this.x - mouseX, 2) + Math.pow(this.y - mouseY, 2));
      if (mouseDistance < 100) {
        const repelForce = (100 - mouseDistance) / 100;
        const angle = Math.atan2(this.y - mouseY, this.x - mouseX);
        this.x += Math.cos(angle) * repelForce * 2;
        this.y += Math.sin(angle) * repelForce * 2;
      }
      
      // Lifecycle
      this.life -= this.decay;
      if (this.life <= 0 || distance > Math.min(canvas.width, canvas.height) * 0.6) {
        this.reset();
      }
    }
    
    draw() {
      const alpha = Math.max(0, this.life / 100) * this.color.a;
      
      // Draw particle with glow
      ctx.save();
      ctx.globalAlpha = alpha;
      
      // Main particle
      ctx.fillStyle = `rgba(${this.color.r}, ${this.color.g}, ${this.color.b}, 1)`;
      ctx.beginPath();
      ctx.arc(this.x, this.y, this.size, 0, Math.PI * 2);
      ctx.fill();
      
      // Glow effect
      ctx.globalAlpha = alpha * 0.3;
      ctx.fillStyle = `rgba(${this.color.r}, ${this.color.g}, ${this.color.b}, 1)`;
      ctx.beginPath();
      ctx.arc(this.x, this.y, this.size * 3, 0, Math.PI * 2);
      ctx.fill();
      
      ctx.restore();
    }
  }
  
  // Initialize particles
  for (let i = 0; i < numParticles; i++) {
    particles.push(new GalaxyParticle());
  }
  
  // Mouse tracking
  document.addEventListener('mousemove', (e) => {
    mouseX = e.clientX;
    mouseY = e.clientY;
  });
  
  // Animation loop
  function animate() {
    time++;
    
    // Clear canvas with fade effect
    ctx.fillStyle = 'rgba(0, 0, 16, 0.05)';
    ctx.fillRect(0, 0, canvas.width, canvas.height);
    
    // Update and draw particles
    particles.forEach(particle => {
      particle.update();
      particle.draw();
    });
    
    // Draw connecting lines between nearby particles
    ctx.strokeStyle = 'rgba(100, 150, 255, 0.1)';
    ctx.lineWidth = 0.5;
    
    for (let i = 0; i < particles.length; i += 5) { // Sample every 5th particle for performance
      for (let j = i + 1; j < particles.length && j < i + 10; j++) {
        const dx = particles[i].x - particles[j].x;
        const dy = particles[i].y - particles[j].y;
        const distance = Math.sqrt(dx * dx + dy * dy);
        
        if (distance < 80) {
          ctx.globalAlpha = (80 - distance) / 80 * 0.2;
          ctx.beginPath();
          ctx.moveTo(particles[i].x, particles[i].y);
          ctx.lineTo(particles[j].x, particles[j].y);
          ctx.stroke();
        }
      }
    }
    
    ctx.globalAlpha = 1;
    requestAnimationFrame(animate);
  }
  
  // Handle resize
  window.addEventListener('resize', () => {
    canvas.width = window.innerWidth;
    canvas.height = window.innerHeight;
  });
  
  // Start animation
  animate();
}

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', initGalaxyBackground);