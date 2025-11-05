#!/usr/bin/env node
/**
 * Copyright (c) 2025 Mr. jwswain - 3000 Studios. All Rights Reserved.
 * Black Vault SUPREME Live Refresh Server
 */

const http = require('http');
const WebSocket = require('ws');

const PORT = 3001;
const WS_PORT = 3002;

// HTTP server for refresh triggers
const server = http.createServer((req, res) => {
    // CORS headers
    res.setHeader('Access-Control-Allow-Origin', '*');
    res.setHeader('Access-Control-Allow-Methods', 'GET, POST, OPTIONS');
    res.setHeader('Access-Control-Allow-Headers', 'Content-Type');

    if (req.method === 'OPTIONS') {
        res.writeHead(200);
        res.end();
        return;
    }

    if (req.url === '/refresh' || req.url === '/reload') {
        console.log('🔄 Refresh triggered - Broadcasting to all clients');
        
        // Broadcast to all WebSocket clients
        wss.clients.forEach(client => {
            if (client.readyState === WebSocket.OPEN) {
                client.send(JSON.stringify({ 
                    type: 'reload',
                    timestamp: Date.now(),
                    message: 'Theme updated - reloading...'
                }));
            }
        });

        res.writeHead(200, { 'Content-Type': 'application/json' });
        res.end(JSON.stringify({ 
            success: true, 
            clients: wss.clients.size,
            message: 'Refresh broadcast sent'
        }));
    } else if (req.url === '/ping') {
        res.writeHead(200, { 'Content-Type': 'application/json' });
        res.end(JSON.stringify({ 
            status: 'alive', 
            clients: wss.clients.size,
            uptime: process.uptime()
        }));
    } else {
        res.writeHead(404);
        res.end('Not found');
    }
});

// WebSocket server for browser connections
const wss = new WebSocket.Server({ port: WS_PORT });

wss.on('connection', (ws) => {
    console.log('✅ Browser client connected');
    
    ws.on('message', (message) => {
        console.log('📨 Received:', message.toString());
    });

    ws.on('close', () => {
        console.log('❌ Browser client disconnected');
    });

    // Send welcome message
    ws.send(JSON.stringify({ 
        type: 'connected',
        message: '3000 Studios Live Reload Active'
    }));
});

server.listen(PORT, () => {
    console.log('\x1b[36m%s\x1b[0m', '⚡ Black Vault SUPREME Refresh Server');
    console.log('\x1b[33m%s\x1b[0m', '━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
    console.log(`🌐 HTTP Trigger: http://localhost:${PORT}/refresh`);
    console.log(`🔌 WebSocket: ws://localhost:${WS_PORT}`);
    console.log('\x1b[32m%s\x1b[0m', '✅ Ready to auto-refresh browsers');
});

// Handle graceful shutdown
process.on('SIGINT', () => {
    console.log('\n\x1b[33m%s\x1b[0m', '⚠️  Shutting down refresh server...');
    server.close();
    wss.close();
    process.exit(0);
});
