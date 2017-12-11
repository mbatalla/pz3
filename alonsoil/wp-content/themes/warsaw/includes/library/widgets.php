<?php
///----Blog widgets---

/// Recent Posts 
class Bunch_Recent_Post extends WP_Widget
{
	/** constructor */
	function __construct()
	{
		parent::__construct( /* Base ID */'Bunch_Recent_Post', /* Name */esc_html__('Warsaw Recent Posts','warsaw'), array( 'description' => esc_html__('Show the recent posts', 'warsaw' )) );
	}
 

	/** @see WP_Widget::widget */
	function widget($args, $instance)
	{
		extract( $args );
		$title = apply_filters( 'widget_title', $instance['title'] );
		
		echo balanceTags($before_widget); ?>
		
        <!-- Recent Posts -->
        <div class="recent-posts">
            <?php echo balanceTags($before_title.$title.$after_title); ?>
		
			<?php $query_string = 'posts_per_page='.$instance['number'];
            if( $instance['cat'] ) $query_string .= '&cat='.$instance['cat'];
            
            $this->posts($query_string);
            ?>
            
        </div>
        
		<?php echo balanceTags($after_widget);
	}
 
 
	/** @see WP_Widget::update */
	function update($new_instance, $old_instance)
	{
		$instance = $old_instance;
		
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['number'] = $new_instance['number'];
		$instance['cat'] = $new_instance['cat'];
		
		return $instance;
	}

	/** @see WP_Widget::form */
	function form($instance)
	{
		$title = ( $instance ) ? esc_attr($instance['title']) : esc_html__('Latest News', 'warsaw');
		$number = ( $instance ) ? esc_attr($instance['number']) : 2;
		$cat = ( $instance ) ? esc_attr($instance['cat']) : '';?>
			
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php esc_html_e('Title: ', 'warsaw'); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('number')); ?>"><?php esc_html_e('No. of Posts:', 'warsaw'); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('number')); ?>" name="<?php echo esc_attr($this->get_field_name('number')); ?>" type="text" value="<?php echo esc_attr( $number ); ?>" />
        </p>
       
    	<p>
            <label for="<?php echo esc_attr($this->get_field_id('cat')); ?>"><?php esc_html_e('Category', 'warsaw'); ?></label>
            <?php wp_dropdown_categories( array('show_option_all'=>esc_html__('All Categories', 'warsaw'), 'selected'=>$cat, 'class'=>'widefat', 'name'=>$this->get_field_name('cat')) ); ?>
        </p>
            
		<?php 
	}
	
	function posts($query_string)
	{
		$query = new WP_Query($query_string); 
		if( $query->have_posts() ):?>
        
           	<!-- Title -->
			<?php while( $query->have_posts() ): $query->the_post(); ?>
            <div class="post">
                <figure class="post-thumb"><?php the_post_thumbnail('warsaw_100x100', array('class' => 'img-responsive'));?><a href="<?php echo esc_url(get_permalink(get_the_id()));?>" class="overlay-link"><span class="fa fa-link"></span></a></figure>
                <div class="desc-text"><a href="<?php echo esc_url(get_permalink(get_the_id()));?>"><?php the_title();?></a></div>
                <div class="time"><?php echo get_the_date('F d, Y');?></div>
            </div>
            <?php endwhile; ?>
            
        <?php endif;
		wp_reset_postdata();
    }
}

///----footer widgets---
//About Us
class Bunch_About_us extends WP_Widget
{
	
	/** constructor */
	function __construct()
	{
		parent::__construct( /* Base ID */'Bunch_Abous_us', /* Name */esc_html__('Warsaw Abous Us','warsaw'), array( 'description' => esc_html__('Show the information about company', 'warsaw' )) );
	}

	/** @see WP_Widget::widget */
	function widget($args, $instance)
	{
		extract( $args );
		
		echo balanceTags($before_widget);?>
      		
			<!--Footer Column-->
            <div class="about-widget">
                <figure class="footer-logo"><a href="<?php echo esc_url(home_url('/')); ?>"><img src="<?php echo esc_url($instance['logo_image']); ?>" alt=""></a></figure>
                <div class="text"><p><?php echo balanceTags($instance['content']); ?></p></div>
            </div>
            
		<?php
		
		echo balanceTags($after_widget);
	}
	
	
	/** @see WP_Widget::update */
	function update($new_instance, $old_instance)
	{
		$instance = $old_instance;
		
		$instance['logo_image'] = strip_tags($new_instance['logo_image']);
		$instance['content'] = $new_instance['content'];

		return $instance;
	}

	/** @see WP_Widget::form */
	function form($instance)
	{
		$logo_image = ($instance) ? esc_attr($instance['logo_image']) : 'http://themekalia.com/wp/warsaw/wp-content/themes/warsaw/images/logo-3.png';
		$content = ($instance) ? esc_attr($instance['content']) : '';
		?>
        
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('logo_image')); ?>"><?php esc_html_e('Logo Image:', 'warsaw'); ?></label>
            <input placeholder="<?php esc_html_e('logo link here', 'warsaw');?>" class="widefat" id="<?php echo esc_attr($this->get_field_id('logo_image')); ?>" name="<?php echo esc_attr($this->get_field_name('logo_image')); ?>" type="text" value="<?php echo esc_attr($logo_image); ?>" />
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('content')); ?>"><?php esc_html_e('Content:', 'warsaw'); ?></label>
            <textarea class="widefat" id="<?php echo esc_attr($this->get_field_id('content')); ?>" name="<?php echo esc_attr($this->get_field_name('content')); ?>" ><?php echo balanceTags($content); ?></textarea>
        </p>
                
		<?php 
	}
	
}

//KEEP IN TOUCH
class Bunch_Keep_in_Touch extends WP_Widget
{
	
	/** constructor */
	function __construct()
	{
		parent::__construct( /* Base ID */'Bunch_Keep_in_Touch', /* Name */esc_html__('Warsaw Keep in Touch','warsaw'), array( 'description' => esc_html__('Show the information about company', 'warsaw' )) );
	}

	/** @see WP_Widget::widget */
	function widget($args, $instance)
	{
		extract( $args );
		$title = apply_filters( 'widget_title', $instance['title'] );
		
		echo balanceTags($before_widget);?>
      		
			<!--Footer Column-->
            <div class="info-widget">
                <?php echo balanceTags($before_title.$title.$after_title); ?>
                <ul class="info">
                    <li><?php esc_html_e('Address :', 'warsaw'); ?> <?php echo wp_kses_post($instance['address']); ?></li>
                    <li><?php esc_html_e('Phone :', 'warsaw'); ?>  <?php echo wp_kses_post($instance['phone']); ?></li>
                    <li><?php esc_html_e('Email :', 'warsaw'); ?> <?php echo sanitize_email($instance['email']); ?></li>
                </ul>
            </div>
            
		<?php
		
		echo balanceTags($after_widget);
	}
	
	
	/** @see WP_Widget::update */
	function update($new_instance, $old_instance)
	{
		$instance = $old_instance;
		
		$instance['title'] = $new_instance['title'];
		$instance['address'] = $new_instance['address'];
		$instance['phone'] = $new_instance['phone'];
		$instance['email'] = $new_instance['email'];

		return $instance;
	}

	/** @see WP_Widget::form */
	function form($instance)
	{
		$title = ( $instance ) ? esc_attr($instance['title']) : 'Keep in Touch';
		$address = ( $instance ) ? esc_attr($instance['address']) : '44 New Design Street, Down Town,  Melbourne 005';
		$phone = ( $instance ) ? esc_attr($instance['phone']) : '800 433 633 (or) 452 23 4354';
		$email = ( $instance ) ? esc_attr($instance['email']) : 'Stevan@Example.com';
		?>
        
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php esc_html_e('Title:', 'warsaw'); ?></label>
            <input placeholder="<?php esc_html_e('Title', 'warsaw');?>" class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('address')); ?>"><?php esc_html_e('Address:', 'warsaw'); ?></label>
            <input placeholder="<?php esc_html_e('Address', 'warsaw');?>" class="widefat" id="<?php echo esc_attr($this->get_field_id('address')); ?>" name="<?php echo esc_attr($this->get_field_name('address')); ?>" type="text" value="<?php echo esc_attr($address); ?>" />
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('phone')); ?>"><?php esc_html_e('Phone:', 'warsaw'); ?></label>
            <input placeholder="<?php esc_html_e('Phone', 'warsaw');?>" class="widefat" id="<?php echo esc_attr($this->get_field_id('phone')); ?>" name="<?php echo esc_attr($this->get_field_name('phone')); ?>" type="text" value="<?php echo esc_attr($phone); ?>" />
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('email')); ?>"><?php esc_html_e('Email:', 'warsaw'); ?></label>
            <input placeholder="<?php esc_html_e('Email', 'warsaw');?>" class="widefat" id="<?php echo esc_attr($this->get_field_id('email')); ?>" name="<?php echo esc_attr($this->get_field_name('email')); ?>" type="text" value="<?php echo esc_attr($email); ?>" />
        </p>
                
		<?php 
	}
	
}

// Recent Gallery 
class Bunch_gallery extends WP_Widget
{
	/** constructor */
	function __construct()
	{
		parent::__construct( /* Base ID */'Bunch_gallery', /* Name */esc_html__('Warsaw Gallery Widget','warsaw'), array( 'description' => esc_html__('Show the Gallery images', 'warsaw' )) );
	}
 

	/** @see WP_Widget::widget */
	function widget($args, $instance)
	{
		extract( $args );
		$title = apply_filters( 'widget_title', $instance['title'] );
		
		echo balanceTags($before_widget); ?>
		
		<div class="gallery-widget">
            <?php echo balanceTags($before_title.$title.$after_title); ?>
            
			<?php 
				$args = array('post_type' => 'bunch_gallery', 'showposts'=>$instance['number']);
				if( $instance['cat'] ) $args['tax_query'] = array(array('taxonomy' => 'gallery_category','field' => 'id','terms' => (array)$instance['cat']));
				 
					
				$this->posts($args);
					
				?>
            
        </div>
        
        <?php echo balanceTags($after_widget);
	}
 
 
	/** @see WP_Widget::update */
	function update($new_instance, $old_instance)
	{
		$instance = $old_instance;
		
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['number'] = $new_instance['number'];
		$instance['cat'] = $new_instance['cat'];
		
		return $instance;
	}

	/** @see WP_Widget::form */
	function form($instance)
	{
		$title = ( $instance ) ? esc_attr($instance['title']) : esc_html__('Gallery Widget', 'warsaw');
		$number = ( $instance ) ? esc_attr($instance['number']) : 6;
		$cat = ( $instance ) ? esc_attr($instance['cat']) : '';?>
			
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php esc_html_e('Title: ', 'warsaw'); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('number')); ?>"><?php esc_html_e('No. of Posts:', 'warsaw'); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('number')); ?>" name="<?php echo esc_attr($this->get_field_name('number')); ?>" type="text" value="<?php echo esc_attr( $number ); ?>" />
        </p>
       
    	<p>
            <label for="<?php echo esc_attr($this->get_field_id('cat')); ?>"><?php esc_html_e('Category', 'warsaw'); ?></label>
            <?php wp_dropdown_categories( array('show_option_all'=>esc_html__('All Categories', 'warsaw'), 'selected'=>$cat, 'taxonomy' => 'gallery_category', 'class'=>'widefat', 'name'=>$this->get_field_name('cat')) ); ?>
        </p>
            
		<?php 
	}
	
	function posts($args)
	{
		$query = new WP_Query($args);
		if( $query->have_posts() ):?>
        
           	<!-- Title -->
				<div class="thumbs-outer clearfix">
				<?php while( $query->have_posts() ): $query->the_post();
					global $post;
				?>
                <?php 
					$post_thumbnail_id = get_post_thumbnail_id($post->ID);
					$post_thumbnail_url = wp_get_attachment_url( $post_thumbnail_id );
				?>
                	<figure class="image"><a href="<?php echo esc_url($post_thumbnail_url);?>" class="lightbox-image" data-fancybox-group="fancybox" title="<?php esc_html_e('Caption Here', 'warsaw');?>"><?php the_post_thumbnail('warsaw_90x90', array('class' => 'img-responsive'));?></a></figure>
                <?php endwhile; ?>
                </div>
        <?php endif;
		wp_reset_postdata();
    }
}
