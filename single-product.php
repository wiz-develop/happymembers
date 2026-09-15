<?php
/**
 * The template for displaying single posts and pages.
 * 
 * Template Name: 商品情報（商品詳細）
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package WordPress
 * @subpackage Twenty_Twenty
 * @since Twenty Twenty 1.0
 */

get_header();
?>

<div id="page-product" class="single-product">
	<div class="mod-body">
		<div class="content flex-side">
			<div class="flame-side">
				<?php get_template_part('/assets/template/product-nav');?>
			</div>
			<div class="flame-body">
				<div class="page-title">
					<img src="/cms/wp-content/themes/happy-members/assets/images/common/buy-step/order.png">
				</div>
				<div class="product">
					<div class="product__image">
						<?//php if ($image) : ?>
							<img src="<?php echo $cfs->get('image'); ?>">
						<?//php else : ?>
							<!-- <img src="/cms/wp-content/themes/happy-members/assets/images/product/no-image.png" alt="no image"> -->
						<?//php endif; ?>
					</div>
					<div class="product__detail">
						<div class="product-category">
							健康食品
						</div>
						<div class="product__detail__name">
							<h1><?php the_title(); ?></h1>
						</div>
						<div class="product__detail__txt">
							<table>
								<tr>
									<td class="item-name">購入金額</td>
									<td class="item-detail price">¥ 000,000<span>(税込)</span></td>
								</tr>
								<tr>
									<td class="item-name">定価</td>
									<td class="item-detail price">¥ <?php echo $cfs->get('regular_price'); ?>(税込)</span></td>
								</tr>
								<tr>
									<td class="item-name">取引区分</td>
									<td class="item-detail">ハッピー</td>
								</tr>
								<tr>
									<td class="item-name">数量</td>
									<td class="item-detail">
										<div class="order-field">
											<button class="button" id="down">－</button>
											<input type="text" value="0" class="inputtext" id="textbox">
											<button class="button" id="up">＋</button>
										</div>
									</td>
								</tr>
							</table>
						</div>	
					</div>
				</div>
				<div class="order-btnlist">
					<div class="cart-button">
						カートに追加
					</div>
					<div class="order-procedure">
						注文手続きへ
					</div>
					<a href="/product/">
						<div class="black-btn">
							商品一覧へ戻る
						</div>
					</a>
				</div>
			</div>
		</div>
	</div>

</div>

<?php get_footer(); ?>
