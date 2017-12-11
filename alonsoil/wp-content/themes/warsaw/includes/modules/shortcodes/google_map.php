<?php   
ob_start();
?>

<!--Fluid Section Two-->
<section class="fluid-section-two" style="background-image:url('<?php echo esc_url(wp_get_attachment_url($bg_img));?>')">
    
    <div class="outer-box clearfix">
        <!--Left Column-->
        <div class="left-column">
            <div class="clearfix">
                <div class="inner-box">
                    <h5><?php echo balanceTags($title);?></h5>
                    <h3><?php echo balanceTags($sub_title);?></h3>
                    
                    <div class="subscribe-form">
                        <form method="get" action="http://feedburner.google.com/fb/a/mailverify" accept-charset="utf-8">
                            <div class="form-group">
                                <input type="hidden" id="uri2" name="uri" value="<?php echo esc_attr($id); ?>">
                                <input type="email" name="email" value="" placeholder="<?php esc_html_e('Ingresa tu correo', 'warsaw')?>" required>
                                <button type="submit" class="theme-btn btn-style-three"><?php esc_html_e('Enviar', 'warsaw')?></button>
                            </div>
                        </form>
                    </div>
                    
                </div>
            </div>
        </div>	
        
        <!--Right Column-->
        <div class="right-column">
            <!--Map Canvas-->
            <div class="map-canvas"
                data-zoom="10"
                data-lat="<?php echo esc_js($lat);?>"
                data-lng="<?php echo esc_js($long);?>"
                data-type="roadmap"
                data-hue="#fc721e"
                data-title="<?php echo esc_js($mark_title);?>"
                data-content="<?php echo esc_js($mark_address);?><br><a href='mailto:<?php echo sanitize_email($email);?>'><?php echo sanitize_email($email);?></a>">
            </div>
        </div>	
        
    </div>
</section>

<?php return ob_get_clean();?>		