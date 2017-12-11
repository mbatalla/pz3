<?php
ob_start() ;?>

<!--Contact Section-->
<section class="contact-section">
    <div class="auto-container">
        <!--Section Title-->
        <div class="sec-title-one contact">
            <h2><?php echo balanceTags($title);?></h2>
        </div>
        
        <div class="contact-form default-form">
            <?php echo do_shortcode(bunch_base_decode($contact_form));?>
        </div> 
    </div>
</section>
    
<?php
	$output = ob_get_contents(); 
   ob_end_clean(); 
   return $output ; ?>
   