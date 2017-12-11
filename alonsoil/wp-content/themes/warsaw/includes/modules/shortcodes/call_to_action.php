<?php
ob_start() ;?>

<!--Call TO Action One-->
<section class="call-to-action-one" style="background-image:url('<?php echo esc_url(wp_get_attachment_url($bg_img));?>')">
    
    <!--Floted Image Left-->
    <figure class="floated-image-left wow fadeInLeft" data-wow-delay="0ms" data-wow-duration="1500ms"><img src="<?php echo esc_url(wp_get_attachment_url($image1));?>" alt=""></figure>
    
    <!--Floted Image Right-->
    <figure class="floated-image-right wow fadeInRight" data-wow-delay="0ms" data-wow-duration="1500ms"><img src="<?php echo esc_url(wp_get_attachment_url($image2));?>" alt=""></figure>
    
    <div class="auto-container">
        <div class="content-box">
            <div class="sub-title"><?php echo balanceTags($title);?></div>
            <div class="phone-number"><?php echo wp_kses_post($phone_no);?></div>
            <div class="text"><?php echo balanceTags($text);?></div>
        </div>
    </div>
</section>

<?php
	$output = ob_get_contents(); 
   ob_end_clean(); 
   return $output ; ?>