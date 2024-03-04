<?php
ini_set('date.timezone', 'Europe/Paris');
use Symfony\Component\Debug\ErrorHandler;
use Symfony\Component\Debug\ExceptionHandler;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Bridge\Twig\Extension\FormExtension;
use Symfony\Bridge\Twig\Form\TwigRenderer;
use Symfony\Component\Security\Csrf\CsrfTokenManager;
use Symfony\Bridge\Twig\Form\TwigRendererEngine;
use Silex\Provider\FormServiceProvider;
// Register global error and exception handlers
//ErrorHandler::register();
//ExceptionHandler::register();

// Register service providers
//$app->register(new Silex\Provider\DoctrineServiceProvider());
$app->register(new \LiquidBox\Silex\Provider\PdoServiceProvider(), array(
    'pdo.dsn' => 'sqlite:/var/www/vhosts/silex.ouvaton.org/httpdocs/db/microcms2.sqlite',
));
$app->register(new Silex\Provider\LocaleServiceProvider());
$app->register(new Silex\Provider\TranslationServiceProvider(), array(
    'locale_fallbacks' => array('en'),
    'locale' => 'en',
));
$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/../views',
));
$app->register(new FormServiceProvider());
$app->register(new Silex\Provider\AssetServiceProvider(), array(
    'assets.version' => 'v1'
));
$app->register(new Silex\Provider\SessionServiceProvider());
$app->register(new Silex\Provider\SecurityServiceProvider(), array(
    'security.firewalls' => array(
        'secured' => array(
            'pattern' => '^/',
            'anonymous' => true,
            'logout' => true,
            'form' => array('login_path' => '/login', 'check_path' => '/login_check'),
            'users' => function () use ($app) {
                return new MicroCMS\DAO\UserDAO($app['pdo']);
            },
        ),
    ),
    'security.role_hierarchy' => array(
        'ROLE_ADMIN' => array('ROLE_USER'),
    ),
    'security.access_rules' => array(
        array('^/admin', 'ROLE_ADMIN'),
        array('^/foo$', 'ROLE_ADMIN'),
    ),
));






// Register services
$app['dao.article'] = function ($app) {
    return new MicroCMS\DAO\ArticleDAO($app['pdo']);
};
$app['dao.user'] = function ($app) {
    return new MicroCMS\DAO\UserDAO($app['pdo']);
};
$app['dao.comment'] = function ($app) {
    $commentDAO = new MicroCMS\DAO\CommentDAO($app['pdo']);
    $commentDAO->setArticleDAO($app['dao.article']);
    $commentDAO->setUserDAO($app['dao.user']);
    return $commentDAO;
};

