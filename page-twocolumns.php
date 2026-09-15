<?php
/*
 * Template Post Type: page
 * Template Name: 固定ページテンプレート（2カラム用）
 * 
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

<div id="page-news">
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
		<div class="content flex-side">
			<div class="flame-body">
				<div class="news-list">
				<?php
                    if (have_posts()) :
                        while (have_posts()) : the_post();

                            global $paged;
                            $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

                            $args = array(
                                'post_status' => 'publish',
                                'posts_per_page' => '2',
                                'post_type' => 'post',
                                'category_name' => 'news,studio',
                                'paged' => $paged,
                                'orderby' => 'date',
                                'order' => 'DESC'
                            );

                            $the_query = new WP_Query($args);

                            $posts = get_posts($args);
                            foreach ($posts as $post) :
                                setup_postdata($post); // 記事データの取得

                                // 新着記事に New マークを表示
                                $days = 30; // New を表示させたい期間の日数
                                $today = date_i18n('U'); // 現在の日付を取得
                                $entry = get_the_time('U'); // 現在の投稿の時刻を取得
                                $total = date('U', ($today - $entry)) / 86400; // 秒数指定 86400 は1日

                                $taxonomies    = lightning_get_display_taxonomies();
                                if ($taxonomies) :
                                    // get $taxonomy name
                                    // $taxonomy   = key( $taxonomies );
                                    foreach ($taxonomies as $key => $value) {
                                        $taxonomy = $key;
                                        break;
                                    }

                                    $terms      = get_the_terms(get_the_ID(), $taxonomy);
                                    $term_url   = esc_url(get_term_link($terms[0]->term_id, $taxonomy));
                                    $term_name  = esc_html($terms[0]->name);
                                    $term_color = '';
                                endif;
                ?>
					<article>
						<div class="article-image">
							<a href="<?php the_permalink(); ?>">
								<?php if( get_the_post_thumbnail() ) { ?>	
								<?php the_post_thumbnail('full'); ?>
								<?php }else{ ?>
								<img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/image/common/no-image.jpg">
								<?php } ?>
							</a>
						</div>				
						<div class="article-detail">
							<div class="post-day">
								<?php if ($days > $total) : ?>
									<p class="new-mark">NEW</p>
								<?php endif; ?>
								<p>
									<a href="<?php the_permalink(); ?>">
										<time datetime="<?php the_time('Y.m.d'); ?>">
											<?php the_time('Y.m.d'); ?>
										</time>
									</a>
									<?php
                                        if (class_exists('Vk_term_color')) {
                                            $term_color = Vk_term_color::get_term_color($terms[0]->term_id);
                                            $term_color = ($term_color) ? ' style="background-color:' . $term_color . ';border:none;"' : '';
                                        }
                                        echo '<span class="entry-meta_items entry-meta_items_term"><a href="' . $term_url . '" class="btn btn-xs btn-primary entry-meta_items_term_button"' . $term_color . '>' . $term_name . '</a></span>';
                                    ?>
								</p>
							</div>
							<a href="<?php the_permalink(); ?>">
								<div class="post-title">
									<p><?php the_title(); ?></p>
								</div>
								<div class="post-content">
									<?php the_excerpt(); ?>
								</div>
							</a>
						</div>
					</article>
					<?php
						endforeach;
					?>
					
					<?php if(!$posts) : ?>
					<div class="no-news">
						<p>最新のニュースはございません。</p>
					</div>
					<?php endif; ?>
					<?php endwhile; endif; ?>
				</div>
				<div class="pnavi">
					<?php //ページリスト表示処理
							global $wp_rewrite;
							$paginate_base = get_pagenum_link(1);
							if (strpos($paginate_base, '?') || !$wp_rewrite->using_permalinks()) {
								$paginate_format = '';
								$paginate_base = add_query_arg('paged', '%#%');
							} else {
								$paginate_format = (substr($paginate_base, -1, 1) == '/' ? '' : '/') .
									user_trailingslashit('page/%#%/', 'paged');
								$paginate_base .= '%_%';
							}
							echo paginate_links(array(
								'base' => $paginate_base,
								'format' => $paginate_format,
								'total' => $the_query->max_num_pages,
								'mid_size' => 1,
								'current' => ($paged ? $paged : 1),
								'prev_text' => '<',
								'next_text' => ' >',
							)); ?>
				</div>
			</div>
			<div class="flame-side">
				<?php get_template_part('/assets/template/news-archive');?>
			</div>
		</div>
	</div>

</div>

<?php get_footer(); ?>
