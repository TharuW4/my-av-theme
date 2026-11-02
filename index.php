<?php

/**
 * AnimeVerze: Full-Width Home Template
 * Existing category slugs are preserved for compatibility.
 */

get_header();
?>

<div id="primary" class="content-area full-width clear">

    <main
        id="main"
        class="site-main clear animeverze-home"
        style="
            width: 100% !important;
            max-width: 100% !important;
            padding: 0 !important;
        ">

        <!-- =====================================================
             HERO SECTION
        ====================================================== -->

        <?php
        get_template_part('template-parts/hero', 'section');
        ?>

        <!-- =====================================================
             HOME CONTENT
        ====================================================== -->

        <div class="animeverze-home-container">

            <h1 class="screen-reader-text">
                AnimeVerze - Anime Movies and Series
            </h1>

            <!-- =================================================
                 TRENDING ANIME
            ================================================== -->

            <section class="movie-section anime-section">
                <div class="section-header">
                    <div class="section-title-wrap">
                        <span class="section-kicker">
                            Popular Today
                        </span>
                        <h2 class="section-title">
                            Popular Picks
                        </h2>
                    </div>

                    <span
                        class="view-all view-all-disabled"
                        aria-disabled="true">
                        View All
                    </span>
                </div>

                <div class="movie-grid">

                    <?php

                    /*
                    -------------------------------------------------
                    DAILY TRENDING CACHE
                    -------------------------------------------------
                    Existing category slugs are kept for compatibility:
                    - anime-movies
                    - anime-series
                    -------------------------------------------------
                    */

                    $trending_posts_ids = get_transient(
                        'daily_trending_ids'
                    );

                    if (false === $trending_posts_ids) {
                        $query_args = array(
                            'post_type'      => 'post',
                            'posts_per_page' => 100,
                            'fields'         => 'ids',
                            'category_name' => 'anime-movies,anime-series',
                            'post_status'    => 'publish',
                        );


                        $all_ids = get_posts($query_args);

                        if (!empty($all_ids)) {
                            shuffle($all_ids);

                            $trending_posts_ids = array_slice(
                                $all_ids,
                                0,
                                8
                            );

                            set_transient(
                                'daily_trending_ids',
                                $trending_posts_ids,
                                24 * HOUR_IN_SECONDS
                            );
                        }
                    }

                    if (!empty($trending_posts_ids)) {
                        $final_query = new WP_Query(
                            array(
                                'post__in'            => $trending_posts_ids,
                                'orderby'             => 'post__in',
                                'ignore_sticky_posts' => true,
                                'post_status'         => 'publish',
                                'posts_per_page'      => 8,
                                'no_found_rows'       => true,
                            )
                        );


                        if ($final_query->have_posts()) :
                            while ($final_query->have_posts()) :
                                $final_query->the_post();

                                get_template_part(
                                    'template-parts/content',
                                    'loop'
                                );
                            endwhile;
                            wp_reset_postdata();
                        endif;
                    } else {
                        echo '
                            <p class="no-posts">
                                Trending anime will appear here soon.
                            </p>
                        ';
                    }
                    ?>
                </div>
            </section>


            <!-- =================================================
                 LATEST ANIME
            ================================================== -->

            <section class="movie-section anime-section">
                <div class="section-header">
                    <div class="section-title-wrap">
                        <span class="section-kicker">
                            Fresh Releases
                        </span>
                        <h2 class="section-title">
                            Latest Anime
                        </h2>
                    </div>

                    <a
                        href="<?php echo esc_url(
                                    home_url(
                                        '/?s=&post_type=post&orderby=date&order=desc'
                                    )
                                ); ?>"
                        class="view-all">
                        View All
                    </a>
                </div>

                <div class="movie-grid">
                    <?php
                    $latest_query = new WP_Query(
                        array(
                            'post_type'           => 'post',
                            'posts_per_page'      => 8,
                            'post_status'         => 'publish',
                            'ignore_sticky_posts' => true,
                            'no_found_rows'       => true,
                        )
                    );

                    if ($latest_query->have_posts()) :
                        while ($latest_query->have_posts()) :
                            $latest_query->the_post();

                            get_template_part(
                                'template-parts/content',
                                'loop'
                            );

                        endwhile;
                        wp_reset_postdata();

                    else :

                        echo '
                            <p class="no-posts">
                                No anime has been added yet.
                            </p>
                        ';

                    endif;

                    ?>
                </div>
            </section>

            <!-- =================================================
                 ANIME MOVIES
            ================================================== -->

            <section class="movie-section anime-section">
                <div class="section-header">
                    <div class="section-title-wrap">
                        <span class="section-kicker">
                            Feature Films
                        </span>
                        <h2 class="section-title">
                            Anime Movies
                        </h2>
                    </div>


                    <a
                        href="<?php
                                echo esc_url(
                                    get_category_link(
                                        get_cat_ID('Anime Movies')
                                    )
                                );
                                ?>"
                        class="view-all">
                        View All
                    </a>
                </div>

                <div class="movie-grid">
                    <?php
                    $anime_movies_query = new WP_Query(
                        array(
                            'category_name'       => 'anime-movies',
                            'posts_per_page'      => 8,
                            'post_status'         => 'publish',
                            'ignore_sticky_posts' => true,
                            'no_found_rows'       => true,
                        )
                    );

                    if ($anime_movies_query->have_posts()) :
                        while (
                            $anime_movies_query->have_posts()
                        ) :
                            $anime_movies_query->the_post();

                            get_template_part(
                                'template-parts/content',
                                'loop'
                            );

                        endwhile;

                        wp_reset_postdata();

                    else :
                        echo '
                            <p class="no-posts">
                                Anime movies will appear here soon.
                            </p>
                        ';

                    endif;
                    ?>
                </div>
            </section>

            <!-- =================================================
                 ANIME SERIES
            ================================================== -->

            <section class="movie-section anime-section">
                <div class="section-header">
                    <div class="section-title-wrap">
                        <span class="section-kicker">
                            Watch Episodes
                        </span>
                        <h2 class="section-title">
                            Anime Series
                        </h2>
                    </div>

                    <a
                        href="<?php
                                echo esc_url(
                                    get_category_link(
                                        get_cat_ID('Anime Series')
                                    )
                                );
                                ?>"
                        class="view-all">
                        View All
                    </a>
                </div>

                <div class="movie-grid">
                    <?php
                    $anime_series_query = new WP_Query(
                        array(
                            'category_name'       => 'anime-series',
                            'posts_per_page'      => 8,
                            'post_status'         => 'publish',
                            'ignore_sticky_posts' => true,
                            'no_found_rows'       => true,
                        )
                    );

                    if ($anime_series_query->have_posts()) :
                        while (
                            $anime_series_query->have_posts()
                        ) :
                            $anime_series_query->the_post();

                            get_template_part(
                                'template-parts/content',
                                'loop'
                            );

                        endwhile;

                        wp_reset_postdata();

                    else :
                        echo '
                            <p class="no-posts">
                                Anime series will appear here soon.
                            </p>
                        ';

                    endif;
                    ?>
                </div>
            </section>
        </div>
    </main>
</div>

<style>
    /*
=========================================================
ANIMEVERZE HOME PAGE
=========================================================
*/

    .animeverze-home {
        background: #0F0F0F;
        color: #FFFFFF;
    }

    .animeverze-home-container {
        width: 94%;
        max-width: 1800px;
        margin: 0 auto;
        padding:
            45px 0 70px;
    }

    /*
=========================================================
SECTIONS
=========================================================
*/

    .anime-section {
        position: relative;
        margin:
            0 0 56px;
        padding: 0;
    }

    .anime-section:last-child {
        margin-bottom: 20px;
    }


    /*
=========================================================
SECTION HEADER
=========================================================
*/

    .anime-section .section-header {
        display: flex;
        align-items: flex-end;
        justify-content: space-between;
        gap: 20px;
        margin-bottom: 22px;
        padding-left: 14px;
        border-left:
            4px solid #38BDF8;
    }

    .section-title-wrap {
        display: flex;
        flex-direction: column;
        gap: 3px;
    }

    .section-kicker {
        display: block;
        margin: 0;
        color: #38BDF8;
        font-size: 10px;
        font-weight: 800;
        line-height: 1.2;
        letter-spacing: 1.3px;
        text-transform: uppercase;
    }

    .anime-section .section-title {
        margin: 0 !important;
        padding: 0 !important;
        color: #FFFFFF;
        font-size:
            clamp(20px,
                2vw,
                27px);
        font-weight: 800;
        line-height: 1.2;
        letter-spacing: -0.4px;
    }

    /*
=========================================================
VIEW ALL
=========================================================
*/

    .anime-section .view-all {
        flex-shrink: 0;
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        min-height: 32px;
        margin:
            0 0 2px !important;
        padding:
            7px 14px !important;
        float: none !important;
        background:
            rgba(56,
                189,
                248,
                0.10) !important;
        border:
            1px solid rgba(56,
                189,
                248,
                0.35) !important;
        border-radius:
            7px !important;
        color:
            #38BDF8 !important;
        font-size:
            10px !important;
        font-weight:
            800 !important;
        line-height:
            1 !important;
        letter-spacing:
            0.6px;
        text-transform:
            uppercase !important;
        text-decoration:
            none !important;
        transition:
            background 0.2s ease,
            border-color 0.2s ease,
            color 0.2s ease,
            transform 0.2s ease !important;
    }

    .anime-section .view-all:hover {
        background:
            #38BDF8 !important;
        border-color:
            #38BDF8 !important;
        color:
            #071018 !important;
        transform:
            translateY(-1px);
    }

    .anime-section .view-all-disabled,
    .anime-section .view-all-disabled:hover {
        background:
            rgba(255,
                255,
                255,
                0.04) !important;
        border-color:
            rgba(255,
                255,
                255,
                0.10) !important;
        color:
            #6B7280 !important;
        cursor:
            default;
        pointer-events:
            none;
        transform:
            none;
    }


    /*
=========================================================
NO POSTS
=========================================================
*/

    .anime-section .no-posts {
        grid-column:
            1 / -1;
        width: 100%;
        margin: 5px 0 0;
        padding:
            28px 20px;
        border:
            1px dashed rgba(255,
                255,
                255,
                0.12);
        border-radius:
            10px;
        background:
            rgba(255,
                255,
                255,
                0.025);
        color:
            #8F949D;
        font-size:
            14px;
        text-align:
            center;
    }


    /*
=========================================================
RESPONSIVE
=========================================================
*/

    @media (max-width: 900px) {
        .animeverze-home-container {
            width: 94%;
            padding-bottom: 50px;
        }

        .anime-section {
            margin-bottom: 44px;
        }
    }

    @media (max-width: 768px) {

        .animeverze-home-container {
            width: calc(100% - 20px);
            padding-top: 24px;
            padding-bottom: 40px;
        }

        .anime-section {
            margin-bottom: 36px;
        }

        .anime-section .section-header {
            gap: 10px;
            margin-bottom: 16px;
            padding-left: 10px;
            border-left-width: 4px;
        }

        /* Kicker එක පැහැදිලි ලෙස ලොකු කිරීම */
        .section-kicker {
            font-size: 10px !important;
            letter-spacing: 1px;
        }

        /* Sinhala Cartoons එකට සමාන 1.2rem (~19px-20px) Headings */
        .anime-section .section-title {
            font-size: 1.25rem !important;
            line-height: 1.2;
        }

        /* Sinhala Cartoons එකේ මෙන් පැහැදිලි View All Button */
        .anime-section .view-all {
            min-height: 32px;
            padding: 6px 12px !important;
            border-radius: 6px !important;
            font-size: 11px !important;
            font-weight: 800 !important;
        }
    }
</style>

<?php
get_footer();
?>