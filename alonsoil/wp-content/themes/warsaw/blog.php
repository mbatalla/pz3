<!--News Style One-->
<div class="news-style-one">
    <div class="inner-box">
    	<?php if ( has_post_thumbnail() ):?>
        <figure class="image-box">
        	<a href="<?php echo esc_url(get_permalink(get_the_id()));?>">
        		<?php the_post_thumbnail('warsaw_1170x450');?>
        	</a>
        </figure>
        <?php endif;?>
        <div class="lower-content">
            <h3><a href="<?php echo esc_url(get_permalink(get_the_id()));?>"><?php the_title();?></a></h3>
            <div class="text"><?php the_excerpt();?> </div>
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