<?php   
ob_start();
?>

<!--Subscribe Section-->
<section class="subscribe-section" style="background-image:url('<?php echo esc_url(wp_get_attachment_url($bg_img));?>')">
    <div class="auto-container">
        <!--Form Container-->
        <div class="form-container">
            <div class="row clearfix">
            
                <div class="col-lg-4 col-md-12 col-xs-12">
                    <h5><?php echo balanceTags($title);?></h5>
                    <h3><?php echo balanceTags($sub_title);?></h3>
                </div>
                <div class="col-lg-8 col-md-12 col-xs-12">
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
    </div>
</section>

<?php return ob_get_clean();?>		