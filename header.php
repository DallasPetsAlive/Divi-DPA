<?php if ( ! isset( $_SESSION ) ) session_start(); ?>
<!DOCTYPE html>
<!--[if IE 6]>
<html id="ie6" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 7]>
<html id="ie7" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 8]>
<html id="ie8" <?php language_attributes(); ?>>
<![endif]-->
<!--[if !(IE 6) | !(IE 7) | !(IE 8)  ]><!-->
<html <?php language_attributes(); ?>>
<!--<![endif]-->
<head>
<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-N68523H');</script>
<!-- End Google Tag Manager -->
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<?php 
	if(is_page('1043')) { 
		dpa_title(); 
	} elseif(is_page('9155')) { // TODO get right page ID
		dpa_title_cat();
	} else { ?>
	<title><?php elegant_titles(); ?></title><?php og_dpa(); ?>
	<?php } ?>
	<?php elegant_description(); ?>
	<?php elegant_keywords(); ?>
	<?php elegant_canonical(); ?>

	<?php do_action( 'et_head_meta' ); ?>

	<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />

	<?php $template_directory_uri = get_template_directory_uri(); ?>
	<!--[if lt IE 9]>
	<script src="<?php echo esc_url( $template_directory_uri . '/js/html5.js"' ); ?>" type="text/javascript"></script>
	<![endif]-->

	<script type="text/javascript">
		document.documentElement.className = 'js';
	</script>
	
	<script>


</script>
<meta name="google-site-verification" content="T2VIj5uajT_LOIAuIuASduujFqZXYRseAZ_41n7MmLY" />
	<?php wp_head(); ?>		<link rel="apple-touch-icon" sizes="57x57" href="/apple-touch-icon-57x57.png"><link rel="apple-touch-icon" sizes="114x114" href="/apple-touch-icon-114x114.png"><link rel="apple-touch-icon" sizes="72x72" href="/apple-touch-icon-72x72.png"><link rel="apple-touch-icon" sizes="144x144" href="/apple-touch-icon-144x144.png"><link rel="apple-touch-icon" sizes="60x60" href="/apple-touch-icon-60x60.png"><link rel="apple-touch-icon" sizes="120x120" href="/apple-touch-icon-120x120.png"><link rel="apple-touch-icon" sizes="76x76" href="/apple-touch-icon-76x76.png"><link rel="apple-touch-icon" sizes="152x152" href="/apple-touch-icon-152x152.png"><link rel="icon" type="image/png" href="/favicon-196x196.png" sizes="196x196"><link rel="icon" type="image/png" href="/favicon-160x160.png" sizes="160x160"><link rel="icon" type="image/png" href="/favicon-96x96.png" sizes="96x96"><link rel="icon" type="image/png" href="/favicon-16x16.png" sizes="16x16"><link rel="icon" type="image/png" href="/favicon-32x32.png" sizes="32x32"><meta name="msapplication-TileColor" content="#2b5797"><meta name="msapplication-TileImage" content="/mstile-144x144.png">
</head>
<body <?php body_class(); ?>>
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-N68523H"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
	<header id="main-header">
		<div class="container clearfix">
		<?php
			$logo = ( $user_logo = et_get_option( 'divi_logo' ) ) && '' != $user_logo
				? $user_logo
				: $template_directory_uri . '/images/logo.png';
		?>
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>">
				<img src="<?php echo esc_attr( $logo ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>" id="logo" />
			</a>

			<div id="et-top-navigation">
				<nav id="top-menu-nav">
				<?php
					$menuClass = 'nav';
					if ( 'on' == et_get_option( 'divi_disable_toptier' ) ) $menuClass .= ' et_disable_top_tier';
					$primaryNav = '';

					$primaryNav = wp_nav_menu( array( 'theme_location' => 'primary-menu', 'container' => '', 'fallback_cb' => '', 'menu_class' => $menuClass, 'menu_id' => 'top-menu', 'echo' => false ) );

					if ( '' == $primaryNav ) :
				?>
					<ul id="top-menu" class="<?php echo esc_attr( $menuClass ); ?>">
						<?php if ( 'on' == et_get_option( 'divi_home_link' ) ) { ?>
							<li <?php if ( is_home() ) echo( 'class="current_page_item"' ); ?>><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'Divi' ); ?></a></li>
						<?php }; ?>

						<?php show_page_menu( $menuClass, false, false ); ?>
						<?php show_categories_menu( $menuClass, false ); ?>
					</ul>
				<?php
					else :
						echo( $primaryNav );
					endif;
				?>
				</nav>

				<div id="et_top_search">
					<span id="et_search_icon"></span>
					<form role="search" method="get" class="et-search-form et-hidden" action="<?php echo esc_url( home_url( '/' ) ); ?>">
					<?php
						printf( '<input type="search" class="et-search-field" placeholder="%1$s" value="%2$s" name="s" title="%3$s" />',
							esc_attr_x( 'Search &hellip;', 'placeholder', 'Divi' ),
							get_search_query(),
							esc_attr_x( 'Search for:', 'label', 'Divi' )
						);
					?>
					</form>
				</div>

				<?php do_action( 'et_header_top' ); ?>
			</div> <!-- #et-top-navigation -->
		</div> <!-- .container -->
	</header> <!-- #main-header -->
