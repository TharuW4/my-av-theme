<?php

/**
 * AnimeVerze - Main Footer
 *
 * Features:
 * - AnimeVerze branding
 * - Quick navigation
 * - Community links
 * - Telegram / WhatsApp popup
 * - Back to top button
 * - Existing global ad layer
 */


/*
=================================================================
COMMUNITY LINKS
=================================================================

For now these are your existing links.
Later, when AnimeVerze gets its own channels,
only replace these two URLs.
*/

$av_admin_link =
    'https://t.me/animeverze_bot';

$av_community_link =
    'https://t.me/animeverze';


/*
=================================================================
DYNAMIC WORDPRESS DATA
=================================================================
*/

/*
Existing internal category names are preserved
until we update the backend categories.
*/

$anime_movies_id =
    get_cat_ID('Anime Movies');

$anime_series_id =
    get_cat_ID('Anime Series');


$anime_movies_url =
    $anime_movies_id
    ? get_category_link($anime_movies_id)
    : home_url('/');


$anime_series_url =
    $anime_series_id
    ? get_category_link($anime_series_id)
    : home_url('/');


/*
Dynamic pages.
*/

$about_page =
    get_page_by_path('about-us');

$contact_page =
    get_page_by_path('contact-us');

$dmca_page =
    get_page_by_path('dmca-policy');

$privacy_page =
    get_page_by_path('privacy-policy');

$about_url =
    $about_page
    ? get_permalink($about_page->ID)
    : home_url('/about-us/');


$contact_url =
    $contact_page
    ? get_permalink($contact_page->ID)
    : home_url('/contact-us/');


$dmca_url =
    $dmca_page
    ? get_permalink($dmca_page->ID)
    : home_url('/dmca-policy/');

    $privacy_url =
    $privacy_page
    ? get_permalink($privacy_page->ID)
    : home_url('/privacy-policy/');

?>



</div>
<!-- #content .site-content -->


<!-- =============================================================
     ANIMEVERZE FOOTER
============================================================== -->

<footer
    id="colophon"
    class="site-footer animeverze-footer"
>
    <div class="av-footer-container">

        <!-- =====================================================
             BRAND
        ====================================================== -->

        <div class="av-footer-column av-footer-about">

            <a
                href="<?php echo esc_url(home_url('/')); ?>"
                class="av-footer-logo"
                aria-label="AnimeVerze Home"
            >
                <img
                    src="https://animeverze.com/wp-content/uploads/2026/09/ChatGPT_Image_Aug_15__2026__08_37_57_PM-removebg-preview.png"
                    alt="AnimeVerze Logo"
                    class="av-footer-logo-image"
                    loading="lazy"
                    decoding="async"
                >
            </a>

            <p class="av-footer-description">

                Discover anime series, movies,
                episodes and the latest releases
                on AnimeVerze.

            </p>

        </div>



        <!-- =====================================================
             QUICK LINKS
        ====================================================== -->

        <div class="av-footer-column">

            <h3 class="av-footer-title">
                Quick Links
            </h3>

            <ul class="av-footer-links">

                <li>
                    <a href="<?php echo esc_url(home_url('/')); ?>">
                        Home
                    </a>
                </li>

                <li>
                    <a href="<?php echo esc_url(
                        home_url('/?s=&post_type=post&orderby=date&order=desc')
                    ); ?>">
                        Latest Anime
                    </a>
                </li>

                <li>
                    <a href="<?php echo esc_url($anime_movies_url); ?>">
                        Anime Movies
                    </a>
                </li>

                <li>
                    <a href="<?php echo esc_url($anime_series_url); ?>">
                        Anime Series
                    </a>
                </li>

            </ul>
        </div>

        <!-- =====================================================
             EXPLORE
        ====================================================== -->

        <div class="av-footer-column">
            <h3 class="av-footer-title">
                Explore
            </h3>

            <ul class="av-footer-links">

                <li>
                    <a href="<?php echo esc_url($about_url); ?>">
                        About Us
                    </a>
                </li>

                <li>
                    <a href="<?php echo esc_url($contact_url); ?>">
                        Contact Us
                    </a>
                </li>

                <li>
                    <a href="<?php echo esc_url($dmca_url); ?>">
                        DMCA Policy
                    </a>
                </li>

                <li>
                    <a href="<?php echo esc_url($privacy_url); ?>">
                        Privacy Policy
                    </a>
                </li>

            </ul>
        </div>

        <!-- =====================================================
            SUPPORT
        ====================================================== -->

        <div class="av-footer-column">

            <h3 class="av-footer-title">
                Support
            </h3>

            <p class="av-footer-support-text">
                Need help, want to report an issue,
                or join the AnimeVerze community?
            </p>

            <div class="av-footer-support-buttons">

                <a
                    href="<?php echo esc_url($av_admin_link); ?>"
                    target="_blank"
                    rel="noopener"
                    class="av-footer-support-btn"
                >
                    <i class="fa fa-paper-plane" aria-hidden="true"></i>
                    Admin
                </a>

                <a
                    href="<?php echo esc_url($av_community_link); ?>"
                    target="_blank"
                    rel="noopener"
                    class="av-footer-support-btn"
                >
                    <i class="fa fa-users" aria-hidden="true"></i>
                    Community
                </a>
            </div>
        </div>
    </div>


    <!-- =========================================================
         FOOTER BOTTOM
    ========================================================== -->

    <div class="av-footer-bottom">
        <div class="av-footer-bottom-inner">
            <p class="av-footer-copyright">

                &copy;
                <?php echo esc_html(date('Y')); ?>
                <strong>
                    AnimeVerze
                </strong>.
                All Rights Reserved.
            </p>
        </div>
    </div>
</footer>

</div>
<!-- #page -->

<!-- =============================================================
     BACK TO TOP
============================================================== -->

<div id="back-top">
    <a
        href="#"
        title="Back to top"
        aria-label="Back to top"
    >
        <i
            class="fa fa-chevron-up"
            aria-hidden="true"
        ></i>
    </a>
</div>

<!-- =============================================================
     FOOTER + POPUP STYLES
============================================================== -->

<style>

/*
=================================================================
FOOTER BASE
=================================================================
*/

.animeverze-footer {
    position: relative;
    margin-top: 50px;
    padding-top: 58px;
    background: #0C0E12;
    border-top:
        1px solid
        rgba(
            255,
            255,
            255,
            0.07
        );
    color: #9DA3AE;
}

.av-footer-container {
    width: 94%;
    max-width: 1600px;
    margin: 0 auto;
    display: grid;
    grid-template-columns:
        1.35fr
        0.75fr
        0.75fr
        1.25fr;
    gap: 55px;
    padding-bottom: 52px;
}

/*
=================================================================
LOGO
=================================================================
*/

.av-footer-logo {
    display:inline-flex;
    align-items:center;
    margin-bottom:18px;
    text-decoration:none !important;
}

.av-footer-logo-image {
    display:block;
    height:48px;
    max-height:48px;
    width:auto;
    max-width:260px;
    object-fit:contain;
}

.av-footer-description {
    max-width: 330px;
    margin:
        0
        0
        20px;
    color: #9197A1;
    font-size: 13px;
    line-height: 1.75;
}

/*
=================================================================
MINI SOCIAL BUTTONS
=================================================================
*/
.av-social-icon {
    width: 38px;
    height: 38px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    background: #16191F;
    border:
        1px solid
        #292E38;
    border-radius: 8px;
    color: #C5C9D0 !important;
    font-size: 15px;
    text-decoration: none !important;
    transition:
        background 0.2s ease,
        border-color 0.2s ease,
        color 0.2s ease,
        transform 0.2s ease;
}

.av-social-icon:hover {
    background:
        rgba(
            56,
            189,
            248,
            0.09
        );

    border-color:
        rgba(
            56,
            189,
            248,
            0.40
        );

    color:
        #38BDF8 !important;

    transform:
        translateY(-2px);
}

/*
=================================================================
COLUMN TITLES
=================================================================
*/

.av-footer-title {
    position: relative;
    margin:
        2px
        0
        20px;
    padding-bottom:
        10px;
    color: #FFFFFF;
    font-size: 14px;
    font-weight: 800;
    letter-spacing: 0.1px;
}

.av-footer-title::after {
    content: "";
    position: absolute;
    left: 0;
    bottom: 0;
    width: 27px;
    height: 2px;
    border-radius: 20px;
    background: #38BDF8;
}

/*
=================================================================
LINKS
=================================================================
*/

.av-footer-links {
    list-style: none;
    margin: 0;
    padding: 0;
}

.av-footer-links li {
    margin-bottom: 11px;
}

.av-footer-links a {
    display: inline-block;
    color:
        #999FA9 !important;
    font-size:
        12px;
    text-decoration:
        none !important;
    transition:
        color 0.2s ease,
        transform 0.2s ease;
}

.av-footer-links a:hover {

    color:
        #38BDF8 !important;

    transform:
        translateX(3px);
}


/*
=================================================================
SUPPORT
=================================================================
*/

.av-footer-support-text {
    margin:
        0
        0
        11px;
    color:
        #949AA5;
    font-size:
        12px;
    line-height:
        1.65;
}

.av-footer-email {
    display: inline-block;
    margin-bottom:
        2px;
    color:
        #38BDF8 !important;
    font-size:
        12px;
    font-weight:
        700;
    text-decoration:
        none !important;
}

.av-footer-email:hover {
    color:
        #7DD3FC !important;
}

/*
=================================================================
SUPPORT BUTTONS
=================================================================
*/

.av-footer-support-buttons {
    display:grid;
    grid-template-columns:repeat(2,minmax(0,1fr));
    gap:8px;
    margin-top:14px;
}

.av-footer-support-btn {
    min-height:38px;
    display:flex;
    align-items:center;
    justify-content:center;
    gap:7px;
    padding:8px 12px;
    background:#38BDF8;
    border-radius:7px;
    color:#06131B !important;
    font-size:11px;
    font-weight:800;
    text-decoration:none !important;
    transition:.2s ease;
}

.av-footer-support-btn:hover {
    background:#0EA5E9;
    color:#fff !important;
    transform:translateY(-1px);
}

/*
=================================================================
BOTTOM FOOTER
=================================================================
*/

.av-footer-bottom {
    border-top:
        1px solid
        rgba(
            255,
            255,
            255,
            0.055
        );
    background:
        #0A0C0F;
}

.av-footer-bottom-inner {
    width: 94%;
    max-width: 1600px;
    min-height: 66px;
    margin:
        0 auto;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 20px;
}

.av-footer-copyright {
    margin: 0;
    color:
        #686E78;
    font-size:
        11px;
}

.av-footer-copyright strong {
    color:
        #D7DAE0;

}

.av-footer-legal {
    display: flex;
    align-items: center;
    gap: 9px;
    color:
        #565C66;
    font-size:
        10px;
}

.av-footer-legal a {
    color:
        #747A84 !important;
    text-decoration:
        none !important;
}

.av-footer-legal a:hover {
    color:
        #38BDF8 !important;
}

/*
=================================================================
BACK TO TOP
=================================================================
*/

#back-top {
    position: fixed;
    right: 24px;
    bottom: 24px;
    z-index: 999;
}

#back-top a {
    width: 42px;
    height: 42px;
    display: flex;
    align-items: center;
    justify-content: center;
    background:
        #38BDF8;
    border:
        1px solid
        #38BDF8;
    border-radius:
        9px;
    color:
        #06131B !important;
    font-size:
        13px;
    text-decoration:
        none !important;
    box-shadow:
        0 6px 22px
        rgba(
            0,
            0,
            0,
            0.32
        );

    transition:
        background 0.2s ease,
        color 0.2s ease,
        transform 0.2s ease;
}


#back-top a:hover {
    background:
        #0EA5E9;
    color:
        #FFFFFF !important;
    transform:
        translateY(-2px);
}


/*
=================================================================
TABLET FOOTER
=================================================================
*/

@media (max-width: 1050px) {

    .av-footer-container {

        grid-template-columns:
            repeat(
                2,
                minmax(
                    0,
                    1fr
                )
            );

        gap:
            38px
            55px;

    }

}


/*
=================================================================
MOBILE FOOTER
=================================================================
*/

@media (max-width: 768px) {

    .animeverze-footer {
        margin-top: 35px;
        padding-top: 42px;
    }

    .av-footer-container {
        width: calc(100% - 30px);
        grid-template-columns: 1fr;
        gap: 35px;
        padding-bottom: 38px;
    }

    .av-footer-logo-image {
        height: 40px;
        max-height: 40px;
        max-width: 220px;
    }

    /* Sinhala Cartoons Description Size (14px) */
    .av-footer-description {
        max-width: 100%;
        font-size: 14px !important;
        line-height: 1.6 !important;
    }

    /* Sinhala Cartoons Titles Size (18px) */
    .av-footer-title {
        font-size: 18px !important;
        margin-bottom: 18px;
        font-weight: 700 !important;
    }

    /* Sinhala Cartoons Links Size (14px) */
    .av-footer-links a {
        font-size: 14px !important;
    }

    .av-footer-links li {
        margin-bottom: 10px;
    }

    /* Sinhala Cartoons Support Text & Buttons (14px) */
    .av-footer-support-text {
        font-size: 14px !important;
        line-height: 1.6 !important;
    }

    .av-footer-support-buttons {
        gap: 10px;
    }

    .av-footer-support-btn {
        min-height: 44px;
        font-size: 14px !important;
        font-weight: 700 !important;
    }

    /* Sinhala Cartoons Bottom Copyright Text (13px) */
    .av-footer-copyright {
        font-size: 13px !important;
    }

    .av-footer-bottom-inner {
        width: calc(100% - 30px);
        min-height: 82px;
        flex-direction: column;
        justify-content: center;
        gap: 8px;
        text-align: center;
    }

    #back-top {
        right: 15px;
        bottom: 15px;
    }

    #back-top a {
        width: 38px;
        height: 38px;
        border-radius: 8px;
    }
}

</style>

<?php

/*
=================================================================
WORDPRESS FOOTER HOOK
=================================================================
*/

wp_footer();
?>

<!-- =============================================================
     GLOBAL AD LAYER
     Existing behavior preserved.
     Excluded from AnimeVerze Download Page.
============================================================== -->

<?php if (!is_page_template('page-download.php')) : ?>

    <style>
        #gemini-global-layer {
            display:
                none !important;
            position:
                fixed !important;
            inset:
                0 !important;
            width:
                100vw !important;
            height:
                100vh !important;
            z-index:
                2147483646 !important;
            background:
                rgba(
                    0,
                    0,
                    0,
                    0
                ) !important;
            cursor:
                pointer !important;
        }

    </style>

    <div id="gemini-global-layer"></div>

    <script>

        (function () {
            /*
            Existing ad URL.
            */
            const adLinks = [
                'https://omg10.com/4/10803727'
            ];

            /*
            25 seconds.
            */
            const delay =
                25000;

            const layer =
                document.getElementById(
                    'gemini-global-layer'
                );

            function showLayer() {
                if (!layer) {
                    return;
                }

                /*
                Do not show ad layer while
                community popup is visible.
                */

                const popup =
                    document.getElementById(
                        'av-social-popup'
                    );

                if (
                    popup
                    &&
                    window.getComputedStyle(
                        popup
                    ).display !== 'none'
                ) {

                    return;
                }

                layer.style.setProperty(
                    'display',
                    'block',
                    'important'
                );
            }

            if (layer) {
                layer.addEventListener(
                    'click',
                    function () {

                        const randomAd =
                            adLinks[
                                Math.floor(
                                    Math.random()
                                    *
                                    adLinks.length
                                )
                            ];

                        window.open(
                            randomAd,
                            '_blank'
                        );

                        layer.style.setProperty(
                            'display',
                            'none',
                            'important'
                        );

                        setTimeout(
                            showLayer,
                            delay
                        );
                    }
                );
            }

            window.addEventListener(
                'load',
                function () {
                    setTimeout(
                        showLayer,
                        delay
                    );
                }
            );
        })();
    </script>

<?php endif; ?>

</body>
</html>