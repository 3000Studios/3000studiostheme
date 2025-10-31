<?php
/**
 * 3000 Studios API Connector
 * Handles all external API calls (OpenAI, Pexels, Unsplash, Pixabay)
 * 
 * @package     3000Studios
 * @author      Mr. jwswain
 * @copyright   Copyright (c) 2025, Mr. jwswain & 3000 Studios
 * @license     Proprietary - All Rights Reserved
 */

if ( ! defined('ABSPATH') ) { exit; }

class Studios_API_Connector {
    
    /**
     * Call OpenAI API for natural language processing
     */
    public static function ask_openai($prompt, $system_message = 'You are a helpful WordPress AI assistant.') {
        $api_key = studios_get_openai_key();
        
        if (empty($api_key)) {
            return [
                'success' => false,
                'error' => 'OpenAI API key not configured'
            ];
        }
        
        $response = wp_remote_post('https://api.openai.com/v1/chat/completions', [
            'timeout' => 30,
            'headers' => [
                'Authorization' => 'Bearer ' . $api_key,
                'Content-Type' => 'application/json'
            ],
            'body' => wp_json_encode([
                'model' => 'gpt-4o-mini', // Cheaper model
                'messages' => [
                    ['role' => 'system', 'content' => $system_message],
                    ['role' => 'user', 'content' => $prompt]
                ],
                'temperature' => 0.7,
                'max_tokens' => 1000
            ])
        ]);
        
        if (is_wp_error($response)) {
            return [
                'success' => false,
                'error' => $response->get_error_message()
            ];
        }
        
        $body = json_decode(wp_remote_retrieve_body($response), true);
        
        if (isset($body['choices'][0]['message']['content'])) {
            return [
                'success' => true,
                'response' => $body['choices'][0]['message']['content'],
                'usage' => $body['usage'] ?? null
            ];
        }
        
        return [
            'success' => false,
            'error' => $body['error']['message'] ?? 'Unknown error'
        ];
    }
    
    /**
     * Search Pexels for images
     */
    public static function search_pexels($query, $per_page = 10) {
        $api_key = studios_get_pexels_key();
        
        if (empty($api_key)) {
            return self::fallback_images($query);
        }
        
        $response = wp_remote_get('https://api.pexels.com/v1/search?' . http_build_query([
            'query' => $query,
            'per_page' => $per_page,
            'orientation' => 'landscape'
        ]), [
            'headers' => [
                'Authorization' => $api_key
            ]
        ]);
        
        if (is_wp_error($response)) {
            return self::fallback_images($query);
        }
        
        $body = json_decode(wp_remote_retrieve_body($response), true);
        
        if (isset($body['photos'])) {
            $images = [];
            foreach ($body['photos'] as $photo) {
                $images[] = [
                    'url' => $photo['src']['large2x'],
                    'thumbnail' => $photo['src']['medium'],
                    'photographer' => $photo['photographer'],
                    'source' => 'Pexels'
                ];
            }
            return ['success' => true, 'images' => $images];
        }
        
        return self::fallback_images($query);
    }
    
    /**
     * Search Unsplash for images
     */
    public static function search_unsplash($query, $per_page = 10) {
        $api_key = studios_get_unsplash_key();
        
        if (empty($api_key)) {
            return self::fallback_images($query);
        }
        
        $response = wp_remote_get('https://api.unsplash.com/search/photos?' . http_build_query([
            'query' => $query,
            'per_page' => $per_page,
            'orientation' => 'landscape'
        ]), [
            'headers' => [
                'Authorization' => 'Client-ID ' . $api_key
            ]
        ]);
        
        if (is_wp_error($response)) {
            return self::fallback_images($query);
        }
        
        $body = json_decode(wp_remote_retrieve_body($response), true);
        
        if (isset($body['results'])) {
            $images = [];
            foreach ($body['results'] as $photo) {
                $images[] = [
                    'url' => $photo['urls']['regular'],
                    'thumbnail' => $photo['urls']['thumb'],
                    'photographer' => $photo['user']['name'],
                    'source' => 'Unsplash'
                ];
            }
            return ['success' => true, 'images' => $images];
        }
        
        return self::fallback_images($query);
    }
    
    /**
     * Search Pixabay for images/videos/music
     */
    public static function search_pixabay($query, $type = 'image', $per_page = 10) {
        $api_key = studios_get_pixabay_key();
        
        if (empty($api_key)) {
            return self::fallback_images($query);
        }
        
        $endpoint = 'https://pixabay.com/api/';
        if ($type === 'video') {
            $endpoint = 'https://pixabay.com/api/videos/';
        } elseif ($type === 'music') {
            $endpoint = 'https://pixabay.com/api/music/';
        }
        
        $response = wp_remote_get($endpoint . '?' . http_build_query([
            'key' => $api_key,
            'q' => $query,
            'per_page' => $per_page,
            'image_type' => 'photo'
        ]));
        
        if (is_wp_error($response)) {
            return self::fallback_images($query);
        }
        
        $body = json_decode(wp_remote_retrieve_body($response), true);
        
        if (isset($body['hits'])) {
            $results = [];
            foreach ($body['hits'] as $item) {
                if ($type === 'music') {
                    $results[] = [
                        'url' => $item['audio']['url'] ?? '',
                        'title' => $item['title'] ?? 'Untitled',
                        'duration' => $item['duration'] ?? 0,
                        'source' => 'Pixabay Music'
                    ];
                } elseif ($type === 'video') {
                    $results[] = [
                        'url' => $item['videos']['medium']['url'] ?? '',
                        'thumbnail' => $item['userImageURL'] ?? '',
                        'source' => 'Pixabay Video'
                    ];
                } else {
                    $results[] = [
                        'url' => $item['largeImageURL'],
                        'thumbnail' => $item['previewURL'],
                        'photographer' => $item['user'],
                        'source' => 'Pixabay'
                    ];
                }
            }
            return ['success' => true, $type . 's' => $results];
        }
        
        return self::fallback_images($query);
    }
    
    /**
     * Fallback images when API keys not available
     */
    private static function fallback_images($query) {
        return [
            'success' => false,
            'error' => 'API key not configured',
            'fallback' => [
                [
                    'url' => 'https://via.placeholder.com/1200x600/00ffe7/000000?text=' . urlencode($query),
                    'thumbnail' => 'https://via.placeholder.com/400x300/00ffe7/000000?text=' . urlencode($query),
                    'photographer' => 'Placeholder',
                    'source' => 'Fallback'
                ]
            ]
        ];
    }
    
    /**
     * Smart AI command processor using OpenAI
     */
    public static function process_command_with_ai($command, $page_context = []) {
        $system_prompt = "You are a WordPress AI assistant that helps users edit their website. 
        Analyze the user's natural language command and return a JSON response with:
        {
            \"action\": \"update_text|change_color|add_animation|add_media|adjust_layout\",
            \"target\": \"element selector or description\",
            \"value\": \"the new value or content\",
            \"page\": \"target page name\",
            \"confidence\": 0.0-1.0
        }
        
        Current page context: " . wp_json_encode($page_context);
        
        $result = self::ask_openai($command, $system_prompt);
        
        if ($result['success']) {
            $parsed = json_decode($result['response'], true);
            if ($parsed) {
                return $parsed;
            }
        }
        
        // Fallback to regex parsing if OpenAI fails
        return self::parse_command_fallback($command);
    }
    
    /**
     * Fallback command parser (regex-based)
     */
    private static function parse_command_fallback($command) {
        $command_lower = strtolower($command);
        
        $action = 'unknown';
        if (preg_match('/\b(change|update|edit|modify)\b/i', $command)) {
            $action = 'update_text';
        }
        if (preg_match('/\b(color|colour)\b/i', $command)) {
            $action = 'change_color';
        }
        if (preg_match('/\b(animation|animate|bounce|fade|glow)\b/i', $command)) {
            $action = 'add_animation';
        }
        if (preg_match('/\b(image|photo|picture|video)\b/i', $command)) {
            $action = 'add_media';
        }
        
        // Extract page name
        $page = 'homepage';
        if (preg_match('/\b(on|to|in)\s+(?:the\s+)?(\w+)\s+page/i', $command, $matches)) {
            $page = $matches[2];
        }
        
        // Extract quoted text
        $value = '';
        if (preg_match('/["\']([^"\']+)["\']/i', $command, $matches)) {
            $value = $matches[1];
        }
        
        return [
            'action' => $action,
            'page' => $page,
            'value' => $value,
            'confidence' => 0.6
        ];
    }
}
