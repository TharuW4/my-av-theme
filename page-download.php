<?php
/**
 * Template Name: Movie Download Page
 */

get_header();

/*
|--------------------------------------------------------------------------
| AnimeVerze Download Data
|--------------------------------------------------------------------------
| single.php එකෙන් එවන:
|
| direct   = Base64 encoded direct download link
| telegram = Base64 encoded Telegram link
| av_name  = Movie name / Series + Episode name
|--------------------------------------------------------------------------
*/

$direct_encoded = isset($_GET['direct'])
    ? wp_unslash($_GET['direct'])
    : '';

$telegram_encoded = isset($_GET['telegram'])
    ? wp_unslash($_GET['telegram'])
    : '';

$direct_link = '';
$telegram_link = '';

/*
|--------------------------------------------------------------------------
| Decode Direct Download Link
|--------------------------------------------------------------------------
*/

if ($direct_encoded !== '') {
    // Base64 + sign එක URL processing වලදී space වෙලා තිබ්බොත් restore කරනවා.
    $direct_encoded = str_replace(' ', '+', $direct_encoded);
    $direct_encoded = rtrim($direct_encoded, '=');
    $direct_encoded .= str_repeat('=', (4 - strlen($direct_encoded) % 4) % 4);
    $decoded_direct = base64_decode($direct_encoded, true);
    if ($decoded_direct !== false) {
        $decoded_direct = trim($decoded_direct);
        if (filter_var($decoded_direct, FILTER_VALIDATE_URL)) {
            $direct_link = esc_url_raw($decoded_direct);
        }
    }
}

/*
|--------------------------------------------------------------------------
| Decode Telegram Download Link
|--------------------------------------------------------------------------
*/

if ($telegram_encoded !== '') {
    $telegram_encoded = str_replace(' ', '+', $telegram_encoded);
    $telegram_encoded = rtrim($telegram_encoded, '=');
    $telegram_encoded .= str_repeat('=', (4 - strlen($telegram_encoded) % 4) % 4);
    $decoded_telegram = base64_decode($telegram_encoded, true);
    if ($decoded_telegram !== false) {
        $decoded_telegram = trim($decoded_telegram);
        if (filter_var($decoded_telegram, FILTER_VALIDATE_URL)) {
            $telegram_link = esc_url_raw($decoded_telegram);
        }
    }
}

/*
|--------------------------------------------------------------------------
| Movie / Episode Name
|--------------------------------------------------------------------------
*/

$movie_title = isset($_GET['av_name'])
    ? sanitize_text_field(wp_unslash($_GET['av_name']))
    : 'Download';

$subtitle_downloads = array();

if (isset($_GET['av_subtitles']) && is_string($_GET['av_subtitles'])) {
    $subtitles_encoded = str_replace(' ', '+', wp_unslash($_GET['av_subtitles']));
    $subtitles_encoded = rtrim($subtitles_encoded, '=');
    $subtitles_encoded .= str_repeat('=', (4 - strlen($subtitles_encoded) % 4) % 4);
    $subtitles_decoded = base64_decode($subtitles_encoded, true);
    $subtitle_tracks = $subtitles_decoded !== false
        ? json_decode($subtitles_decoded, true)
        : array();

    if (is_array($subtitle_tracks)) {
        foreach ($subtitle_tracks as $track) {
            if (!is_array($track)) {
                continue;
            }

            $lang = isset($track['lang']) && is_string($track['lang'])
                ? sanitize_key($track['lang'])
                : '';

            $url = isset($track['url']) && is_string($track['url'])
                ? esc_url_raw(trim($track['url']), array('http', 'https'))
                : '';

            if ($lang === '' || $url === '' || !filter_var($url, FILTER_VALIDATE_URL)) {
                continue;
            }

            $subtitle_downloads[] = array(
                'label' => animeverze_subtitle_label($lang),
                'url'   => $url,
            );
        }
    }
}

?>


<div
    class="dl-landing-wrapper"
    style="
        background:#0b0b0b;
        min-height:100vh;
        padding-top:130px;
        padding-bottom:60px;
        color:#fff;
        font-family:'Inter', sans-serif;
    "
>

    <div
        class="dl-card-box"
        style="
            max-width:650px;
            margin:0 auto;
            background:#141414;
            padding:40px;
            border-radius:16px;
            border:1px solid #222;
            box-shadow:0 30px 60px rgba(0,0,0,0.8);
            text-align:center;
        "
    >


        <!-- =========================================================
             TITLE
        ========================================================== -->

        <div style="margin-bottom:30px;">

            <p
                style="
                    color:#38BDF8;
                    font-size:16px;
                    font-weight:800;
                    letter-spacing:2px;
                    text-transform:uppercase;
                    margin-bottom:10px;
                "
            >
                AnimeVerze Download
            </p>

            <h1
                style="
                    font-size:2.2rem;
                    font-weight:900;
                    color:#fff;
                    line-height:1.25;
                    margin:0;
                "
            >
                <?php echo esc_html($movie_title); ?>
            </h1>

            <p
                style="
                    color:#8f96a3;
                    font-size:14px;
                    line-height:1.7;
                    margin:14px auto 0;
                    max-width:520px;
                "
            >
                Choose your preferred download option below.
                Download directly from our high-speed server or continue through Telegram.
            </p>

        </div>


        <!-- =========================================================
             VERIFIED BOX
        ========================================================== -->

        <div
            class="verified-box"
            style="
                margin:25px 0;
                padding:15px;
                background:rgba(46,204,113,0.1);
                border:1px solid rgba(46,204,113,0.3);
                border-radius:12px;
                display:flex;
                align-items:center;
                justify-content:center;
                gap:10px;
            "
        >

            <i
                class="fa fa-shield"
                style="
                    color:#2ecc71;
                    font-size:20px;
                "
            ></i>

            <span
                style="
                    color:#2ecc71;
                    font-weight:600;
                    font-size:14px;
                    text-transform:uppercase;
                    letter-spacing:1px;
                "
            >
                Secure Anime Download Options
            </span>

        </div>


        <!-- =========================================================
             COUNTDOWN
        ========================================================== -->

        <div
            id="countdown-section"
            style="margin:40px 0;"
        >

            <div
                id="timer-circle"
                style="
                    width:80px;
                    height:80px;
                    border:4px solid #38BDF8;
                    border-radius:50%;
                    margin:0 auto;
                    display:flex;
                    align-items:center;
                    justify-content:center;
                    font-size:2.5rem;
                    font-weight:900;
                    color:#fff;
                    box-sizing:border-box;
                "
            >
                <span
                    id="timer-num"
                    style="
                        display:block;
                        line-height:1;
                        transform:translateY(-1px);
                    "
                >5</span>
            </div>

            <p
                style="
                    color:#888;
                    margin-top:15px;
                "
            >
                Your download options are being prepared...
            </p>

        </div>


        <!-- =========================================================
             DOWNLOAD BUTTONS
        ========================================================== -->

        <div
            id="download-action"
            style="
                display:none;
                margin-top:30px;
            "
        >

            <div
                class="bulk-download-list"
                style="
                    display:flex;
                    flex-direction:column;
                    gap:15px;
                "
            >


                <!-- DIRECT DOWNLOAD -->

                <?php if (!empty($direct_link)) : ?>

                    <a
                        href="<?php echo esc_url($direct_link); ?>"
                        target="_self"
                        download
                        class="dl-card-landing force-download-btn"
                    >

                        <div class="dl-icon-l">
                            <i class="fa fa-arrow-down"></i>
                        </div>

                        <div class="dl-text-l">

                            <strong>
                                Direct Download
                            </strong>

                            <span>
                                Cloudflare High Speed Direct Download
                            </span>
                        </div>
                    </a>
                <?php endif; ?>


                <!-- TELEGRAM DOWNLOAD -->

                <?php if (!empty($telegram_link)) : ?>

                    <a
                        href="<?php echo esc_url($telegram_link); ?>"
                        class="dl-card-landing telegram"
                        target="_self"
                    >

                        <div class="dl-icon-l">
                            <i class="fa fa-paper-plane"></i>
                        </div>

                        <div class="dl-text-l">

                            <strong>
                                Telegram Download
                            </strong>

                            <span>
                                Download through Telegram
                            </span>
                        </div>
                    </a>
                <?php endif; ?>

                <!-- SUBTITLE DOWNLOADS -->

                <?php foreach ($subtitle_downloads as $subtitle) : ?>
                    <a
                        href="<?php echo esc_url($subtitle['url']); ?>"
                        class="dl-card-landing subtitle-download"
                        target="_self"
                        download
                    >
                        <div class="dl-icon-l">
                            <i class="fa fa-file-text-o" aria-hidden="true"></i>
                        </div>

                        <div class="dl-text-l">
                            <strong>
                                <?php echo esc_html('Download ' . $subtitle['label'] . ' Subtitle'); ?>
                            </strong>
                            <span>Download subtitle file</span>
                        </div>
                    </a>
                <?php endforeach; ?>

                <!-- NO DOWNLOAD LINKS -->

                <?php if (empty($direct_link) && empty($telegram_link) && empty($subtitle_downloads)) : ?>
                    <div class="download-unavailable">
                        <i class="fa fa-exclamation-circle"></i>

                        <strong>
                            Download links are currently unavailable.
                        </strong>

                        <span>
                            Please try again later or contact AnimeVerze Support.
                        </span>
                    </div>
                <?php endif; ?>

            </div>

            <!-- =====================================================
                 EXPLORE
            ====================================================== -->

            <div
                class="footer-divider-section"
                style="margin-top:40px;"
            >
                <div class="faded-line-wrap">
                    <span class="faded-line"></span>
                    <span class="divider-text">
                        Explore More Anime -
                        <a href="<?php echo esc_url(home_url('/')); ?>">
                            AnimeVerze
                        </a>
                    </span>
                    <span class="faded-line"></span>
                </div>

                <!-- =================================================
                     HELP
                ================================================== -->

                <div
                    class="help-section"
                    style="
                        margin-top:25px;
                        text-align:left;
                        padding:0 10px;
                    "
                >
                    <h4
                        style="
                            color:#fff;
                            font-size:16px;
                            font-weight:800;
                            margin-bottom:10px;
                        "
                    >
                        Help
                    </h4>

                    <p
                        style="
                            color:#aaa;
                            font-size:13px;
                            line-height:1.6;
                            margin-bottom:8px;
                        "
                    >
                        If you have any problems with a download link,
                        please contact AnimeVerze Support through Telegram.
                    </p>
                    <p class="contact-admin-line">
                        Need Help?
                        <a
                            href="https://t.me/animeverze_bot"
                            target="_blank"
                            rel="noopener"
                            class="admin-link"
                        >
                            Contact AnimeVerze Support
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- =============================================================
         CSS
    ============================================================== -->

    <style>
        .dl-card-landing {
            background:linear-gradient(145deg,#1a1a1a,#111);
            border:1px solid #222;
            padding:20px;
            border-radius:12px;
            display:flex;
            align-items:center;
            gap:20px;
            text-decoration:none !important;
            transition:.3s ease;
            text-align:left;
        }

        .dl-card-landing:hover {
            border-color:#38BDF8;
            transform:translateY(-3px);
            background:#161616;
        }

        .dl-icon-l {
            background:#38BDF8;
            width:55px;
            height:55px;
            border-radius:50%;
            display:flex;
            align-items:center;
            justify-content:center;
            color:#06131B;
            font-size:22px;
            flex-shrink:0;
        }

        .dl-text-l {
            min-width:0;
        }

        .dl-text-l strong {
            display:block;
            color:#fff;
            font-size:17px;
            font-weight:800;
        }

        .dl-text-l span {
            display:block;
            margin-top:4px;
            color:#888;
            font-size:14px;
        }

        .dl-card-landing.telegram:hover .dl-icon-l {
            background:#0088cc;
            color:#fff;
        }

        .download-unavailable {
            padding:22px;
            border:1px solid #292e38;
            border-radius:12px;
            background:#111318;
            display:flex;
            flex-direction:column;
            align-items:center;
            gap:8px;
            color:#888;
        }

        .download-unavailable i {
            color:#38BDF8;
            font-size:24px;
        }

        .download-unavailable strong {
            color:#fff;
            font-size:14px;
        }

        .download-unavailable span {
            color:#888;
            font-size:12px;
        }

        .faded-line-wrap {
            display:flex;
            align-items:center;
            gap:15px;
            margin:30px 0;
        }

        .faded-line {
            height:1px;
            flex-grow:1;
            background:linear-gradient(
                to right,
                transparent,
                #333,
                transparent
            );
        }

        .divider-text {
            color:#fff;
            font-size:14px;
            white-space:nowrap;
        }

        .divider-text a {
            color:#38BDF8 !important;
            font-weight:800;
            text-decoration:none;
        }

        .divider-text a:hover {
            text-decoration:underline !important;
        }

        .help-section h4 {
            position:relative;
            display:inline-block;
        }

        .help-section a:hover {
            text-decoration:underline !important;
        }

        .contact-admin-line {
            color:#888;
            font-size:12px;
        }

        .admin-link {
            color:#38BDF8 !important;
            text-decoration:none;
            font-weight:bold;
        }

        .admin-link:hover {
            text-decoration:underline !important;
        }

        @media (max-width:768px) {
            .dl-landing-wrapper {
                padding-top:110px !important;
            }

            .dl-card-box {
                width:calc(100% - 30px);
                box-sizing:border-box;
                padding:25px !important;
            }

            .dl-card-box h1 {
                font-size:1.8rem !important;
            }

            .dl-card-landing {
                padding:15px;
                gap:14px;
            }

            .dl-icon-l {
                width:46px;
                height:46px;
                flex:0 0 46px;
                font-size:18px;
            }

            .dl-text-l strong {
                font-size:15px;
            }

            .dl-text-l span {
                font-size:12px;
            }

            .divider-text {
                font-size:12px;
                white-space:normal;
                text-align:center;
            }
        }
    </style>


    <!-- =============================================================
         JAVASCRIPT
    ============================================================== -->

    <script>

        document.addEventListener('DOMContentLoaded', function() {

            let timeLeft = 5;

            const timerElement =
                document.getElementById('timer-num');

            const countdownSection =
                document.getElementById('countdown-section');

            const downloadAction =
                document.getElementById('download-action');

            /*
            |--------------------------------------------------------------------------
            | Countdown
            |--------------------------------------------------------------------------
            */

            const interval = setInterval(function() {
                timeLeft--;
                if (timerElement) {
                    timerElement.textContent = timeLeft;
                }

                if (timeLeft <= 0) {
                    clearInterval(interval);
                    if (countdownSection) {
                        countdownSection.style.display = 'none';
                    }
                    if (downloadAction) {
                        downloadAction.style.display = 'block';
                    }
                }
            }, 1000);

            /*
            |--------------------------------------------------------------------------
            | Download Button Ads
            |--------------------------------------------------------------------------
            */

            const allButtons =
                document.querySelectorAll('.dl-card-landing');

            allButtons.forEach(function(button) {
                button.addEventListener('click', function(event) {
                    const isSubtitle = this.classList.contains('subtitle-download');

                    if (!isSubtitle) {
                        event.preventDefault();
                    }

                    const adLinks = [
                        'https://omg10.com/4/10803727',
                        'https://omg10.com/4/10803753'
                    ];

                    const randomAd =
                        adLinks[
                            Math.floor(
                                Math.random() * adLinks.length
                            )
                        ];

                    const downloadUrl =
                        this.href;

                    /*
                    |--------------------------------------------------------------
                    | Open advertisement
                    |--------------------------------------------------------------
                    */

                    window.open(
                        randomAd,
                        '_blank'
                    );

                    /*
                    |--------------------------------------------------------------
                    | Continue to selected download link
                    |--------------------------------------------------------------
                    */

                    if (isSubtitle) {
                        return;
                    }

                    setTimeout(() => {
                        if (
                            this.classList.contains('telegram')
                        ) {
                            window.location.href =
                                downloadUrl;
                        } else {
                            window.location.href =
                                downloadUrl;
                        }
                    }, 500);
                });
            });
        });
    </script>

<?php get_footer(); ?>