<?php

// Load Composer
require_once('../vendor/autoload.php');

// Load other files
require_once('../classes/WordPressSite.php');
require_once('../classes/WordPressEntity.php');
require_once('../classes/WordPressEntityWithGravatar.php');
require_once('../classes/WordPressUser.php');
require_once('../classes/WordPressComment.php');
require_once('../classes/EmailHashReverser.php');
require_once('../classes/WordPressDataCollection.php');
require_once('../classes/WordPressUsersCollection.php');
require_once('../classes/WordPressCommentsCollection.php');

// Twig
$twig_loader = new Twig_Loader_Filesystem('../views');
$twig = new Twig_Environment($twig_loader);
$twig->addExtension(new Twig_Extension_Debug());
$twig->enableDebug();

// Load .env
$dotenv = new \Dotenv\Dotenv(__DIR__ . '/..');
$dotenv->load();

// Router
$router = new \Klein\Klein();

////////////
// Routes //
////////////

// Home Page
$router->respond('GET', '/', function () use ($twig) {
	echo $twig->render('home.twig');
});

// Users
$router->respond('GET', '/users', function () use ($twig) {

	$data = array();

	$data['url'] = $_REQUEST['site_url'];

	try {

		// Site data
		$data['site'] = new WordPressSite($data['url']);

		// Users data
		$data['items'] = new WordPressUsersCollection(
			$data['url'],
			$data['site']->get_users_endpoint_url(),
			$_REQUEST['page'] ?? 1
		);

	} catch (Exception $e) {
		$data['error'] = $e->getMessage();
	}

	echo $twig->render('users.twig', $data);

});

// Comments
$router->respond('GET', '/comments', function () use ($twig) {

	$data = array();

	$data['url'] = $_REQUEST['site_url'];

	try {

		// Site data
		$data['site'] = new WordPressSite($data['url']);

		// Users data
		$data['items'] = new WordPressCommentsCollection(
			$data['url'],
			$data['site']->get_comments_endpoint_url(),
			$_REQUEST['page'] ?? 1
		);

	} catch (Exception $e) {
		$data['error'] = $e->getMessage();
	}

	echo $twig->render('comments.twig', $data);

});


$router->dispatch();