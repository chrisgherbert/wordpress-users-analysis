<?php

// Load Composer
require_once('../vendor/autoload.php');

// Load other files
require_once('../classes/WordPressUsers.php');
require_once('../classes/EmailHashReverser.php');

// Twig
$twig_loader = new Twig_Loader_Filesystem('../views');
$twig = new Twig_Environment($twig_loader, array(
	'debug' => true
));

// Load .env
$dotenv = new \Dotenv\Dotenv(__DIR__ . '/..');
$dotenv->load();

// Routes
$router = new \Klein\Klein();

// Home Page
$router->respond('GET', '/', function () use ($twig) {
	echo $twig->render('home.twig');
});

// Search
$router->respond('GET', '/search', function () use ($twig) {

	$data = array();

	$data['url'] = $_REQUEST['site_url'];

	$data_getter = new WordPressUsers($data['url']);

	try {
		$data['users'] = $data_getter->get_data();
	} catch (Exception $e) {
		$data['error'] = $e->getMessage();
	}

	echo $twig->render('results.twig', $data);

});

$router->dispatch();