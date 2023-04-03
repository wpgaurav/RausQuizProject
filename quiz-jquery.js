jQuery(document).ready(function($) {
  // Get all the quiz answers
  var quizAnswers = $('.quiz-answer');

  // Add click event listeners to each answer
  quizAnswers.on('click', function() {
    // Get the parent of the currently clicked <li>
    var currentQuizAnswers = $(this).closest('.quiz-answers');

    // If the .answered class already exists on the parent, don't execute the rest of the code
    if (currentQuizAnswers.hasClass('answered')) {
      return;
    }

    // Remove any existing .chosen-answer classes within the current quiz answer container
    currentQuizAnswers.children('.quiz-answer').removeClass('chosen-answer');

    // Add .chosen-answer class to the clicked element
    $(this).addClass('chosen-answer');

    // Add .answered class to the current quiz answer container
    currentQuizAnswers.addClass('answered');
  });

  $('.show-answer').on('click', function(e) {
    e.preventDefault();
    $(this).siblings('.correct-answer-text').show();
  });
});