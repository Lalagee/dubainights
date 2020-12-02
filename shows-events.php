<?php
function showEvents(){ 

$h_url = home_url();

?>

<div class="custom-page Show_Events-page">
	<div class="event-header">
		<div class="container">
			<div class="row">
				<div class="col span_6">
					<ul class="view-tab">
						<li>View:</li>
						<li><a class="grid-btn" href="javascript:void(0)"><i class="fa fa-th" aria-hidden="true"></i>Grid</a></li>
						<li><a class="list-btn" href="javascript:void(0)"><i class="fa fa-list" aria-hidden="true"></i>List</a></li>
						<li><a class="map-btn" href="javascript:void(0)"><i class="fa fa-map-marker" aria-hidden="true"></i>Map</a></li>
					</ul>
				</div>
				<div class="col span_6 col_last">
					<ul class="sort-by">
						<li>Sort By:</li>
						<li><a href="javascript:void(0)">Name</a></li>
						<li><a href="javascript:void(0)">Date</a></li>
					</ul>
				</div>
			</div>
		</div>
	</div>
	<div class="evens_body">
		<div class="col span_3">
			<div class="side-baaar">
				<div class="search_box side_b_box gb-color">
					<form action="" method="post">
						<input id ="mysearch" type="text" name="" required="required" placeholder="Search...">
						<button><i class="fa fa-search" aria-hidden="true"></i></button>
					</form>
				</div>
				<div class="posted-event side_b_box ">
					<div class="txt-box">
						<h3>When?</h3>
					</div>
					<div class="event-box gb-color">
							<input type="radio" class="sradio" name="when" value="<?php echo date("Ymd"); ?>">
  							<label for="male">Today</label><br>
							<input type="radio" class="sradio" name="when" value="<?php echo date( 'Ymd',strtotime('next Saturday') ) ?>" >
							<label for="female">This Weekend</label><br>
							
						<h3>Date Range</h3>
						<input type="date" class="rdate" name = "srdate" >
						<input type="date" class="rdate" name = "erdate" >

					</div>
				</div>
				<div class="posted-event side_b_box ">
					<div class="txt-box">
						<h3>Where</h3>
					</div>
					<div class="event-box gb-color">
						<div class="event-check">
							<?php
							$terms = get_terms([
								    'taxonomy' => 'where_event',
									'hide_empty' => false,
												]);
							foreach ($terms as $term) {
							
							?>
							<label class="mycheck"><span class="check-txt"><?= $term->name ?></span>
								<input type="checkbox" class="scheckbox" value="<?= $term->term_id ?>" name="<?= $term->name ?>" >
								<span class="checkmark"></span>
							</label>
							<?php
							}
							?>

						</div>
					</div>
				</div>
				<div class="posted-event side_b_box ">
					<div class="txt-box">
						<h3>Music Genre</h3>
					</div>
					<div class="event-box gb-color">
						<div class="event-check">
							<?php
							$terms = get_terms([
								    'taxonomy' => 'music_event',
									'hide_empty' => false,
												]);
							foreach ($terms as $term) {
							
							?>
							<label class="mycheck"><span class="check-txt"><?= $term->name ?></span>
								<input type="checkbox" class="scheckbox" value="<?= $term->term_id ?>" name="<?= $term->name ?>" >
								<span class="checkmark"></span>
							</label>
							<?php
							}
							?>
													
						</div>
					</div>
				</div>
				<div class="posted-event side_b_box ">
					<div class="txt-box">
						<h3>Categories</h3>
					</div>
					<div class="event-box gb-color">
						<div class="event-check">
							<?php
							$terms = get_terms([
								    'taxonomy' => 'category_event',
									'hide_empty' => false,
												]);
							foreach ($terms as $term) {
							
							?>
							<label class="mycheck"><span class="check-txt"><?= $term->name ?></span>
								<input type="checkbox" class="scheckbox" value="<?= $term->term_id ?>" name="<?= $term->name ?>" >
								<span class="checkmark"></span>
							</label>
							<?php
							}
							?>
													
						</div>
						<div class="clear-btn">
							<a class="more-info-btn" onclick="window.location.reload();return false;" href="#">CLEAR FILTERS</a>
						</div>
					</div>
				</div>
				
			</div>
		</div>
		<div class="col span_9 waitmeclass">

			<!-- <div class="loading"><i class="fa fa-circle-o-notch fa-spin" style="font-size:20px"></i></div> -->


			<div class="event-body" id ="main" >
				<div class="event-heading">
					<div class="txt-box">
						<h1>Today’s events</h1>
						<p>Here Are The Most Happening Today and This week Events In Dubai. From Coolest Pool and beach parties to trendy brunches or Ladies day. Clubbing Dubai got it all right here for you! Get ready for a amazing day ahead! </p>
					</div>
				</div>
				<div class="googlemap" id="map">
					<h2>Hello i am there</h2>
				</div>
				<div class="events-pst grid">
					<div class="events-row">
						<?php
										$the_query =  new WP_Query( array(
										'posts_per_page' 	=> -1,
		                                'post_type'       	=> 'event_custom',
		                                'order'           	=> 'DESC',
		                                'post_status' 		=> 'publish',
		                                'author'        	=>  1
		                                )
		                              );

								 	?>
						<?php
			$counter = 1;
			if ( $the_query->have_posts() ) {
      			while ( $the_query->have_posts() ) {
      				$the_query->the_post();
						$divcalss = "col span_4 onclick_full_width one-fourths clear-both";
						if($counter%3 ==0)
						$divcalss = "col span_4 col_last onclick_full_width";
						$post_id 		= get_the_ID();
						$title 			= get_the_title();
						$descrip    	= get_the_content();
						$event_date 	= get_post_meta( $post_id, 'edate', true);
						$event_stime	= get_post_meta( $post_id, 'stime', true);
						$event_etime 	= get_post_meta( $post_id, 'etime', true);
						$where_event	= wp_get_post_terms($post_id,'where_event', array('fields' => 'names' ) );
						$music 	    	= wp_get_post_terms($post_id,'music_event', array('fields' => 'names' ) );
						$category   	= wp_get_post_terms($post_id,'category_event', array('fields' => 'names' ) );
						$thumbnail_id 	= get_post_thumbnail_id($post_id);
	        			$x 			  	= wp_get_attachment_image_url($thumbnail_id, 'home-slide-img-mobile',true);
						$status 	  	= get_post_status ( $post_id );
						$post_date 	  	= get_the_date( 'F j, Y' );
						?>


						<div class="<?= $divcalss; ?>">
							<div class="img-box">
								<img src="<?= $x; ?>">
							</div>
							<div class="event-disc">
								<h3><?= $title; ?></h3>
								<p><?= $event_date."".$event_stime."".$event_etime."".$descrip; ?></p>
								<a class="more-info-btn" href="#">More Info</a>
							</div>
						</div>
						<?php
						$counter++;
}
}
?>

						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<?php }

add_shortcode('Show_Events','showEvents');
//[Show_Events]
?>