<?php
/**
 * 3000 Studios WordPress Intelligence Layer
 * Understands WordPress structure, pages, blocks, grids, and layouts
 * 
 * @package     3000Studios
 * @author      Mr. jwswain
 * @copyright   Copyright (c) 2025, Mr. jwswain & 3000 Studios
 * @license     Proprietary - All Rights Reserved
 */

if ( ! defined('ABSPATH') ) { exit; }

class Studios_WP_Intelligence {
    
    /**
     * Analyze WordPress page structure
     */
    public static function analyze_page($page_identifier) {
        $page = self::get_page_object($page_identifier);
        
        if (!$page) {
            return ['error' => 'Page not found'];
        }
        
        $structure = [
            'id' => $page->ID,
            'slug' => $page->post_name,
            'title' => $page->post_title,
            'template' => get_page_template_slug($page->ID),
            'content' => $page->post_content,
            'blocks' => [],
            'layout_type' => 'unknown',
            'has_grid' => false,
            'has_flex' => false,
            'sections' => []
        ];
        
        // Detect Gutenberg blocks
        if (has_blocks($page->post_content)) {
            $structure['blocks'] = parse_blocks($page->post_content);
            $structure['layout_type'] = 'gutenberg';
        }
        
        // Analyze template file
        $template_file = self::get_template_file($page);
        if ($template_file && file_exists($template_file)) {
            $template_content = file_get_contents($template_file);
            
            // Detect CSS Grid
            if (preg_match('/display:\s*grid|grid-template|grid-area/i', $template_content)) {
                $structure['has_grid'] = true;
            }
            
            // Detect Flexbox
            if (preg_match('/display:\s*flex|flex-direction|flex-wrap/i', $template_content)) {
                $structure['has_flex'] = true;
            }
            
            // Extract sections
            $structure['sections'] = self::extract_sections($template_content);
        }
        
        // Cache the structure
        Studios_AI_Learning::cache_page_structure(
            $page->ID,
            $page->post_name,
            $page->post_title,
            $structure['template'],
            $structure
        );
        
        return $structure;
    }
    
    /**
     * Get page object by various identifiers
     */
    private static function get_page_object($identifier) {
        // Try as page ID
        if (is_numeric($identifier)) {
            $page = get_post(intval($identifier));
            if ($page && $page->post_type === 'page') {
                return $page;
            }
        }
        
        // Try as slug
        $page = get_page_by_path($identifier);
        if ($page) {
            return $page;
        }
        
        // Try as title
        $page = get_page_by_title($identifier);
        if ($page) {
            return $page;
        }
        
        // Try partial slug match
        $pages = get_pages(['name' => $identifier]);
        if (!empty($pages)) {
            return $pages[0];
        }
        
        // Fuzzy search by title
        $all_pages = get_pages();
        foreach ($all_pages as $p) {
            if (stripos($p->post_title, $identifier) !== false) {
                return $p;
            }
        }
        
        return null;
    }
    
    /**
     * Get template file path
     */
    private static function get_template_file($page) {
        $template_slug = get_page_template_slug($page->ID);
        
        if ($template_slug) {
            return get_template_directory() . '/' . $template_slug;
        }
        
        // Check for custom template
        $template_files = [
            'page-' . $page->post_name . '.php',
            'page-' . $page->ID . '.php',
            'page.php',
            'singular.php',
            'index.php'
        ];
        
        foreach ($template_files as $file) {
            $path = get_template_directory() . '/' . $file;
            if (file_exists($path)) {
                return $path;
            }
        }
        
        return null;
    }
    
    /**
     * Extract sections from template
     */
    private static function extract_sections($content) {
        $sections = [];
        
        // Look for common section patterns
        $patterns = [
            'header' => '/<header[^>]*>(.*?)<\/header>/is',
            'main' => '/<main[^>]*>(.*?)<\/main>/is',
            'section' => '/<section[^>]*>(.*?)<\/section>/is',
            'article' => '/<article[^>]*>(.*?)<\/article>/is',
            'aside' => '/<aside[^>]*>(.*?)<\/aside>/is',
            'footer' => '/<footer[^>]*>(.*?)<\/footer>/is',
            'div_sections' => '/<div[^>]*class="[^"]*section[^"]*"[^>]*>(.*?)<\/div>/is'
        ];
        
        foreach ($patterns as $type => $pattern) {
            if (preg_match_all($pattern, $content, $matches)) {
                foreach ($matches[0] as $match) {
                    $sections[] = [
                        'type' => $type,
                        'content_preview' => substr(strip_tags($match), 0, 100),
                        'has_grid' => preg_match('/grid-template|display:\s*grid/i', $match),
                        'has_flex' => preg_match('/display:\s*flex/i', $match),
                        'classes' => self::extract_classes($match)
                    ];
                }
            }
        }
        
        return $sections;
    }
    
    /**
     * Extract CSS classes from HTML
     */
    private static function extract_classes($html) {
        if (preg_match('/class="([^"]+)"/', $html, $matches)) {
            return explode(' ', $matches[1]);
        }
        return [];
    }
    
    /**
     * Get all editable pages
     */
    public static function get_all_pages() {
        return get_pages([
            'post_status' => 'publish',
            'sort_column' => 'post_title',
            'sort_order' => 'ASC'
        ]);
    }
    
    /**
     * Understand grid/flexbox layout
     */
    public static function understand_layout($page_identifier) {
        $structure = self::analyze_page($page_identifier);
        
        $layout_info = [
            'page' => $structure['title'],
            'layout_system' => [],
            'editable_areas' => [],
            'suggestions' => []
        ];
        
        if ($structure['has_grid']) {
            $layout_info['layout_system'][] = 'CSS Grid';
            $layout_info['suggestions'][] = 'Can adjust grid-template-columns, grid-gap, grid-areas';
        }
        
        if ($structure['has_flex']) {
            $layout_info['layout_system'][] = 'Flexbox';
            $layout_info['suggestions'][] = 'Can adjust flex-direction, justify-content, align-items';
        }
        
        if ($structure['layout_type'] === 'gutenberg') {
            $layout_info['layout_system'][] = 'Gutenberg Blocks';
            $layout_info['suggestions'][] = 'Can add/remove/reorder blocks';
            
            foreach ($structure['blocks'] as $block) {
                if (!empty($block['blockName'])) {
                    $layout_info['editable_areas'][] = [
                        'type' => 'block',
                        'name' => $block['blockName'],
                        'can_edit' => true
                    ];
                }
            }
        }
        
        // Identify editable sections
        foreach ($structure['sections'] as $section) {
            $layout_info['editable_areas'][] = [
                'type' => $section['type'],
                'has_grid' => $section['has_grid'],
                'has_flex' => $section['has_flex'],
                'classes' => $section['classes']
            ];
        }
        
        return $layout_info;
    }
    
    /**
     * Get smart suggestions based on command
     */
    public static function get_smart_suggestions($command, $page) {
        $suggestions = [];
        $command_lower = strtolower($command);
        
        $structure = self::analyze_page($page);
        
        // Suggest based on layout type
        if (strpos($command_lower, 'layout') !== false || strpos($command_lower, 'grid') !== false) {
            if ($structure['has_grid']) {
                $suggestions[] = "This page uses CSS Grid. I can adjust columns, gaps, or grid areas.";
            }
            if ($structure['has_flex']) {
                $suggestions[] = "This page uses Flexbox. I can change direction or alignment.";
            }
        }
        
        // Suggest editable areas
        if (strpos($command_lower, 'change') !== false || strpos($command_lower, 'edit') !== false) {
            $suggestions[] = "Available sections: " . implode(', ', array_column($structure['sections'], 'type'));
        }
        
        return $suggestions;
    }
    
    /**
     * Apply intelligent changes to page
     */
    public static function apply_change($page_identifier, $action, $params) {
        $page = self::get_page_object($page_identifier);
        
        if (!$page) {
            return ['success' => false, 'error' => 'Page not found'];
        }
        
        $structure = self::analyze_page($page_identifier);
        $template_file = self::get_template_file($page);
        
        if (!$template_file || !file_exists($template_file)) {
            return ['success' => false, 'error' => 'Template file not found'];
        }
        
        $start_time = microtime(true);
        $content = file_get_contents($template_file);
        $original_content = $content;
        
        // Apply changes based on action
        switch ($action) {
            case 'update_text':
                $content = self::update_text($content, $params);
                break;
                
            case 'change_layout':
                $content = self::change_layout($content, $params);
                break;
                
            case 'add_animation':
                $content = self::add_animation($content, $params);
                break;
                
            case 'change_color':
                $content = self::change_color($content, $params);
                break;
                
            default:
                return ['success' => false, 'error' => 'Unknown action'];
        }
        
        // Save changes
        if ($content !== $original_content) {
            $backup_file = $template_file . '.backup.' . time();
            copy($template_file, $backup_file);
            
            file_put_contents($template_file, $content);
            
            $exec_time = microtime(true) - $start_time;
            
            return [
                'success' => true,
                'page' => $page->post_title,
                'changes_made' => true,
                'execution_time' => $exec_time,
                'backup_created' => $backup_file
            ];
        }
        
        return ['success' => false, 'error' => 'No changes made'];
    }
    
    /**
     * Update text in template
     */
    private static function update_text($content, $params) {
        $target = $params['target'] ?? 'h1';
        $new_text = $params['text'] ?? '';
        $style = $params['style'] ?? '';
        
        $pattern = '/<' . preg_quote($target) . '[^>]*>(.*?)<\/' . preg_quote($target) . '>/is';
        $replacement = '<' . $target . ($style ? ' style="' . $style . '"' : '') . '>' . esc_html($new_text) . '</' . $target . '>';
        
        return preg_replace($pattern, $replacement, $content, 1);
    }
    
    /**
     * Change layout properties
     */
    private static function change_layout($content, $params) {
        // Implement grid/flex changes
        return $content;
    }
    
    /**
     * Add animation to elements
     */
    private static function add_animation($content, $params) {
        // Implement animation injection
        return $content;
    }
    
    /**
     * Change colors
     */
    private static function change_color($content, $params) {
        // Implement color changes
        return $content;
    }
}
