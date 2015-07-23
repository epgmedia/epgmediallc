<?php
/*
 * Template Name: Page Peel Example
 */

 add_action('wp_enqueue_scripts', 'peelback_scripts');


 get_header(); ?>

 <div id="content-full" class="grid col-940">

 	<?php if( have_posts() ) : ?>

 		<?php while( have_posts() ) : the_post(); ?>

 			<?php get_template_part( 'loop-header' ); ?>

 			<?php responsive_entry_before(); ?>
 			<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
 				<?php responsive_entry_top(); ?>

 				<?php get_template_part( 'post-meta-page' ); ?>

 				<div class="post-entry">
 					<?php the_content( __( 'Read more &#8250;', 'responsive' ) ); ?>
 					<?php wp_link_pages( array( 'before' => '<div class="pagination">' . __( 'Pages:', 'responsive' ), 'after' => '</div>' ) ); ?>
 				</div>
 				<!-- end of .post-entry -->

 				<?php get_template_part( 'post-data' ); ?>

 				<?php responsive_entry_bottom(); ?>
 			</div><!-- end of #post-<?php the_ID(); ?> -->
 			<?php responsive_entry_after(); ?>

 			<?php responsive_comments_before(); ?>
 			<?php comments_template( '', true ); ?>
 			<?php responsive_comments_after(); ?>

 		<?php
 		endwhile;

 		get_template_part( 'loop-nav' );

 	else :

 		get_template_part( 'loop-no-posts' );

 	endif;
 	?>

 </div><!-- end of #content-full -->

 <?php get_footer(); ?>