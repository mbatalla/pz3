<?php   
ob_start();
?>

<!--Map Section-->
<section class="map-section">
    <div class="map-outer">

        <!--Map Canvas-->
        <div class="map-canvas"
            data-zoom="10"
            data-lat="<?php echo esc_js($lat);?>"
            data-lng="<?php echo esc_js($long);?>"
            data-type="roadmap"
            data-hue="#fc721e"
            data-title="<?php echo esc_js($mark_title);?>"
            data-content="<?php echo esc_js($mark_address);?><br><a href='mailto:<?php echo sanitize_email($email);?>'><?php echo sanitize_email($email);?></a>"
            style="height:450px;">
        </div>

    </div>
</section>

<?php return ob_get_clean();?>		