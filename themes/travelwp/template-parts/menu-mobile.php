<?php
add_action( 'wp_footer', 'phsycode_travelwp_add_menu_mobile' );
function phsycode_travelwp_add_menu_mobile() {
	$show_menu_mobile = travelwp_get_option( 'header_mobile_menu', 0 );

	$is_single_tour = $show_search = false;
	if ( is_single() && get_post_type() == 'product' && wc_get_product()->get_type() == 'tour_phys' ) {
		$is_single_tour = true;
	}
	if ( ! empty( $show_menu_mobile ) && $show_menu_mobile == 1 && ! $is_single_tour ) {
		$nav_mobile_items = travelwp_get_option( 'menu_mobile_icon' );
		if ( ! empty( $nav_mobile_items ) && is_array( $nav_mobile_items ) ) {
			$nav_mobile_items = array_keys( array_filter( $nav_mobile_items, fn( $v ) => $v === '1' ) );
			echo '<div class="navbar-mobile-button">';
			foreach ( $nav_mobile_items as $nav_item ) {
				$nav_item = apply_filters( 'physc_navbar_mobile_button', $nav_item );
				switch ( $nav_item ) {
					case 'home':
						$active    = ( is_front_page() && ! is_home() ) ? ' active' : '';
						$icon_home = '<svg width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg"> 
										<path d="M8.14438 19.7824V16.7156C8.14437 15.9384 8.77632 15.307 9.55909 15.3021H12.4333C13.2195 15.3021 13.8569 15.935 13.8569 16.7156V16.7156V19.7735C13.8569 20.4476 14.4047 20.9954 15.0836 21.0003H17.0445C17.9603 21.0027 18.8394 20.6431 19.4878 20.001C20.1362 19.3589 20.5007 18.4871 20.5007 17.5778V8.86617C20.5006 8.13171 20.1727 7.43504 19.6053 6.96383L12.9436 1.67459C11.7792 0.749448 10.116 0.779334 8.98604 1.74571L2.46766 6.96383C1.87339 7.42115 1.5182 8.11989 1.50065 8.86617V17.569C1.50065 19.464 3.04803 21.0003 4.95682 21.0003H6.87294C7.19982 21.0027 7.51414 20.8754 7.74612 20.6467C7.97811 20.4181 8.10858 20.107 8.10857 19.7824H8.14438Z" stroke="#4F5E71" stroke-width="1.5"/>
									</svg>';
						echo '<a href="' . esc_url( home_url( '/' ) ) . '" title="' . esc_attr( get_bloginfo( 'name', 'display' ) ) . '" class="item-menubar' . $active . '">' . $icon_home . '<span>' . esc_html__( 'Home', 'travelwp' ) . '</span></a>';
						break;
					case 'tours':
						if ( class_exists( 'Tour_Settings_Tab_Phys' ) ) {
							$tour_page_id = get_option( Tour_Settings_Tab_Phys::$_tours_show_page_id );
							$active       = TravelPhysUtility::check_is_tour_archive() ? ' active' : '';
							$icon_tours   = '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M8 6H21" stroke="#4F5E71" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
							<path d="M8 12H21" stroke="#4F5E71" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
							<path d="M8 18H21" stroke="#4F5E71" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
							<path d="M3 6H3.01" stroke="#4F5E71" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
							<path d="M3 12H3.01" stroke="#4F5E71" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
							<path d="M3 18H3.01" stroke="#4F5E71" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
							</svg>';
							echo '<a href="' . get_the_permalink( $tour_page_id ) . '" title="' . esc_html__( 'Tours', 'travelwp' ) . '" class="item-menubar' . $active . '">' . $icon_tours . '<span>' . esc_html__( 'Tours', 'travelwp' ) . '</span></a>';
						}

						break;
					case 'shop':
						if ( class_exists( 'WooCommerce' ) ) {
							$active       = is_shop() ? ' active' : '';
							$shop_page_id = get_option( 'woocommerce_shop_page_id' );
							$icon_shop    = '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
												<path d="M6 2L3 6V20C3 20.5304 3.21071 21.0391 3.58579 21.4142C3.96086 21.7893 4.46957 22 5 22H19C19.5304 22 20.0391 21.7893 20.4142 21.4142C20.7893 21.0391 21 20.5304 21 20V6L18 2H6Z" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
												<path d="M3 6H21" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
												<path d="M16 10C16 11.0609 15.5786 12.0783 14.8284 12.8284C14.0783 13.5786 13.0609 14 12 14C10.9391 14 9.92172 13.5786 9.17157 12.8284C8.42143 12.0783 8 11.0609 8 10" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
												</svg>';
							echo '<a href="' . get_the_permalink( $shop_page_id ) . '" title="' . esc_html__( 'Shop', 'travelwp' ) . '" class="item-menubar' . $active . '">' . $icon_shop . '<span>' . esc_html__( 'Shop', 'travelwp' ) . '</span></a>';
						}
						break;
					case 'search':
						$active      = ( is_search() ) ? ' active' : '';
						$icon_search = '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
						<path d="M10.875 18.75C15.2242 18.75 18.75 15.2242 18.75 10.875C18.75 6.52576 15.2242 3 10.875 3C6.52576 3 3 6.52576 3 10.875C3 15.2242 6.52576 18.75 10.875 18.75Z" stroke="#4F5E71" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
						<path d="M16.4437 16.4437L21 21" stroke="#4F5E71" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
						</svg>';
						echo '<a href="#" title="' . esc_html__( 'Search Tour', 'travelwp' ) . '" class="item-menubar search-popup-tours' . $active . '">' . $icon_search . '<span>' . esc_html__( 'Search', 'travelwp' ) . '</span></a>';
						$show_search = true;
						break;
					case 'account':
						$link_account = apply_filters( 'physc_link_account', ( is_user_logged_in() ) ? get_permalink( get_option( 'woocommerce_myaccount_page_id' ) ) : get_permalink( get_option( 'woocommerce_myaccount_page_id' ) ) );
						$icon_account = '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
							<path d="M23.9236 27.121V24.2322C23.9236 22.6998 23.3149 21.2302 22.2313 20.1467C21.1478 19.0631 19.6782 18.4544 18.1458 18.4544H6.59028C5.05792 18.4544 3.58832 19.0631 2.50477 20.1467C1.42123 21.2302 0.8125 22.6998 0.8125 24.2322V27.121" stroke="black" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
							<path d="M12.368 12.6767C15.559 12.6767 18.1458 10.0899 18.1458 6.89887C18.1458 3.70789 15.559 1.12109 12.368 1.12109C9.17706 1.12109 6.59026 3.70789 6.59026 6.89887C6.59026 10.0899 9.17706 12.6767 12.368 12.6767Z" stroke="black" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
							</svg>';
						$active       = ( $link_account == get_the_permalink( get_the_ID() ) ) ? ' active' : '';
						echo '<div class="item-menubar thim-login-popup thim-link-login' . $active . '"><a class="login js-show-popup flex-center" href="' . esc_url( $link_account ) . '" title="' . esc_html__( 'Account', 'travelwp' ) . '">' . $icon_account . '<span>' . esc_html__( 'Account', 'travelwp' ) . '</span></a></div>';
						break;
					case 'cart':
						if ( class_exists( 'WooCommerce' ) ) {
							$active    = is_cart() ? ' active' : '';
							$icon_cart = '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
							<path d="M17.25 17.25H6.54375L3.92813 2.86875C3.89752 2.69653 3.80768 2.54042 3.67415 2.42743C3.54062 2.31444 3.37179 2.25168 3.19687 2.25H1.5" stroke="#4F5E71" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
							<path d="M7.5 21C8.53553 21 9.375 20.1605 9.375 19.125C9.375 18.0895 8.53553 17.25 7.5 17.25C6.46447 17.25 5.625 18.0895 5.625 19.125C5.625 20.1605 6.46447 21 7.5 21Z" stroke="#4F5E71" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
							<path d="M17.25 21C18.2855 21 19.125 20.1605 19.125 19.125C19.125 18.0895 18.2855 17.25 17.25 17.25C16.2145 17.25 15.375 18.0895 15.375 19.125C15.375 20.1605 16.2145 21 17.25 21Z" stroke="#4F5E71" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
							<path d="M5.85938 13.5H17.6344C17.985 13.5011 18.3247 13.3785 18.5939 13.1539C18.8631 12.9293 19.0445 12.617 19.1063 12.2719L20.25 6H4.5" stroke="#4F5E71" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
							</svg>';
							echo '<a href="' . wc_get_cart_url() . '" title="' . esc_html__( 'Cart', 'travelwp' ) . '" class="item-menubar' . $active . '">' . $icon_cart . '<span>' . esc_html__( 'Cart', 'travelwp' ) . '</span></a>';
						}
						break;
				}
			}
			echo '</div>';
		}

		// Show content popup of search
		$form_search_mobile_popup = travelwp_get_option( 'form_search_mobile_popup', '' );
		if ( $form_search_mobile_popup && $show_search ) {
			?>
			<div class="phys-search-tour-mobile phys-search-popup-mobile">
				<div class="phys-search-tour-mobile-inner">
					<div class="phys-search-tour-mobile-header">
						<span></span>
						<h4><?php esc_html_e( 'Search', 'travelwp' ); ?></h4>
						<span class="phys-search-tour-mobile-close">
							<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16"
								fill="none">
								<path d="M12.5 3.5L3.5 12.5" stroke="#AAAFB6" stroke-width="1.5" stroke-linecap="round"
										stroke-linejoin="round"></path>
								<path d="M12.5 12.5L3.5 3.5" stroke="#AAAFB6" stroke-width="1.5" stroke-linecap="round"
										stroke-linejoin="round"></path>
							</svg>
						</span>
					</div>
					<?php
					echo '<div class="phys-search-tour-mobile-content">' . do_shortcode( $form_search_mobile_popup ) . '</div>';
					?>
				</div>
			</div>
			<?php
		}
	}
}
