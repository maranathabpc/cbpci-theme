<?php

//
//  Custom Child Theme Functions
//

// I've included a "commented out" sample function below that'll add a home link to your menu
// More ideas can be found on "A Guide To Customizing The Thematic Theme Framework" 
// http://themeshaper.com/thematic-for-wordpress/guide-customizing-thematic-theme-framework/

// Adds a home link to your menu
// http://codex.wordpress.org/Template_Tags/wp_page_menu
//function childtheme_menu_args($args) {
//    $args = array(
//        'show_home' => 'Home',
//        'sort_column' => 'menu_order',
//        'menu_class' => 'menu',
//        'echo' => true
//    );
//	return $args;
//}
//add_filter('wp_page_menu_args','childtheme_menu_args');

// Unleash the power of Thematic's dynamic classes
// 
// define('THEMATIC_COMPATIBLE_BODY_CLASS', true);
// define('THEMATIC_COMPATIBLE_POST_CLASS', true);

// Unleash the power of Thematic's comment form
//
// define('THEMATIC_COMPATIBLE_COMMENT_FORM', true);

// Unleash the power of Thematic's feed link functions
//
// define('THEMATIC_COMPATIBLE_FEEDLINKS', true);

function new_header() {
	remove_action('thematic_header','thematic_brandingopen',1);
	remove_action('thematic_header','thematic_access',9);
}

add_action('init','new_header');
add_action('thematic_header','thematic_access',1);
add_action('thematic_header','thematic_brandingopen',2);

function footer_pagelinks() { ?>
	<div id='footer-links'>
		<a href='<?php bloginfo('url');?>/privacy-statement'>Privacy Statement</a>
		&nbsp;|&nbsp;
		<a href='<?php bloginfo('url');?>/disclaimer'>Disclaimer</a>
	</div>
<?php
}
add_action('thematic_footer','footer_pagelinks',35);

function childtheme_postheader($postheader) {
	if(!is_front_page()) {
		$postheader = yoast_breadcrumb("<div id='yoastbreadcrumb'>",'</div>',false) . $postheader;
	}
	return $postheader;
}
add_filter('thematic_postheader','childtheme_postheader');

/*
//filters for guest authors
add_filter('the_author','guest_author_name');
add_filter('get_the_author_display_name','guest_author_name');

function guest_author_name($name) {
	global $post;
	$author = get_post_meta($post->ID,'guest-author',true);

	if($author)
		$name = $author;

	return $name;
}*/

function page_sidebar($origSidebar) {
	if(!is_page())
		get_sidebar('page');
	else
		get_sidebar('resources');
}
add_filter('thematic_sidebar','page_sidebar');

/*
function photo_content($content) {
	if (is_category('Photos'))
		$content = 'full';
	return $content;
}
add_filter('thematic_content','photo_content');
 */

//overrides default function for authorlink
//if guest-author custom field is filled, use that as a display name but do not provide a link
//else provide a link for all posts by that author as usual
function childtheme_override_postmeta_authorlink()
{
	global $authordata;
	global $post;
	$author = get_post_meta($post->ID,'guest-author',true);

	$authorlink = '<span class="meta-prep meta-prep-author">' . __('By ', 'thematic') . '</span>';
	$authorlink .= '<span class="author vcard">';
	if(!$author) {
		$authorlink .= '<a class="url fn n" href="';
		$authorlink .= get_author_posts_url($authordata->ID, $authordata->user_nicename);
		$authorlink .= '" title="' . __('View all posts by ', 'thematic') . get_the_author_meta( 'display_name' ) . '">';
		$authorlink .= get_the_author_meta( 'display_name' );
		$authorlink .= '</a>';
	}
	else {
		$authorlink .= $author;
	}
	$authorlink .= '</span>';

	return apply_filters('thematic_post_meta_authorlink', $authorlink);

}

/*
//overrides default function for thematic content
//checks post category and shows the full post if it belongs to the category
//if not, show only the excerpt
function childtheme_override_content()
{
	global $thematic_content_length;

	if(in_category('Photos') || is_single())
		$thematic_content_length = 'full';
	else
		$thematic_content_length = 'excerpt';

	if ( strtolower($thematic_content_length) == 'full' ) {
		$post = get_the_content(more_text());
		$post = apply_filters('the_content', $post);
		$post = str_replace(']]>', ']]&gt;', $post);
	} elseif ( strtolower($thematic_content_length) == 'excerpt') {
		$post = '';
		$post .= get_the_excerpt();
		$post = apply_filters('the_excerpt',$post);
		if ( apply_filters( 'thematic_post_thumbs', TRUE) ) {
			$post_title = get_the_title();
			$size = apply_filters( 'thematic_post_thumb_size' , array(100,100) );
			$attr = apply_filters( 'thematic_post_thumb_attr', array('title'	=> 'Permalink to ' . $post_title) );
			if ( has_post_thumbnail() ) {
				$post = '<a class="entry-thumb" href="' . get_permalink() . '" title="Permalink to ' . get_the_title() . '" >' . get_the_post_thumbnail(get_the_ID(), $size, $attr) . '</a>' . $post;
			}
		}
	} elseif ( strtolower($thematic_content_length) == 'none') {
	} else {
		$post = get_the_content(more_text());
		$post = apply_filters('the_content', $post);
		$post = str_replace(']]>', ']]&gt;', $post);
	}
	echo apply_filters('thematic_post', $post);
} */

//add filter to thematic_content filter hook
//show full post if Photos or single post, else show excerpt
//needed to show excerpts on search/category pages
function chooseContentLength() {
	if(in_category('Photos') || is_single()) 
		return 'full';
	else
		return 'excerpt';
}

add_filter('thematic_content','chooseContentLength');

//remove default index loop and replace it with a custom one
//shows 1st post and Photo posts as full posts, else show excerpts
function remove_index_loop() {
	remove_action('thematic_indexloop', 'thematic_index_loop');
}
add_action('init','remove_index_loop');

function child_index_loop() {
		global $options, $blog_id;
		
		foreach ($options as $value) {
		    if (get_option( $value['id'] ) === FALSE) { 
		        $$value['id'] = $value['std']; 
		    } else {
		    	if (THEMATIC_MB) 
		    	{
		        	$$value['id'] = get_option($blog_id,  $value['id'] );
		    	}
		    	else
		    	{
		        	$$value['id'] = get_option( $value['id'] );
		    	}
		    }
		}
		
		/* Count the number of posts so we can insert a widgetized area */ $count = 1;
		while ( have_posts() ) : the_post();
				global $thematic_content_length;	

				thematic_abovepost(); 
				if($count==1)
					$thematic_content_length = 'full'; ?>	
				
				<div id="post-<?php the_ID();
					echo '" ';
					if (!(THEMATIC_COMPATIBLE_POST_CLASS)) {
						post_class();
						echo '>';
					} else {
						echo 'class="';
						thematic_post_class();
						echo '">';
					}
     				thematic_postheader(); ?>
					<div class="entry-content">
<?php thematic_content(); ?>

					<?php wp_link_pages('before=<div class="page-link">' .__('Pages:', 'thematic') . '&after=</div>') ?>
					</div><!-- .entry-content -->
					<?php thematic_postfooter(); ?>
				</div><!-- #post -->

			<?php 
				
				thematic_belowpost();
				
				comments_template();

				if ($count==$thm_insert_position) {
						get_sidebar('index-insert');
				}
				$count = $count + 1;
		endwhile;
}
 //end child index loop

add_action('thematic_indexloop', 'child_index_loop');

function new_excerpt_more($more) {
	global $post;
	return '<a href="'. get_permalink($post->ID) . '">'. $more . '</a>';
}
add_filter('excerpt_more', 'new_excerpt_more');

//code for google analytics
add_action('wp_footer','add_googleanalytics');
function add_googleanalytics() { ?>
	<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-20095209-1']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>
<?php }

function ubuntu_font() {
?>
    <link href='http://fonts.googleapis.com/css?family=Ubuntu:regular,italic,bold' rel='stylesheet' type='text/css'>
<?php
}

add_action('wp_head', 'ubuntu_font');
?>
