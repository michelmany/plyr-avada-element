<?php
/**
 * Admin page for PLYRAE database cleanup
 * Add this to your WordPress admin temporarily for cleanup
 */

// Add admin menu item
add_action('admin_menu', 'plyrae_add_cleanup_page');

function plyrae_add_cleanup_page() {
    add_management_page(
        'PLYRAE Cleanup',
        'PLYRAE Cleanup', 
        'manage_options',
        'plyrae-cleanup',
        'plyrae_cleanup_page'
    );
}

function plyrae_cleanup_page() {
    if (!current_user_can('manage_options')) {
        wp_die('Access denied');
    }
    
    ?>
    <div class="wrap">
        <h1>PLYRAE Database Cleanup</h1>
        
        <?php
        if (isset($_POST['run_cleanup']) && $_POST['run_cleanup'] === 'yes' && wp_verify_nonce($_POST['cleanup_nonce'], 'plyrae_cleanup')) {
            plyrae_run_cleanup();
        } else {
            plyrae_show_cleanup_form();
        }
        ?>
    </div>
    <?php
}

function plyrae_show_cleanup_form() {
    global $wpdb;
    
    // Quick check to see if there are issues
    $problematic_count = $wpdb->get_var(
        "SELECT COUNT(*) 
         FROM {$wpdb->options} 
         WHERE option_value LIKE '%PLYRAE_plyr_audio%' 
         AND option_name LIKE '%fusion%'"
    );
    
    ?>
    <div class="notice notice-warning">
        <p><strong>Warning:</strong> This tool will clean duplicate PLYRAE_plyr_audio entries from your database.</p>
        <p><strong>Make a database backup before proceeding!</strong></p>
    </div>
    
    <?php if ($problematic_count > 0): ?>
        <div class="notice notice-info">
            <p>Found <?php echo $problematic_count; ?> option(s) that may contain duplicate entries.</p>
        </div>
        
        <form method="post">
            <?php wp_nonce_field('plyrae_cleanup', 'cleanup_nonce'); ?>
            <input type="hidden" name="run_cleanup" value="yes">
            
            <table class="form-table">
                <tr>
                    <th scope="row">Confirmation</th>
                    <td>
                        <label>
                            <input type="checkbox" name="confirm_backup" required>
                            I have made a database backup
                        </label>
                    </td>
                </tr>
            </table>
            
            <?php submit_button('Run Cleanup', 'primary', 'submit', false); ?>
        </form>
    <?php else: ?>
        <div class="notice notice-success">
            <p>No problematic entries found. Your database is clean!</p>
        </div>
    <?php endif; ?>
    <?php
}

function plyrae_run_cleanup() {
    global $wpdb;
    
    echo '<div class="notice notice-info"><p>Running cleanup...</p></div>';
    
    // Check if cleanup has already been run
    if (get_option('plyrae_cleanup_completed')) {
        echo '<div class="notice notice-warning"><p>Cleanup has already been completed.</p></div>';
        return;
    }
    
    // Find and clean problematic options
    $problematic_options = $wpdb->get_results(
        "SELECT option_name, option_value 
         FROM {$wpdb->options} 
         WHERE option_value LIKE '%PLYRAE_plyr_audio%' 
         AND option_name LIKE '%fusion%'"
    );
    
    $cleaned_count = 0;
    $fixed_options = [];
    
    foreach ($problematic_options as $option) {
        $option_value = maybe_unserialize($option->option_value);
        
        if (is_array($option_value)) {
            $original_count = count($option_value);
            $cleaned_value = array_unique($option_value);
            
            if (count($cleaned_value) < $original_count) {
                update_option($option->option_name, $cleaned_value);
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
    
    if ($cleaned_count > 0) {
        echo '<div class="notice notice-success"><p><strong>Success!</strong> Removed ' . $cleaned_count . ' duplicate entries.</p></div>';
        
        if (!empty($fixed_options)) {
            echo '<h3>Cleanup Results:</h3>';
            echo '<table class="widefat">';
            echo '<thead><tr><th>Option Name</th><th>Duplicates Removed</th><th>Entries Remaining</th></tr></thead>';
            echo '<tbody>';
            foreach ($fixed_options as $fix) {
                echo '<tr>';
                echo '<td>' . esc_html($fix['name']) . '</td>';
                echo '<td style="color: red;">' . $fix['removed'] . '</td>';
                echo '<td style="color: green;">' . $fix['remaining'] . '</td>';
                echo '</tr>';
            }
            echo '</tbody></table>';
        }
        
        update_option('plyrae_cleanup_completed', current_time('mysql'), false);
        echo '<div class="notice notice-info"><p><strong>Cleanup completed!</strong> You can now remove this cleanup code.</p></div>';
        
    } else {
        echo '<div class="notice notice-info"><p>No duplicates found to remove.</p></div>';
    }
}
?>
