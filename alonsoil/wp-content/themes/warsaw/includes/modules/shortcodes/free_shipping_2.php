<?php
ob_start() ;?>

<!--Call To Action One-->
<section class="call-to-action-two" style="background-image:url('<?php echo esc_url(wp_get_attachment_url($bg_img));?>')">
    <div class="auto-container">
        <div class="row clearfix">
        
            <div class="col-md-3 col-sm-12 col-xs-12" >
                <div class="left">
                    <h2><?php echo balanceTags($title1);?></h2>
                    <h3><?php echo balanceTags($price1);?></h3>
                </div>
            </div>
            <div class="col-md-6 col-sm-12 col-xs-12">
                <figure class="image-box wow zoomInStable" data-wow-delay="0ms" data-wow-duration="1500ms"><img src="<?php echo esc_url(wp_get_attachment_url($image));?>" alt=""></figure>
            </div>
            <div class="col-md-3 col-sm-12 col-xs-12">
                <div class="right">
                    <h2><?php echo balanceTags($title2);?></h2>
                    <h3><?php echo balanceTags($price2);?></h3>
                </div>
            </div>
            
        </div>
    </div>
</section>

<?php
	$output = ob_get_contents(); 
   ob_end_clean(); 
   return $output ; ?>