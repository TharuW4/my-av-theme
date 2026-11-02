<?php
/**
 * STREAM-LK: Cinematic 404 Page (Signal Lost Design)
 */

get_header(); ?>

<style>
    /* --- 404 Page Custom Styles --- */
    .error-404-wrapper {
        min-height: 80vh;
        display: flex;
        align-items: center;
        justify-content: center;
        text-align: center;
        padding: 40px 20px;
        background-color: #0f141e; /* Dark background like the screenshot */
        color: #fff;
    }

    .error-content {
        max-width: 600px;
    }

    /* 404 Glitch Effect */
    .glitch-text {
        font-size: 150px;
        font-weight: 900;
        position: relative;
        margin: 0;
        line-height: 1;
        letter-spacing: -5px;
        /* 3D Glitch Shadows */
        text-shadow: 
            -3px -3px 0 #00fff2, 
            3px 3px 0 #e50914;
    }

    .error-content h2 {
        font-size: 28px;
        font-weight: 800;
        text-transform: uppercase;
        margin: 20px 0 10px;
        letter-spacing: 1px;
    }

    .error-description {
        font-size: 16px;
        color: rgba(255, 255, 255, 0.6);
        line-height: 1.6;
        margin-bottom: 30px;
    }

    /* Red "Back to Home" Button */
    .back-home-btn {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        background-color: #38BDF8;
        color: #06131B;
        text-decoration: none;
        padding: 12px 25px;
        font-size: 16px;
        font-weight: 700;
        border-radius: 6px;
        transition: 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 4px 15px rgba(56, 189, 248, 0.3);
    }

    .back-home-btn:hover {
        background-color: #0EA5E9;
        color: #06131B;
        transform: translateY(-3px);
        box-shadow: 0 6px 20px rgba(56, 189, 248, 0.5);
    }

    /* Responsive scaling */
    @media (max-width: 768px) {
        .glitch-text { font-size: 100px; }
        .error-content h2 { font-size: 20px; }
    }
</style>

<div class="error-404-wrapper">
    <div class="error-content">
        <!-- Main 404 Glitch Text -->
        <h1 class="glitch-text">404</h1>

        <!-- Subtitle -->
        <h2>SIGNAL LOST / PAGE NOT FOUND</h2>

        <!-- Description -->
        <p class="error-description">
            Sorry! We couldn't find the video or page you were looking for. <br>
            It may have been removed, or the link might be broken.
        </p>

        <!-- Red Action Button -->
        <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="back-home-btn">
            <i class="fa fa-home"></i> Back to Home
        </a>
    </div>
</div>

<?php get_footer(); ?>