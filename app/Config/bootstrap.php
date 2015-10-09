<?php
Configure::write('Dispatcher.filters', array(
	'AssetDispatcher',
	'CacheDispatcher'
));

App::uses('CakeLog', 'Log');
CakeLog::config('debug', array(
	'engine' => 'File',
	'types' => array('notice', 'info', 'debug'),
	'file' => 'debug',
));
CakeLog::config('error', array(
	'engine' => 'File',
	'types' => array('warning', 'error', 'critical', 'alert', 'emergency'),
	'file' => 'error',
));

Configure::write('Config.language', 'rus');

CakePlugin::loadAll();

Configure::write('ZzapApi', array(
	// 'url' => 'http://www.zzap.ru/webservice/test/datasharing1.asmx/',
	'url' => 'http://www.zzap.ru/webservice/datasharing.asmx/',
	'key' => 'EAAAAOInZ5vBwkgYdsvhjHvBppQYdUTeJ640oUJJzxCoE2vglu4v2Wm5xwo77ZCTSXvOHA==',
	'log' => ROOT.DS.APP_DIR.DS.'tmp'.DS.'logs'.DS.'zzap_api.log'
));

Configure::write('ElcatsApi', array(
	'url' => 'http://www.elcats.ru/',
	'cookie' => ROOT.DS.APP_DIR.DS.'config'.DS.'cookie_jar.txt',
	'log' => ROOT.DS.APP_DIR.DS.'tmp'.DS.'logs'.DS.'zzap_api.log'
));

Configure::write('TechDocApi', array(
	'url' => 'http://pilot.api.iauto.by/get/',
	'key' => '6e8d6e800a22725dd2fc31b172a98401',
	'log' => ROOT.DS.APP_DIR.DS.'tmp'.DS.'logs'.DS.'techdoc_api.log'
));
Cache::config('techdoc', array(
	'engine' => 'DbTable',
	'storage' => 'cache_techdoc',
	'lock' => false,
	'serialize' => true,
));

Configure::write('AutoxpApi', array(
	'url' => 'http://app.autoxp.ru/pscomplex/catalog.aspx?salerind=917',
	'search_url' => 'http://app.autoxp.ru/support/catvinident.aspx?salerind=917&lavel=2',
	'cookies' => ROOT.DS.APP_DIR.DS.'tmp'.DS.'logs'.DS.'autoxp_cookies.txt',
	'log' => ROOT.DS.APP_DIR.DS.'tmp'.DS.'logs'.DS.'autoxp_api.log'
));
Cache::config('autoxp', array(
	'engine' => 'DbTable',
	'storage' => 'cache_autoxp',
	'lock' => false,
	'serialize' => true,
));

Configure::write('PartTradeApi', array(
	'url' => 'http://www.parttrade.ru/ws/services?wsdl',
	'log' => ROOT.DS.APP_DIR.DS.'tmp'.DS.'logs'.DS.'parttrade_api.log',
	'username' => 'giperzap',
	'password' => 'mogirus159'
));

// Values from google recaptcha account
define('RECAPTCHA_PUBLIC_KEY', '6Lezy-QSAAAAAJ_mJK5OTDYAvPEhU_l-EoBN7rxV');
define('RECAPTCHA_PRIVATE_KEY', '6Lezy-QSAAAAACCM1hh6ceRr445OYU_D_uA79UFZ');

Configure::write('Recaptcha.publicKey', RECAPTCHA_PUBLIC_KEY);
Configure::write('Recaptcha.privateKey', RECAPTCHA_PRIVATE_KEY);

define('DOMAIN_NAME', 'giperzap.dev');
define('DOMAIN_TITLE', 'GiperZap.dev');

define('AUTH_ERROR', __('Invalid username or password, try again'));
define('TEST_ENV', $_SERVER['SERVER_ADDR'] == '192.168.1.22');

define('EMAIL_ADMIN', 'fyr.work@gmail.com');
define('EMAIL_ADMIN_CC', 'fyr.work@gmail.com');

define('PATH_FILES_UPLOAD', $_SERVER['DOCUMENT_ROOT'].'/files/');


function fdebug($data, $logFile = 'tmp.log', $lAppend = true) {
		file_put_contents($logFile, mb_convert_encoding(print_r($data, true), 'cp1251', 'utf8'), ($lAppend) ? FILE_APPEND : null);
}