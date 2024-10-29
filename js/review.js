jQuery(document).ready(function ($) {
    $('.ai-quiz-notice-dismiss-permanently').click(function (e) {
        e.preventDefault();
        var data = {
            'action': 'ai_quiz_dismiss_review',
            'ai_quiz_review_dismiss': 1
        };
        $.post('admin-ajax.php', data, function (response) {
            $('#ai_quiz-review-notice').hide();
        });
    });
    $('.ai-quiz-notice-dismiss-temporarily').click(function (e) {
        e.preventDefault();
        var data = {
            'action': 'ai_quiz_dismiss_review',
            'ai_quiz_review_later': 1
        };
        $.post('admin-ajax.php', data, function (response) {
            $('#ai_quiz-review-notice').hide();
        });
    });
});
