<?php 
		
$um_user_id = um_profile_id();
$user_id = wp_get_current_user()->ID;
$my_class = ($user_id == $um_user_id) ? 'vg-secs-block' : '';	

if ($_GET['profiletab'] !== 'latest-secs' && $_GET['profiletab'] !== 'trending-secs') {
 
?>
	<div class="container-fluid <?php echo $my_class; ?>">
    	<div class="row">
    	<?php	
    		if(is_user_logged_in()){
    			$args = array( 
    			'post_type' =>  array( 'vg_video', 'attachment'),
    			'post_status' => array('publish', 'inherit'),
    			'posts_per_page' => 5,
    			'author' =>  $um_user_id);
    		}
    		if(!is_user_logged_in()){
    			$args = array( 
    			'post_type' =>  array( 'vg_video', 'attachment'),
    			'post_status' => array('publish', 'inherit'),
    			'posts_per_page' => 5,
    			'author' =>  um_profile_id());
    		}
				$loop = new WP_Query( $args );
				if ($loop->have_posts()) { ?>

					<div id="my-secs">

					<?php foreach ($loop->posts as $post) {
							echo(vg_get_day_block($post, $um_user_id));
						} 
					?>

					</div>

					<div class="clearfix"></div>
				<?php } 
				?>

				<div id="all-secs">

				</div>
				<div class="clearfix"></div>
    	</div>
    </div>
<?php }  ?>
