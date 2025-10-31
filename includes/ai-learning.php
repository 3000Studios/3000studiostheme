<?php
/**
 * 3000 Studios AI Learning Database Schema
 * 
 * @package     3000Studios
 * @author      Mr. jwswain
 * @copyright   Copyright (c) 2025, Mr. jwswain & 3000 Studios
 * @license     Proprietary - All Rights Reserved
 */

if ( ! defined('ABSPATH') ) { exit; }

/**
 * Create AI learning database tables
 */
function studios_ai_create_tables() {
    global $wpdb;
    $charset_collate = $wpdb->get_charset_collate();
    
    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    
    // AI Commands Log - Store every command for learning
    $table_commands = $wpdb->prefix . 'ai_commands';
    $sql_commands = "CREATE TABLE IF NOT EXISTS $table_commands (
        id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
        command TEXT NOT NULL,
        parsed_intent VARCHAR(255),
        target_page VARCHAR(255),
        target_element VARCHAR(255),
        extracted_data LONGTEXT,
        success TINYINT(1) DEFAULT 0,
        execution_time FLOAT,
        user_feedback TINYINT(1),
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (id),
        INDEX idx_intent (parsed_intent),
        INDEX idx_page (target_page),
        INDEX idx_success (success),
        INDEX idx_created (created_at)
    ) $charset_collate;";
    dbDelta($sql_commands);
    
    // AI Patterns - Learn from successful patterns
    $table_patterns = $wpdb->prefix . 'ai_patterns';
    $sql_patterns = "CREATE TABLE IF NOT EXISTS $table_patterns (
        id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
        pattern_type VARCHAR(100),
        pattern_keywords TEXT,
        action_type VARCHAR(100),
        confidence_score FLOAT DEFAULT 0,
        usage_count INT DEFAULT 0,
        success_rate FLOAT DEFAULT 0,
        last_used DATETIME,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (id),
        INDEX idx_type (pattern_type),
        INDEX idx_confidence (confidence_score)
    ) $charset_collate;";
    dbDelta($sql_patterns);
    
    // Page Structure Cache - Store WordPress page structures
    $table_pages = $wpdb->prefix . 'ai_page_structures';
    $sql_pages = "CREATE TABLE IF NOT EXISTS $table_pages (
        id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
        page_id BIGINT(20),
        page_slug VARCHAR(255),
        page_title VARCHAR(255),
        template_file VARCHAR(255),
        has_blocks TINYINT(1) DEFAULT 0,
        layout_type VARCHAR(50),
        css_grid_detected TINYINT(1) DEFAULT 0,
        flexbox_detected TINYINT(1) DEFAULT 0,
        sections LONGTEXT,
        last_analyzed DATETIME,
        PRIMARY KEY (id),
        UNIQUE KEY unique_page (page_id),
        INDEX idx_slug (page_slug)
    ) $charset_collate;";
    dbDelta($sql_pages);
    
    // AI Learning Data - Store intelligence
    $table_learning = $wpdb->prefix . 'ai_learning';
    $sql_learning = "CREATE TABLE IF NOT EXISTS $table_learning (
        id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
        context_type VARCHAR(100),
        context_data LONGTEXT,
        learned_response LONGTEXT,
        confidence FLOAT DEFAULT 0,
        times_used INT DEFAULT 0,
        last_updated DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        PRIMARY KEY (id),
        INDEX idx_context (context_type)
    ) $charset_collate;";
    dbDelta($sql_learning);
    
    // User Preferences - Remember user's style
    $table_prefs = $wpdb->prefix . 'ai_user_preferences';
    $sql_prefs = "CREATE TABLE IF NOT EXISTS $table_prefs (
        id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
        user_id BIGINT(20),
        preference_key VARCHAR(100),
        preference_value LONGTEXT,
        updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        PRIMARY KEY (id),
        UNIQUE KEY user_pref (user_id, preference_key)
    ) $charset_collate;";
    dbDelta($sql_prefs);
    
    // Command Corrections - Learn from user fixes
    $table_corrections = $wpdb->prefix . 'ai_corrections';
    $sql_corrections = "CREATE TABLE IF NOT EXISTS $table_corrections (
        id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
        original_command TEXT,
        corrected_result TEXT,
        correction_type VARCHAR(100),
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (id),
        INDEX idx_type (correction_type)
    ) $charset_collate;";
    dbDelta($sql_corrections);
}

// Run on theme activation
add_action('after_switch_theme', 'studios_ai_create_tables');

/**
 * AI Learning Functions
 */
class Studios_AI_Learning {
    
    /**
     * Log a command for learning
     */
    public static function log_command($command, $intent, $target_page, $element, $data, $success, $exec_time) {
        global $wpdb;
        $table = $wpdb->prefix . 'ai_commands';
        
        $wpdb->insert($table, [
            'command' => sanitize_text_field($command),
            'parsed_intent' => sanitize_text_field($intent),
            'target_page' => sanitize_text_field($target_page),
            'target_element' => sanitize_text_field($element),
            'extracted_data' => wp_json_encode($data),
            'success' => $success ? 1 : 0,
            'execution_time' => floatval($exec_time)
        ]);
        
        return $wpdb->insert_id;
    }
    
    /**
     * Learn from successful patterns
     */
    public static function learn_pattern($type, $keywords, $action, $success) {
        global $wpdb;
        $table = $wpdb->prefix . 'ai_patterns';
        
        // Check if pattern exists
        $existing = $wpdb->get_row($wpdb->prepare(
            "SELECT * FROM $table WHERE pattern_type = %s AND action_type = %s",
            $type, $action
        ));
        
        if ($existing) {
            // Update existing pattern
            $new_count = $existing->usage_count + 1;
            $new_success_rate = (($existing->success_rate * $existing->usage_count) + ($success ? 1 : 0)) / $new_count;
            $new_confidence = min(1.0, $existing->confidence_score + 0.05);
            
            $wpdb->update($table, [
                'usage_count' => $new_count,
                'success_rate' => $new_success_rate,
                'confidence_score' => $new_confidence,
                'last_used' => current_time('mysql')
            ], ['id' => $existing->id]);
        } else {
            // Insert new pattern
            $wpdb->insert($table, [
                'pattern_type' => $type,
                'pattern_keywords' => wp_json_encode($keywords),
                'action_type' => $action,
                'confidence_score' => 0.5,
                'usage_count' => 1,
                'success_rate' => $success ? 1.0 : 0.0,
                'last_used' => current_time('mysql')
            ]);
        }
    }
    
    /**
     * Get best matching pattern
     */
    public static function get_best_pattern($keywords) {
        global $wpdb;
        $table = $wpdb->prefix . 'ai_patterns';
        
        $patterns = $wpdb->get_results(
            "SELECT * FROM $table WHERE confidence_score > 0.7 ORDER BY confidence_score DESC, usage_count DESC LIMIT 10"
        );
        
        $best_match = null;
        $highest_score = 0;
        
        foreach ($patterns as $pattern) {
            $pattern_keywords = json_decode($pattern->pattern_keywords, true);
            $match_score = self::calculate_keyword_match($keywords, $pattern_keywords);
            
            if ($match_score > $highest_score) {
                $highest_score = $match_score;
                $best_match = $pattern;
            }
        }
        
        return $best_match;
    }
    
    /**
     * Calculate keyword matching score
     */
    private static function calculate_keyword_match($input_keywords, $pattern_keywords) {
        $matches = 0;
        $total = count($pattern_keywords);
        
        foreach ($pattern_keywords as $pk) {
            foreach ($input_keywords as $ik) {
                if (stripos($ik, $pk) !== false || stripos($pk, $ik) !== false) {
                    $matches++;
                    break;
                }
            }
        }
        
        return $total > 0 ? $matches / $total : 0;
    }
    
    /**
     * Store page structure analysis
     */
    public static function cache_page_structure($page_id, $slug, $title, $template, $structure) {
        global $wpdb;
        $table = $wpdb->prefix . 'ai_page_structures';
        
        $wpdb->replace($table, [
            'page_id' => $page_id,
            'page_slug' => $slug,
            'page_title' => $title,
            'template_file' => $template,
            'has_blocks' => isset($structure['blocks']) ? 1 : 0,
            'layout_type' => $structure['layout_type'] ?? 'unknown',
            'css_grid_detected' => $structure['has_grid'] ? 1 : 0,
            'flexbox_detected' => $structure['has_flex'] ? 1 : 0,
            'sections' => wp_json_encode($structure['sections'] ?? []),
            'last_analyzed' => current_time('mysql')
        ]);
    }
    
    /**
     * Get page structure from cache
     */
    public static function get_page_structure($page_identifier) {
        global $wpdb;
        $table = $wpdb->prefix . 'ai_page_structures';
        
        // Try by slug first, then by ID
        $structure = $wpdb->get_row($wpdb->prepare(
            "SELECT * FROM $table WHERE page_slug = %s OR page_id = %d ORDER BY last_analyzed DESC LIMIT 1",
            $page_identifier, intval($page_identifier)
        ));
        
        if ($structure) {
            $structure->sections = json_decode($structure->sections, true);
        }
        
        return $structure;
    }
    
    /**
     * Save user preference
     */
    public static function save_preference($user_id, $key, $value) {
        global $wpdb;
        $table = $wpdb->prefix . 'ai_user_preferences';
        
        $wpdb->replace($table, [
            'user_id' => $user_id,
            'preference_key' => $key,
            'preference_value' => is_array($value) ? wp_json_encode($value) : $value
        ]);
    }
    
    /**
     * Get user preference
     */
    public static function get_preference($user_id, $key, $default = null) {
        global $wpdb;
        $table = $wpdb->prefix . 'ai_user_preferences';
        
        $result = $wpdb->get_var($wpdb->prepare(
            "SELECT preference_value FROM $table WHERE user_id = %d AND preference_key = %s",
            $user_id, $key
        ));
        
        if ($result) {
            $decoded = json_decode($result, true);
            return $decoded !== null ? $decoded : $result;
        }
        
        return $default;
    }
}
