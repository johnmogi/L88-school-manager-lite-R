<?php
/**
 * Debug script to test student user query
 */

// Load WordPress
require_once('../../../wp-load.php');

// Test the student manager query
$student_manager = School_Manager_Lite_Student_Manager::instance();
$students = $student_manager->get_student_users();

echo "<h2>Debug: Student User Query Results</h2>";
echo "<p>Total students found: " . count($students) . "</p>";

if (!empty($students)) {
    echo "<table border='1'>";
    echo "<tr><th>ID</th><th>Name</th><th>Email</th><th>Capabilities</th></tr>";
    
    foreach (array_slice($students, 0, 10) as $student) {
        $capabilities = get_user_meta($student->ID, 'edc_capabilities', true);
        if (empty($capabilities)) {
            $capabilities = get_user_meta($student->ID, 'wp_capabilities', true);
        }
        
        echo "<tr>";
        echo "<td>" . $student->ID . "</td>";
        echo "<td>" . esc_html($student->display_name) . "</td>";
        echo "<td>" . esc_html($student->user_email) . "</td>";
        echo "<td>" . esc_html(is_array($capabilities) ? print_r($capabilities, true) : $capabilities) . "</td>";
        echo "</tr>";
    }
    
    echo "</table>";
} else {
    echo "<p>No students found. Let's check the meta query...</p>";
    
    // Test direct WP_User_Query
    $user_query = new WP_User_Query(array(
        'meta_query' => array(
            'relation' => 'OR',
            array(
                'key' => 'edc_capabilities',
                'value' => 'לקוח',
                'compare' => 'LIKE'
            ),
            array(
                'key' => 'edc_capabilities', 
                'value' => 'תלמיד חינוך תעבורתי',
                'compare' => 'LIKE'
            ),
            array(
                'key' => 'edc_capabilities',
                'value' => 'תלמיד עצמאי', 
                'compare' => 'LIKE'
            ),
            array(
                'key' => 'edc_capabilities',
                'value' => 'school_student',
                'compare' => 'LIKE'
            )
        ),
        'fields' => 'all'
    ));
    
    $direct_results = $user_query->get_results();
    echo "<p>Direct WP_User_Query results: " . count($direct_results) . "</p>";
    
    if (!empty($direct_results)) {
        echo "<p>Sample user capabilities:</p>";
        $sample_user = $direct_results[0];
        $sample_caps = get_user_meta($sample_user->ID, 'edc_capabilities', true);
        echo "<pre>" . print_r($sample_caps, true) . "</pre>";
    }
}
?>
