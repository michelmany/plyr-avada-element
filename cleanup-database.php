<?php
/**
 * PLYRAE Database Cleanup Script
 * 
 * This script removes duplicate PLYRAE_plyr_audio entries from wp_options table
 * that were created due to the auto-activation bug.
 * 
 * IMPORTANT: 
 * - Run this ONLY ONCE after fixing the auto-activation issue
 * - Make a database backup before running this script
 * - Remove this file after running it
 */

// Security: Only run if WordPress is loaded and user is admin
if (!defined('ABSPATH')) {
    die('Direct access not allowed');
}

// Only allow admin users to run this
if (!current_user_can('manage_options')) {
    die('Access denied');
}

function plyrae_cleanup_database() {
    global $wpdb;
    
    echo "<h2>PLYRAE Database Cleanup</h2>";
    echo "<p><strong>WARNING:</strong> Make sure you have a database backup before proceeding!</p>";
    
    // Check if cleanup has already been run
    if (get_option('plyrae_cleanup_completed')) {
        echo "<div style='color: orange; font-weight: bold;'>Cleanup has already been completed. If you need to run it again, delete the 'plyrae_cleanup_completed' option first.</div>";
        return;
    }
    
    // Find options that contain the problematic data
    $problematic_options = $wpdb->get_results(
        "SELECT option_name, option_value 
         FROM {$wpdb->options} 
         WHERE option_value LIKE '%PLYRAE_plyr_audio%' 
         AND option_name LIKE '%fusion%'"
    );
    
    if (empty($problematic_options)) {
        echo "<div style='color: green;'>No problematic entries found. Database is clean!</div>";
        return;
    }
    
    echo "<h3>Found " . count($problematic_options) . " potentially problematic options:</h3>";
    
    $cleaned_count = 0;
    $fixed_options = [];
    
    foreach ($problematic_options as $option) {
        $option_value = maybe_unserialize($option->option_value);
        
        if (is_array($option_value)) {
            $original_count = count($option_value);
            
            // Remove duplicates while preserving one instance
            $cleaned_value = array_unique($option_value);
            
            if (count($cleaned_value) < $original_count) {
                // Update the option with cleaned data
                $success = update_option($option->option_name, $cleaned_value);
                
                if ($success) {
                    $removed_count = $original_count - count($cleaned_value);
                    $fixed_options[] = [
                        'name' => $option->option_name,
                        'removed' => $removed_count,
                        'remaining' => count($cleaned_value)
                    ];
                    $cleaned_count += $removed_count;
                }
            }
        }
    }
    
    if ($cleaned_count > 0) {
        echo "<h3>Cleanup Results:</h3>";
        echo "<div style='color: green; font-weight: bold;'>Successfully removed {$cleaned_count} duplicate entries!</div>";
        echo "<table border='1' cellpadding='5' style='border-collapse: collapse; margin: 10px 0;'>";
        echo "<tr><th>Option Name</th><th>Duplicates Removed</th><th>Entries Remaining</th></tr>";
        
        foreach ($fixed_options as $fix) {
            echo "<tr>";
            echo "<td>{$fix['name']}</td>";
            echo "<td style='color: red;'>{$fix['removed']}</td>";
            echo "<td style='color: green;'>{$fix['remaining']}</td>";
            echo "</tr>";
        }
        echo "</table>";
        
        // Mark cleanup as completed
        update_option('plyrae_cleanup_completed', date('Y-m-d H:i:s'), false);
        
        echo "<div style='background: #e7f3ff; padding: 10px; border: 1px solid #b3d9ff; margin: 10px 0;'>";
        echo "<strong>Cleanup completed successfully!</strong><br>";
        echo "The database has been cleaned and future duplications are now prevented.<br>";
        echo "<strong>Remember to delete this cleanup script file for security.</strong>";
        echo "</div>";
        
    } else {
        echo "<div style='color: orange;'>No duplicates found to remove.</div>";
    }
}

// Only run if specifically requested
if (isset($_GET['run_cleanup']) && $_GET['run_cleanup'] === 'yes') {
    plyrae_cleanup_database();
} else {
    ?>
    <h2>PLYRAE Database Cleanup</h2>
    <p><strong style="color: red;">WARNING:</strong> This will clean duplicate entries from your database.</p>
    <p><strong>Before proceeding:</strong></p>
    <ol>
        <li>Make a complete database backup</li>
        <li>Ensure you've updated the plugin to the fixed version</li>
        <li>Only run this once</li>
    </ol>
    
    <form method="get">
        <input type="hidden" name="run_cleanup" value="yes">
        <button type="submit" style="background: #dc3545; color: white; padding: 10px 20px; border: none; cursor: pointer;">
            Run Database Cleanup
        </button>
    </form>
    
    <p><em>After running the cleanup, delete this file for security.</em></p>
    <?php
}
?>
