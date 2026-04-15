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

get_header(); ?>

<div class="experience-container">
    <button class="exit-btn" onclick="window.location.href='<?php echo home_url(); ?>'">× Exit Experience</button>

    <div class="game-container">
        <!-- KBH Games Integration -->
        <div id="game-frame">
            <div class="experience-loading">
                <h1>🎮 GAME LOADING...</h1>
                <div class="loading-spinner"></div>
            </div>
        </div>
    </div>
</div>

<script>
    // Initialize the KBH Games experience
    document.addEventListener('DOMContentLoaded', function() {
        setTimeout(function() {
            initializeGame();
        }, 2000);
    });

    function initializeGame() {
        const gameFrame = document.getElementById('game-frame');

        gameFrame.innerHTML = `
        <div class="game-interface">
            <h1 class="game-title">KBH GAMES</h1>
            <div class="game-canvas-container">
                <canvas class="game-canvas" id="main-game-canvas" width="1200" height="600"></canvas>
            </div>
            <div class="game-controls">
                <button class="control-btn" onclick="startGame()">🎮 START GAME</button>
                <button class="control-btn" onclick="pauseGame()">⏸️ PAUSE</button>
                <button class="control-btn" onclick="resetGame()">🔄 RESET</button>
                <button class="control-btn" onclick="toggleFullscreen()">🔳 FULLSCREEN</button>
            </div>
        </div>
    `;

        // Initialize the actual game
        setupKBHGame();
    }

    function setupKBHGame() {
        const canvas = document.getElementById('main-game-canvas');
        const ctx = canvas.getContext('2d');

        // Game state
        let gameRunning = false;
        let animationFrame;

        // Game objects
        let player = {
            x: 100,
            y: canvas.height / 2,
            width: 40,
            height: 40,
            speed: 5,
            color: '#ff6b6b',
            dy: 0,
            jumping: false
        };

        let obstacles = [];
        let particles = [];
        let score = 0;
        let gameSpeed = 2;

        // Create obstacle
        function createObstacle() {
            obstacles.push({
                x: canvas.width,
                y: Math.random() * (canvas.height - 100) + 50,
                width: 30,
                height: 60,
                color: '#4ecdc4'
            });
        }

        // Create particle effect
        function createParticle(x, y, color) {
            for (let i = 0; i < 5; i++) {
                particles.push({
                    x: x,
                    y: y,
                    dx: (Math.random() - 0.5) * 6,
                    dy: (Math.random() - 0.5) * 6,
                    color: color,
                    life: 30,
                    maxLife: 30
                });
            }
        }

        // Handle keyboard input
        document.addEventListener('keydown', function(e) {
            if (e.code === 'Space' || e.code === 'ArrowUp') {
                e.preventDefault();
                if (gameRunning && !player.jumping) {
                    player.dy = -12;
                    player.jumping = true;
                    createParticle(player.x, player.y + player.height, '#fff');
                }
            }
        });

        // Handle mouse/touch input
        canvas.addEventListener('click', function() {
            if (gameRunning && !player.jumping) {
                player.dy = -12;
                player.jumping = true;
                createParticle(player.x, player.y + player.height, '#fff');
            }
        });

        // Game update function
        function update() {
            if (!gameRunning) return;

            // Update player physics
            player.dy += 0.8; // gravity
            player.y += player.dy;

            // Ground collision
            if (player.y + player.height >= canvas.height - 50) {
                player.y = canvas.height - 50 - player.height;
                player.dy = 0;
                player.jumping = false;
            }

            // Update obstacles
            obstacles.forEach((obstacle, index) => {
                obstacle.x -= gameSpeed;

                // Remove off-screen obstacles
                if (obstacle.x + obstacle.width < 0) {
                    obstacles.splice(index, 1);
                    score += 10;

                    // Increase game speed
                    if (score % 100 === 0) {
                        gameSpeed += 0.5;
                    }
                }
            });

            // Update particles
            particles.forEach((particle, index) => {
                particle.x += particle.dx;
                particle.y += particle.dy;
                particle.life--;

                if (particle.life <= 0) {
                    particles.splice(index, 1);
                }
            });

            // Check collisions
            obstacles.forEach(obstacle => {
                if (player.x < obstacle.x + obstacle.width &&
                    player.x + player.width > obstacle.x &&
                    player.y < obstacle.y + obstacle.height &&
                    player.y + player.height > obstacle.y) {

                    // Game over
                    createParticle(player.x + player.width / 2, player.y + player.height / 2, '#ff6b6b');
                    gameRunning = false;

                    setTimeout(() => {
                        if (confirm(`Game Over! Score: ${score}\\nPlay again?`)) {
                            resetGame();
                        }
                    }, 100);
                }
            });

            // Spawn new obstacles
            if (Math.random() < 0.01) {
                createObstacle();
            }
        }

        // Game render function
        function render() {
            // Clear canvas with gradient background
            const gradient = ctx.createLinearGradient(0, 0, canvas.width, canvas.height);
            gradient.addColorStop(0, '#1e3c72');
            gradient.addColorStop(1, '#2a5298');
            ctx.fillStyle = gradient;
            ctx.fillRect(0, 0, canvas.width, canvas.height);

            // Draw ground
            ctx.fillStyle = '#333';
            ctx.fillRect(0, canvas.height - 50, canvas.width, 50);

            // Draw player with glow effect
            ctx.shadowColor = player.color;
            ctx.shadowBlur = 20;
            ctx.fillStyle = player.color;
            ctx.fillRect(player.x, player.y, player.width, player.height);
            ctx.shadowBlur = 0;

            // Draw obstacles with glow
            obstacles.forEach(obstacle => {
                ctx.shadowColor = obstacle.color;
                ctx.shadowBlur = 15;
                ctx.fillStyle = obstacle.color;
                ctx.fillRect(obstacle.x, obstacle.y, obstacle.width, obstacle.height);
                ctx.shadowBlur = 0;
            });

            // Draw particles
            particles.forEach(particle => {
                const alpha = particle.life / particle.maxLife;
                ctx.globalAlpha = alpha;
                ctx.fillStyle = particle.color;
                ctx.fillRect(particle.x, particle.y, 3, 3);
            });
            ctx.globalAlpha = 1;

            // Draw UI
            ctx.fillStyle = '#fff';
            ctx.font = 'bold 24px Arial';
            ctx.fillText(`Score: ${score}`, 20, 40);
            ctx.fillText(`Speed: ${gameSpeed.toFixed(1)}x`, 20, 70);

            if (!gameRunning) {
                ctx.fillStyle = 'rgba(0, 0, 0, 0.7)';
                ctx.fillRect(0, 0, canvas.width, canvas.height);

                ctx.fillStyle = '#fff';
                ctx.font = 'bold 48px Arial';
                ctx.textAlign = 'center';
                ctx.fillText('PAUSED', canvas.width / 2, canvas.height / 2);
                ctx.font = 'bold 20px Arial';
                ctx.fillText('Press START to begin!', canvas.width / 2, canvas.height / 2 + 50);
                ctx.textAlign = 'left';
            }
        }

        // Game loop
        function gameLoop() {
            update();
            render();
            animationFrame = requestAnimationFrame(gameLoop);
        }

        // Start the game loop
        gameLoop();

        // Global game control functions
        window.startGame = function() {
            gameRunning = true;
        };

        window.pauseGame = function() {
            gameRunning = !gameRunning;
        };

        window.resetGame = function() {
            player.x = 100;
            player.y = canvas.height / 2;
            player.dy = 0;
            player.jumping = false;
            obstacles = [];
            particles = [];
            score = 0;
            gameSpeed = 2;
            gameRunning = true;
        };

        window.toggleFullscreen = function() {
            if (!document.fullscreenElement) {
                document.documentElement.requestFullscreen();
            } else {
                document.exitFullscreen();
            }
        };
    }
</script>

<?php get_footer(); ?>