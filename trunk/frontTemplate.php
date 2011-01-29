<?php
/*
Template Name: FrontPage
*/
?>

<?php

    // calling the header.php
    get_header();

    // action hook for placing content above #container
    thematic_abovecontainer();

?>

		<div id="container">
		
			<?php thematic_abovecontent(); ?>
		
			<div id="content">
	
	            <?php
	        
	            // calling the widget area 'page-top'
	            get_sidebar('page-top');
	
	            the_post();
	            
	            thematic_abovepost();
	        
	            ?>
	            
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
	                
	                // creating the post header
	                thematic_postheader();
	                
	                ?>
	                
					<div class="entry-content">
	
	                    <?php
	                    
	                    the_content();
	                    
	                    wp_link_pages("\t\t\t\t\t<div class='page-link'>".__('Pages: ', 'thematic'), "</div>\n", 'number');
	                    
	                    edit_post_link(__('Edit', 'thematic'),'<span class="edit-link">','</span>') ?>
	
					</div><!-- .entry-content -->
				</div><!-- #post -->
	
	        <?php
	        
	        thematic_belowpost();
	        
	        // calling the comments template
       		if (THEMATIC_COMPATIBLE_COMMENT_HANDLING) {
				if ( get_post_custom_values('comments') ) {
					// Add a key/value of "comments" to enable comments on pages!
					thematic_comments_template();
				}
			} else {
				thematic_comments_template();
			}
	        
	        // calling the widget area 'page-bottom'
	        get_sidebar('page-bottom');
	        
	        ?>
	
			</div><!-- #content -->
			
			<?php thematic_belowcontent(); ?> 
			
		</div><!-- #container -->

<?php 

    // action hook for placing content below #container
    thematic_belowcontainer();

    // calling the standard sidebar 
  //  thematic_sidebar();
?>
<!-- custom side bar for the front page -->
<div id='frontpage-sidebar' class='aside main-aside'>

<!-- table listing the weekly activities -->
	<div id='weekly-activities'>
		<h3>Weekly Activities</h3>
		<table id='weekly-activities-table'>
		<tbody>
			<tr> 
				<td colspan='2' class='activity'><h4 class='activity-day'>Sun</h4></td>
			</tr>
			<tr>
				<td class='activity-time'>9am</td>
				<td class='activity'>English Worship Service (Main)</td>
			</tr>
			<tr>
				<td class='activity-time'>11am</td>
				<td class='activity'>Sunday School</td>
			</tr>
			<tr>
				<td class='activity-time'>4pm</td>
				<td class='activity'>Evening Combined Service (Kannada, Tamil & Malayalam)</td>
			</tr>

			<tr>
				<td colspan='2' class='activity'><h4 class='activity-day'>Wed</h4></td>
			</tr>
			<tr>
				<td class='activity-time'>6.30pm</td>
				<td class='activity'>Mid-Week Prayer Meeting</td>
			</tr>

			<tr>
				<td colspan='2' class='activity'><h4 class='activity-day'>Fri</h4></td>
			</tr>
			<tr>
				<td class='activity-time'>6.30pm</td>
				<td class='activity'>Weekly Bible Study</td>
			</tr>

			<tr>
				<td colspan='2' class='activity'><h4 class='activity-day'>Sat</h4></td>
			</tr>
			<tr>
				<td class='activity-time'>4pm</td>
				<td class='activity'>Children's Fellowship <br/> Youth Fellowship</td>
			</tr>
		</tbody>	
		</table>
	</div>

	<div id='mission-label'>
		<span><a href="http://www.maranatha-bpc.com/" title="Maranatha Bible-Presbyterian Church">Maranatha Bible-Presbyterian Church</a></span>
	</div>
</div>
<?php
    
    // calling footer.php
    get_footer();

?>
