<div class="entry-meta" style="font-size: 12px; color: #888; margin-top: 5px;">

    <?php if ( is_single() ) : ?>
        <span class="entry-category" style="color: var(--primary-red); font-weight: 700;"><?php videoclub_first_category(); ?></span>
    <?php endif; ?>

    <?php if ( (!is_category()) && (!is_single()) ) : ?>
        <span class="entry-category"><?php videoclub_first_category(); ?></span>
    <?php endif; ?>

    <span class="sep" style="margin: 0 5px;">&middot;</span>
    <span class='entry-comment'>
        <i class="fa fa-comments" style="margin-right: 3px;"></i>
        <?php comments_popup_link( __('0','videoclub'), __('1','videoclub'), __('%','videoclub'), 'comments-link', ''); ?>
    </span>
    
</div>```