<?php
/**
 * AnimeVerze: Custom Dark Comments Template
 */

if ( post_password_required() ) {
    return;
}

if ( ! function_exists( 'animeverze_comment_item' ) ) {
    function animeverze_comment_item( $comment, $args, $depth ) {
        $GLOBALS['comment'] = $comment;

        $user_id  = isset( $comment->user_id ) ? (int) $comment->user_id : 0;
        $is_admin = $user_id > 0 && user_can( $user_id, 'manage_options' );
        ?>
        <li <?php comment_class( $is_admin ? 'av-admin-comment' : 'av-user-comment', $comment ); ?> id="li-comment-<?php comment_ID(); ?>">
            <article id="comment-<?php comment_ID(); ?>" class="comment-body">

                <div class="comment-meta">
                    <?php if ( $is_admin ) : ?>
                        <div class="comment-author vcard av-admin-author">
                            <cite class="fn">Admin</cite>
                        </div>
                    <?php else : ?>
                        <div class="av-comment-avatar">
                            <?php echo get_avatar( $comment, 50 ); ?>
                        </div>

                        <div class="av-comment-user-meta">
                            <div class="comment-author vcard">
                                <cite class="fn"><?php echo esc_html( get_comment_author( $comment ) ); ?></cite>
                            </div>
                            <div class="comment-metadata">
                                <?php echo esc_html( get_comment_date( 'M j, Y', $comment ) ); ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>

                <?php if ( '0' === $comment->comment_approved ) : ?>
                    <p class="comment-awaiting-moderation">Your comment is awaiting approval.</p>
                <?php endif; ?>

                <div class="comment-content">
                    <?php comment_text(); ?>
                </div>

                <div class="reply">
                    <?php
                    comment_reply_link(
                        array_merge(
                            $args,
                            array(
                                'depth'      => $depth,
                                'max_depth'  => $args['max_depth'],
                                'reply_text' => 'Reply',
                            )
                        ),
                        $comment
                    );
                    ?>
                </div>
            </article>
        <?php
    }
}
?>

<div id="comments" class="comments-area">

    <style>
        .comments-area { color: #ccc; }
        .comments-title { font-size: 1.5rem; color: #fff; margin-bottom: 30px; border-left: 4px solid var(--av-blue); padding-left: 15px; }

        /* Posted Comments */
        .comment-list { list-style: none; padding: 0; margin: 0 0 30px; }
        .comment-list li.comment { list-style: none; margin: 0 0 14px; padding: 0; background: transparent; border: 0; }
        .comment-list .comment-body { background: #1a1a1a; padding: 20px; border-radius: 8px; border: 1px solid #333; }
        .comment-meta { display: flex; align-items: center; gap: 15px; margin-bottom: 15px; }
        .av-comment-avatar img { display: block; width: 50px; height: 50px; border-radius: 50%; border: 2px solid var(--av-blue); }
        .av-comment-user-meta { display: flex; flex-direction: column; gap: 3px; min-width: 0; }
        .av-comment-user-meta .comment-author,
        .av-comment-user-meta .comment-metadata {
            margin:0 !important;
            padding:0 !important;
            position:static !important;
        }
        .comment-author cite { color: #fff; font-style: normal; font-weight: 700; font-size: 16px; }
        .comment-metadata { color: #888; font-size: 12px; }
        .comment-content p { line-height: 1.6; color: #bbb; margin: 0 0 10px; }
        .comment-content p:last-child { margin-bottom: 0; }
        .reply { margin-top: 12px; }
        .reply a { color: var(--av-blue); text-decoration: none; font-weight: 700; font-size: 14px; text-transform: uppercase; }
        .comment-awaiting-moderation { color: #facc15; font-size: 12px; }

        /* Admin reply: frontend identity shows only Admin */
        .av-admin-comment > .comment-body { border-color: rgba(56,189,248,.35); }
        .av-admin-author cite { display: inline-block; padding: 5px 10px; background: var(--av-blue); color: #06131b; border-radius: 5px; font-size: 11px; font-weight: 900; text-transform: uppercase; }
        .comment-list .children { list-style: none; margin: 12px 0 0 34px; padding: 0 0 0 14px; border-left: 1px solid rgba(56,189,248,.22); }

        /* Comment Form - kept the same design */
        .comment-respond { background: #111; padding: 30px; border-radius: 10px; border: 1px solid #222; margin-top: 40px; }
        .comment-reply-title { color: #fff; font-size: 1.3rem; margin-bottom: 20px; }
        .comment-form input, .comment-form textarea {
            width: 100%; background: #222; border: 1px solid #333; border-radius: 5px; padding: 12px; color: #fff; margin-bottom: 15px; outline: none; transition: 0.3s;
        }
        .comment-form input:focus, .comment-form textarea:focus { border-color: var(--av-blue); background: #2a2a2a; }
        .form-submit input[type="submit"] {
            background: var(--av-blue); color: #fff; border: none; padding: 12px 30px; border-radius: 5px; font-weight: 700; cursor: pointer; transition: 0.3s; text-transform: uppercase;
        }
        .form-submit input[type="submit"]:hover { background: var(--av-blue-hover); transform: scale(1.05); }
        .logged-in-as a { color: var(--av-blue); }

        @media (max-width: 768px) {
            .comment-list .comment-body { padding: 14px; }
            .av-comment-avatar img { width: 42px; height: 42px; }
            .comment-list .children { margin-left: 12px; padding-left: 10px; }
        }
    </style>

    <?php if ( have_comments() ) : ?>
        <h2 class="comments-title">
            <?php
            $comments_number = get_comments_number();

            if ( '1' === $comments_number ) {
                printf( esc_html__( '1 Response to &ldquo;%s&rdquo;', 'videoclub' ), get_the_title() );
            } else {
                printf(
                    esc_html( _nx( '%1$s Response to &ldquo;%2$s&rdquo;', '%1$s Responses to &ldquo;%2$s&rdquo;', $comments_number, 'comments title', 'videoclub' ) ),
                    number_format_i18n( $comments_number ),
                    get_the_title()
                );
            }
            ?>
        </h2>

        <ol class="comment-list">
            <?php
            wp_list_comments(
                array(
                    'style'       => 'ol',
                    'short_ping'  => true,
                    'avatar_size' => 50,
                    'callback'    => 'animeverze_comment_item',
                )
            );
            ?>
        </ol>

        <?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : ?>
            <nav id="comment-nav-below" class="navigation comment-navigation">
                <div class="nav-links">
                    <div class="nav-previous"><?php previous_comments_link( esc_html__( 'Older Comments', 'videoclub' ) ); ?></div>
                    <div class="nav-next"><?php next_comments_link( esc_html__( 'Newer Comments', 'videoclub' ) ); ?></div>
                </div>
            </nav>
        <?php endif; ?>
    <?php endif; ?>

    <?php if ( ! comments_open() && get_comments_number() && post_type_supports( get_post_type(), 'comments' ) ) : ?>
        <p class="no-comments"><?php esc_html_e( 'Comments are closed.', 'videoclub' ); ?></p>
    <?php endif; ?>

    <?php
    /* Existing Comment Form - unchanged */
    $comment_args =
		array(

			'title_reply' =>
				'Leave a Reply',

			'label_submit' =>
				'Post Comment',

			'class_submit' =>
				'submit-btn',

		);

    $animeverze_comment_fields_filter = function( $fields ) {

        unset(
            $fields['url'],
            $fields['cookies']
        );

        return $fields;
    };

    add_filter(
        'comment_form_default_fields',
        $animeverze_comment_fields_filter
    );

    comment_form( $comment_args );

    remove_filter(
        'comment_form_default_fields',
        $animeverze_comment_fields_filter
    );
    ?>

</div>
