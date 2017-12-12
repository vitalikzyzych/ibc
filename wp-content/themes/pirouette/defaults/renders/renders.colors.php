<?php

class PirouetteColorsCSS{

	private $_color_bg;
	private $_color_text;
	private $_color_primary;
	private $_color_links;

	private $_color_nav_text;
	private $_color_nav_active;
	private $_color_nav_bg;

	private $_color_nav_sticky_text;
	private $_color_nav_sticky_active;
	private $_color_nav_sticky_bg;

	private $_color_heading;
	private $_color_heading_bg;
	private $_color_heading_subtitle;

	private $_color_h1;
	private $_color_h2;
	private $_color_h3;
	private $_color_h4;
	private $_color_h5;
	private $_color_h6;

	private $_color_footer_bg;
	private $_color_footer_text;
	private $_color_footer_links;
	private $_color_footer_titles;

	private $_svg_dropdown;

	public function __construct(){

		/** Render Typography */
		add_action( 'wp_enqueue_scripts', array( $this, 'render_colors' ) );

	}

	/** Render Typography */
	function render_colors(){

		$this->_color_bg 			= new PirouetteColor( esc_attr( get_theme_mod( 'color_bg', '#ffffff' ) ) );
		$this->_color_text 		= new PirouetteColor( esc_attr( get_theme_mod( 'color_text', '#666666' ) ) );
		$this->_color_primary = new PirouetteColor( esc_attr( get_theme_mod( 'color_primary', '#c9ac8c' ) ) );
		$this->_color_links 	= new PirouetteColor( esc_attr( get_theme_mod( 'color_links', '#011627' ) ) );

		$this->_color_h1 			= new PirouetteColor( esc_attr( get_theme_mod( 'color_h1', $this->_color_links ) ), $this->_color_links );
		$this->_color_h2 			= new PirouetteColor( esc_attr( get_theme_mod( 'color_h2', $this->_color_links ) ), $this->_color_links );
		$this->_color_h3 			= new PirouetteColor( esc_attr( get_theme_mod( 'color_h3', $this->_color_links ) ), $this->_color_links );
		$this->_color_h4 			= new PirouetteColor( esc_attr( get_theme_mod( 'color_h4', $this->_color_links ) ), $this->_color_links );
		$this->_color_h5 			= new PirouetteColor( esc_attr( get_theme_mod( 'color_h5', $this->_color_links ) ), $this->_color_links );
		$this->_color_h6 			= new PirouetteColor( esc_attr( get_theme_mod( 'color_h6', $this->_color_links ) ), $this->_color_links );

		$this->_color_heading 			= new PirouetteColor( esc_attr( get_theme_mod( 'color_page_heading', $this->_color_h1 ) ), $this->_color_h1 );
		$this->_color_heading_bg 		= new PirouetteColor( esc_attr( get_theme_mod( 'color_page_heading_bg' ) ) );
		$this->_color_heading_subtitle	= new PirouetteColor( esc_attr( get_theme_mod( 'color_page_subtitle', $this->_color_primary ) ), $this->_color_primary );

		$this->_color_nav_text 		= new PirouetteColor( esc_attr( get_theme_mod( 'color_navigation', $this->_color_text->opacity(0.5) ) ), $this->_color_text->opacity(0.5) );
		$this->_color_nav_active 	= new PirouetteColor( esc_attr( get_theme_mod( 'color_navigation_active', $this->_color_primary ) ), $this->_color_primary );
		$this->_color_nav_bg 			= new PirouetteColor( esc_attr( get_theme_mod( 'color_navigation_bg', $this->_color_bg ) ), $this->_color_bg );

		$this->_color_footer_bg 		= new PirouetteColor( esc_attr( get_theme_mod( 'color_footer_bg', '#656565' ) ) );
		$this->_color_footer_text 	= new PirouetteColor( esc_attr( get_theme_mod( 'color_footer', '#a7a7a7' ) ) );
		$this->_color_footer_titles = new PirouetteColor( esc_attr( get_theme_mod( 'color_footer_titles', '#ffffff' ) ) );
		$this->_color_footer_links 	= new PirouetteColor( esc_attr( get_theme_mod( 'color_footer_links', '#ffffff' ) ) );

		$this->_svg_dropdown = '<?xml version="1.0" encoding="UTF-8" standalone="no"?>
				<svg width="40px" height="15px" viewBox="0 0 40 15" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:sketch="http://www.bohemiancoding.com/sketch/ns">
				    <defs></defs>
				    <g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd" sketch:type="MSPage">
				        <path d="M20,15 L0,15 L10,0 L20,15 Z" id="Triangle-1" fill="'.$this->_color_primary.'" sketch:type="MSShapeGroup" transform="translate(10.000000, 7.500000) rotate(-180.000000) translate(-10.000000, -7.500000) "></path>
				    </g>
				</svg>';

		/** Basic Colors */
		$css = "
			body{
				background-color: $this->_color_bg;
				color: $this->_color_text;
			}
			h1, .h1{ color: $this->_color_h1 }
			h2, .h2{ color: $this->_color_h2 }
			h3, .h3{ color: $this->_color_h3 }
			h4, .h4{ color: $this->_color_h4 }
			h5, .h5{ color: $this->_color_h5 }
			h6, .h6{ color: $this->_color_h6 }
			a{
				color: $this->_color_links;
			}
			a:hover{
				color: {$this->_color_links->opacity(0.75)};
			}
			.ct-content{
				border-color: {$this->_color_text->opacity(0.15)}
			}
			input[type=text],
			input[type=search],
			input[type=password],
			input[type=email],
			input[type=number],
			input[type=url],
			input[type=date],
			input[type=tel],
			select,
			textarea,
			.form-control{
				border: 1px solid {$this->_color_text->opacity(0.25)}
			}
			input[type=text]:focus,
			input[type=search]:focus,
			input[type=password]:focus,
			input[type=email]:focus,
			input[type=number]:focus,
			input[type=url]:focus,
			input[type=date]:focus,
			input[type=tel]:focus,
			select:focus,
			textarea:focus,
			.form-control:focus{
				border-color: {$this->_color_primary};
			}
			select{
				background-image: url(data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iVVRGLTgiIHN0YW5kYWxvbmU9Im5vIj8+CgkJCQk8c3ZnIHdpZHRoPSI0MHB4IiBoZWlnaHQ9IjE1cHgiIHZpZXdCb3g9IjAgMCA0MCAxNSIgdmVyc2lvbj0iMS4xIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHhtbG5zOnhsaW5rPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5L3hsaW5rIiB4bWxuczpza2V0Y2g9Imh0dHA6Ly93d3cuYm9oZW1pYW5jb2RpbmcuY29tL3NrZXRjaC9ucyI+CgkJCQkgICAgPGRlZnM+PC9kZWZzPgoJCQkJICAgIDxnIGlkPSJQYWdlLTEiIHN0cm9rZT0ibm9uZSIgc3Ryb2tlLXdpZHRoPSIxIiBmaWxsPSJub25lIiBmaWxsLXJ1bGU9ImV2ZW5vZGQiIHNrZXRjaDp0eXBlPSJNU1BhZ2UiPgoJCQkJICAgICAgICA8cGF0aCBkPSJNMjAsMTUgTDAsMTUgTDEwLDAgTDIwLDE1IFoiIGlkPSJUcmlhbmdsZS0xIiBmaWxsPSIjNjY2NjY2IiBza2V0Y2g6dHlwZT0iTVNTaGFwZUdyb3VwIiB0cmFuc2Zvcm09InRyYW5zbGF0ZSgxMC4wMDAwMDAsIDcuNTAwMDAwKSByb3RhdGUoLTE4MC4wMDAwMDApIHRyYW5zbGF0ZSgtMTAuMDAwMDAwLCAtNy41MDAwMDApICI+PC9wYXRoPgoJCQkJICAgIDwvZz4KCQkJCTwvc3ZnPg==) !important;
			}
			::-webkit-input-placeholder {
				color: {$this->_color_text->opacity(0.2)}
			}
			::-moz-placeholder {
				color: {$this->_color_text->opacity(0.2)}
			}
			:-ms-input-placeholder {
				color: {$this->_color_text->opacity(0.2)}
			}
		";

		$css .= "
		input[type=submit],
		input[type=button],
		button,
		.btn-primary{
			background-color: $this->_color_primary;
			color: {$this->_color_bg};
			border-color: $this->_color_primary;
		}
		input[type=submit]:hover,
		input[type=submit]:active,
		input[type=button]:hover,
		input[type=button]:active,
		button:hover,
		button:active,
		.btn-primary:hover,
		.btn-primary:active,
		.btn-primary:active:hover{
			background-color: {$this->_color_primary->darken()};
			border-color: {$this->_color_primary->darken()};
		}
		.btn-link{
			color: {$this->_color_primary};
		}
		.btn-link:hover{
			color: {$this->_color_primary->darken()};
		}
		.btn-link::after{
			background-color: {$this->_color_primary};
			color: {$this->_color_bg};
		}
		.btn-link:hover::after{
			background-color: {$this->_color_primary->darken()};
		}
		.btn-primary-outline{
			border-color: $this->_color_primary;
			color: $this->_color_primary;
		}
		.btn-outline-primary:active,
		.btn-outline-primary:hover,
		.btn-outline-primary:hover:active,
		.btn-outline-primary:focus,
		.btn-outline-primary:disabled,
		.btn-outline-primary:disabled:hover{
			border-color: {$this->_color_primary};
			background-color: {$this->_color_primary};
			color: {$this->_color_bg};
		}
		.color-primary{
			color: $this->_color_primary;
		}
		.color-primary--hover{
			color: {$this->_color_primary->darken()};
		}
		.color-text{
			color: {$this->_color_text}
		}
		.color-text-inverted{
			color: {$this->_color_text->contrast()}
		}
		.color-bg{
			color: {$this->_color_bg}
		}
		.color-bg-inverted{
			color: {$this->_color_bg->contrast()}
		}
		";

		/** Navigation */
		$css .= "
			.ct-header{
				color: $this->_color_nav_text;
				background-color: $this->_color_nav_bg;
			}
			.ct-header__logo-nav a{
				color: $this->_color_nav_text;
			}
			.ct-header__logo-nav a:hover{
				color: {$this->_color_nav_text->opacity(0.65)};
			}
			.ct-header__logo-nav .current-menu-ancestor > a,
		  .ct-header__logo-nav .current-menu-parent > a,
		  .ct-header__logo-nav .current-menu-item > a,
		  .ct-header__logo-nav .current-page-parent > a,
		  .ct-header__logo-nav .current_page_parent > a,
		  .ct-header__logo-nav .current_page_ancestor > a,
			.ct-header__logo-nav .current-page-ancestor > a,
		  .ct-header__logo-nav .current_page_item > a{
				color: $this->_color_nav_active;
			}

			.ct-header__wrapper--stuck{
				background-color: $this->_color_nav_bg;
			}

			.color-primary,
			.wpml-switcher .active,
			#ct-header__hero-navigator > a,
			.section-bullets,
			.special-title em:first-child,
			.special-title small:last-child,
			#ct-scroll-top{
				color: $this->_color_nav_active;
			}

			.ct-layout--without-slider .ct-header__hero{
				color: $this->_color_nav_text;
			}

			.ct-hero--without-image .ct-header__hero::after{
				{$this->_color_heading_bg->rule( 'background-color: %s' )}
			}
			.ct-header__main-heading small,
			.ct-header__main-heading span,
			.ct-header__main-heading-excerpt{
				color: $this->_color_heading_subtitle;
			}

			.ct-header__main-heading-title h1{
				color: $this->_color_heading;
			}

			.img-frame-small,
			.img-frame-large{
				background-color: $this->_color_bg;
				border-color: $this->_color_bg;
			}

		";

		/** WordPress */
		$css .= "
			.ct-social-box .fa-boxed.fa-envelope{
				color: {$this->_color_primary->contrast()};
			}
			.ct-social-box .fa-boxed.fa-envelope::after{
				background-color: $this->_color_primary;
			}
			h4.media-heading{
				color: $this->_color_primary;
			}
			.comment-reply-link,
			.btn-outline-primary{
				color: $this->_color_primary;
				border-color: $this->_color_primary;
			}
			.comment-reply-link:hover,
			.btn-outline-primary:hover,
			.btn-outline-primary:hover:active,
			.btn-outline-primary:active{
				background-color: $this->_color_primary;
				color: $this->_color_bg;
				border-color: $this->_color_primary;
			}
			.media.comment{
				border-color: {$this->_color_text->opacity(0.125)};
			}
			.ct-posts .ct-post.format-quote .ct-post__content{
				background-color: $this->_color_primary;
			}
			.ct-posts .ct-post.format-quote blockquote,
			.ct-posts .ct-post.format-quote blockquote cite,
			.ct-posts .ct-post.format-quote blockquote cite::before{
				color: $this->_color_bg;
			}
			.ct-posts .ct-post.format-link{
				border-color: {$this->_color_text->opacity(0.125)};
			}
			.pagination .current{
				color: $this->_color_bg;
			}
			.pagination .nav-links .current::before{
				background-color: $this->_color_primary;
			}
			.pagination .current{
				color: $this->_color_bg;
			}
			.pagination a{
				color: {$this->_color_primary->darken()};
			}
			.pagination .nav-links .prev,
			.pagination .nav-links .next{
				border-color: $this->_color_primary
			}
			.ct-sidebar .widget_archive,
			.ct-sidebar .widget_categories{
				color: {$this->_color_text->opacity(0.35)};
			}
			.ct-sidebar ul li::before{
				color: {$this->_color_text}
			}
			.ct-sidebar .sidebar-widget .widget-title::after{
				border-color: {$this->_color_text};
			}
			.ct-sidebar .sidebar-widget .widget-title,
			.ct-sidebar .sidebar-widget .widget-title a{
				color: $this->_color_primary
			}
			.ct-sidebar .sidebar-widget.widget_tag_cloud .tag{
				color: $this->_color_bg;
			}
			.ct-sidebar .sidebar-widget.widget_tag_cloud .tag::before{
				background-color: {$this->_color_text}
			}
			.ct-sidebar .sidebar-widget.widget_tag_cloud .tag.x-large::before{
				background-color: {$this->_color_primary}
			}
			#wp-calendar thead th,
			#wp-calendar tbody td{
				border-color: {$this->_color_text->opacity(0.125)};
			}
		";

		/** Footer */
		$css .= "
			.ct-footer{
				background-color: $this->_color_footer_bg;
				color: $this->_color_footer_text;
			}
			.ct-footer a{
				color: {$this->_color_footer_links};
			}
			.ct-footer .widget-title{
				color: $this->_color_footer_titles;
			}
		";

		/** Bootstrap */
		$css .= "
			blockquote,
			blockquote cite::before,
			q,
			q cite::before{
				color: {$this->_color_primary}
			}
			blockquote cite,
			q site{
				color: {$this->_color_text}
			}
			table{
				border-color: {$this->_color_text->opacity(0.15)};
			}
			table thead th{
				color: {$this->_color_primary}
			}
		";


		/** xtender */
		$css .= "
			.ct-vc-recent-news-post{
				border-color: {$this->_color_text->opacity(0.125)};
			}
			.ct-vc-recent-news-post .ti-calendar{
				color: $this->_color_primary;
			}
			.ct-vc-services-carousel__item-title{
				color: $this->_color_primary;
			}
			.ct-vc-services-carousel__item{
				background-color: $this->_color_bg;
			}
			.wcs-timetable--week .wcs-class__title,
			.wcs-timetable--agenda .wcs-class__title,
			.wcs-timetable--compact-list .wcs-class__title{
				color: $this->_color_links
			}
			.wcs-timetable--carousel .wcs-class__title{
				color: $this->_color_links !important
			}
			.wcs-timetable__carousel .wcs-class__title::after,
			.wcs-timetable__carousel .owl-prev, .wcs-timetable__carousel .owl-next{
				border-color: $this->_color_primary;
				color: $this->_color_primary;
			}

			.wcs-timetable--carousel .wcs-class__title small{
				color: $this->_color_text;
			}
			body .wcs-timetable--carousel .wcs-btn--action{
				background-color: $this->_color_primary;
				color: $this->_color_bg;
			}
			body .wcs-timetable--carousel .wcs-btn--action:hover{
				background-color: {$this->_color_primary->darken()};
				color: $this->_color_bg;
			}

			.wcs-timetable__container .wcs-filters__filter-wrapper:hover{
				color: $this->_color_primary !important;
			}
			.wcs-timetable--compact-list .wcs-day__wrapper{
				background-color: {$this->_color_text};
				color: {$this->_color_bg}
			}

			.wcs-timetable__week,
			.wcs-timetable__week .wcs-day,
			.wcs-timetable__week .wcs-class,
			.wcs-timetable__week .wcs-day__title{
				border-color: {$this->_color_text->opacity(0.125)};
			}
			.wcs-timetable__week .wcs-class{
				background-color: $this->_color_bg;
			}
			.wcs-timetable__week .wcs-day__title,
			.wcs-timetable__week .wcs-class__instructors::before{
				color: $this->_color_primary !important;
			}
			.wcs-timetable__week .wcs-day__title::before{
				background-color: $this->_color_text;
			}

			.wcs-timetable__week .wcs-class__title::after{
				color: $this->_color_bg;
				background-color: $this->_color_primary;
			}
			.wcs-filters__title{
				color: $this->_color_primary !important;
			}

			.xtd-carousel-mini,
			.xtd-carousel-mini .owl-image-link:hover::after{
				color: $this->_color_primary !important;
			}
			.xtd-carousel-mini .onclick-video_link a::before{
				background-color: {$this->_color_primary->opacity(0.85)};
			}
			.xtd-carousel-mini .onclick-video_link a::after{
				color: $this->_color_bg;
			}
			.xtd-carousel-mini .onclick-video_link a:hover::after{
				background-color: {$this->_color_primary->opacity(0.98)};
			}

			.wcs-modal:not(.wcs-modal--large) .wcs-modal__title,
			.wcs-modal:not(.wcs-modal--large) .wcs-modal__close{
				color: $this->_color_bg;
			}
			.wcs-modal:not(.wcs-modal--large) .wcs-btn--action.wcs-btn--action{
				background-color: $this->_color_primary;
				color: $this->_color_bg;
			}
			.wcs-modal:not(.wcs-modal--large) .wcs-btn--action.wcs-btn--action:hover{
				background-color: {$this->_color_primary->darken()};
				color: $this->_color_bg;
			}
			.wcs-timetable--agenda .wcs-timetable__agenda-data .wcs-class__duration::after{
				border-color: {$this->_color_primary}
			}
			.wcs-timetable--agenda .wcs-timetable__agenda-data .wcs-class__time,
			.wcs-timetable--compact-list .wcs-class__time{
				color: {$this->_color_text->opacity(0.75)}
			}
			.wcs-modal:not(.wcs-modal--large),
			div.pp_overlay.pp_overlay,
			.mfp-bg{
				background-color: {$this->_color_primary->opacity(0.97)} !important;
			}
			.owl-image-link::before{
				color: $this->_color_bg;
			}

			.owl-nav .owl-prev::before,
			.owl-nav .owl-next::after,
			.owl-dots {
				color: $this->_color_primary !important;
			}

			.xtd-ninja-modal-container{
				background-color: {$this->_color_bg};
			}


			.xtd-recent-posts__post__date::before{
				color: $this->_color_primary;
			}

			.xtd-gmap-info{
				background-color: $this->_color_bg;
				color: $this->_color_text;
			}

			.fa-boxed{
				background-color: $this->_color_primary;
				color: $this->_color_bg;
			}



		";


		wp_add_inline_style( 'pirouette-style', apply_filters( 'pirouette_minify_css', htmlspecialchars_decode( $css ) ) );


	}

}
$piroutte_renders_colors = new PirouetteColorsCSS();
?>
