<?php
/*
Plugin Name: * 1dp - show network blogs
Plugin URI: http://1dollarplug.com
Description:  by <a href='http://1dollarplug.com'>1dollarplug.com</a> | displays all blogs in a multisite environment (tip: Visit the widgets section to activate this widget).
Version: 1.1
Author: Peter scheepens
Author URI: http://1dollarplug.com
*/

add_action( 'widgets_init', 'func_dp1_1' );

function func_dp1_1() { register_widget( 'widget_dp1_1' );}

class widget_dp1_1 extends WP_Widget 
{

function widget_dp1_1() 
	{
	$widget_ops = array( 'classname' => 'dp1_1', 'description' => __('Show all blogs in a network environment as a linked blogname (optionally with amount of posts).', 'dp1_1') );
	$control_ops = array( 'width' => 200, 'height' => 300, 'id_base' => 'dp1_1-widget' );
	$this->WP_Widget( 'dp1_1-widget', __('* 1dolllarplug.com - nework blogs<br>', 'dp1_1'), $widget_ops, $control_ops );
	}
	
	
function widget( $args, $instance ) 
	{
	extract( $args );
	$title = apply_filters('widget_title', $instance['title'] );
	$show_num = isset( $instance['show_num'] ) ? $instance['show_num'] : false;
	
	echo $before_widget;
	if ( $title ) echo $before_title . $title . $after_title;
	// begin on screen info	
	if ( is_multisite() ) 
	{
		?>
		<ul>
		<?php
		$blogs = get_last_updated('',0,160);
		if( is_array( $blogs ) ) 
			{
			foreach( $blogs as $details ) 
				{
				?>
				<div style="width:49%;float:left;overflow:hidden">
				<a href="http://<?php echo $details[ 'domain' ] . $details[ 'path' ] ?>"><?php echo get_blog_option( $details[ 'blog_id' ], 'blogname' ) ?></a>
				<?PHP
				if ($show_num)
							{
							switch_to_blog($details[ 'blog_id' ]);
							$published_posts = wp_count_posts()->publish;
							echo " -$published_posts";
							restore_current_blog();
							}
				?>
				</div>
				<?php
				}
				?>
				<div style="clear:both"></div>
				<?PHP
			}
		?>
		</ul>
		<?PHP
	}
	// end on screen info
	echo $after_widget;
	}
	
function update( $new_instance, $old_instance ) 
	{
	$instance = $old_instance;
	$instance['title'] = strip_tags( $new_instance['title'] );
	$instance['name'] = strip_tags( $new_instance['name'] );
	$instance['merch'] = strip_tags( $new_instance['merch'] );
	$instance['show_num'] = strip_tags( $new_instance['show_num'] );
	return $instance;
	}
	
function form( $instance )
	{
	if (! is_multisite() ) 
		{
		echo "Sorry, but this plugin only operates in NETWORK mode. It appears you are not running WordPress as a network right now.<br>Visit <a href='http://1dollarplug.com'>1dollarplug.com</a> for more information.";
		}
	else 
		{
		echo "<strong>Show blogs</strong><br>By placing this widget here you will show a linked list of all blogs that operate inside your network.";
		?>
		<HR>
		Your widget Title (optional)<br>
		<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" style="width:90%"/>
		<hr>
		
		<input class="checkbox" type="checkbox" <?php if( $instance['show_num']) echo "checked"; ?> id="<?php echo $this->get_field_id( 'show_num' ); ?>" name="<?php echo $this->get_field_name( 'show_num' ); ?>" />
		Show number of posts in each blog ? (Checking this option creates a bit of heavier load on server)<br>
		<hr>
		<small>Need more options ?<br>
		Visit <a href='http://1dollarplug.com'>1dollarplug.com</a> for an expanded version.</small>
		<?php
		}	
	}

}
?>
