<?php
/**
 * Plugin Name: WP Parser Import/Export
 */

add_shortcode( 'parser_export' , 'wppie_export' );
add_shortcode( 'parser_import' , 'wppie_import' );
add_action( 'wp_head', 'wppie_user_head' );

function wppie_export(){

	$args = array(
		'post_type' 		=> 'wp-parser-function',
		'posts_per_page' 	=> -1
	);

	$the_query = new WP_Query( $args );

	$string = array();

	if ( $the_query->have_posts() ) {		

		while ( $the_query->have_posts() ) {

			$the_query->the_post();

			$string[] = array(
				'title' => get_the_title(),
				'descr' => get_the_content()
			);

		}
		echo "<h3>Functions</h3>";
		echo "<textarea>".json_encode( $string )."</textarea>";

		wp_reset_postdata();

	}

	$args = array(
		'post_type' 		=> 'wp-parser-method',
		'posts_per_page' 	=> -1
	);

	$the_query = new WP_Query( $args );

	$string = array();

	if ( $the_query->have_posts() ) {		

		while ( $the_query->have_posts() ) {

			$the_query->the_post();

			$string[] = array(
				'title' => get_the_title(),
				'descr' => get_the_excerpt()
			);

		}
		echo "<h3>Methods</h3>";
		echo "<textarea>".json_encode( $string )."</textarea>";

		wp_reset_postdata();

	}

	$args = array(
		'post_type' 		=> 'wp-parser-class',
		'posts_per_page' 	=> -1
	);

	$the_query = new WP_Query( $args );

	$string = array();

	if ( $the_query->have_posts() ) {		

		while ( $the_query->have_posts() ) {

			$the_query->the_post();

			$string[] = array(
				'title' => get_the_title(),
				'descr' => get_the_excerpt()
			);

		}
		echo "<h3>Classes</h3>";
		echo "<textarea>".json_encode( $string )."</textarea>";

		wp_reset_postdata();

	}

	$args = array(
		'post_type' 		=> 'wp-parser-hook',
		'posts_per_page' 	=> -1
	);

	$the_query = new WP_Query( $args );

	$string = array();

	if ( $the_query->have_posts() ) {		

		while ( $the_query->have_posts() ) {

			$the_query->the_post();

			$string[] = array(
				'title' => get_the_title(),
				'descr' => get_the_excerpt()
			);

		}
		echo "<h3>Hooks</h3>";
		echo "<textarea>".json_encode( $string )."</textarea>";

		wp_reset_postdata();

	}

}

function wppie_import(){

	$ret = "";

	$ret .= "<form method='POST' class='form'>";
	$ret .= "<table class='table'>";

	$ret .= "<tr>";
	
	$ret .= "<td><label for='post_type'>Post Type</label></td>";
	$ret .= "<td><select name='post_type' id='post_type'>";
	$ret .= "<option value='wp-parser-function'>Functions</option>";
	$ret .= "<option value='wp-parser-method'>Methods</option>";
	$ret .= "<option value='wp-parser-class'>Classes</option>";
	$ret .= "<option value='wp-parser-hook'>Hooks</option>";
	$ret .= "</select></td>";

	$ret .= "</tr>";

	$ret .= "<tr>";

	$ret .= "<td><label for='json_contents'>JSON Content</label></td>";
	$ret .= "<td><textarea name='json_contents' id='json_contents'></textarea></td>";

	$ret .= "</tr>";

	$ret .= "<tr>";

	$ret .= "<td></td>";
	$ret .= "<td><input type='submit' name='import_parser_data' /></td>";

	$ret .= "</tr>";

	$ret .= "</table>";

	$ret .= "</form>";

	return $ret;

}

function wppie_user_head(){

	if( isset( $_POST['import_parser_data'] ) ){
		
		$post_type = sanitize_text_field( $_POST['post_type'] );

		$json = stripslashes( $_POST['json_contents'] );		

		$contents = json_decode( $json );
		
		if( $contents ){

			foreach( $contents as $content ){
				$post_content = array(
				  'post_title'    	=> wp_strip_all_tags( $content->title ),
				  'post_content'  	=> $content->descr,
				  'post_status'   	=> 'publish',
				  'post_author'   	=> 1
				  'post_type'		=> $post_type
				);
								
				wp_insert_post( $post_content );

			}

		}
		
	}

}