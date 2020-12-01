<?php
// if (WP_DEBUG) { 
//     ini_set('display_errors', 1);
//     ini_set('display_startup_errors', 1);
//     error_reporting(E_ALL);
// }

//var_dump('hi i am in custom.php');
//exit();
// var_dump(get_post_meta(1519,'located',true));
// exit;
function show_all_events($post_ids=''){
	// if (!empty($post_ids)) {
	// 	# code...
	// }
	// else{
	// }
	$locations = array();
	$count = 0;
	$the_query =  new WP_Query( array(
							'posts_per_page' 	=> -1,
                            'post_type'       	=> 'event_custom',
                            'order'           	=> 'DESC',
                            'post_status' 		=> 'publish',
                            'author'        	=>  1
		                                )
		                              );
        if ( $the_query->have_posts() ) {
      			while ( $the_query->have_posts() ) {
      				$the_query->the_post();
      				$post_id = get_the_ID();
      				$address = get_post_meta($post_id,'located',true);
      				$lat = $address['lat'];
      				$lng = $address['lng'];
      				$locations[$count]['lat'] = $lat;
      				$locations[$count]['lng'] = $lng;
      				
      				?>
					
      				<?php

      				$count++;
      			}
  		}
  		return $locations;
}

add_action('wp_enqueue_scripts','custom_scripts');

function custom_scripts(){
	if (is_page('show-events')) {

		wp_enqueue_script( 'google_maps', 'https://maps.googleapis.com/maps/api/js?key=AIzaSyC1cDxNrmPLxUFXAIEp4VNdXEpituJPYWs&libraries=&v=weekly', '','' , true );
		/*wp_enqueue_script( 'google_maps', 'https://maps.googleapis.com/maps/api/js?key=AIzaSyC1cDxNrmPLxUFXAIEp4VNdXEpituJPYWs&callback=initMap&libraries=&v=weekly', '','' , true );*/
	
	}
	wp_enqueue_script( 'waitme-js', get_stylesheet_directory_uri().'/custom/js/waitme.js', '','' , true );
	wp_enqueue_style( 'waitme-css', get_stylesheet_directory_uri().'/custom/css/waitme.css');
	wp_enqueue_style( 'maps-css', get_stylesheet_directory_uri().'/custom/css/custom-style.css');
	wp_enqueue_script( 'custom-script', get_stylesheet_directory_uri().'/custom/js/custom-script.js', '','' , true );


	wp_localize_script('custom-script', 'the_ajax_script', array(
		'ajaxurl' => admin_url('admin-ajax.php'),
		'show_all_posts' => show_all_events()

));
}






// Custom Post type for Events


function Events() {
   $supports = array(
        'title', // post title
        'editor', // post content
        'author', // post author
        'thumbnail', // featured images
        'excerpt', // post excerpt
        'custom-fields', // custom fields
        'comments', // post comments
        'revisions', // post revisions
        'post-formats', // post formats
    );

    $labels = array(
    'name'              => _x('Events', 'plural'),
    'singular_name'     => _x('Event', 'singular'),
    'menu_name'         => _x('Events', 'admin menu'),
    'name_admin_bar'    => _x('Events', 'admin bar'),
    'view_item'         => __('View Events Property'),
    'all_items'         => __('All Events'),
    'search_items'      => __('Search Events Properties'),
    'not_found'         => __('No Events Found.'),
    

    );


    $args = array(
    'supports'          => $supports,
    'labels'            => $labels,
    'public'            => true,
    'query_var'         => true,
    'rewrite'           => array('slug' => 'events'),
    'has_archive'       => true,
    'hierarchical'      => false,
    'map_meta_cap'      => true,
    'capabilities'      => array('create_posts' => true)
    );
$args_taxonomy = array(
        'name'              => _x('Where', 'plural'),
        'public'       => false,
        'rewrite'      => false,
        'hierarchical' => true
    );

register_taxonomy('where_event',array('event_custom'), array(
    'hierarchical' => true,
    'labels' => $args_taxonomy,
    'show_ui' => true,
    'show_admin_column' => true,
    'query_var' => true,
    'rewrite' => array( 'slug' => 'event_custom' ),
  ));
$args_taxonomy = array(
        'name'              => _x('Music Genre', 'plural'),
        'public'       => true,
        'rewrite'      => true,
        'hierarchical' => true
    );

    register_taxonomy('music_event',array('event_custom'), array(
        'hierarchical' => true,
        'labels' => $args_taxonomy,
        'show_ui' => true,
        'show_admin_column' => true,
        'query_var' => true,
        'rewrite' => array( 'slug' => 'event_custom' ),
      ));
    $args_taxonomy = array(
        'name'              => _x('Category', 'plural'),
        'public'       => true,
        'rewrite'      => true,
        'hierarchical' => true
    );

    register_taxonomy('category_event',array('event_custom'), array(
        'hierarchical' => true,
        'labels' => $args_taxonomy,
        'show_ui' => true,
        'show_admin_column' => true,
        'query_var' => true,
        'rewrite' => array( 'slug' => 'event_custom' ),
      ));
register_post_type('event_custom', $args);
}
add_action('init', 'Events');


add_action('wp_ajax_search_post_by_tax','search_post_by_tax');
add_action('wp_ajax_nopriv_search_post_by_tax','search_post_by_tax');
function search_post_by_tax(){
	
	$maps = false;
	$cat_ids_arr = $_POST['cat_ids'];
	$event_date = $_POST['find_by_date'];

	$srange = str_replace('-','',$_POST['srdate']);
	$erange = str_replace('-','',$_POST['erdate']);
	if (empty($erange)) {
		 $erange = $srange;
	}
	if (is_null($event_date)) {
		$meta_query_dates = array(
			'key'     => 'edate',
            'value'   => [$srange, $erange],
            'compare' => 'BETWEEN',
            'type'    => 'DATE'
		);
	}
	if (empty($erange)) {
		$meta_query_dates = array(
			'key' => 'edate',
        	'value' => $event_date
		);
	}
	                  
		$args = array(
						'post_type' => 'event_custom',
						'posts_per_page' => -1,
						'meta_query'      => array(
                        'relation' => 'OR',
		                    $meta_query_dates
	                 ),
						'relation' => 'OR',
	           			'tax_query' => array(
						'relation' => 'OR',
					    array(
					    'taxonomy' => 'category_event',
					    'field' => 'term_id',
					    'terms' => $cat_ids_arr
					     ),
					     array(
					    'taxonomy' => 'music_event',
					    'field' => 'term_id',
					    'terms' => $cat_ids_arr
					     ),
					      array(
					    'taxonomy' => 'where_event',
					    'field' => 'term_id',
					    'terms' => $cat_ids_arr
			     )
                ),
		);

				$meta_query_range = array(
						'meta_query'      => array(
                        'relation' => 'OR',
	                     array(
	                        'key'     => 'edate',
		                    'value'   => [$srange, $erange],
		                    'compare' => 'BETWEEN',
		                    'type'    => 'DATE'
	                   )
	                 ),
				);

		// if (!empty($cat_ids_arr) || !empty($test_date)){
		$unset_this_from_query = array(
				'tax_query' => $cat_ids_arr,
				// 'meta_query' => $event_date,
			);
			foreach ($unset_this_from_query as $arg_key => $post_value) {
				if (empty($post_value)) {
					unset($args[$arg_key]);
				}

			 } 
			 // if (!empty($erange)) {
			 // 	array_push($args,$meta_query_range);
			 // }
			 // echo "<pre>";
			 // var_dump($args);exit;
		// if (empty($cat_ids_arr)) {

		// 	unset($args['tax_query']);
		// 	// $args = array(
		// 	// 			'post_type' => 'event_custom',
		// 	// 			'posts_per_page' => -1,
		// 	// 			'meta_query'      => array(
  //  //                      'relation' => 'OR',
	 //  //                    array(
	 //  //                       'key' => 'edate',
	 //  //                       'value' => $event_date
	 //  //                  )
	 //  //                ),
		// 	// 		);

		// }
		// if(empty($event_date)){
		// 	unset($args['meta_query']);

					
		// }
			
		$html ='';
		$the_query = new WP_Query( $args );
		$counter = 1;
		if ( $the_query->have_posts() ) {
          			while ( $the_query->have_posts() ) {
              				$the_query->the_post();
								$divcalss = 'col span_4 onclick_full_width one-fourths clear-both';
								if($counter%3 ==0){
									$counter ==1;
									$divcalss = 'col span_4 col_last onclick_full_width';	
								}
								
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
								
							<?php 
							$html.='	<div class="'.$divcalss.'">
									<div class="img-box">
										<img src="'.$x.'"">
									</div>
									<div class="event-disc">
										<h3>'.$title.'</h3>
										<p>'.$event_date.''.$event_stime.''.$event_etime.''.$descrip.'</p>
										<a class="more-info-btn" href="#">More Info</a>
									</div>
								</div>
								';

								$counter++;
								
		}
			if ($maps) {
				echo "maps";
					// $response['lat'] = $lat;
					// $response['long'] = $long;
					// $response['status'] = true;
				# code...
			}
			else{
					$response['html'] = $html;
					$response['status'] = true;
			}
		
	}
	else{
		$response['html'] = '<p><b>No events found!</b></p>';
		$response['status'] = false;
	}
		return response_json($response);

}


function my_acf_init() {
    acf_update_setting('google_api_key', 'AIzaSyC1cDxNrmPLxUFXAIEp4VNdXEpituJPYWs');
}
add_action('acf/init', 'my_acf_init');

function response_json($data){
    header('Content-Type: application/json');
    echo json_encode($data);
    wp_die();
}




https://www.visitlasvegas.com/shows-events/?skip=0&regionids=2&startDate=11%2F27%2F2020&endDate=12%2F03%2F2020&sort=title#



