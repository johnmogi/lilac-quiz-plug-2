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

// Get global post object
global $post;
?>

<main id="primary" class="site-main">
    <div class="quiz-container quiz-with-sidebar">
        <div class="quiz-content">
            <?php
            if (have_posts()) :
                while (have_posts()) : the_post();
                    // Output quiz content
                    ?>
                    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                        <header class="entry-header">
                            <?php the_title('<h1 class="entry-title">', '</h1>'); ?>
                        </header>

                        <div class="entry-content">
                            <?php 
                            // Use the_content() directly - LearnDash hooks into this to render quizzes
                            // This is the most reliable way to render the quiz content
                            echo '<div id="quiz-content-wrapper">';
                            the_content(); 
                            echo '</div>';
                            ?>
                        </div>
                    </article>
                    <?php
                endwhile;
            endif;
            ?>
        </div>
        
        <aside id="quiz-context" class="ld-quiz-sidebar">
            <div id="question-media">
                <div class="media-content question-media-image">
                    <img src="/wp-content/uploads/2025/05/noPic.png" alt="<?php _e('No Media Available', 'lilac-quiz-sidebar'); ?>" class="fallback-image">
                </div>
                <div class="media-error">
                    <p><?php _e('Media could not be loaded', 'lilac-quiz-sidebar'); ?></p>
                </div>
            </div>
        </aside>
    </div>
</main>

<?php
// Include theme footer
get_footer();
