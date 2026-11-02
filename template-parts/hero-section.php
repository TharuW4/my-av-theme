<?php
/**
 * AnimeVerze: Featured Anime Hero Slider
 */
$hero_query = new WP_Query(
    array(
        'post_type'      => 'post',
        'meta_key'       => '_is_featured',
        'meta_value'     => 'yes',
        'posts_per_page' => 5,
        'post_status'    => 'publish',
        'no_found_rows'  => true,
    )
);
?>

<div class="swiper heroSwiper">
    <div class="swiper-wrapper">
        <?php if ($hero_query->have_posts()) : ?>
            <?php
            while ($hero_query->have_posts()) :
                $hero_query->the_post();
                $hero_index = (int) $hero_query->current_post;
                $hero_img = get_post_meta(
                    get_the_ID(),
                    '_hero_image_url',
                    true
                );
                $rating = get_post_meta(
                    get_the_ID(),
                    '_rating',
                    true
                );
                $year = get_post_meta(
                    get_the_ID(),
                    '_release_year',
                    true
                );
                $categories = get_the_category();

                /*
                =========================================
                HERO IMAGE FALLBACK
                =========================================
                */

                if (empty($hero_img)) {
                    $hero_img = get_the_post_thumbnail_url(
                        get_the_ID(),
                        'full'
                    );
                }

                $hero_img = str_replace(
                    '/t/p/original/',
                    '/t/p/w1280/',
                    (string) $hero_img
                );
                /*
                =========================================
                FRONTEND TYPE LABEL
                =========================================
                */

                $hero_type = '';

                if (!empty($categories)) {
                    foreach ($categories as $category) {
                        $name = strtolower(
                            $category->name
                        );
                        if ($name === 'anime series') {
                            $hero_type = 'Anime Series';
                            break;
                        }
                        if ($name === 'anime movies') {
                            $hero_type = 'Anime Movie';
                            break;
                        }
                    }
                }

                /*
                =========================================
                GENRE / CATEGORY DISPLAY
                =========================================
                */

                $display_categories = array();
                if (!empty($categories)) {
                    foreach ($categories as $category) {
                        $category_name =
                            $category->name;
                        $lower =
                            strtolower(
                                $category_name
                            );

                        if ($lower === 'uncategorized') {
                            continue;
                        }

                        if ($lower === 'anime series') {
                            $category_name = 'Anime Series';
                        }

                        if ($lower === 'anime movies') {
                            $category_name = 'Anime Movie';
                        }

                        $display_categories[] =
                            $category_name;
                    }
                }
            ?>

                <div class="swiper-slide">
                    <section
                        class="zeta-hero-wrap"
                        <?php if (!empty($hero_img)) : ?>
                            <?php if ($hero_index < 2) : ?>
                                style="background-image: url('<?php echo esc_url($hero_img); ?>');"
                            <?php else : ?>
                                data-hero-bg="<?php echo esc_url($hero_img); ?>"
                            <?php endif; ?>
                        <?php endif; ?>
                    >
                        <div class="anime-hero-shade"></div>

                        <?php if (!empty($hero_img) && $hero_index < 2) : ?>
                            <img
                                src="<?php echo esc_url($hero_img); ?>"
                                alt=""
                                fetchpriority="<?php echo $hero_index === 0 ? 'high' : 'auto'; ?>"
                                loading="eager"
                                decoding="async"
                                class="anime-hero-preload"
                            >
                        <?php endif; ?>

                        <div class="zeta-container">
                            <div class="hero-content">

                                <!-- =================================
                                     SMALL LABEL
                                ================================== -->

                                <div class="hero-featured-label">
                                    <span class="featured-dot"></span>
                                    Featured
                                </div>

                                <!-- =================================
                                     TITLE
                                ================================== -->

                                <h2 class="hero-title">
                                    <?php the_title(); ?>
                                </h2>

                                <!-- =================================
                                     META
                                ================================== -->

                                <div class="hero-meta-line">

                                    <?php if (!empty($rating)) : ?>
                                        <span class="hero-rating">
                                            <i
                                                class="fa fa-star"
                                                aria-hidden="true"
                                            ></i>
                                            <?php
                                            echo esc_html(
                                                $rating
                                            );
                                            ?>
                                        </span>
                                    <?php endif; ?>

                                    <?php if (!empty($year)) : ?>
                                        <span class="hero-meta-pill">
                                            <?php
                                            echo esc_html(
                                                $year
                                            );
                                            ?>
                                        </span>
                                    <?php endif; ?>

                                    <?php if (!empty($hero_type)) : ?>
                                        <span class="hero-meta-pill">
                                            <?php
                                            echo esc_html(
                                                $hero_type
                                            );
                                            ?>
                                        </span>
                                    <?php endif; ?>

                                    <?php if (!empty($quality = get_post_meta(get_the_ID(), '_quality', true))) : ?>
                                        <span class="hero-quality">
                                            <?php
                                            echo esc_html(
                                                $quality
                                            );
                                            ?>
                                        </span>
                                    <?php endif; ?>
                                </div>

                                <!-- =================================
                                     CATEGORIES
                                ================================== -->

                                <?php if (!empty($display_categories)) : ?>
                                    <div class="hero-genres">
                                        <?php
                                        echo esc_html(
                                            implode(
                                                '  •  ',
                                                array_slice(
                                                    $display_categories,
                                                    0,
                                                    4
                                                )
                                            )
                                        );
                                        ?>
                                    </div>
                                <?php endif; ?>

                                <!-- =================================
                                     DESCRIPTION
                                ================================== -->

                                <?php
                                $hero_excerpt =
                                    get_the_excerpt();

                                if (empty($hero_excerpt)) {
                                    $hero_excerpt =
                                        wp_strip_all_tags(
                                            get_the_content()
                                        );
                                }

                                ?>

                                <?php if (!empty($hero_excerpt)) : ?>
                                    <p class="hero-excerpt">
                                        <?php
                                        echo esc_html(
                                            wp_trim_words(
                                                $hero_excerpt,
                                                30,
                                                '...'
                                            )
                                        );
                                        ?>
                                    </p>
                                <?php endif; ?>

                                <!-- =================================
                                     BUTTONS
                                ================================== -->

                                <div class="hero-zeta-actions">

                                    <a
                                        href="<?php the_permalink(); ?>"
                                        class="btn-zeta-play"
                                    >
                                        <i
                                            class="fa fa-play"
                                            aria-hidden="true"
                                        ></i>
                                        Watch Now
                                    </a>

                                    <a
                                        href="<?php the_permalink(); ?>"
                                        class="btn-zeta-info"
                                    >
                                        <i
                                            class="fa fa-info-circle"
                                            aria-hidden="true"
                                        ></i>
                                        Details
                                    </a>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>

            <?php endwhile; ?>
            <?php wp_reset_postdata(); ?>

        <?php else : ?>

            <!-- =============================================
                 NO FEATURED CONTENT FALLBACK
            ============================================== -->

            <div class="swiper-slide">
                <section class="zeta-hero-wrap anime-hero-empty">
                    <div class="anime-hero-shade"></div>
                    <div class="zeta-container">
                        <div class="hero-content">
                            <div class="hero-featured-label">
                                <span class="featured-dot"></span>
                                AnimeVerze
                            </div>

                            <h2 class="hero-title">
                                Discover Your Next Anime
                            </h2>

                            <p class="hero-excerpt">
                                Explore anime series, movies and
                                the latest releases on AnimeVerze.
                            </p>

                            <div class="hero-zeta-actions">
                                <a
                                    href="<?php echo esc_url(home_url('/')); ?>"
                                    class="btn-zeta-play"
                                >
                                    <i
                                        class="fa fa-play"
                                        aria-hidden="true"
                                    ></i>
                                    Browse Anime
                                </a>
                            </div>
                        </div>
                    </div>
                </section>
            </div>

        <?php endif; ?>

    </div>

    <?php if ($hero_query->post_count > 1) : ?>
        <div class="swiper-pagination"></div>
    <?php endif; ?>
</div>

<style>

/*
=========================================================
ANIMEVERZE HERO
=========================================================
*/

.heroSwiper {
    position: relative;
    width: 100%;
    overflow: hidden;
    background: #0B0D12;
}

.zeta-hero-wrap {
    position: relative;
    width: 100%;
    height: 720px;
    min-height: 720px;
    display: flex;
    align-items: center;
    overflow: hidden;
    background-color: #0B0D12;
    background-size: cover;
    background-position: center 10%;
    background-repeat: no-repeat;
    color: #FFFFFF;
}

/*
=========================================================
BACKGROUND SHADING
=========================================================
*/

.anime-hero-shade {
    position: absolute;
    inset: 0;
    z-index: 1;
    pointer-events: none;
    background:
        linear-gradient(
            90deg,
            rgba(7, 9, 13, 0.98) 0%,
            rgba(7, 9, 13, 0.91) 22%,
            rgba(7, 9, 13, 0.58) 47%,
            rgba(7, 9, 13, 0.17) 69%,
            rgba(7, 9, 13, 0.20) 100%
        ),

        linear-gradient(
            0deg,
            #0F0F0F 0%,
            rgba(15, 15, 15, 0.74) 10%,
            rgba(15, 15, 15, 0.05) 38%,
            transparent 65%
        );
}

.anime-hero-preload {
    position: absolute;
    width: 1px;
    height: 1px;
    opacity: 0;
    pointer-events: none;
}

/*
=========================================================
CONTENT CONTAINER
=========================================================
*/

.zeta-container {
    position: relative;
    z-index: 3;
    width: 94%;
    max-width: 1800px;
    margin: 0 auto;
}

.hero-content {
    width: 100%;
    max-width: 680px;
    padding: 34px 0;
}

/*
=========================================================
FEATURED LABEL
=========================================================
*/

.hero-featured-label {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 14px;
    color: #7DD3FC;
    font-size: 11px;
    font-weight: 800;
    line-height: 1;
    letter-spacing: 1.4px;
    text-transform: uppercase;
}

.featured-dot {
    width: 7px;
    height: 7px;
    flex-shrink: 0;
    border-radius: 50%;
    background: #38BDF8;
    box-shadow:
        0 0 12px
        rgba(
            56,
            189,
            248,
            0.75
        );
}

/*
=========================================================
TITLE
=========================================================
*/

.hero-title {
    max-width: 680px;
    margin:
        0
        0
        18px;
    padding: 0;
    color: #FFFFFF;
    font-size:
        clamp(
            40px,
            4.4vw,
            72px
        );
    font-weight: 900;
    line-height: 1.02;
    letter-spacing: -2px;
    text-shadow:
        0 5px 24px
        rgba(
            0,
            0,
            0,
            0.55
        );
}

/*
=========================================================
META
=========================================================
*/

.hero-meta-line {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 9px;
    margin-bottom: 14px;
}

.hero-rating,
.hero-meta-pill,
.hero-quality {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-height: 27px;
    padding:
        5px
        9px;
    border-radius: 6px;
    font-size: 11px;
    font-weight: 700;
    line-height: 1;
}

.hero-rating {
    gap: 5px;
    background:
        rgba(
            8,
            10,
            14,
            0.64
        );
    border:
        1px solid
        rgba(
            255,
            255,
            255,
            0.15
        );
    backdrop-filter: blur(8px);
    -webkit-backdrop-filter: blur(8px);
    color: #FFFFFF;
}

.hero-rating i {
    color: #FACC15;
}

.hero-meta-pill {
    background:
        rgba(
            255,
            255,
            255,
            0.08
        );
    border:
        1px solid
        rgba(
            255,
            255,
            255,
            0.12
        );
    color: #E7E8EB;
}


.hero-quality {
    background: #38BDF8;
    color: #06131B;
    font-weight: 900;
}

/*
=========================================================
GENRES
=========================================================
*/

.hero-genres {
    margin-bottom: 15px;
    color: #D7DAE0;
    font-size: 13px;
    font-weight: 600;
    line-height: 1.45;
}

/*
=========================================================
DESCRIPTION
=========================================================
*/

.hero-excerpt {
    max-width: 620px;
    margin:
        0
        0
        26px;
    color: #C6C9D0;
    font-size: 15px;
    line-height: 1.65;
    text-shadow:
        0 2px 8px
        rgba(
            0,
            0,
            0,
            0.45
        );
}

/*
=========================================================
ACTIONS
=========================================================
*/

.hero-zeta-actions {
    display: flex;
    align-items: center;
    flex-wrap: wrap;

    gap: 10px;
}


.btn-zeta-play,
.btn-zeta-info {
    min-height: 46px;

    display: inline-flex;
    align-items: center;
    justify-content: center;

    gap: 9px;

    padding:
        0
        22px;

    border-radius: 8px;

    font-size: 13px;
    font-weight: 800;

    text-decoration: none !important;

    transition:
        background 0.22s ease,
        color 0.22s ease,
        border-color 0.22s ease,
        transform 0.22s ease;
}


.btn-zeta-play {
    background: #38BDF8;

    border:
        1px solid #38BDF8;

    color: #06131B !important;
}


.btn-zeta-play:hover {
    background: #0EA5E9;

    border-color: #0EA5E9;

    color: #FFFFFF !important;

    transform: translateY(-2px);
}


.btn-zeta-info {
    background:
        rgba(
            255,
            255,
            255,
            0.10
        );

    border:
        1px solid
        rgba(
            255,
            255,
            255,
            0.16
        );

    backdrop-filter: blur(9px);
    -webkit-backdrop-filter: blur(9px);

    color: #FFFFFF !important;
}


.btn-zeta-info:hover {
    background:
        rgba(
            255,
            255,
            255,
            0.18
        );

    border-color:
        rgba(
            255,
            255,
            255,
            0.25
        );

    transform: translateY(-2px);
}


/*
=========================================================
PAGINATION
=========================================================
*/

.heroSwiper .swiper-pagination {
    bottom: 28px !important;

    gap: 7px;
}


.heroSwiper
.swiper-pagination-bullet {

    width: 7px;
    height: 7px;

    margin:
        0
        4px !important;

    background:
        rgba(
            255,
            255,
            255,
            0.62
        ) !important;

    border-radius: 30px;

    opacity: 1;

    transition:
        width 0.25s ease,
        background 0.25s ease;
}


.heroSwiper
.swiper-pagination-bullet-active {

    width: 27px;

    background:
        #38BDF8 !important;
}


/*
=========================================================
REMOVE ARROWS
=========================================================
*/

.heroSwiper
.swiper-button-next,
.heroSwiper
.swiper-button-prev {

    display: none !important;
}


/*
=========================================================
EMPTY HERO FALLBACK
=========================================================
*/

.anime-hero-empty {
    background:

        radial-gradient(
            circle at 75% 40%,
            rgba(
                56,
                189,
                248,
                0.18
            ),
            transparent 34%
        ),

        radial-gradient(
            circle at 85% 15%,
            rgba(
                14,
                165,
                233,
                0.12
            ),
            transparent 28%
        ),

        #0B0D12;
}


/*
=========================================================
TABLET
=========================================================
*/

@media (max-width: 991px) {

    .zeta-hero-wrap {
        height: 560px;
        min-height: 560px;

        background-position: center top;
    }


    .anime-hero-shade {
        background:

            linear-gradient(
                90deg,
                rgba(7, 9, 13, 0.96) 0%,
                rgba(7, 9, 13, 0.76) 45%,
                rgba(7, 9, 13, 0.30) 100%
            ),

            linear-gradient(
                0deg,
                #0F0F0F 0%,
                rgba(15, 15, 15, 0.65) 18%,
                transparent 60%
            );
    }


    .hero-content {
        max-width: 600px;
    }


    .hero-title {
        font-size:
            clamp(
                36px,
                7vw,
                56px
            );

        letter-spacing:
            -1.4px;
    }

}


/*
=========================================================
MOBILE
=========================================================
*/

@media (max-width: 600px) {

    .zeta-hero-wrap {
        height: 500px;
        min-height: 500px;

        align-items: flex-end;

        background-position:
            center top;
    }


    .anime-hero-shade {
        background:

            linear-gradient(
                0deg,
                #0F0F0F 0%,
                rgba(15, 15, 15, 0.94) 20%,
                rgba(15, 15, 15, 0.52) 55%,
                rgba(15, 15, 15, 0.12) 100%
            ),

            linear-gradient(
                90deg,
                rgba(7, 9, 13, 0.45),
                rgba(7, 9, 13, 0.08)
            );
    }


    .zeta-container {
        width:
            calc(
                100% - 28px
            );
    }


    .hero-content {
        max-width: 100%;

        padding:
            0
            0
            56px;
    }


    .hero-featured-label {
        margin-bottom:
            10px;

        font-size:
            9px;
    }


    .featured-dot {
        width: 6px;
        height: 6px;
    }


    .hero-title {
        margin-bottom:
            13px;

        font-size:
            32px;

        line-height:
            1.05;

        letter-spacing:
            -0.8px;
    }


    .hero-meta-line {
        gap: 6px;

        margin-bottom:
            10px;
    }


    .hero-rating,
    .hero-meta-pill,
    .hero-quality {
        min-height:
            23px;

        padding:
            4px
            7px;

        border-radius:
            5px;

        font-size:
            9px;
    }


    .hero-genres {
        margin-bottom:
            10px;

        font-size:
            11px;
    }


    .hero-excerpt {
        display:
            -webkit-box;

        overflow: hidden;

        margin-bottom:
            18px;

        font-size:
            12px;

        line-height:
            1.55;

        -webkit-box-orient:
            vertical;

        -webkit-line-clamp:
            3;
    }


    .btn-zeta-play,
    .btn-zeta-info {
        min-height:
            40px;

        padding:
            0
            16px;

        border-radius:
            7px;

        font-size:
            11px;
    }


    .heroSwiper
    .swiper-pagination {

        bottom:
            19px !important;
    }

}


/*
=========================================================
VERY SMALL MOBILE
=========================================================
*/

@media (max-width: 360px) {

    .hero-title {
        font-size:
            28px;
    }


    .btn-zeta-play,
    .btn-zeta-info {
        padding:
            0
            13px;
    }

}

</style>



<script>

document.addEventListener(
    'DOMContentLoaded',
    function () {

        const heroElement =
            document.querySelector(
                '.heroSwiper'
            );


        if (
            !heroElement ||
            typeof Swiper === 'undefined'
        ) {
            return;
        }


        const slideCount =
            heroElement.querySelectorAll(
                '.swiper-slide'
            ).length;


        const loadHeroBackground = function (section) {
            if (
                !section ||
                !section.dataset.heroBg ||
                section.dataset.heroLoading === '1'
            ) {
                return;
            }

            const imageUrl = section.dataset.heroBg;

            section.dataset.heroLoading = '1';

            const image = new Image();

            image.decoding = 'async';

            image.onload = function () {
                section.style.backgroundImage =
                    'url("' + imageUrl + '")';

                section.removeAttribute('data-hero-bg');
                section.removeAttribute('data-hero-loading');
            };

            image.onerror = function () {
                section.removeAttribute('data-hero-loading');
            };

            image.src = imageUrl;
        };


        const loadActiveAndNextHero = function (swiper) {
            if (!swiper || !swiper.slides.length) {
                return;
            }

            const activeSlide =
                swiper.slides[swiper.activeIndex];

            const nextSlide =
                swiper.slides[
                    (swiper.activeIndex + 1) %
                    swiper.slides.length
                ];

            loadHeroBackground(
                activeSlide
                    ? activeSlide.querySelector('.zeta-hero-wrap')
                    : null
            );

            loadHeroBackground(
                nextSlide
                    ? nextSlide.querySelector('.zeta-hero-wrap')
                    : null
            );
        };


        const heroSwiper = new Swiper(
            '.heroSwiper',
            {

                loop:
                    slideCount > 1,

                speed:
                    700,

                autoplay:
                    slideCount > 1
                    ? {
                        delay: 6500,
                        disableOnInteraction: false,
                        pauseOnMouseEnter: true
                    }
                    : false,

                effect:
                    'fade',

                fadeEffect:
                    {
                        crossFade: true
                    },

                pagination:
                    {
                        el:
                            '.swiper-pagination',

                        clickable:
                            true
                    },

                on:
                    {
                        slideChangeTransitionStart:
                            function () {
                                loadActiveAndNextHero(this);
                            }
                    }

            }
        );


        const startDelayedHeroLoading = function () {
            const delayedSections =
                heroElement.querySelectorAll(
                    '.zeta-hero-wrap[data-hero-bg]'
                );

            delayedSections.forEach(
                function (section, index) {

                    setTimeout(
                        function () {
                            loadHeroBackground(section);
                        },

                        1200 + (index * 1400)
                    );
                }
            );
        };


        if (document.readyState === 'complete') {

            startDelayedHeroLoading();

        } else {

            window.addEventListener(
                'load',
                startDelayedHeroLoading,
                { once: true }
            );
        }


        loadActiveAndNextHero(heroSwiper);

    }
);

</script>