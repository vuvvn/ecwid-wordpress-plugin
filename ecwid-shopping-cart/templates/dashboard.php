<div class="wrap ecwid-admin ecwid-dashboard">
	<div class="box">
		<div class="head">
			<?php ecwid_embed_svg('ecwid_logo_symbol_RGB');?>
			<h3>
				<?php _e( 'Ecwid Shopping Cart', 'ecwid-shopping-cart' ); ?>
			</h3>
			<div class="store-id drop-down">
					<span>
						<?php _e( 'Store ID', 'ecwid-shopping-cart' ); ?> : <?php echo get_ecwid_store_id(); ?>
					</span>
				<ul>
					<li>
						<a href="admin-post.php?action=ecwid_disconnect"><?php _e( 'Disconnect store', 'ecwid-shopping-cart' ); ?></a>
					</li>
				</ul>
			</div>
		</div>
		<div class="body">
			<div class="greeting-image">
				<img src="<?php echo(esc_attr(ECWID_PLUGIN_URL)); ?>/images/store_ready.png" width="142" />
			</div>

			<div class="greeting">
			<?php if (@$_GET['settings-updated']): ?>
				<div class="greeting-title">
					<?php _e('Congratulations!', 'ecwid-shopping-cart'); ?>
				</div>
				<div class="greeting-message mobile-br">
					<?php _e( 'Your Ecwid store is now connected<br /> to your WordPress website', 'ecwid-shopping-cart' ); ?>
				</div>
			<?php else: ?>

				<div class="greeting-title">
					<?php _e('Greetings!', 'ecwid-shopping-cart'); ?>
				</div>
				<div class="greeting-message mobile-br">
					<?php _e( 'Your Ecwid store is connected<br /> to your WordPress website', 'ecwid-shopping-cart' ); ?>
				</div>
			<?php endif; ?>

			<ul class="greeting-links">
				<li>
					<a target="_blank" href="<?php echo ecwid_get_store_page_url(); ?>"><?php _e('Visit storefront', 'ecwid-shopping-cart'); ?></a>
				</li>
				<li>
					<a target="_blank" href="//my.ecwid.com/cp?source=wporg"><?php _e('Open control panel', 'ecwid-shopping-cart'); ?></a>
				</li>
			</ul>



			</div>
		</div>

	</div>
	<p><?php echo sprintf(__('Questions? Visit <a %s>Ecwid support center</a>', 'ecwid-shopping-cart'), 'target="_blank" href="http://help.ecwid.com/?source=wporg"'); ?></p>
</div>
