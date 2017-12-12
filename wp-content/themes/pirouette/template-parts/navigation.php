<div id='ct-main-nav'>
	<label id="ct-main-nav__toggle-navigation" for="ct-main-nav__toggle-navigation-main"><?php echo apply_filters( 'pirouette_menu_name', 'main_navigation' ); ?> <i class="ti-align-justify"></i></label>
	<input type="checkbox" hidden id="ct-main-nav__toggle-navigation-main">
	<nav id="ct-main-nav__wrapper" itemscope itemtype="http://schema.org/SiteNavigationElement">
		<?php

			wp_nav_menu(
				array(
					'theme_location' => 'main_navigation',
					'container' => false
				)
			);
			
		?>
	</nav>
</div>
