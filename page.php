<?php
/*
 * Template Post Type: page
 * Template Name: 固定ページテンプレート
 * 
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package WordPress
 * @subpackage Twenty_Twenty
 * @since Twenty Twenty 1.0
*/

get_header(); ?>

<div id="page-<?php echo $slug; ?>">
	<div class="mod-head">
		<div class="section page-header">
			<div class="content mb-0">
				<div class="header-content">
					<div>
						<h1 class="page-header_pageTitle"><?php the_title(); ?></h1>
					</div>
				</div>
			</div>
		</div><!-- [ /.page-header ] -->

		<?php
			/*-------------------------------------------*/
			/* BreadCrumb
			/*-------------------------------------------*/
			// do_action( 'lightning_breadcrumb_before' );
			// $old_file_name[] = 'module_panList.php';
			// if ( locate_template( $old_file_name, false, false ) ) {
			// 	locate_template( $old_file_name, true, false );
			// } else {
			// 	get_template_part( 'template-parts/breadcrumb' );
			// }
			// do_action( 'lightning_breadcrumb_after' );
		?>
	</div>

	<div class="mod-body">
		<div class="content">
			<?php if (have_posts()): ?>
				<?php while (have_posts()) : the_post(); ?>
					<?php the_content(); ?>
				<?php endwhile; ?>
			<?php endif; ?>
		</div>
	</div>

</div>

<?php get_footer(); ?>
