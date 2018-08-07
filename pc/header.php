<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1, maximum-scale=1">
<meta name="format-detection" content="telephone=no">
<title>一戸建て・注文住宅（潮来・神栖・鹿嶋）ならサンアンホームへお任せください。</title>

<link rel="shortcut icon" href="<?php bloginfo('template_directory'); ?>/img/common/favicon.ico" />
<link href="style.css" rel="stylesheet" type="text/css" />
<link href="<?php bloginfo('template_directory'); ?>/css/sanitize.css" rel="stylesheet" type="text/css" /><!-- リセットcss -->
<link href="<?php bloginfo('template_directory'); ?>/css/page.css" rel="stylesheet" type="text/css" /><!-- 通常css -->
<script src="//code.jquery.com/jquery-1.10.1.min.js"></script>
<!--===== PCだけ読み込まれる =====-->
<?php if(!wp_is_mobile()): ?>

<!-- ボタンのロールオーバーアニメーション -->
<script src="<?php bloginfo('template_directory'); ?>/js/jquery.easy-rollover.js"></script>
<!-- //ボタンのロールオーバーアニメーション -->

<!-- ナビのプルダウン実装 -->
<!-- //ナビのプルダウン実装 -->

<?php endif; ?>
<!--===== //PCだけ読み込まれる =====-->

<!-- ドロワーメニュー -->
<link href="<?php bloginfo('template_directory'); ?>/css/slicknav.css" rel="stylesheet" type="text/css" />
<!-- //ドロワーメニュー -->

<!-- スライドショー -->
<link href="<?php bloginfo('template_directory'); ?>/css/slick-theme.css" rel="stylesheet" type="text/css" />
<link href="<?php bloginfo('template_directory'); ?>/css/slick.css" rel="stylesheet" type="text/css" />
<!-- //スライドショー -->

<?php if(is_page(array('jupiter','symphony','just','fc003','hiraya') ) ): ?>
<!-- 商品ラインナップ -->
<link href="<?php bloginfo('template_directory'); ?>/css/lineup.css" rel="stylesheet" type="text/css" />
<!-- //商品ラインナップ -->
<?php endif; ?>

<?php if( is_page('seko')|| is_parent_slug() === 'seko'): ?>
<!-- 施工事例 -->
<link href="<?php bloginfo('template_directory'); ?>/css/seko.css" rel="stylesheet" type="text/css" />
<!-- //施工事例 -->
<?php endif; ?>

<link href="<?php bloginfo('template_directory'); ?>/css/<?php echo esc_html(get_post_type_object(get_post_type())->name); ?>.css" rel="stylesheet" type="text/css" />

<!-- lightbox -->
<link href="<?php bloginfo('template_directory'); ?>/css/lightbox.css" rel="stylesheet" type="text/css" />
<script src="<?php bloginfo('template_directory'); ?>/js/lightbox.js"></script>
<!-- //lightbox -->

<script>
    $(function () {
        var ua = window.navigator.userAgent;
        if (ua.indexOf('iPhone') > 0 || ua.indexOf('Android') > 0 && ua.indexOf('Mobile') > 0) {
            // スマートフォン用コード
        } else if (ua.indexOf('iPad') > 0 || ua.indexOf('Android') > 0) {
            $("div#footerFloatingMenu").addClass("del-ffm");
        } else {
            // PC用コード
        }
    })
</script>

<?php wp_head(); ?>

<!-- トラッキングコード -->
<script>
(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
})(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

ga('create', 'UA-98011803-1', 'auto');
ga('send', 'pageview');

</script>
<!-- //トラッキングコード -->

<head>

<body>

<a id="top" name="top"></a>


<!-- ヘッダー -->
<div id="head">
<div id="header_inner">
<div class="head-left">
<h1>潮来・神栖・鹿嶋で新築を建てるならサンアンホームにお任せ！</h1>


<?php if(!wp_is_mobile()): ?>
<div class="sp_none">
<a href="http://www.san-an.com/" target="_blank" class="go_reform">
<span>▲</span>リフォームについてはこちら
</a>
<br clear="all" />
</div>
<?php endif; ?>


<a href="<?php echo home_url(); ?>"><img src="<?php bloginfo('template_directory'); ?>/img/header/rogo.gif" alt="サンアンホーム・新築専門サイト" width="264" height="60" class="rogo" /></a>
<h2>サンアンホーム・新築専門サイト</h2>
</div>


<?php if(!wp_is_mobile()): ?>
<div class="sp_none">
<div class="head-right">
<a href="<?php echo home_url(); ?>/book" class="book">資料請求</a>
</div>
<div class="head-middle">
<a href="<?php echo home_url(); ?>/company" class="b79d81_btn">
会社概要
</a>
<a href="<?php echo home_url(); ?>/event" class="b79d81_btn">
イベント情報
</a>
<br clear="all" />
<img src="<?php bloginfo('template_directory'); ?>/img/header/head_tel.gif" width="301" height="37" />
<p>受付時間 : 9:00～18:00 | 定休日 : 水曜・日曜・祝日</p>
</div>
</div>
<?php endif; ?>

</div><!-- end #header_inner -->


<div id="groval_navi">
<div id="navi">
    <ul id="menu" class="clearfix">
      <li><a href="<?php echo home_url(); ?>/concept"><span class="menu_sub_tit">サンアンホームの</span><br />家づくりのコンセプト</a></li>
      <li class="one"><a>商品ラインナップ<i class="fa fa-caret-right"></i></a>
        <ul class="sub-menu">
          <li><a href="<?php echo home_url(); ?>/lineup/jupiter">　―Jupiter Cube</a></li>
          <li><a href="<?php echo home_url(); ?>/lineup/symphony">　―Symphony</a></li>
          <li><a href="<?php echo home_url(); ?>/lineup/just">　―Just201</a></li>
          <li><a href="<?php echo home_url(); ?>/lineup/fc003">　―FC003</a></li>
          <li><a href="<?php echo home_url(); ?>/lineup/hiraya">　―HIRAYA</a></li>
        </ul>
      </li>
      <li class="one"><a href="<?php echo home_url(); ?>/nagare">家づくりの流れ</a></li>
      <li class="one"><a href="<?php echo home_url(); ?>/seko">施工事例</a></li>
      <li class="one"><a href="<?php echo home_url(); ?>/faq">よくあるご質問</a></li>
     <div class="sp_none"> <li class="one"><a href="<?php echo home_url(); ?>/staff">スタッフ紹介</a></li></div>
	  
	  <?php if(wp_is_mobile()): ?>
      <li class="one"><a href="<?php echo home_url(); ?>/company">会社概要</a></li>
	   <li class="one"><a href="<?php echo home_url(); ?>/staff">スタッフ紹介</a></li>
	   <!--<li class="one"><a href="<?php echo home_url(); ?>/event">イベント情報</a></li>-->	   <li class="one"><a href="<?php echo home_url(); ?>/contact">お問い合わせ</a></li>
	   <li class="one"><a href="<?php echo home_url(); ?>/book">資料請求</a></li>
<?php endif; ?>

    </ul>
  </div>
</div><!-- end #groval_navi -->

</div><!-- end #head -->
<!-- //ヘッダー -->