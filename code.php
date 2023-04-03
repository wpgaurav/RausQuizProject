<?php
// From this line onwards, we have added Quiz and Questions Module.
// Every code is quoted with essential hints
 // Register quiz custom post type
function create_quiz_post_type() {
  $labels = array(
    'name' => __( 'Quizzes' ),
    'singular_name' => __( 'Quiz' )
  );
  $args = array(
    'labels' => $labels,
    'public' => true,
    'has_archive' => true,
    'rewrite' => array('slug' => 'quiz'),
    'supports' => array('title', 'editor')
  );
  register_post_type( 'quiz', $args );
}
add_action( 'init', 'create_quiz_post_type' );

// Register quiz taxonomy
function create_quiz_taxonomy() {
  $labels = array(
    'name' => __( 'Question Sections' ),
    'singular_name' => __( 'Question Section' )
  );
  $args = array(
    'labels' => $labels,
    'public' => true,
    'hierarchical' => true
  );
  register_taxonomy( 'quiz_category', 'quiz', $args );
}
add_action( 'init', 'create_quiz_taxonomy' );

// Add custom fields for quiz question and answers
function add_quiz_custom_fields() {
  add_meta_box( 'quiz_fields', 'Quiz Fields', 'display_quiz_fields', 'quiz', 'normal', 'default' );
}
add_action( 'add_meta_boxes', 'add_quiz_custom_fields' );

function display_quiz_fields( $post ) {
  wp_nonce_field( basename( __FILE__ ), 'quiz_fields_nonce' );
  $question = get_post_meta( $post->ID, 'question', true );
  $answer1 = get_post_meta( $post->ID, 'answer1', true );
  $answer2 = get_post_meta( $post->ID, 'answer2', true );
  $answer3 = get_post_meta( $post->ID, 'answer3', true );
  $answer4 = get_post_meta( $post->ID, 'answer4', true );
  $correct_answer = get_post_meta( $post->ID, 'correct_answer', true );
  ?>
  <p>
    <label for="question">Question</label>
    <input type="text" name="question" id="question" value="<?php echo $question; ?>" />
  </p>
  <p>
    <label for="answer1">Option A</label>
    <input type="text" name="answer1" id="answer1" value="<?php echo $answer1; ?>" />
  </p>
  <p>
    <label for="answer2">Option B</label>
    <input type="text" name="answer2" id="answer2" value="<?php echo $answer2; ?>" />
  </p>
  <p>
    <label for="answer3">Option C</label>
    <input type="text" name="answer3" id="answer3" value="<?php echo $answer3; ?>" />
  </p>
  <p>
    <label for="answer4">Option D</label>
    <input type="text" name="answer4" id="answer4" value="<?php echo $answer4; ?>" />
    </p>
  <p>
    <label for="correct_answer">Correct Answer</label>
    <select name="correct_answer" id="correct_answer">
      <option value="A" <?php selected( $correct_answer, 'A' ); ?>>Option A</option>
      <option value="B" <?php selected( $correct_answer, 'B' ); ?>>Option B</option>
      <option value="C" <?php selected( $correct_answer, 'C' ); ?>>Option C</option>
      <option value="D" <?php selected( $correct_answer, 'D' ); ?>>Option D</option>
    </select>
  </p>
  <?php
}
// Let's add these to database and make sure only editors and above can edit these
function save_quiz_custom_fields( $post_id ) {
  if ( ! isset( $_POST['quiz_fields_nonce'] ) || ! wp_verify_nonce( $_POST['quiz_fields_nonce'], basename( __FILE__ ) ) ) {
return;
}
if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
return;
}
if ( isset( $_POST['post_type'] ) && 'quiz' == $_POST['post_type'] ) {
if ( ! current_user_can( 'edit_page', $post_id ) ) {
return;
}
} else {
if ( ! current_user_can( 'edit_post', $post_id ) ) {
return;
}
}
$question = sanitize_text_field( $_POST['question'] );
update_post_meta( $post_id, 'question', $question );
$answer1 = sanitize_text_field( $_POST['answer1'] );
update_post_meta( $post_id, 'answer1', $answer1 );
$answer2 = sanitize_text_field( $_POST['answer2'] );
update_post_meta( $post_id, 'answer2', $answer2 );
$answer3 = sanitize_text_field( $_POST['answer3'] );
update_post_meta( $post_id, 'answer3', $answer3 );
$answer4 = sanitize_text_field( $_POST['answer4'] );
update_post_meta( $post_id, 'answer4', $answer4 );
$correct_answer = sanitize_text_field( $_POST['correct_answer'] );
update_post_meta( $post_id, 'correct_answer', $correct_answer );
}
add_action( 'save_post', 'save_quiz_custom_fields', 10, 1 );

// Trying to create a shortcode here

function quiz_shortcode( $atts ) {
  $atts = shortcode_atts( array(
    'category' => ''
  ), $atts );
  $args = array(
    'post_type' => 'quiz',
    'post_status' => 'publish',
    'posts_per_page' => -1,
    'order' => 'ASC',
    'orderby' => 'menu_order',
    'tax_query' => array(
      array(
        'taxonomy' => 'quiz_category',
        'field' => 'slug',
        'terms' => $atts['category']
      )
    )
  );
  $quiz_query = new WP_Query( $args );
  ob_start();
  ?>
  <div class="quiz">
    <?php while ( $quiz_query->have_posts() ) : $quiz_query->the_post(); ?>
      <div class="quiz-question">
        <p><strong><?php echo get_post_meta( get_the_ID(), 'question', true ); ?></strong></p>
<ul class="quiz-answers">
<li class="quiz-answer <?php echo get_post_meta( get_the_ID(), 'correct_answer', true ) == 'A' ? 'correct-answer' : 'incorrect-answer'; ?>"><span class="option-circle">A</span> <span class="option-text"><?php echo get_post_meta( get_the_ID(), 'answer1', true ); ?></span></li>
<li class="quiz-answer <?php echo get_post_meta( get_the_ID(), 'correct_answer', true ) == 'B' ? 'correct-answer' : 'incorrect-answer'; ?>"><span class="option-circle">B</span> <span class="option-text"><?php echo get_post_meta( get_the_ID(), 'answer2', true ); ?></span></li>
<li class="quiz-answer <?php echo get_post_meta( get_the_ID(), 'correct_answer', true ) == 'C' ? 'correct-answer' : 'incorrect-answer'; ?>"><span class="option-circle">C</span> <span class="option-text"><?php echo get_post_meta( get_the_ID(), 'answer3', true ); ?></span></li>
<li class="quiz-answer <?php echo get_post_meta( get_the_ID(), 'correct_answer', true ) == 'D' ? 'correct-answer' : 'incorrect-answer'; ?>"><span class="option-circle">D</span> <span class="option-text"><?php echo get_post_meta( get_the_ID(), 'answer4', true ); ?></span></li>
</ul>
<br/>
<a href="#" class="show-answer"><strong>Show Answer</strong></a>
<div class="correct-answer-text">The correct answer is <?php echo get_post_meta( get_the_ID(), 'correct_answer', true ); ?>.<br>
<div class="explanation"><?php the_content();?></div>		  
</div>
</div>
<?php endwhile; ?>

  </div>
  <?php
  wp_reset_postdata();
  return ob_get_clean();
}
add_shortcode( 'quiz', 'quiz_shortcode' );