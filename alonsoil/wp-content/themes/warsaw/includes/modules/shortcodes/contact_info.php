<?php
ob_start() ;?>

<section class="info-section">
    <div class="auto-container">
        <div class="row clearfix">
        
            <!--Info Column-->
            <div class="info-column col-md-4 col-sm-4 col-xs-12 wow fadeInUp" data-wow-delay="0ms" data-wow-duration="1500ms">
                <div class="column-header"><h3><?php esc_html_e('Ubícanos', 'warsaw');?></h3></div>
                <div class="info-box">
                    <div class="inner">
                        <div class="icon"><span class="flaticon-placeholder"></span></div>
                        <h4><?php esc_html_e('Dirección', 'warsaw');?></h4>
                        <div class="text"><?php echo wp_kses_post($address);?></div>
                    </div>
                </div>
            </div>
            
            <!--Info Column-->
            <div class="info-column col-md-4 col-sm-4 col-xs-12 wow fadeInUp" data-wow-delay="300ms" data-wow-duration="1500ms">
                <div class="column-header"><h3><?php esc_html_e('Llámanos', 'warsaw');?></h3></div>
                <div class="info-box">
                    <div class="inner">
                        <div class="icon"><span class="flaticon-technology-4"></span></div>
                        <h4><?php esc_html_e('Teléfono', 'warsaw');?></h4>
                        <div class="text"><?php echo wp_kses_post($phone_no);?> <br><?php echo balanceTags($fax);?> </div>
                    </div>
                </div>
            </div>
            
            <!--Info Column-->
            <div class="info-column col-md-4 col-sm-4 col-xs-12 wow fadeInUp" data-wow-delay="600ms" data-wow-duration="1500ms">
                <div class="column-header"><h3><?php esc_html_e('Escríbenos', 'warsaw');?></h3></div>
                <div class="info-box">
                    <div class="inner">
                        <div class="icon"><span class="flaticon-envelope"></span></div>
                        <h4><?php esc_html_e('Email', 'warsaw');?></h4>
                        <div class="text"><?php echo sanitize_email($email);?></div>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</section>

<?php
	$output = ob_get_contents(); 
   ob_end_clean(); 
   return $output ; ?>