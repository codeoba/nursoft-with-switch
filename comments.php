<?php
/**
 * The template for displaying comments
 *
 * This is the template that displays the area of the page that contains both the current comments
 * and the comment form.
 *
 * @author  Mohamed Nurdin Mgaza <codeoba@gmail.com>
 * @country Tanzania | +687001775
 * @version 1.6.0
 * @package Nursoft
 */

/*
 * If the current post is password protected and
 * the visitor has not yet entered the password we will
 * return early without loading the comments.
 */
if ( post_password_required() ) {
    return;
}
?>

<div id="comments" class="comments-area">

    <?php if ( have_comments() ) : ?>
        <h5 class="comments-title soft_section_title">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" style="width:18px;height:18px;color:var(--accent-blue);"><path d="M20 2H4c-1.1 0-2 .9-2 2v18l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm0 14H6l-2 2V4h16v12z"/></svg>
            <span>
                <?php
                $comment_count = get_comments_number();
                if ( 1 === $comment_count ) {
                    printf(
                        /* translators: 1: title. */
                        esc_html__( 'Join the Discussion (1 Comment)', 'nursoft' )
                    );
                } else {
                    printf(
                        /* translators: 1: comment count number, 2: title. */
                        esc_html( sprintf( _n( 'Join the Discussion (%s Comment)', 'Join the Discussion (%s Comments)', $comment_count, 'nursoft' ), number_format_i18n( $comment_count ) ) )
                    );
                }
                ?>
            </span>
        </h5>

        <ul class="comment-list">
            <?php
            wp_list_comments( array(
                'style'       => 'ul',
                'short_ping'  => true,
                'avatar_size' => 48,
                'max_depth'   => 3,
            ) );
            ?>
        </ul>

        <?php
        the_comments_navigation( array(
            'prev_text' => '&larr; ' . __( 'Older Comments', 'nursoft' ),
            'next_text' => __( 'Newer Comments', 'nursoft' ) . ' &rarr;',
        ) );
        ?>

    <?php endif; // Check for have_comments(). ?>

    <?php
    // If comments are closed and there are comments, let's leave a little note, shall we?
    if ( ! comments_open() && get_comments_number() && post_type_supports( get_post_type(), 'comments' ) ) :
        ?>
        <p class="no-comments"><?php esc_html_e( 'Comments are closed.', 'nursoft' ); ?></p>
    <?php endif; ?>

    <?php
    // Customizing the comment form arguments
    $comment_form_args = array(
        'title_reply'          => __( 'Leave a Reply', 'nursoft' ),
        'title_reply_to'       => __( 'Leave a Reply to %s', 'nursoft' ),
        'comment_notes_before' => '',
        'comment_field'        => '<div class="comment-form-field comment-form-comment"><textarea id="comment" name="comment" cols="45" rows="4" placeholder="' . esc_attr__( 'Write your reply or review here...', 'nursoft' ) . '" required></textarea></div>',
        'fields'               => array(
            'author' => '<div class="comment-fields-row"><div class="comment-form-field comment-form-author"><input id="author" name="author" type="text" placeholder="' . esc_attr__( 'Name *', 'nursoft' ) . '" value="" required /></div>',
            'email'  => '<div class="comment-form-field comment-form-email"><input id="email" name="email" type="email" placeholder="' . esc_attr__( 'Email *', 'nursoft' ) . '" value="" required /></div></div>',
        ),
        'submit_button'        => '<button name="%1$s" type="submit" id="%2$s" class="comment_submit_btn download_btn"><span>%4$s</span></button>',
        'class_submit'         => 'submit-btn-wrap', // wrap class
    );

    comment_form( $comment_form_args );
    ?>

</div><!-- #comments -->
