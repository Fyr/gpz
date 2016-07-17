<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, user-scalable=no, maximum-scale=1.0, initial-scale=1.0, minimum-scale=1.0">
		<meta name="format-detection" content="telephone=no">
<?
	echo $this->Html->charset();
	echo $this->element('Seo.seo_info', array('data' => $seo));
	echo $this->Html->meta('icon');

	echo $this->Html->css(array('fonts', 'style', 'extra'));
	
	$aScripts = array(
		'vendor/jquery/jquery-1.10.2.min',
		'main'
	);
	echo $this->Html->script($aScripts);

	echo $this->fetch('meta');
	echo $this->fetch('css');
	echo $this->fetch('script');
?>

        <!--[if gte IE 9]>
            <style type="text/css">
                .gradient { filter: none; }
            </style>
        <![endif]-->
	</head>
	<body>
		<div class="shadowRight">
			<div class="shadowLeft">
				<div class="header">
					<div class="menuLogo clearfix">
						<a href="/" class="logo"></a>
						<?=$this->element('SiteUI/main_menu')?>
					</div>
					<div class="rightContent">
						<div class="address">
							<span class="icon mapTop"></span>
							<span class="text">
								<?=Configure::read('Settings.office_address')?>
							</span>
						</div>
						<div class="contacts">
							<div>
								<a href="callto:giperzap" class="icon skype"></a>
								<a href="callto:giperzap"><?=Configure::read('Settings.skype')?></a>
							</div>
							<div>
								<a href="mailto:Giperzap@mail.ru" class="icon email"></a>
								<a href="mailto:Giperzap@mail.ru"><?=Configure::read('Settings.email')?></a>
							</div>
						</div>
						<div class="phones">
							<span class="icon phoneTop"></span>
							<span class="numbers">
								<?=Configure::read('Settings.phone1')?><br />
								<?=Configure::read('Settings.phone2')?>
							</span>
						</div>
					</div>
				</div>
				<div class="wrapper">
					<div class="searchShadow">
						<form class="searchBlock" action="<?=$this->Html->url(array('controller' => 'Search', 'action' => 'index'))?>" method="get">
							<button type="button" class="submit" onclick="$('form.searchBlock').submit()">поиск</button>
							<div class="outerSearch">
								<span class="icon search"></span>
								<input type="text" name="q" value="<?=$this->request->query('q')?>"  placeholder="Например V-200 alfa" />
							</div>
						</form>
					</div>
<?
	if ($this->request->controller == 'Pages' && $this->request->action == 'home') {
?>
					<div class="banners clearfix">
						<a href="<?=$this->Html->url(array('controller' => 'Techdoc', 'action' => 'index'))?>">
							<img src="/img/tecdoc.png" alt="Поиск по каталогу TecDoc" />
						</a>
						<a href="<?=$this->Html->url(array('controller' => 'Autoxp', 'action' => 'index'))?>">
							<img src="/img/autoxp.png" alt="Поиск по каталогу AutoXP" style=""/>
						</a>
					</div>
<?
	}
	if ($aBreadCrumbs) {
		echo $this->element('bread_crumbs');
	}
?>
					<?=$this->fetch('content')?>
<?
	if (!($this->request->controller == 'Pages' && $this->request->action == 'home')) {
?>
					<div class="banners clearfix">
						<a href="<?=$this->Html->url(array('controller' => 'Techdoc', 'action' => 'index'))?>">
							<img src="/img/tecdoc.png" alt="Поиск по каталогу TecDoc" />
						</a>
						<a href="<?=$this->Html->url(array('controller' => 'Autoxp', 'action' => 'index'))?>">
							<img src="/img/autoxp.png" alt="Поиск по каталогу AutoXP" style=""/>
						</a>
					</div>
<?
	}
?>

				</div>
				<div class="footer">
					<div class="footerWrap clearfix">
						<div class="leftSide">
							<a href="#" class="logo"></a>
							<div class="copyright">Все права защищены © <?=date('Y')?></div>
						</div>
						<?=$this->element('SiteUI/bottom_links')?>
						<div class="contacts">
							<div>
								<a href="callto:giperzap" class="icon skype"></a>
								<a href="callto:giperzap"><?=Configure::read('Settings.skype')?></a>
							</div>
							<div>
								<a href="mailto:Giperzap@mail.ru" class="icon email"></a>
								<a href="mailto:Giperzap@mail.ru"><?=Configure::read('Settings.email')?></a>
							</div>
						</div>
						<div class="colomn">
							<div class="address">
								<span class="icon mapTop"></span>
								<span class="text">
									<?=Configure::read('Settings.office_address')?>
								</span>
							</div>
							<div class="phones">
								<span class="icon phoneTop"></span>
								<span class="numbers">
									<?=Configure::read('Settings.phone1')?><br />
									<?=Configure::read('Settings.phone2')?>
								</span>
							</div>
						</div>
						<img src="/img/car1.png" alt="" class="carFirst" />
						<img src="/img/car2.png" alt="" class="carSecond" />
					</div>
				</div>
				<div class="footerLine" style="text-align: center; height: auto; padding: 2px 0">&nbsp;
<?
	if (Configure::read('domain.url') == 'giperzap.by') {
?>
<!--LiveInternet counter--><script type="text/javascript"><!--
document.write("<a href='//www.liveinternet.ru/click' "+
"target=_blank><img src='//counter.yadro.ru/hit?t14.12;r"+
escape(document.referrer)+((typeof(screen)=="undefined")?"":
";s"+screen.width+"*"+screen.height+"*"+(screen.colorDepth?
screen.colorDepth:screen.pixelDepth))+";u"+escape(document.URL)+
";"+Math.random()+
"' alt='' title='LiveInternet: показано число просмотров за 24"+
" часа, посетителей за 24 часа и за сегодня' "+
"border='0' width='88' height='31'><\/a>")
//--></script><!--/LiveInternet-->
<!-- Yandex.Metrika counter -->
<script type="text/javascript">
    (function (d, w, c) {
        (w[c] = w[c] || []).push(function() {
            try {
                w.yaCounter35355930 = new Ya.Metrika({
                    id:35355930,
                    clickmap:true,
                    trackLinks:true,
                    accurateTrackBounce:true
                });
            } catch(e) { }
        });

        var n = d.getElementsByTagName("script")[0],
            s = d.createElement("script"),
            f = function () { n.parentNode.insertBefore(s, n); };
        s.type = "text/javascript";
        s.async = true;
        s.src = "https://mc.yandex.ru/metrika/watch.js";

        if (w.opera == "[object Opera]") {
            d.addEventListener("DOMContentLoaded", f, false);
        } else { f(); }
    })(document, window, "yandex_metrika_callbacks");
</script>
<noscript><div><img src="https://mc.yandex.ru/watch/35355930" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
<!-- /Yandex.Metrika counter -->
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-73744599-1', 'auto');
  ga('send', 'pageview');

</script>
<?
	}
?>
				</div>
			</div>
		</div>
		<div id="loader">
			<img src="/img/mogirus-logo-bw.png" alt="" /><br />
			<img src="/img/ajax-loader-bar.gif" alt="" /><br />
			Обработка запроса...
		</div>
<?
	if (TEST_ENV) {
		// echo $this->element('sql_dump');
	} else {
		echo $this->element('jivosite');
	}
?>
	</body>
</html>
<?
echo '<!-- '.round(microtime(true) - TIME_START, 4).'-->';
?>