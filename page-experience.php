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

<style>
    /* Full-screen game experience */
    .experience-container {
        position: fixed;
        top: 0;
        left: 0;
        width: 100vw;
        height: 100vh;
        background: #000;
        z-index: 1000;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        color: #fff;
    }

    .game-container {
        width: 100%;
        height: 100%;
        display: flex;
        justify-content: center;
        align-items: center;
        position: relative;
    }

    .game-canvas {
        width: 100%;
        height: 100%;
        object-fit: contain;
        background: #000;
    }

    .exit-btn {
        position: absolute;
        top: 20px;
        right: 20px;
        background: rgba(255, 255, 255, 0.1);
        border: 2px solid rgba(255, 255, 255, 0.3);
        color: #fff;
        padding: 10px 20px;
        border-radius: 8px;
        cursor: pointer;
        font-family: inherit;
        font-weight: bold;
        transition: all 0.3s ease;
        z-index: 1001;
    }

    .exit-btn:hover {
        background: rgba(255, 255, 255, 0.2);
        border-color: rgba(255, 255, 255, 0.5);
    }

    /* Hide header and footer for full experience */
    body.page-template-page-experience header,
    body.page-template-page-experience footer {
        display: none !important;
    }

    body.page-template-page-experience {
        overflow: hidden;
    }
</style>

<div class="experience-container">
    <button class="exit-btn" onclick="window.location.href='<?php echo home_url(); ?>'">× Exit Experience</button>

    <div class="game-container">
        <!-- KBH Games Integration -->
        <div id="game-frame" style="width: 100%; height: 100%; display: flex; justify-content: center; align-items: center;">
            <div style="text-align: center; color: #fff;">
                <h1 style="margin-bottom: 2rem; font-size: 3rem; background: linear-gradient(45deg, #ff6b6b, #4ecdc4); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
                    🎮 GAME LOADING...
                </h1>
                <div class="loading-spinner" style="width: 60px; height: 60px; border: 4px solid rgba(255,255,255,0.1); border-left: 4px solid #ff6b6b; border-radius: 50%; animation: spin 1s linear infinite; margin: 0 auto;"></div>
            </div>
        </div>
    </div>
</div>

<style>
    @keyframes spin {
        0% {
            transform: rotate(0deg);
        }

        100% {
            transform: rotate(360deg);
        }
    }

    /* KBH Game Styles */
    .game-interface {
        width: 100%;
        height: 100%;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        position: relative;
        overflow: hidden;
    }

    .game-interface::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background:
            radial-gradient(circle at 20% 50%, rgba(120, 119, 198, 0.3), transparent 50%),
            radial-gradient(circle at 80% 20%, rgba(255, 119, 198, 0.3), transparent 50%),
            radial-gradient(circle at 40% 80%, rgba(120, 200, 255, 0.3), transparent 50%);
        animation: float 20s ease-in-out infinite;
    }

    @keyframes float {

        0%,
        100% {
            transform: translateY(0px) rotate(0deg);
        }

        33% {
            transform: translateY(-20px) rotate(120deg);
        }

        66% {
            transform: translateY(10px) rotate(240deg);
        }
    }

    .game-title {
        font-size: 4rem;
        font-weight: 900;
        text-align: center;
        margin-bottom: 2rem;
        background: linear-gradient(45deg, #ff6b6b, #4ecdc4, #45b7d1, #f9ca24);
        background-size: 400% 400%;
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        animation: gradient-shift 3s ease-in-out infinite;
        text-shadow: 0 0 30px rgba(255, 255, 255, 0.5);
        z-index: 10;
        position: relative;
    }

    @keyframes gradient-shift {

        0%,
        100% {
            background-position: 0% 50%;
        }

        50% {
            background-position: 100% 50%;
        }
    }

    .game-canvas-container {
        width: 90%;
        max-width: 1200px;
        height: 70vh;
        background: rgba(0, 0, 0, 0.8);
        border-radius: 20px;
        border: 3px solid rgba(255, 255, 255, 0.2);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
        overflow: hidden;
        position: relative;
        z-index: 10;
    }

    .game-canvas {
        width: 100%;
        height: 100%;
        background: #000;
        display: block;
    }

    .game-controls {
        position: absolute;
        bottom: 30px;
        left: 50%;
        transform: translateX(-50%);
        display: flex;
        gap: 15px;
        z-index: 11;
    }

    .control-btn {
        padding: 12px 24px;
        background: rgba(255, 255, 255, 0.1);
        border: 2px solid rgba(255, 255, 255, 0.3);
        border-radius: 25px;
        color: white;
        font-weight: bold;
        cursor: pointer;
        transition: all 0.3s ease;
        backdrop-filter: blur(10px);
    }

    .control-btn:hover {
        background: rgba(255, 255, 255, 0.2);
        border-color: rgba(255, 255, 255, 0.5);
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(255, 255, 255, 0.2);
    }
</style>

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