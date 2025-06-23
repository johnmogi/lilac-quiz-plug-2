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
    if ($has_sidebar) {
        $classes[] = 'quiz-has-sidebar';
    }
    return $classes;
});
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
                                // Use the LearnDash quiz shortcode
                                echo do_shortcode('[ld_quiz quiz_id="' . $quiz_id . '"]');
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
                             alt="" 
                             class="fallback-image"
                             style="max-width: 100%; height: auto;">
                    </div>
                    <div class="media-error" style="display: none;">
                        <p><?php _e('Media could not be loaded', 'lilac-quiz-sidebar'); ?></p>
                    </div>
                </div>
            </aside>
        <?php endif; ?>
    </div>
</main>

<?php
// Include theme footer
get_footer();
