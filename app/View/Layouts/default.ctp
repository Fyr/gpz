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
								<a href="mailto:Giperzap@mail.ru"><?=Configure::read('Settings.admin_email')?></a>
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
					<div class="banners clearfix">
						<a href="<?=$this->Html->url(array('controller' => 'Techdoc', 'action' => 'index'))?>">
							<img src="/img/tecdoc.png" alt="Поиск по TecDoc каталогу" />
						</a>
						<a href="<?=$this->Html->url(array('controller' => 'Techdoc', 'action' => 'index'))?>">
							<img src="/img/tecdoc.png" alt="Поиск по TecDoc каталогу" style=""/>
						</a>
					</div>
<?
	if ($aBreadCrumbs) {
		echo $this->element('bread_crumbs');
	}
?>
					<?=$this->fetch('content')?>
				</div>
				
				<div class="footer">
					<div class="wrapper clearfix">
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
								<a href="mailto:Giperzap@mail.ru"><?=Configure::read('Settings.admin_email')?></a>
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
				<div class="footerLine"></div>
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
