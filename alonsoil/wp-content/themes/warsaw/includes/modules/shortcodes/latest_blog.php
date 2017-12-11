<?php  
   global $post ;
   $count = 0;
   $query_args = array('post_type' => 'post' , 'showposts' => $num , 'order_by' => $sort , 'order' => $order);
   if( $cat ) $query_args['category_name'] = $cat;
   $query = new WP_Query($query_args) ; 
   ob_start() ;?>   
<?php if($query->have_posts()):  ?>  

<!--News Section-->
<section class="news-section">
    <div class="auto-container">
        <!--Section Title-->
        <div class="sec-title-one">
            <h2><?php echo balanceTags($title);?></h2>
        </div>
        
        <div class="row clearfix">
            <?php while($query->have_posts()): $query->the_post();
                global $post ; 
                $post_meta = _WSH()->get_meta();
            ?>
            <!--News Style One-->
            <div class="news-style-one col-md-4 col-sm-6 col-xs-12">
                <div class="inner-box">
                    <figure class="image-box"><a href="<?php echo esc_url(get_permalink(get_the_id()));?>"><?php the_post_thumbnail('');?></a></figure>
                    <div class="lower-content">
                        <h3 class="main-title"><a href="<?php echo esc_url(get_permalink(get_the_id()));?>"><?php the_title();?></a></h3>
                        <div class="text"><?php echo wp_kses_post(warsaw_trim(get_the_content(), $text_limit));?></div>
                        <div class="info clearfix">
                            <ul class="post-meta clearfix">
                                <li><a href="<?php echo esc_url(get_month_link(get_the_date('Y'), get_the_date('m'))); ?>"><span class="fa fa-clock-o"></span> <?php echo get_the_date('M d, Y')?></a></li>
                                <li><a href="<?php echo esc_url(get_permalink(get_the_id()).'#comment');?>"><span class="fa fa-comment-o"></span> <?php comments_number( '0 comment', '1 comment', '% comments' ); ?></a></li>
                            </ul>
                            <div class="more-link"><a href="<?php echo esc_url(get_permalink(get_the_id()));?>"><?php esc_html_e('Read More', 'warsaw');?></a></div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endwhile;?>
        </div>
    </div>
</section>

<?php endif; ?>
<?php 
	wp_reset_postdata();
   $output = ob_get_contents(); 
   ob_end_clean(); 
   return $output ; ?>