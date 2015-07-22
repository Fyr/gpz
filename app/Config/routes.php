<?php
Router::parseExtensions('html', 'json');
Router::connect('/', array('controller' => 'Pages', 'action' => 'home'));
// Router::connect('/', array('controller' => 'pages', 'action' => 'display', 'home'));
// Router::connect('/', array('controller' => 'Admin', 'action' => 'index'));

CakePlugin::routes();

Router::connect('/TecDoc', 
	array(
		'controller' => 'Techdoc',
		'action' => 'index'
	)
);
Router::connect('/TecDoc/:action/*', 
	array(
		'controller' => 'Techdoc'
	)
);

Router::connect('/car/:carType/:carSubtype/:slug', 
	array(
		'controller' => 'Search', 
		'action' => 'index',
	)
);


Router::connect('/car/:carType/:carSubtype/', 
	array(
		'controller' => 'Car', 
		'action' => 'view',
	)
);

Router::connect('/car/:carType/', 
	array(
		'controller' => 'Car', 
		'action' => 'viewCarType',
	)
);

require CAKE.'Config'.DS.'routes.php';