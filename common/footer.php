		<footer role="contentinfo">
			<div id="footer-text">
				<?php echo get_theme_option('Footer Text'); ?>

				<p class="contact">
					Questions or problems contact: 
					<a href="mailto:<?php echo option('administrator_email'); ?>">
					<?php echo option('author'); ?></a>
				</p>

				<?php if ((get_theme_option('Display Footer Copyright') == 1) && $copyright = option('copyright')): ?>
					<p class="copyright"><?php echo $copyright; ?></p>
				<?php endif; ?>

				<!-- p><?php echo __('Proudly powered by <a href="http://omeka.org">Omeka</a>.'); ?></p -->

			</div>
			<?php fire_plugin_hook('public_footer', array('view' => $this)); ?>
		</footer>
	</body>
</html>
