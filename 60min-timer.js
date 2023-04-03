jQuery(document).ready(function($) {
    // Function to disable the quiz
    function disableQuiz() {
      $('.quiz-answer').off('click'); // Remove click event from quiz answers
      $('.quiz-answers').addClass('disabled'); // Add a 'disabled' class to the quiz answers
    }
  
    // Countdown timer
    var countdown = 60 * 60; // 60 minutes in seconds
    var countdownDisplay = $('#countdown-timer');
  
    function updateCountdown() {
      var minutes = Math.floor(countdown / 60);
      var seconds = countdown % 60;
      countdownDisplay.text(minutes + ':' + (seconds < 10 ? '0' : '') + seconds);
      countdown--;
  
      if (countdown < 0) {
        clearInterval(countdownInterval);
        disableQuiz();
      }
    }
  
    var countdownInterval = setInterval(updateCountdown, 1000);
  
    // ... Rest of the code ...
  });