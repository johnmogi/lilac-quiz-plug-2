<?php
/**
 * Template for quizzes with sidebar
 *
 * Based on the LearnDash quiz template but with added sidebar
 *
 * @package Lilac_Quiz_Sidebar
 */

if (!defined('ABSPATH')) {
    exit;
}

// Include theme header
get_header();

// Get quiz ID and settings
$quiz_id = get_the_ID();
$enforce_hint = get_post_meta($quiz_id, '_ld_quiz_enforce_hint', true);
$has_sidebar = get_post_meta($quiz_id, '_ld_quiz_toggle_sidebar', true);

// Add body class for sidebar
add_filter('body_class', function($classes) use ($has_sidebar) {
    $classes[] = 'single-sfwd-quiz';
    if ($has_sidebar) {
        $classes[] = 'quiz-has-sidebar';
    }
    return $classes;
});

// Enqueue necessary scripts
add_action('wp_enqueue_scripts', function() use ($quiz_id, $has_sidebar, $enforce_hint) {
    // Load LearnDash assets
    if (function_exists('learndash_asset_enqueue_scripts')) {
        learndash_asset_enqueue_scripts();
    }
    
    // Enqueue the main quiz script
    wp_enqueue_script(
        'lilac-quiz-sidebar',
        plugins_url('assets/js/quiz-sidebar-media.js', dirname(__FILE__)),
        array('jquery'),
        filemtime(plugin_dir_path(dirname(__FILE__)) . 'assets/js/quiz-sidebar-media.js'),
        true
    );
    
    // Localize script with quiz data
    wp_localize_script('lilac-quiz-sidebar', 'lilacQuizSidebar', array(
        'ajaxurl' => admin_url('admin-ajax.php'),
        'quizId' => $quiz_id,
        'nonce' => wp_create_nonce('lilac_quiz_sidebar_nonce'),
        'hasSidebar' => $has_sidebar,
        'enforceHint' => $enforce_hint
    ));
}, 20);
?>

<main id="primary" class="site-main">
    <div class="quiz-container <?php echo $has_sidebar ? 'quiz-with-sidebar' : ''; ?>">
        <div class="quiz-content">
            <?php
            if (have_posts()) :
                while (have_posts()) : the_post();
                    ?>
                    <article id="post-<?php the_ID(); ?>" <?php post_class('sfwd-quiz'); ?>>
                        <header class="entry-header">
                            <h1 class="entry-title"><?php the_title(); ?></h1>
                        </header>
                        <div class="entry-content">
                            <div id="quiz-content-wrapper">
                                <?php 
                                // Output the quiz content using LearnDash shortcode
                                echo do_shortcode('[ld_quiz quiz_id="' . $quiz_id . '"]');
                                
                                // Also output the content directly in case shortcode doesn't work
                                $quiz_content = get_the_content();
                                if (!empty($quiz_content)) {
                                    echo '<div class="quiz-content-fallback">';
                                    echo apply_filters('the_content', $quiz_content);
                                    echo '</div>';
                                }
                                ?>
                            </div>
                        </div>
                    </article>
                    <?php
                endwhile;
            endif;
            ?>
        </div><!-- .quiz-content -->
        
        <?php if ($has_sidebar) : ?>
            <aside id="quiz-context" class="ld-quiz-sidebar">
                <div id="question-media">
                    <div class="media-content question-media-image">
                        <img src="<?php echo esc_url(plugins_url('assets/images/placeholder-quiz.jpg', dirname(__FILE__))); ?>" 
                             alt="<?php esc_attr_e('Loading question media...', 'lilac-quiz-sidebar'); ?>" 
                             class="fallback-image"
                             style="max-width: 100%; height: auto; display: none;">
                    </div>
                    <div class="media-loading">
                        <p><?php _e('Loading question...', 'lilac-quiz-sidebar'); ?></p>
                    </div>
                    <div class="media-error" style="display: none;">
                        <p><?php _e('Media could not be loaded', 'lilac-quiz-sidebar'); ?></p>
                    </div>
                </div>
                
                <?php if ($enforce_hint) : ?>
                    <div class="quiz-hint-container" style="display: none;">
                        <h3><?php _e('Need a hint?', 'lilac-quiz-sidebar'); ?></h3>
                        <div class="hint-content"></div>
                    </div>
                <?php endif; ?>
            </aside>
        <?php endif; ?>
    </div>
</main>

<style>
.quiz-with-sidebar {
    display: flex;
    gap: 20px;
}
.quiz-content {
    flex: 1;
    min-width: 0;
}
.ld-quiz-sidebar {
    width: 300px;
    background: #f5f5f5;
    padding: 20px;
    border-radius: 4px;
}
</style>

<script type="text/javascript">
// Debug script to check if template is loaded
console.log('Lilac Quiz Sidebar: Template loaded');
console.log('Quiz ID:', <?php echo json_encode($quiz_id); ?>);
console.log('Has Sidebar:', <?php echo $has_sidebar ? 'true' : 'false'; ?>);
console.log('Enforce Hint:', <?php echo $enforce_hint ? 'true' : 'false'; ?>);

// Check if lilacQuizSidebar is defined
jQuery(document).ready(function($) {
    console.log('jQuery is ready');
    
    // Check if our localized script is available
    if (typeof lilacQuizSidebar !== 'undefined') {
        console.log('lilacQuizSidebar is defined:', lilacQuizSidebar);
    } else {
        console.error('lilacQuizSidebar is not defined!');
    }
    
    // Check if LearnDash is available
    if (typeof LearnDashData !== 'undefined') {
        console.log('LearnDash is loaded');
    } else {
        console.warn('LearnDash not detected on the page');
    }
});
</script>

<?php
// Include theme footer
get_footer();
