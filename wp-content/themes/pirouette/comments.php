<?php if ( post_password_required() ) return; ?>
<div id="comments" class="comments">
	<?php if ( have_comments() ) : ?>
    <h3 class="comments-title">
        <?php
            printf( _n( 'One comment for &ldquo;%2$s&rdquo;', '%1$s comments on &ldquo;%2$s&rdquo;', get_comments_number(), 'pirouette' ),
                    number_format_i18n( get_comments_number() ),
                    '<strong>' . get_the_title() . '</strong>' );
        ?>
    </h3>
    <?php wp_list_comments( array( 'walker' => new PirouetteComments() ) ); ?>

    <?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : ?>
    <nav id="comment-nav-below" class="navigation">
        <h5 class="assistive-text section-heading color-primary"><?php esc_html_e( 'Comment navigation', 'pirouette' ); ?></h5>
        <div class="nav-previous"><?php previous_comments_link( esc_html__( '&larr; Older Comments', 'pirouette' ) ); ?></div>
        <div class="nav-next"><?php next_comments_link( esc_html__( 'Newer Comments &rarr;', 'pirouette' ) ); ?></div>
    </nav>
    <?php endif;

    if ( ! comments_open() && get_comments_number() ) : ?>
    <p class="nocomments"><?php esc_html_e( 'Comments are closed.' , 'pirouette' ); ?></p>

    <?php endif; endif;

	$pirouette_req = get_option( 'require_name_email' );

	$pirouette_comments_args = array(
        /*:: Title*/
        'title_reply' => esc_html__('Leave Comment','pirouette'),
        /*:: After Notes*/
        'comment_notes_after'  => '',
        /*:: Before Notes*/
        'comment_notes_before' => '',
        /*:: Submit*/
        'label_submit' => esc_html__( 'Submit Comment' , 'pirouette'),
        /*:: Logged In*/
        'logged_in_as' => '<p>'. sprintf(esc_html__('You are logged in as %1$s. %2$sLog out &raquo;%3$s', 'pirouette'), '<a href="'.get_option('siteurl').'/wp-admin/profile.php">'.$user_identity.'</a>', '<a href="'.(function_exists('wp_logout_url') ? wp_logout_url(get_permalink()) : get_option('siteurl').'/wp-login.php?action=logout" title="').'" title="'.esc_html__('Log Out', 'pirouette').'">', '</a>') . '</p>',
        /*:: Comment Field*/
        'comment_field' => '<div class="comment-form-content form-group"><label for="comment" class="input-textarea sr-only">' . esc_html__('<b>Comment</b> ( * )','pirouette'). '</label>
		<textarea class="required form-control" name="comment" id="comment" rows="4" placeholder="'.esc_html__( 'Comment', 'pirouette' ).'"></textarea></div>',

		'fields' => apply_filters( 'comment_form_default_fields', array(

		    'author' =>
		      '<div class="form-group row">' .
		      '<div class="comment-form-author col-lg-6" '.( $pirouette_req ? "data-required" : null ).'>'.
		      '<label for="author" class="sr-only">'.esc_html__( 'Name', 'pirouette' ).'</label> ' .
		      '<input class="form-control" id="author" name="author" type="text" placeholder="'.esc_html__( 'Name', 'pirouette' ).'" value="' . esc_attr( $commenter['comment_author'] ) .'" size="30" /></div>',

		    'email' =>
		      '<div class="comment-form-email col-lg-6" '.( $pirouette_req ? "data-required" : null ).'><label for="email" class="sr-only">'.esc_html__( 'Email', 'pirouette' ).'</label> ' .
		      '<input class="form-control" id="email" name="email" type="text" placeholder="'.esc_html__( 'Email', 'pirouette' ).'" value="'. esc_attr(  $commenter['comment_author_email'] ) .
		      '" size="30" /></div></div>',

		    'url' =>
		      '<div class="form-group"><div class="comment-form-url"><label for="url" class="sr-only"><strong>' .
		      esc_html__( 'Website', 'pirouette' ) . '</strong></label>' .
		      '<input class="form-control" id="url" name="url" type="text" placeholder="'.esc_html__( 'Website', 'pirouette' ).'"  value="' . esc_attr( $commenter['comment_author_url'] ) .
		      '" size="30" /></div></div>'
		    )
		  ),
		);

	comment_form( $pirouette_comments_args );

	?>
</div>
