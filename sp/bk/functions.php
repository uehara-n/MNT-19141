<?php
add_action( 'add_admin_bar_menus', 'gr_add_admin_bar_menus' );
function gr_add_admin_bar_menus() {
/*
	remove_action( 'admin_bar_menu', 'wp_admin_bar_my_account_menu', 0 );
	remove_action( 'admin_bar_menu', 'wp_admin_bar_search_menu', 4 );
	remove_action( 'admin_bar_menu', 'wp_admin_bar_my_account_item', 7 );
*/
	// Site related.
	remove_action( 'admin_bar_menu', 'wp_admin_bar_wp_menu', 10 );
	remove_action( 'admin_bar_menu', 'wp_admin_bar_my_sites_menu', 20 );
	remove_action( 'admin_bar_menu', 'wp_admin_bar_site_menu', 30 );
	remove_action( 'admin_bar_menu', 'wp_admin_bar_updates_menu', 40 );
	add_action( 'admin_bar_menu', 'gr_admin_bar_wp_menu', 10 );

	// Content related.
	if ( ! is_network_admin() && ! is_user_admin() ) {
		remove_action( 'admin_bar_menu', 'wp_admin_bar_comments_menu', 60 );
		remove_action( 'admin_bar_menu', 'wp_admin_bar_new_content_menu', 70 );
	}
	remove_action( 'admin_bar_menu', 'wp_admin_bar_edit_menu', 80 );

//	add_action( 'admin_bar_menu', 'wp_admin_bar_add_secondary_groups', 200 );
}
function gr_admin_bar_wp_menu( $wp_admin_bar ) {
	$wp_admin_bar->add_menu( array(
		'id'    => 'gr-logo',
		'title' => '<img class="gr-icon" src="'.get_stylesheet_directory_uri().'/images/gr-logo-t.png"/>',
		'href'  => 'http://www.gotta-ride.com',
		'meta'  => array(
			'title' => 'ゴッタライド',
		),
	) );
}
add_action( 'wp_head', 'gr_head' );
add_action( 'admin_head', 'gr_head' );
function gr_head() {
?>
<style type="text/css" media="screen">
#wpadminbar{background:#f5f5f5;border-bottom:1px solid #333;}
#wpadminbar .quicklinks a, #wpadminbar .quicklinks .ab-empty-item, #wpadminbar .shortlink-input, #wpadminbar { height: 40px; line-height: 40px; }
#wpadminbar #wp-admin-bar-gr-logo { background-color: #f5f5f5;}
#wpadminbar .gr-icon { vertical-align: middle; }
body.admin-bar #wpcontent, body.admin-bar #adminmenu { padding-top: 40px;}
#wpadminbar .ab-top-secondary,
#wpadminbar .ab-top-menu > li:hover > .ab-item, #wpadminbar .ab-top-menu > li.hover > .ab-item, #wpadminbar .ab-top-menu > li > .ab-item:focus, #wpadminbar.nojq .quicklinks .ab-top-menu > li > .ab-item:focus, #wpadminbar #wp-admin-bar-gr-logo a:hover{background-color:transparent;background-image:none;color:#333;}
#screen-meta-links{display:none;}
#wpadminbar .ab-sub-wrapper, #wpadminbar ul, #wpadminbar ul li {background:#F5F5F5;}
#wpadminbar .quicklinks .ab-top-secondary > li > a, #wpadminbar .quicklinks .ab-top-secondary > li > .ab-empty-item,
#wpadminbar .quicklinks .ab-top-secondary > li {border-left: 1px solid #f5f5f5;}
#wpadminbar * {color: #333;text-shadow: 0 1px 0 #fff;}
</style>
<?php
}
add_filter( 'admin_footer_text', '__return_false' );
add_filter( 'update_footer', '__return_false', 9999 );
add_action( 'admin_notices', 'gr_update_nag', 0 );
function gr_update_nag() {
	if ( ! current_user_can( 'administrator' ) ) {
		remove_action( 'admin_notices', 'update_nag', 3 );
	}
}

// seko ページでは editor 非表示
add_action( 'admin_print_styles-post.php', 'bc_post_page_style' );
add_action( 'admin_print_styles-post-new.php', 'bc_post_page_style' );
function bc_post_page_style() {
	if ( in_array( $GLOBALS['current_screen']->post_type, array( 'seko', 'slide_img', 'leaflet','event' ,'voice','craftsman','staff','whatsnew','price','faq','tenpo','customer','sekoseat' ) ) ) :
?>
<style type="text/css">
#postdivrich{display:none;}
#<?php global $current_screen; var_dump( $current_screen) ?>{}
</style>
<?php
	endif;
}


// カスタムフィールド&カスタム投稿タイプの追加
function gr_register_terms( $terms, $taxonomy ) {
	foreach ( $terms as $key => $label ) {
		$keys = explode( '/', $key );
		if ( 1 < count( $keys ) ) {
			$key = $keys[1];
			$parent_id = get_term_by( 'slug', $keys[0], $taxonomy )->term_id;
		} else {
			$parent_id = 0;
		}
		if ( ! term_exists( $key, $taxonomy ) ) {
			wp_insert_term( $label, $taxonomy, array( 'slug' => $key, 'parent' => $parent_id ) );
		}
	}
}

add_action( 'init', 'bc_create_customs', 0 );
function bc_create_customs() {

	// 施工事例
    register_post_type( 'seko', array(
        'labels' => array(
            'name' => __( '施工事例' ),
        ),
        'public' => true,
        'has_archive' => true,
        'menu_position' => 4,
        'supports' => array( 'title', 'editor','author' ),
    ) );

    register_taxonomy( 'seko_cat', 'seko', array(
         'label' => '施工事例カテゴリー',
         'hierarchical' => true,
    ) );

		$terms = array(
			'kitchen' => 'キッチン',
			'ohuro' => 'お風呂',
			'toilet' => 'トイレ',
			'senmen' => '洗面',
			'gaiheki' => '外壁',
			'yane' => '屋根',
			'exterior' => '外構・エクステリア・庭',
			'genkan' => '玄関',
			'rouka' => '廊下',
			'kaidan' => '階段',
			'living' => 'リビング・内装',
			'zenmen' => '全面改装',
			'sizen' => '自然素材',
			'bfree' => 'バリアフリー',
			'design' => 'デザインリフォーム',
			'kominka' => '古民家再生',
			'sintiku' => '新築',
			'renov' => 'リノベーション',
			'zouchiku' => '増築',
			'taishin' => '耐震',
			'siroari' => 'シロアリ',
			'tirasiplan' => 'チラシで紹介したプラン',
			'alldenka' => 'オール電化',
			'ogata' => '大規模リフォーム',
			'movie' => '動画で紹介',
			'other' => 'その他',
	);
	gr_register_terms( $terms, 'seko_cat' );

	register_taxonomy( 'seko_staff', 'seko', array(
		'label' => 'スタッフカテゴリー',
         	'hierarchical' => true,
	) );

	// こだわり施工事例
	register_post_type( 'good_seko', array(
			'labels' => array(
		'name' => __( 'こだわり施工事例' ),
			),
			'public' => true,
			'has_archive' => true,
			'menu_position' => 5,
	'supports' => array( 'title', 'editor' ),
	) );
	register_taxonomy( 'good_cat', 'good_seko', array(
			 'label' => 'こだわり施工事例カテゴリー',
	) );
	$terms = array(
		'good_kitchen' => 'キッチン',
		'good_ohuro' => 'お風呂',
	);
	gr_register_terms( $terms, 'good_cat' );

	// リフォームMenu
	register_post_type( 'reformmenu', array(
			'labels' => array(
		'name' => __( 'リフォームMenu' ),
			),
			'public' => true,
			'has_archive' => true,
			'menu_position' => 6,
	'supports' => array( 'title', 'editor' ),
	) );
	register_taxonomy( 'reformmenu_cat', 'reformmenu', array(
			 'label' => 'リフォームMenuカテゴリー',
	 'hierarchical' => true,
	) );
	$terms = array(
		'reform_kitchen' => 'キッチン',
		'reform_ohuro' => 'お風呂',
		'reform_toilet' => 'トイレ',
		'reform_j2w' => '和室から洋室',
		'reform_kabegami' => '壁紙クロス',
		'reform_gaiheki' => '外壁',
		'reform_yane' => '屋根',
		'reform_kyuto' => '給湯',
		'reform_taishin' => '耐震',
		'reform_yuka' => '床',
	);
	gr_register_terms( $terms, 'reformmenu_cat' );

	// 価格表
	register_post_type( 'price', array(
			'labels' => array(
		'name' => __( '価格表' ),
			),
			'public' => true,
			'has_archive' => true,
			'menu_position' => 7,
	'supports' => array( 'title', 'editor','author' ),
	) );
	register_taxonomy( 'price_cat', 'price', array(
			 'label' => '価格表カテゴリー',
			 'hierarchical' => true,
	) );
	$terms = array(
		'price_kitchen' => 'キッチン',
		'price_ohuro' => 'お風呂',
		'price_ohuro/price_unitex' => 'ユニットバスの入替え',
		'price_ohuro/price_old2unit' => '在来工法のお風呂をユニットバスに',
		'price_ohuro/price_oldreform' => '在来工法のお風呂リフォーム',
		'price_toilet' => 'トイレ',
		'price_j2w' => '和室から洋室',
		'price_kabegami' => '壁紙クロス',
		'price_gaiheki' => '外壁リフォーム',
		'price_yane' => '屋根',
		'price_yanereform' => '屋根リフォーム',
		'price_yuka' => '床リフォーム',
		'price_kyuto' => '給湯器',
		'price_taishin' => '耐震リフォーム',
	);
	gr_register_terms( $terms, 'price_cat' );

	// 価格表(一覧)
	register_post_type( 'maker', array(
			'labels' => array(
		'name' => __( '価格表 一覧' ),
			),
			'public' => true,
			'has_archive' => true,
			'menu_position' => 8,
		'supports' => array( 'title', 'editor','author' ),
	) );
	register_taxonomy( 'maker_cat', 'maker', array(
			 'label' => '価格表 一覧カテゴリー',
					 'hierarchical' => true,
	) );
	$terms = array(//ここにカテゴリー
	);
	gr_register_terms( $terms, 'maker_cat' );


	// よくあるご相談
	register_post_type( 'soudan', array(
			'labels' => array(
		'name' => __( 'よくあるご相談' ),
			),
			'public' => true,
			'has_archive' => true,
			'menu_position' => 9,
		'supports' => array( 'title', 'editor','author' ),
	) );
	register_taxonomy( 'soudan_cat', 'soudan', array(
			 'label' => 'よくあるご相談カテゴリー',
			 'hierarchical' => true,
	) );
	$terms = array(
		'soudan_kitchen' => 'キッチン',
		'soudan_ohuro' => 'お風呂',
		'soudan_toilet' => 'トイレ',
		'soudan_j2w' => '和室から洋室',
		'soudan_kabegami' => '壁紙クロス',
		'soudan_yuka' => '床リフォーム',
	);
	gr_register_terms( $terms, 'soudan_cat' );


	// 工事の流れ
	register_post_type( 'nagare', array(
			'labels' => array(
		'name' => __( '工事の流れ' ),
			),
			'public' => true,
			'has_archive' => true,
			'menu_position' => 9,
		'supports' => array( 'title', 'editor','author' ),
	) );
	register_taxonomy( 'nagare_cat', 'nagare', array(
			 'label' => '工事の流れカテゴリー',
			 'hierarchical' => true,
	) );
	$terms = array(
		'nagare_kitchen' => 'キッチン',
		'nagare_ohuro' => 'お風呂',
		'nagare_toilet' => 'トイレ',
		'nagare_j2w' => '和室から洋室',
		'nagare_kabegami' => '壁紙クロス',
		'nagare_yuka' => '床リフォーム',
	);
	gr_register_terms( $terms, 'nagare_cat' );


	// 初めてのお客様へ
	register_post_type( 'first', array(
			'labels' => array(
		'name' => __( '初めてのお客様へ' ),
			),
			'public' => true,
			'has_archive' => true,
			'menu_position' => 10,
	'supports' => array( 'title', 'editor' ),
	) );
	register_taxonomy( 'first_cat', 'first', array(
			 'label' => '初めてのお客様へ',
	) );


	// お知らせ
	register_post_type( 'whatsnew', array(
			'labels' => array(
		'name' => __( 'お知らせ' ),
			),
			'public' => true,
			'has_archive' => true,
			'menu_position' => 11,
	'supports' => array( 'title', 'editor','author' ),
	) );
	register_taxonomy( 'whatsnew_cat', 'whatsnew', array(
			 'label' => 'お知らせカテゴリー',
		     'hierarchical' => true,
	) );

	// イベント
	register_post_type( 'event', array(
			'labels' => array(
		'name' => __( 'イベント' ),
			),
			'public' => true,
			'has_archive' => true,
			'menu_position' => 12,
	'supports' => array( 'title', 'editor','author' ),
	) );
	register_taxonomy( 'event_cat', 'event', array(
			 'label' => 'イベントカテゴリー',
		     'hierarchical' => true,
	) );

	// スタッフ
	register_post_type( 'staff', array(
			'labels' => array(
		'name' => __( 'スタッフ' ),
			),
			'public' => true,
			'has_archive' => true,
			'menu_position' => 13,
	'supports' => array( 'title', 'editor','author' ),
	) );
	register_taxonomy( 'staff_cat', 'staff', array(
			 'label' => 'スタッフカテゴリー',
		     'hierarchical' => true,
	) );

	// 職人
	register_post_type( 'craftsman', array(
			'labels' => array(
		'name' => __( '職人' ),
			),
			'public' => true,
			'has_archive' => true,
			'menu_position' => 14,
	'supports' => array( 'title', 'editor','author' ),
	) );

	// お客様登場
	register_post_type( 'customer', array(
			'labels' => array(
		'name' => __( 'お客様登場' ),
			),
			'public' => true,
			'has_archive' => true,
			'menu_position' => 22,
	'supports' => array( 'title', 'editor' ),
	) );

	// お客様の声
	register_post_type( 'voice', array(
			'labels' => array(
		'name' => __( 'お客様の声' ),
			),
			'public' => true,
			'has_archive' => true,
			'menu_position' => 16,
	'supports' => array( 'title', 'editor' ),
	) );
	register_taxonomy( 'voice_cat', 'voice', array(
			 'label' => '☆評価数',
				     'hierarchical' => true,
	) );

	// 現場日記
	register_post_type( 'genbanikki', array(
			'labels' => array(
		'name' => __( '現場日記' ),
			),
			'public' => true,
			'has_archive' => true,
			'menu_position' => 16,
	'supports' => array( 'title', 'editor','author' ),
	) );
	register_taxonomy( 'genba_cat', 'genbanikki', array(
			 'label' => '現場日記カテゴリー',
				     'hierarchical' => true,
	) );

	// 現場日記2
	register_post_type( 'genbaseki', array(
			'rewrite' => array('slug' => 'genbapatrol'),
			'labels' => array(
		'name' => __( '現場パトロール' ),
			),
			'public' => true,
			'has_archive' => true,
			'menu_position' => 16,
	'supports' => array( 'title', 'editor','author' ),
	) );
	register_taxonomy( 'genbapatrol_cat', 'genbapatrol', array(
			 'label' => '現場パトロールカテゴリー',
				     'hierarchical' => true,
	) );


	// チラシ
	register_post_type( 'leaflet', array(
			'labels' => array(
		'name' => __( 'チラシ' ),
		'singular_name' => __( 'チラシ')
			),
			'public' => true,
			'has_archive' => true,
			'menu_position' => 18,
	'supports' => array( 'title', 'editor','author' ),
	) );
		// アルバム
	register_post_type( 'album', array(
			'labels' => array(
		'name' => __( 'アルバム' ),
		'singular_name' => __( 'アルバム')
			),
			'public' => true,
			'has_archive' => true,
			'menu_position' => 19,
	'supports' => array( 'title', 'editor','author' ),
	) );

		// スタッフブログ
	register_post_type( 'blog', array(
			'labels' => array(
		'name' => __( 'スタッフブログ' ),
			),
			'public' => true,
			'has_archive' => true,
			'menu_position' => 19,
	'supports' => array( 'title', 'editor' ),
	) );
	register_taxonomy( 'blog_cat', 'blog', array(
			 'label' => 'カテゴリー',
				     'hierarchical' => true,
	) );
	// 施工事例シート
	register_post_type( 'sekoseat', array(
			'labels' => array(
		'name' => __( '施工事例シート' ),
			),
			'public' => true,
			'has_archive' => true,
			'menu_position' => 23,
	'supports' => array( 'title', 'editor' ),
	) );
	register_taxonomy( 'sekoseat_cat', 'sekoseat', array(
			 'label' => '施工事例シートカテゴリー',
				     'hierarchical' => true,
	) );
	// よくある質問
	register_post_type( 'faq', array(
			'labels' => array(
		'name' => __( 'よくある質問' ),
			),
			'public' => true,
			'has_archive' => true,
			'menu_position' => 10,
	'supports' => array( 'title', 'editor' ),
	) );
	register_taxonomy( 'faq_cat', 'faq', array(
			 'label' => '質問カテゴリー',
				     'hierarchical' => true,
	) );

}

//// hooks
add_filter( 'wp_list_categories', 'gr_list_categories', 10, 2 );
function gr_list_categories( $output, $args ) {
	return preg_replace( '@</a>\s*\((\d+)\)@', ' ($1)</a>', $output );
}

add_action( 'pre_get_posts', 'gr_pre_get_posts' );
function gr_pre_get_posts( $query ) {
	if ( is_admin() ) {
		if ( in_array( $query->get( 'post_type' ), array( 'seko', 'staff' ) ) ) {
			$query->set( 'posts_per_page', -1 );
		}
		return;
	}
/*
	if ( is_post_type_archive() ) {
		if ( 'seko' == get_query_var( 'post_type' ) ) {
			$query->tax_query[] = array(
				'taxonomy' =>	'seko_cat',
				'term'     => 'kitchen',
				'field'    => 'slug',
			);
		}
	}
*/
}

function gr_adjacent_post_join( $join, $in_same_cat, $excluded_categories ) {
	if ( false && $in_same_cat ) {
		global $post, $wpdb;

		$taxonomy  = $post->post_type . '_cat';
		$terms     = implode( ',', wp_get_object_terms( $post->ID, $taxonomy, array('fields' => 'ids') ) );
		$join      = " INNER JOIN $wpdb->term_relationships AS tr ON p.ID = tr.object_id INNER JOIN $wpdb->term_taxonomy tt ON tr.term_taxonomy_id = tt.term_taxonomy_id";
		$join     .= $wpdb->prepare( " AND tt.taxonomy = %s AND tt.term_id IN ($terms)", $taxonomy );
	}

	return $join;
}

//// functions
function gr_title() {
	global $page, $paged;

	wp_title( '|', true, 'right' );
	bloginfo( 'name' );

	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && is_front_page() )
		echo " | $site_description";

	if ( $paged >= 2 || $page >= 2 )
		echo ' | ' . sprintf(  '%sページ', max( $paged, $page ) );
}

function gr_description() {
	$desc = get_option( 'gr_description' );

	if ( is_front_page() || ! $desc ) {
		bloginfo( 'description' );
	} else {
		$title = str_replace( '|', '', wp_title( '|', false ) );
		echo str_replace( '%title%', $title, get_option( 'gr_description' ) );
	}
}

function gr_get_posts_count() {
	global $wp_query;
	return get_query_var( 'posts_per_page' ) ? $wp_query->found_posts : $wp_query->post_count;
}

function gr_get_pagename() {
	$pagename = '';

	if ( is_page() ) {
		/*
		$obj = get_queried_object();
		if ( 14 == $obj->post_parent )
			$pagename = 'business';
		else
		*/
			$pagename = get_query_var( 'pagename' );
	} elseif( ! $pagename = get_query_var( 'post_type' ) ) {
		//
	}

	return $pagename;
}

define( 'GR_IMAGES', get_stylesheet_directory_uri() . '/images/' );
function gr_img( $file, $echo = true ) {
	$img = esc_attr( GR_IMAGES . $file );

	if ( $echo )
		echo $img;
	else
		return $img;
}

function gr_get_post( $post_name ) {
	global $wpdb;
	$null = $_post = null;

	if ( ! $_post = wp_cache_get( $post_name, 'posts' ) ) {
		$_post = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $wpdb->posts WHERE post_name = %s LIMIT 1", $post_name ) );
		if ( ! $_post )
			return $null;
		_get_post_ancestors($_post);
		$_post = sanitize_post( $_post, 'raw' );
		wp_cache_add( $post_name, $_post, 'posts' );
	}

	return $_post;
}

function gr_get_permalink( $name, $taxonomy = '' ) {
	$link = false;

	if ( false && term_exists( $name, $taxnomy ) ) {
		$link = get_term_link( $name );
	} else if ( post_type_exists( $name ) ) {
		$link = get_post_type_archive_link( $name );
	} else {
		$_post = gr_get_post( $name );
		if ( $_post )
			$link = get_permalink( $_post );
	}

	return $link;
}

function gr_image_id( $key ) {
    $imagefield = post_custom( $key );
    return  preg_replace('/(\[)([0-9]+)(\])(http.+)?/', '$2', $imagefield );
}

function gr_get_image( $key, $att = '' ) {
	$id = gr_image_id( $key );

	if ( is_numeric( $id ) ) {
		if ( isset( $att['size'] ) ) {
			$size = $att['size'];
			unset( $att['size'] );
		}
		if ( isset( $att['width'] ) ) {
			$size = array( $att['width'], 99999 );
			unset( $att['width'] );
		}
		return wp_get_attachment_image( $id, $size, false, $att );
	}

	if ( $id ) {
		/* ファイル存在チェック
		 * $id = /images/seko/289-2-t.jpg のようなパスでここに渡ってくるので
		 * get_stylesheet_directory_uri()のようなhttpで絶対パスを指定せず
		 * dirname(__FILE__)でチェック
		 */
		if( file_exists( dirname(__FILE__) . "$id" ) ) {
			return sprintf(
				'<img src="%1$s%2$s"%3$s%4$s%5$s />',
				get_stylesheet_directory_uri(),
				$id,
				( $att['width' ] ? ' width="' .$att['width' ].'"' : '' ),
				( $att['height'] ? ' height="'.$att['height'].'"' : '' ),
				( $att['alt'   ] ? ' alt="'   .$att['alt'   ].'"' : '' )
			);
		}
	}

	return '';
}

function gr_get_image_src( $key ) {
	$id = gr_image_id( $key );
	$src = '';

	if ( is_numeric( $id ) ) {
		@list( $src, $width, $height ) = wp_get_attachment_image_src( $id, $size, false );
	} else if ( $id ) {
		$src = get_stylesheet_directory_uri() . $id;
	}
	return $src;
}




function contactset() {
?>

<div class="top_read_btn_contact">
<a href="<?php bloginfo('url'); ?>/contact"><img src="<?php bloginfo('template_url'); ?>/page_image/top/top_read_btn_contact.png" alt="WEBからお問合せ" width="280" height="179" /></a>
</div>
<div class="top_read_btn_raiten">
<a href="<?php bloginfo('url'); ?>/net_yoyaku"><img src="<?php bloginfo('template_url'); ?>/page_image/top/top_read_btn_raiten.png" alt="来店予約" width="280" height="179" /></a>
</div>

<?php
}

function telset() {
?>

<div class="full_wide">
<img src="<?php bloginfo('template_url'); ?>/page_image/top/top_tel_read.png" alt="お電話での来店予約・資料請求も受付中" width="640" height="150" />
<a href="tel:05073000827"><img src="<?php bloginfo('template_url'); ?>/page_image/top/top_tel_btn.png" alt="お電話での来店予約・資料請求も受付中" width="640" height="164" class="mb20" /></a>
</div>

<?php
}

function kaiyu_new() {
?>

<div class="default_wide clearfix">

<a href="<?php bloginfo('url'); ?>/net_yoyaku"><img src="<?php echo get_template_directory_uri(); ?>/page_image/top/top_raitenyoyaku_btn.png" width="580" height="106" alt="この店舗に来店予約する" class="mb20"></a>

<a href="<?php bloginfo('url'); ?>/company#sodan"><img src="<?php bloginfo('template_url'); ?>/page_image/top/top_kaiyu01_after.png" alt="充実のアフターメンテナンス" width="580" height="73" class="mb10" /></a>
<!--<a href="<?php bloginfo('url'); ?>/tantou"><img src="<?php bloginfo('template_url'); ?>/page_image/top/top_kaiyu02_hosyo.png" alt="ミスターデイクの工事保証" width="580" height="73" class="mb10" /></a>-->
<div class="left">
<a href="<?php bloginfo('url'); ?>/company"><img src="<?php bloginfo('template_url'); ?>/page_image/top/top_kaiyu03_company.png" alt="会社概要" width="280" height="73" class="mb10" /></a>
</div>
<div class="right clearfix">
<a href="<?php bloginfo('url'); ?>/staff"><img src="<?php bloginfo('template_url'); ?>/page_image/top/top_kaiyu04_staff.png" alt="スタッフ紹介" width="280" height="73" class="mb10" /></a>
</div>

<div class="left">
<a href="<?php bloginfo('url'); ?>/faq"><img src="<?php bloginfo('template_url'); ?>/page_image/top/top_kaiyu05_faq.png" alt="よくあるご質問" width="280" height="73" class="mb20" /></a>
</div>
<div class="right clearfix">
<a href="http://www.mrdeiku-recruit.com/" target="_blank"><img src="<?php bloginfo('template_url'); ?>/page_image/top/top_kaiyu06_recruit.png" alt="採用情報" width="280" height="73" class="mb20" /></a>
</div>
</div>

<?php
}

function seko_cat_under() {
?>


<div class="default_wide clearfix">
<img src="<?php echo get_template_directory_uri(); ?>/page_image/top/top_seko_cat_tit.png" width="580" height="52" alt="施工事例カテゴリー" class="mb10">
<div class="left">
<a href="<?php bloginfo('url'); ?>/seko_cat/ohuro"><img src="<?php echo get_template_directory_uri(); ?>/page_image/top/top_seko_cat_ohuro.png" width="283" height="103" alt="お風呂" class="mb10"></a>
</div>
<div class="right clearfix">
<a href="<?php bloginfo('url'); ?>/seko_cat/kitchen"><img src="<?php echo get_template_directory_uri(); ?>/page_image/top/top_seko_cat_kitchen.png" width="283" height="103" alt="キッチン" class="mb10"></a>
</div>

<div class="left">
<a href="<?php bloginfo('url'); ?>/seko_cat/toilet"><img src="<?php echo get_template_directory_uri(); ?>/page_image/top/top_seko_cat_toilet.png" width="283" height="103" alt="トイレ" class="mb10"></a>
</div>
<div class="right">
<a href="<?php bloginfo('url'); ?>/seko_cat/senmen"><img src="<?php echo get_template_directory_uri(); ?>/page_image/top/top_seko_cat_senmen.png" width="283" height="103" alt="洗面所" class="mb10"></a>
</div>

<div class="left">
<div class="left_in_seko">
<a href="<?php bloginfo('url'); ?>/seko_cat/kyuto"><img src="<?php echo get_template_directory_uri(); ?>/page_image/top/top_seko_cat_kyuto.png" width="133" height="123" alt="給湯" class="mb10"></a>
</div>
<div class="right_in_seko">
<a href="<?php bloginfo('url'); ?>/seko_cat/solor"><img src="<?php echo get_template_directory_uri(); ?>/page_image/top/top_seko_cat_solor.png" width="133" height="123" alt="オール電化" class="mb10"></a>
</div>
</div>
<div class="right clearfix">
<div class="left_in_seko">
<a href="<?php bloginfo('url'); ?>/seko_cat/living"><img src="<?php echo get_template_directory_uri(); ?>/page_image/top/top_seko_cat_living.png" width="133" height="123" alt="内装" class="mb10"></a>
</div>
<div class="right_in_seko">
<a href="<?php bloginfo('url'); ?>/seko_cat/gaiheki"><img src="<?php echo get_template_directory_uri(); ?>/page_image/top/top_seko_cat_gaiheki.png" width="133" height="123" alt="屋根・外壁" class="mb10"></a>
</div>
</div>

<div class="left clearfix">
<div class="left_in_seko">
<a href="<?php bloginfo('url'); ?>/seko_cat/bfree"><img src="<?php echo get_template_directory_uri(); ?>/page_image/top/top_seko_cat_bfree.png" width="133" height="123" alt="バリアフリー" class="mb20"></a>
</div>
<div class="right_in_seko">
<a href="<?php bloginfo('url'); ?>/seko_cat/zenmen"><img src="<?php echo get_template_directory_uri(); ?>/page_image/top/top_seko_cat_zenmen.png" width="133" height="123" alt="全面リフォーム" class="mb20"></a>
</div>
</div>
<div class="right">
<div class="left_in_seko">
<a href="<?php bloginfo('url'); ?>/seko_cat/exterior"><img src="<?php echo get_template_directory_uri(); ?>/page_image/top/top_seko_cat_exterior.png" width="133" height="123" alt="その他" class="mb20"></a>
</div>
</div>
</div>

<!--
<div class="default_wide_bs">
<img src="<?php bloginfo('template_url'); ?>/page_image/seko/cate_ttl_.png" alt="施工事例カテゴリー" width="640" height="73" /><ul class="seko_cate_list">
<li><a href="<?php bloginfo('url'); ?>/seko_cat/ohuro">お風呂</a></li>
<li><a href="<?php bloginfo('url'); ?>/seko_cat/ohuro">キッチン</a></li>
<li><a href="<?php bloginfo('url'); ?>/seko_cat/ohuro">トイレ</a></li>
<li><a href="<?php bloginfo('url'); ?>/seko_cat/ohuro">洗面所</a></li>
<li><a href="<?php bloginfo('url'); ?>/seko_cat/ohuro">給湯器</a></li>
<li><a href="<?php bloginfo('url'); ?>/seko_cat/ohuro">太陽光・オール電化</a></li>
<li><a href="<?php bloginfo('url'); ?>/seko_cat/ohuro">内装・リビング</a></li>
<li><a href="<?php bloginfo('url'); ?>/seko_cat/ohuro">窓・玄関</a></li>
<li><a href="<?php bloginfo('url'); ?>/seko_cat/ohuro">その他</a></li>
<li><a href="<?php bloginfo('url'); ?>/seko_cat/ohuro">外壁・屋根</a></li>
<li><a href="<?php bloginfo('url'); ?>/seko_cat/ohuro">介護リフォーム</a></li>
<li><a href="<?php bloginfo('url'); ?>/seko_cat/ohuro">全面改装</a></li>
</ul>
</div>
-->

<?php
}
















function gr_contact_banner() {
?>
	<!-- ======================問合わせテーブルここから======================= -->
    <div class="content_inquiry"><a href="/contact/"><img src="/wp-content/themes/reform/page_image/top/bnr_contactb_rollout.gif" width="159" height="70" alt="無料お見積り・お問合わせはこちら" /></a><a href="/book/" class="book"><img src="/wp-content/themes/reform/page_image/top/bnr_book_rollout.gif" width="159" height="70" alt="じっくり検討したい！資料請求はこちら" /></a></div>
    	<!-- ======================問合わせテーブルここまで======================= -->

<?php
}

function sekobottom_link() {
?>
	<!-- ======================施工リンク類ここから======================= -->
  <div id="pageseko_cate">
<div class="inner_l">
	<a href="<?php bloginfo('url'); ?>/seko_cat/ohuro" class="btn">お風呂</a>
<a href="<?php bloginfo('url'); ?>/seko_cat/toilet" class="btn">トイレ</a>
<a href="<?php bloginfo('url'); ?>/seko_cat/kyuto" class="btn">給湯器</a>
<a href="<?php bloginfo('url'); ?>/seko_cat/living" class="btn">内装・リビング</a>
	<a href="<?php bloginfo('url'); ?>/seko_cat/gaiheki" class="btn">外壁・屋根</a>
<a href="<?php bloginfo('url'); ?>/seko_cat/zenmen" class="btn">全面改装</a>
</div>
<div class="inner_r">
	<a href="<?php bloginfo('url'); ?>/seko_cat/kitchen" class="btn">キッチン</a>
	<a href="<?php bloginfo('url'); ?>/seko_cat/senmen" class="btn">洗面所・脱衣所</a>
	<a href="<?php bloginfo('url'); ?>/seko_cat/solor" class="btn">太陽光・オール電化</a>
	<a href="<?php bloginfo('url'); ?>/seko_cat/mado" class="btn">窓・玄関</a>
	<a href="<?php bloginfo('url'); ?>/seko_cat/bfree" class="btn">介護リフォーム</a>
	<a href="<?php bloginfo('url'); ?>/seko_cat/exterior" class="btn">その他</a>
</div>


  </div>

	<!-- ======================施工リンク類ここまで======================= -->

<?php
}

function go_top() {
?>
	<!-- ======================トップページへ戻るここから======================= -->
									<a href="#head" class="go_top"><img src="<?php bloginfo('template_url'); ?>/images/foot/go_top.png" width="12" height="12" alt="トップページへ戻る">トップページへ戻る</a>
	<!-- ======================トップページへ戻るここまで======================= -->

<?php
}


function bottom_link() {
?>
	<!-- ======================トップページへ戻るここから======================= -->
<div id="bottom_link">
	<a href="?pc-switcher=1" class="pc"><img src="<?php bloginfo('template_url'); ?>/images/blink/pc.png" width="21" height="21">PCサイト</a>
	<div class="inner_l">
		<a href="<?php bloginfo('url'); ?>/blog" class="btn left">ブログ</a><a href="<?php bloginfo('url'); ?>/staff" class="btn left">スタッフ紹介</a>
	</div>
	<div class="inner_r">
		<a href="<?php bloginfo('url'); ?>/faq" class="btn">よくある質問</a><a href="<?php bloginfo('url'); ?>/event" class="btn">イベント情報</a>
	</div>
</div>
	<!-- ======================トップページへ戻るここまで======================= -->

<?php
}


function gr_contact_sbanner() {
?>
<!-- エラー回避 -->
<?php
}



if ( function_exists('register_sidebar') ) {
	register_sidebar( array(
				'name' => 'sidebar',
				'before_widget' => '',
				'after_widget' => '</ul>',
				'before_title' => '<p class="pic">',
				'after_title' => '</p><ul class="page_left_menu">',
	) );
}

//// enqueue
add_action( 'wp_print_styles', 'gr_print_styles' );
function gr_print_styles() {
	if( ! is_admin() ) {
		if ( is_front_page() ) {
			wp_enqueue_style( 'gr_orbit'  , get_stylesheet_directory_uri() . '/common/css/orbit.css' );
		}
		wp_enqueue_style( 'gr_common'   , get_stylesheet_directory_uri() . '/css/common.css' );
	}
}

add_action( 'wp_enqueue_scripts', 'gr_enqueue_scripts' );
function gr_enqueue_scripts() {
	if ( is_singular() ) wp_enqueue_script( 'comment-reply' );

	if ( ! is_admin() ) {
		wp_enqueue_script( 'jquery'	);//		, get_stylesheet_directory_uri() . '/common/js/jquery-1.5.1.min.js'			, array(		  ), false, true );
		if ( is_front_page() ) {
			wp_enqueue_script( 'gr_orbit'		, get_stylesheet_directory_uri() . '/common/js/jquery.orbit-1.2.3.min.js'	, array( 'jquery' ), false, true );
		}
		wp_enqueue_script( 'gr_rollover'	, get_stylesheet_directory_uri() . '/common/js/rollover2.js'				, array( 'jquery' ), false, true );
		wp_enqueue_script( 'gr_scroll'		, get_stylesheet_directory_uri() . '/common/js/smoothScroll.js'				, array( 'jquery' ), false, true );
		wp_enqueue_script( 'gr_index'		  , get_stylesheet_directory_uri() . '/common/js/index.js'					, array( 'jquery', 'gr_shadowbox' ), false, true );
	}
}

//// admin

//add_action( 'admin_print_scripts-options-general.php', 'gr_options_general' );
add_action( 'admin_footer-options-general.php', 'gr_options_general' );
function gr_options_general() {
?>
<script type="text/javascript">
//<![CDATA[
(function($) {
	if($('body.options-general-php').length) {
		$('#blogdescription').parent().parent().before( $('#gr_companyname' ).parent().parent() );
		$('#blogdescription').parent().parent()
			.after( $('#gr_author' ).parent().parent() )
			.after( $('#gr_keywords' ).parent().parent() )
			.after( $('#gr_description' ).parent().parent() );
	}
})(jQuery);
//]]>
</script>
<?php
}

class GR_Admin {
	static private $options = NULL;

	public function GR_Admin() {
		$this->__construct;
	}

	public function __construct() {
		$this->options = array(
			array( 'id' => 'companyname', 'label' => '会社名'		     , 'desc' => '著作権表示用などに使用する会社名です。' ),
			array( 'id' => 'author'		, 'label' => '作成者'		     , 'desc' => 'サイトの作成者情報です。' ),
			array( 'id' => 'description', 'label' => 'ディスクリプション', 'desc' => '下層ページ用description' ),
			array( 'id' => 'keywords'	, 'label' => 'キーワード'	     , 'desc' => '半角コンマ（,）で区切って複数指定できます。' ),
		);
		add_action( 'admin_init'			, array( &$this, 'add_settings_fields' 		) );
		add_filter( 'whitelist_options'		, array( &$this, 'whitelist_options' 		) );
	}
	public function whitelist_options( $whitelist_options ) {
		foreach ( (array) $this->options as $option ) {
			$whitelist_options['general'][] = 'gr_' . $option['id'];
		}

		return $whitelist_options;
	}
	public function add_settings_fields() {
		foreach ( (array) $this->options as $key => $option ) {
			add_settings_field(
				$key+1, $option['label'], array( &$this, 'print_settings_field' ), 'general', 'default',
				array(
					'label_for' 	=> 'gr_' . $option['id'],
					'description' 	=> $option['desc'],
				)
			);
		}
	}
	public function print_settings_field( $args ) {
		printf(
			'<input name="%1$s" type="text" id="%1$s" value="%2$s" class="regular-text" />',
			esc_attr( $args['label_for'] ),
			esc_attr( get_option( $args['label_for'] ) )
		);
		if ( ! empty( $args['description'] ) )
			printf(
				'<span class="description">%1$s</span>',
				esc_html( $args['description'] )
			);
	}
}

new GR_Admin;

/***************************************/

/**
 * 管理画面でのフォーカスハイライト
 */
function focus_highlight() {
	?>
		<style type="text/css">
		input:focus,textarea:focus{
			background-color: #dee;
		}
	</style>
		<?php
}

add_action( 'admin_head', 'focus_highlight' );

/**
 * 投稿での改行
 * [br] または [br num="x"] x は数字を入れる
 */
function sc_brs_func( $atts, $content = null ) {
	extract( shortcode_atts( array(
					'num' => '5',
					), $atts ));
	$out = "";
	for ($i=0;$i<$num;$i++) {
		$out .= "<br />";
	}
	return $out;
}

add_shortcode( 'br', 'sc_brs_func' );

//---------------------------------------------------------------------------
//\r\nの文字列の無効化
//---------------------------------------------------------------------------

add_filter('post_custom', 'fix_gallery_output');

function fix_gallery_output( $output ){
  $output = str_replace('rn', '', $output );
  return $output;
}


// echo fix_gallery_output(file_get_contents(__FILE__));

//---------------------------------------------------------------------------
//パンくず
//---------------------------------------------------------------------------

function the_pankuzu_keni( $separator = '　→　', $multiple_separator = '　|　' )
{
	global $wp_query;

	echo("<li><a href=\""); bloginfo('url'); echo("\">HOME</a>$separator</li>" );

	$queried_object = $wp_query->get_queried_object();

	if( is_page() )
	{
		//ページ
		if( $queried_object->post_parent )
		{
			echo( get_page_parents_keni( $queried_object->post_parent, $separator ) );
		}
		echo '<li>'; the_title(); echo '</li>';
	}
	else if( is_archive() )
	{
		if( is_post_type_archive() )
		{
			echo '<li>'; post_type_archive_title(); echo '</li>';
		}
		else if( is_category() )
		{
			//カテゴリアーカイブ
			if( $queried_object->category_parent )
			{
				echo get_category_parents( $queried_object->category_parent, 1, $separator );
			}
			echo '<li>'; single_cat_title(); echo '</li>';
		}
		else if( is_day() )
		{
			echo '<li>'; printf( __('Archive List for %s','keni'), get_the_time(__('F j, Y','keni'))); echo '</li>';
		}
		else if( is_month() )
		{
			echo '<li>'; printf( __('Archive List for %s','keni'), get_the_time(__('F Y','keni'))); echo '</li>';
		}
		else if( is_year() )
		{
			echo '<li>'; printf( __('Archive List for %s','keni'), get_the_time(__('Y','keni'))); echo '</li>';
		}
		else if( is_author() )
		{
			echo '<li>'; _e('Archive List for authors','keni'); echo '</li>';
		}
		else if(isset($_GET['paged']) && !empty($_GET['paged']))
		{
			echo '<li>'; _e('Archive List for blog','keni'); echo '</li>';
		}
		else if( is_tag() )
		{
			//タグ
			echo '<li>'; printf( __('Tag List for %s','keni'), single_tag_title('',0)); echo '</li>';
		}
	}
	else if( is_single() )
	{
		$obj = get_post_type_object( $queried_object->post_type );
		if ( $obj->has_archive ) {
			printf(
				'<li><a href="%1$s">%2$s</a>%3$s</li>',
				get_post_type_archive_link( $obj->name ),
				apply_filters( 'post_type_archive_title', $obj->labels->name ),
				$separator
			);
		} else {
			//シングル
			echo '<li>'; the_category_keni( $separator, 'multiple', false, $multiple_separator ); echo '</li>';
			echo( $separator );
		}
		echo '<li>'; the_title(); echo '</li>';
	}
	else if( is_search() )
	{
		//検索
		echo '<li>'; printf( __('Search Result for %s','keni'), strip_tags(get_query_var('s'))); echo '</li>';
	}
	else
	{
		$request_value = "";
		foreach( $_REQUEST as $request_key => $request_value ){
			if( $request_key == 'sitemap' ){ $request_value = $request_key; break; }
		}

		if( $request_value == 'sitemap' )
		{
			echo '<li>'; _e('Sitemap','keni'); echo '</li>';
		}
		else
		{
			echo '<li>'; the_title(); echo '</li>';
		}
	}
}

function get_page_parents_keni( $page, $separator )
{
	$pankuzu = "";

	$post = get_post( $page );

	$pankuzu = '<li><a href="'. get_permalink( $post ) .'">' . $post->post_title . '</a>' . $separator . '</li>';

	if( $post->post_parent )
	{
		$pankuzu = get_page_parents_keni( $post->post_parent, $separator ) . $pankuzu;
	}

	return $pankuzu;
}

function the_category_keni($separator = '', $parents='', $post_id = false, $multiple_separator = '/') {
	echo get_the_category_list_keni($separator, $parents, $post_id, $multiple_separator);
}

function get_the_category_list_keni($separator = '', $parents='', $post_id = false, $multiple_separator = '/')
{
	global $wp_rewrite;
	$categories = get_the_category($post_id);
	if (empty($categories))
		return apply_filters('the_category', __('Uncategorized', 'keni'), $separator, $parents);

	$rel = ( is_object($wp_rewrite) && $wp_rewrite->using_permalinks() ) ? 'rel="category tag"' : 'rel="category"';

	$thelist = '';
	if ( '' == $separator ) {
		$thelist .= '<ul class="post-categories">';
		foreach ( $categories as $category ) {
			$thelist .= "\n\t<li>";
			switch ( strtolower($parents) ) {
				case 'multiple':
					if ($category->parent)
						$thelist .= get_category_parents($category->parent, TRUE, $separator);
					$thelist .= '<a href="' . get_category_link($category->term_id) . '" title="' . sprintf(__('View all posts in %s', 'keni'), $category->name) . '" ' . $rel . '>' . $category->name.'</a></li>';
					break;
				case 'single':
					$thelist .= '<a href="' . get_category_link($category->term_id) . '" title="' . sprintf(__('View all posts in %s', 'keni'), $category->name) . '" ' . $rel . '>';
					if ($category->parent)
						$thelist .= get_category_parents($category->parent, FALSE);
					$thelist .= $category->name.'</a></li>';
					break;
				case '':
				default:
					$thelist .= '<a href="' . get_category_link($category->term_id) . '" title="' . sprintf(__('View all posts in %s', 'keni'), $category->name) . '" ' . $rel . '>' . $category->cat_name.'</a></li>';
			}
		}
		$thelist .= '</ul>';
	} else {
		$i = 0;
		foreach ( $categories as $category ) {
			if ( 0 < $i )
				$thelist .= $multiple_separator . ' ';
			switch ( strtolower($parents) ) {
				case 'multiple':
					if ( $category->parent )
						$thelist .= get_category_parents($category->parent, TRUE, $separator);
					$thelist .= '<a href="' . get_category_link($category->term_id) . '" title="' . sprintf(__('View all posts in %s', 'keni'), $category->name) . '" ' . $rel . '>' . $category->cat_name.'</a>';
					break;
				case 'single':
					$thelist .= '<a href="' . get_category_link($category->term_id) . '" title="' . sprintf(__('View all posts in %s', 'keni'), $category->name) . '" ' . $rel . '>';
					if ( $category->parent )
						$thelist .= get_category_parents($category->parent, FALSE);
					$thelist .= "$category->cat_name</a>";
					break;
				case '':
				default:
					$thelist .= '<a href="' . get_category_link($category->term_id) . '" title="' . sprintf(__('View all posts in %s', 'keni'), $category->name) . '" ' . $rel . '>' . $category->name.'</a>';
			}
			++$i;
		}
	}
	return apply_filters('the_category', $thelist, $separator, $parents);
}
function get_6kajo(){
	include "yakusoku6.php";
}
function get_specials(){
	include "specials.php";
}
function get_menuohuro1(){
	include "menuohuro1.php";
}
function get_menuohuro2(){
	include "menuohuro2.php";
}

function get_menukitchen1(){
	include "menukitchen1.php";
}
function get_menukitchen2(){
	include "menukitchen2.php";
}

function get_menutoilet1(){
	include "menutoilet1.php";
}
function get_menutoilet2(){
	include "menutoilet2.php";
}

function get_menuyane1(){
	include "menuyane1.php";
}
function get_menuyane2(){
	include "menuyane2.php";
}

function get_menugaiheki1(){
	include "menugaiheki1.php";
}
function get_menugaiheki2(){
	include "menugaiheki2.php";
}

function get_menuyuka1(){
	include "menuyuka1.php";
}
function get_menuyuka2(){
	include "menuyuka2.php";
}

function get_menukabegami1(){
	include "menukabegami1.php";
}
function get_menukabegami2(){
	include "menukabegami2.php";
}

function get_menuj2w1(){
	include "menuj2w1.php";
}
function get_menuj2w2(){
	include "menuj2w2.php";
}
function get_menutaishin1(){
	include "menutaishin1.php";
}
function get_menutaishin2(){
	include "menutaishin2.php";
}

function get_menukyuto1(){
	include "menukyuto1.php";
}
function get_menukyuto2(){
	include "menukyuto2.php";
}

function get_menusenmen1(){
	include "menusenmen1.php";
}
function get_menusenmen2(){
	include "menusenmen2.php";
}
function get_menuexterior1(){
	include "menuexterior1.php";
}
function get_menuexterior2(){
	include "menuexterior2.php";
}


//ダッシュボードの記述▼

add_action('wp_dashboard_setup', 'my_custom_dashboard_widgets');

function my_custom_dashboard_widgets() {
global $wp_meta_boxes;

wp_add_dashboard_widget('custom_help_widget', 'ゴッタライドからのお知らせ', 'dashboard_text');
}
function dashboard_text() {
echo '<iframe src="http://www.gotta-ride.com/cloud/news.html" height=200 width=100% scrolling=no>
この部分は iframe 対応のブラウザで見てください。
</iframe>';
}

function example_remove_dashboard_widgets() {
    global $wp_meta_boxes;
    //unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_right_now']); // 現在の状況
    unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_recent_comments']); // 最近のコメント
    unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_incoming_links']); // 被リンク
    unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_plugins']); // プラグイン
    unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_quick_press']); // クイック投稿
    unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_recent_drafts']); // 最近の下書き
    unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_primary']); // WordPressブログ
    unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_secondary']); // WordPressフォーラム
}
add_action('wp_dashboard_setup', 'example_remove_dashboard_widgets');

//ダッシュボードの記述▲

//投稿画面から消す▼

function remove_post_metaboxes() {
    remove_meta_box('tagsdiv-post_tag', 'post', 'normal'); // タグ
}
add_action('admin_menu', 'remove_post_metaboxes');

//投稿画面から消す▲ /ログイン時メニューバー消す▼

add_filter('show_admin_bar', '__return_false');

//ログイン時メニューバー消す▲　/アップデートのお知らせを管理者のみに　▼
if (!current_user_can('edit_users')) {
  function wphidenag() {
    remove_action( 'admin_notices', 'update_nag');
  }
  add_action('admin_menu','wphidenag');
}

//アップデートのお知らせ▲

/**
 *
 * 最新記事のIDを取得
 * @return  Int ID
 *
 */
function get_the_latest_ID() {
    global $wpdb;
    $row = $wpdb->get_row("SELECT ID FROM $wpdb->posts WHERE post_type = 'post' AND post_status = 'publish' ORDER BY post_date DESC");
    return !empty( $row ) ? $row->ID : '0';
}
function the_latest_ID() {
    echo get_the_latest_ID();
}

/*ＩＤ取得*/

function get_gaiyobar(){

echo <<<BNR

<div class="content_gaiyobt">
<h3><img src="/wp-content/themes/reform/page_image/gaiyo/tit_gaiyobt.gif" width="202" height="17" alt="ありがとうの家　会社概要" title="ありがとうの家　会社概要" /></h3>
<ul>
	<li><a href="/company/" title="会社案内"><img src="/wp-content/themes/reform/page_image/gaiyo/bt_gaiyo1_rollout.gif" width="222" height="52" alt="会社案内" /></a></li>
	<li><a href="/event/" title="イベント情報"><img src="/wp-content/themes/reform/page_image/gaiyo/bt_gaiyo2_rollout.gif" width="222" height="52" alt="イベント情報" /></a></li>
	<li><a href="/voice/" title="お客様の声"><img src="/wp-content/themes/reform/page_image/gaiyo/bt_gaiyo3_rollout.gif" width="222" height="52" alt="お客様の声" /></a></li>
	<li><a href="/album/" title="匠アルバム"><img src="/wp-content/themes/reform/page_image/gaiyo/bt_gaiyo4_rollout.gif" width="222" height="52" alt="匠アルバム" /></a></li>
</ul>
</div>
BNR;

}


function get_tenpoinfo(){

echo <<<BNR

<div class="content_gaiyo_list">
<h3><img src="/wp-content/themes/reform/page_image/company/tit_tenpolist.gif" width="730" height="67" alt="店舗一覧" /></h3>
<ul>
	<li><a href="/company/yasu"><img src="/wp-content/themes/reform/page_image/company/bnr_yasu_rollout.jpg" width="223" height="148" alt="野洲店を見る" /></a></li>
	<li><a href="/company/katata"><img src="/wp-content/themes/reform/page_image/company/bnr_katata_rollout.jpg" width="223" height="148" alt="堅田店を見る" /></a></li>
	<li><a href="/company/moriyama"><img src="/wp-content/themes/reform/page_image/company/bnr_moriyama_rollout.jpg" width="223" height="148" alt="守山駅前店を見る" /></a></li>
	<li class="clear_left"><a href="/company/minakuchi"><img src="/wp-content/themes/reform/page_image/company/bnr_mizuguchi_rollout.jpg" width="223" height="148" alt="水口店を見る" /></a></li>
	<li><a href="/company/konan"><img src="/wp-content/themes/reform/page_image/company/bnr_fushimi_rollout.jpg" width="223" height="148" alt="イオンタウン湘南店を見る" /></a></li>
</div>
BNR;

}

//リフォームメニュー　一覧のURL取得処理
function getReformListUrl($cat,$post_id){
	$terms = get_the_terms($post_id,'soudan_cat');
	foreach($terms as $term){
		if($term->slug === $cat){
		  $link = get_term_link((int)$term->term_id,'soudan_cat'). '#' . $post_id;
		  break;
		}
	}
	return $link;
}

//トップ施工事例部分
function getTopSeko($post_id){
	query_posts( array( 'seko_cat' => $post_id, 'posts_per_page' => 6 ));
	$i = 0;
	if(have_posts()) : while (have_posts()) : the_post();
	?>

        <p class="c_jirei<?php
          if($i%3==0){
               echo " clear_left";
          }
     ?>">
       <?php echo '<a href="';
        the_permalink();
        echo '"><span class="im_box">';
        if(post_custom('seko_after_image')){
		printf(
			'%s',
			gr_get_image(
				'seko_after_image',
				array( 'width' => 216, 'alt' => esc_attr( get_the_title() ), 'title' => esc_attr( get_the_title() ) )
			)
		);
	}
        else if(post_custom('seko_point_image01')){
		printf(
			'%s',
			gr_get_image(
				'seko_point_image01',
				array( 'width' => 216, 'alt' => esc_attr( get_the_title() ), 'title' => esc_attr( get_the_title() ) )
			)
		);
	}
	else if($img = post_custom('seko_csv01')){
		echo '<img src="/wp-content/themes/reform/page_image' . $img . '" width="216" alt="' . get_the_title() . '" />';
	}
	else if($img = post_custom('seko_csv2')){
		echo '<img src="/wp-content/themes/reform/page_image' . $img . '" width="216" alt="' . get_the_title() . '" />';
	}
	else if($img = post_custom('seko_csv3')){
		echo '<img src="/wp-content/themes/reform/page_image' . $img . '" width="216" alt="' . get_the_title() . '" />';
	}
	else if($img = post_custom('seko_csv4')){
		echo '<img src="/wp-content/themes/reform/page_image' . $img . '" width="216" alt="' . get_the_title() . '" />';
	}
	else if($img = post_custom('seko_csv5')){
		echo '<img src="/wp-content/themes/reform/page_image' . $img . '" width="216" alt="' . get_the_title() . '" />';
	}
	echo '</span><br />';
        echo '<b>' . get_the_title();
	if( post_custom('seko_newicon') ){
	 	echo '<img src="/wp-content/themes/reform/page_image/new.gif" width="30" height="10" alt="NEW" />';
	};
        echo '</b><br />';
        echo post_custom( 'seko_city' ) . post_custom( 'seko_name' );
        echo '<br />';
        echo '費用：' . post_custom( 'seko_price' );
        echo '工期：' . post_custom( 'seko_duration' ) . '<br />';
        echo '<span class="array_jirei">施工事例を見る</span></a></p>';
 $i++;
 	endwhile; endif; wp_reset_query();
}
//店舗別スタッフ紹介
function tenpoStaffList($tenpo,$belong){
	$args = array(
		'tax_query' => array(
		'relation' => 'AND',
			array(
				'taxonomy' => 'staff_cat',
				'terms' => array( $tenpo ),
				'field' => 'slug',
				'operator' => 'IN',
			),
			array(
				'taxonomy' => 'staff_cat',
				'terms' => array( $belong ),
				'field' => 'slug',
				'operator' => 'IN',
			),
		),
		'posts_per_page' => 15,
	);
	query_posts( $args );
	if(have_posts()) :
	echo '<h4><img src="/wp-content/themes/reform/page_image/staff/' . $belong . '.gif" width="92" alt="" /></h4>';
	echo '<ul>';

	while (have_posts()) : the_post();
	echo '<li><a href="';
	the_permalink();
	echo '">' . gr_get_image('staff_new_img',array('width'=>'78')) . '</a><div><span><strong>' . get_the_title() . '</strong>' . post_custom('staff_name_romaji') . '</br>';
	echo post_custom('staff_belongs') .'</span></div></li>';
	endwhile;

	echo '</ul>';
	endif;
}
function tenpoStaff($tenpo){
	tenpoStaffList($tenpo,'belong_responsible');
	tenpoStaffList($tenpo,'belong_estate');
	tenpoStaffList($tenpo,'belong_reform');
	tenpoStaffList($tenpo,'belong_execution');
	tenpoStaffList($tenpo,'belong_drafting');
	tenpoStaffList($tenpo,'belong_asset');
	tenpoStaffList($tenpo,'belong_planning');
	tenpoStaffList($tenpo,'belong_general');
	tenpoStaffList($tenpo,'belong_sales');
	tenpoStaffList($tenpo,'belong_reform_r');
	tenpoStaffList($tenpo,'belong_estate_r');
	tenpoStaffList($tenpo,'belong_office');
}


//店舗別スタッフ紹介スマホ用
function tenpoStaffListsp($tenpo,$belong){
	$args = array(
		'tax_query' => array(
		'relation' => 'AND',
			array(
				'taxonomy' => 'staff_cat',
				'terms' => array( $tenpo ),
				'field' => 'slug',
				'operator' => 'IN',
			),
			array(
				'taxonomy' => 'staff_cat',
				'terms' => array( $belong ),
				'field' => 'slug',
				'operator' => 'IN',
			),
		),
		'posts_per_page' => 15,
	);
	query_posts( $args );
	if(have_posts()) :
	echo '<h3 class="cate"><img src="/wp-content/themes/reform/page_image/staff/' . $belong . '.gif" alt="" /></h3>';
	echo '<ul>';

	while (have_posts()) : the_post();
	echo '<li><a href="';
	the_permalink();
	echo '">' . gr_get_image('staff_new_img',array('width'=>'78')) . '</a><div><span><strong>' . get_the_title() . '</strong>' . post_custom('staff_name_romaji') . '</br>';
	echo post_custom('staff_belongs') .'</span></div></li>';
	endwhile;

	echo '</ul>';
	endif;
}
function tenpoStaffsp($tenpo){
	tenpoStaffListsp($tenpo,'belong_responsible');
	tenpoStaffListsp($tenpo,'belong_estate');
	tenpoStaffListsp($tenpo,'belong_reform');
	tenpoStaffListsp($tenpo,'belong_execution');
	tenpoStaffListsp($tenpo,'belong_drafting');
	tenpoStaffListsp($tenpo,'belong_asset');
	tenpoStaffListsp($tenpo,'belong_planning');
	tenpoStaffListsp($tenpo,'belong_general');
	tenpoStaffListsp($tenpo,'belong_sales');
	tenpoStaffListsp($tenpo,'belong_reform_r');
	tenpoStaffListsp($tenpo,'belong_estate_r');
	tenpoStaffListsp($tenpo,'belong_office');
}



//現場日記
function get_the_post_image_src($postid,$size,$order=0,$max=null) {
    $attachments = get_children(array('post_parent' => $postid, 'post_type' => 'attachment', 'post_mime_type' => 'image'));
    if ( is_array($attachments) ){
        foreach ($attachments as $key => $row) {
            $mo[$key]  = $row->menu_order;
            $aid[$key] = $row->ID;
        }
        array_multisort($mo, SORT_ASC,$aid,SORT_DESC,$attachments);
        $max = empty($max)? $order+1 :$max;
        for($i=$order;$i<$max;$i++){
            return wp_get_attachment_image_src( $attachments[$i]->ID, $size );
        }
    }
}

function get_the_post_image_id($post_id,$size){
	$attachments = get_children(array('post_parent' => $post_id, 'post_type' => 'attachment', 'post_mime_type' => 'image','posts_per_page' => 1 ));
	if(is_array($attachments)){
	        foreach ($attachments as $attachments) {
	            $imgL = wp_get_attachment_image_src( $attachments->ID, 'large' );
	            echo '<p><a href="' . $imgL[0] . '" rel="lightbox[genba]" title="' . get_the_title() . '">' . wp_get_attachment_image( $attachments->ID, $size ) . '</a></p>';
	        }
	}
}

// Contact Form 7 にショートコードを追加
// function the_tenpo_info ($args) {
//   $template = dirname(__FILE__) . '/archive-tenpo.php';
//   if (!file_exists($template)) {
//     return;
//   }
//   $args = shortcode_atts($def, $args);
//   $posts = get_posts($args);
//   ob_start();
//   foreach ($posts as $post) {
//     $post_custom = get_post_custom($post->ID);
//     include($template);
//   }
//   $output = ob_get_clean();
//   return $output;
// }
// wpcf7_add_shortcode('tenpo_info', 'the_tenpo_info');

//archive-tenpo.php自体をショートコードへ
function the_tenpo_info2 () {
	$args = array(
		'post_type' => 'tenpo', 		/* 投稿タイプを指定 */
		'paged' => $paged,				/* ページ番号を指定 */
		'posts_per_page' => 6,			/* 最大表示数 */
	);
	$postslist = new WP_Query( $args );
	ob_start();
	if ( $postslist->have_posts() ) : while ( $postslist->have_posts() ) : $postslist->the_post(); ?>
	<div class="tenpo_box">
		<p class="tenpo_check">
			<input type="checkbox" name="place[]" value="<?php the_title(); ?>" id="<?php the_title(); ?>">
			<label for="<?php the_title(); ?>">
<?php if( post_custom( 'tenpo_checkbox' ) <>'' ){	//項目が空白でなかったら表示
	echo post_custom( 'tenpo_checkbox' ); }?>
			</label>
		</p>
		<h4><?php the_title(); ?></h4>
		<p><?php printf( '%s', gr_get_image('tenpo_img')); ?></p>
		<p>
<?php if( post_custom( 'tenpo_address' ) <>'' ){	//項目が空白でなかったら表示
	echo '<span class="tenpo_add">住所</span>'.post_custom( 'tenpo_address' ) ;
}?>
		</p>
	</div>
<? endwhile; endif; wp_reset_postdata();
	$output = ob_get_clean();
	return $output;
}
wpcf7_add_shortcode('tenpo_info2', 'the_tenpo_info2');

function change_default_title( $title ) {
$screen = get_current_screen();
if ( $screen->post_type == 'customer' ) {
	$title = '○○市 △△様';
}
	return $title;
}
add_filter( 'enter_title_here', 'change_default_title' );

add_action('edit_form_after_title', function () {
	global $post;
	echo "地域名とお客様名を入力してください。例）テスト市　テスト様";
});

add_action( 'pre_get_posts', 'my_pre_get_posts' );
function my_pre_get_posts( $query ) {
    if ( $query->is_main_query() && ! is_admin() && ( is_post_type_archive( 'seko' ) ) ) {
        $query->set( 'posts_per_page', 15 );
    }
}

function change_posts_per_page($query) {
    if ( is_admin() || ! $query->is_main_query() )
        return;
    //20件ずつ表示させたい
    if ( $query->is_tax( 'seko_cat' ) ) {
        $query->set( 'posts_per_page', '15' );
    }
}
add_action( 'pre_get_posts', 'change_posts_per_page' );

//archive-tenpo.php自体をショートコードへ
function the_event_yoyaku () {
	$args = array(
		'post_type' => 'event', 		/* 投稿タイプを指定 */
		'paged' => $paged,				/* ページ番号を指定 */
		'posts_per_page' => 5,			/* 最大表示数 */
	);
	$postslist = new WP_Query( $args );
	ob_start();
	if ( $postslist->have_posts() ) : while ( $postslist->have_posts() ) : $postslist->the_post(); ?>

<?php if( post_custom('event_yoyaku')){ ?>

		<p class="raiten_event">
			<input type="radio" name="event" value="<?php echo post_custom('event_yoyaku_date'); ?>" id="<?php echo post_custom('event_yoyaku_date'); ?>">
			<label for="<?php echo post_custom('event_yoyaku_date'); ?>"><?php echo post_custom( 'event_yoyaku_date' ); ?>
			</label>
		</p>
<? } ?>
<? endwhile; endif; wp_reset_postdata();
	$output = ob_get_clean();
	return $output;
} ?>
<? 
wpcf7_add_shortcode('event_yoyaku', 'the_event_yoyaku');

//img srcの記述を拾って画像を取得し表示する
function catch_that_image() {
    global $post, $posts;
    $first_img = '';
    ob_start();
    ob_end_clean();
    $output = preg_match_all("/<img[^>]+src=[\"'](s?https?:\/\/[\-_\.!~\*'()a-z0-9;\/\?:@&=\+\$,%#]+\.(jpg|jpeg|png))[\"'][^>]+>/i", $post->post_content, $matches);
    $first_img = $matches [1] [0];
  
if(empty($first_img)){ //Defines a default image
        $first_img = "/wp-content/themes/sp_new/page_image/top/top_event_dammy.png";
    }
    return $first_img;
}

//contactform7プラグインに記事のカスタムフィールドの値を挿入
add_filter('wpcf7_special_mail_tags', 'my_special_mail_tags',10,2);
function my_special_mail_tags($output, $name)
{
    if ( ! isset( $_POST['_wpcf7_unit_tag'] ) || empty( $_POST['_wpcf7_unit_tag'] ) )
        return $output;
    if ( ! preg_match( '/^wpcf7-f(\d+)-p(\d+)-o(\d+)$/', $_POST['_wpcf7_unit_tag'], $matches ) )
        return $output;

    $post_id = (int) $matches[2];//開催日
    if ( ! $post = get_post( $post_id ) )
        return $output;
    $name = preg_replace( '/^wpcf7\./', '_', $name );
    if ( 'event_date_check' == $name )
        $output = get_post_meta($post->ID,'event_date',true);

    $post_id = (int) $matches[2];//開催時間
    if ( ! $post = get_post( $post_id ) )
        return $output;
    $name = preg_replace( '/^wpcf7\./', '_', $name );
    if ( 'event_time_check' == $name )
        $output = get_post_meta($post->ID,'event_time',true);

    $post_id = (int) $matches[2];//開催場所
    if ( ! $post = get_post( $post_id ) )
        return $output;
    $name = preg_replace( '/^wpcf7\./', '_', $name );
    if ( 'event_place_check' == $name )
        $output = get_post_meta($post->ID,'event_place',true);

    $post_id = (int) $matches[2];//開催場所郵便番号
    if ( ! $post = get_post( $post_id ) )
        return $output;
    $name = preg_replace( '/^wpcf7\./', '_', $name );
    if ( 'event_zip_check' == $name )
        $output = get_post_meta($post->ID,'event_zip',true);

    $post_id = (int) $matches[2];//開催場所住所
    if ( ! $post = get_post( $post_id ) )
        return $output;
    $name = preg_replace( '/^wpcf7\./', '_', $name );
    if ( 'event_add_check' == $name )
        $output = get_post_meta($post->ID,'event_add',true);

    return $output;

    if ( ! isset( $_POST['_wpcf7_unit_tag'] ) || empty( $_POST['_wpcf7_unit_tag'] ) )
        return $output;
    if ( ! preg_match( '/^wpcf7-f(\d+)-p(\d+)-o(\d+)$/', $_POST['_wpcf7_unit_tag'], $matches ) )
        return $output;

}
