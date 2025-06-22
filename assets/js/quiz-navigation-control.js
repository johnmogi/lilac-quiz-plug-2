jQuery(document).ready(function($) {
    // Check if this is a quiz page with enforce_hint enabled
    if (typeof lilacQuizSidebar !== 'undefined' && lilacQuizSidebar.enforceHint) {
        // Add class to body to trigger the CSS rules
        $('body').addClass('quiz-enforce-hint');
        
        // Remove any existing notice if present
        $('.hint-navigation-notice').remove();
        
        // Prevent keyboard navigation
        $('.wpProQuiz_reviewQuestion').on('click', 'li', function(e) {
            if ($('body').hasClass('quiz-enforce-hint')) {
                e.preventDefault();
                e.stopPropagation();
                return false;
            }
        });
    }
});
