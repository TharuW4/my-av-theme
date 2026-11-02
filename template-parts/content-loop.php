<?php

/**
 * AnimeVerze: Anime Poster Card
 * Used on homepage, archives and listing sections.
 */

$quality = get_post_meta(
    get_the_ID(),
    '_quality',
    true
);

$rating = get_post_meta(
    get_the_ID(),
    '_rating',
    true
);

/*
=========================================================
FRONTEND CATEGORY LABEL
=========================================================
Old WordPress category names are preserved internally.
Cartoon Series -> Anime Series
Cartoon Movies -> Anime Movie
=========================================================
*/

$anime_category_label = '';
$categories = get_the_category();
if (!empty($categories)) {
    $category_name = $categories[0]->name;
    if (
        strtolower($category_name) !==
        'uncategorized'
    ) {
        if (
            strtolower($category_name) ===
            'anime series'
        ) {
            $anime_category_label =
                'Anime Series';
        } elseif (
            strtolower($category_name) ===
            'anime movies'
        ) {
            $anime_category_label =
                'Anime Movie';
        } else {

            $anime_category_label =
                $category_name;
        }
    }
}

?>

<div
    id="post-<?php the_ID(); ?>"
    <?php
    post_class(
        'movie-card-vertical'
    );
    ?>
>
    <a
        class="thumbnail-link"
        href="<?php the_permalink(); ?>"
        aria-label="<?php echo esc_attr(get_the_title()); ?>"
    >
        <div class="thumbnail-wrap">

            <!-- =============================================
                 POSTER
            ============================================== -->

            <?php if (has_post_thumbnail()) : ?>
                <?php
                the_post_thumbnail(
                    'streamlk_vertical_poster',
                    array(
                        'loading'  => 'lazy',
                        'decoding' => 'async',
                        'sizes'    => '(max-width: 700px) 33vw, (max-width: 992px) 25vw, 250px',
                        'alt'      => esc_attr(get_the_title()),
                    )
                );

                ?>
            <?php else : ?>
                <img
                    src="<?php
                    echo esc_url(
                        get_template_directory_uri()
                        .
                        '/assets/img/no-poster.jpg'
                    );
                    ?>"
                    alt="<?php
                    echo esc_attr(
                        get_the_title()
                    );
                    ?>"
                    loading="lazy"
                    decoding="async"
                    width="300"
                    height="450"
                >
            <?php endif; ?>

            <!-- =============================================
                 QUALITY
            ============================================== -->

            <?php if (!empty($quality)) : ?>
                <div class="movie-quality">
                    <?php
                    echo esc_html(
                        $quality
                    );
                    ?>
                </div>
            <?php endif; ?>

            <!-- =============================================
                 RATING
            ============================================== -->

            <?php if (!empty($rating)) : ?>
                <div class="movie-rating">
                    <i
                        class="fa fa-star"
                        aria-hidden="true"
                    ></i>
                    <span>
                        <?php
                        echo esc_html(
                            $rating
                        );
                        ?>
                    </span>
                </div>
            <?php endif; ?>

            <!-- =============================================
                 TITLE + TYPE
            ============================================== -->

            <div class="movie-details-overlay">
                <h2 class="entry-title">
                    <?php
                    the_title();
                    ?>
                </h2>

                <?php if (!empty($anime_category_label)) : ?>
                    <span class="movie-category">
                        <?php
                        echo esc_html(
                            $anime_category_label
                        );
                        ?>
                    </span>
                <?php endif; ?>
            </div>
        </div>
    </a>
</div>