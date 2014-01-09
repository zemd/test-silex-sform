<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Doctrine\Common\Cache\ArrayCache;
use Neutron\Silex\Provider\MongoDBODMServiceProvider;
use Silex\Provider\FormServiceProvider;
use Symfony\Component\HttpFoundation\Request;
use Silex\Provider\TranslationServiceProvider;
use Silex\Provider\TwigServiceProvider;
use Symfony\Component\Validator\Constraints;

$app = new Silex\Application();
$app->register(new FormServiceProvider());
$app->register(new TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/../views',
));
$app->register(new TranslationServiceProvider(), array(
    'locale' => 'en_US',
    'translation.class_path' =>  __DIR__ . '/../vendor/symfony',
    'translator.messages' => array()
)) ;
$app->register(new MongoDBODMServiceProvider(), array(
    'doctrine.odm.mongodb.connection_options' => array(
        'database' => 'webfilter',
        'host'     => 'localhost',
        'port'     => '27017',
    ),
    'doctrine.odm.mongodb.proxies_dir'             => __DIR__.'/../cache/doctrine/odm/mongodb/Proxy',
    'doctrine.odm.mongodb.proxies_namespace'       => 'DoctrineMongoDBProxy',
    'doctrine.odm.mongodb.auto_generate_proxies'   => true,
    'doctrine.odm.mongodb.hydrators_dir'           => __DIR__.'/../cache/doctrine/odm/mongodb/Hydrator',
    'doctrine.odm.mongodb.hydrators_namespace'     => 'DoctrineMongoDBHydrator',
    'doctrine.odm.mongodb.auto_generate_hydrators' => true,
    'doctrine.odm.mongodb.metadata_cache'          => new ArrayCache(),
    'doctrine.odm.mongodb.logger_callable'         => null,
    'doctrine.odm.mongodb.documents' => array(
        array(
            'type' => 'annotation',
            'path' => array(
                'src/Webfilter',
            ),
            'namespace' => 'Webfilter'
        ),
    ),
));
$app['debug'] = true;

$app->get('/', function() use ($app) {
    $orgs = $app['doctrine.odm.mongodb.dm']
        ->getRepository('Webfilter\\Document\\Organization')
        ->findAll();

    return $app['twig']->render('list.twig', array('orgs' => $orgs));
});
$dm = $app['doctrine.odm.mongodb.dm'];


$app->match('/add', function(Request $request) use ($app, $dm) {
    /**
     * @var Symfony\Component\Form\Form $form
     */
    $form = $app['form.factory']->create(new \Webfilter\Forms\OrganizationType());
    $form->handleRequest($request);

    if ($form->isValid()) {
        /**
         * @var Webfilter\Document\Organization $org
         */
        $org = $form->getData();
        $dm->persist($org);
        $dm->flush();

        return $app->redirect("/{$org->getId()}");
    }

    return $app['twig']->render('form.twig', array('form' => $form->createView()));
});

$app->match('/edit/{id}', function (Request $request, $id) use ($app, $dm) {
    /**
     * @var Webfilter\Document\Organization $org
     */
    $org = $app['doctrine.odm.mongodb.dm']
        ->getRepository('Webfilter\\Document\\Organization')
        ->find($id);

    if (!$org) {
        $app->abort(404, 'No such organization');
    }

    /**
     * @var Symfony\Component\Form\Form $form
     */
    $form = $app['form.factory']->create(new \Webfilter\Forms\OrganizationType(), $org);
    $form->handleRequest($request);

    if ($form->isValid()) {
        /**
         * @var Webfilter\Document\Organization $org
         */
        $org = $form->getData();
        $dm->persist($org);
        $dm->flush();

        return $app->redirect("/{$org->getId()}");
    }

    return $app['twig']->render('form.twig', array('form' => $form->createView()));
});

$app->run();
