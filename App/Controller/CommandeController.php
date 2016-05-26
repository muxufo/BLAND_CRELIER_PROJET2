<?php
namespace App\Controller;

use App\Model\CommandeModel;
use App\Model\ProduitModel;
use Silex\Application;
use Silex\ControllerProviderInterface;

use Symfony\Component\HttpFoundation\Request;   // pour utiliser request

use App\Model\PanierModel;
use App\Model\TypeProduitModel;


use Symfony\Component\Validator\Constraints as Assert;   // pour utiliser la validation
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Security;

class CommandeController  implements ControllerProviderInterface
{
    private $commandeModel;

    public function __construct() {}

    public function index(Application $app) {
        return $this->showVendeur($app);
    }

    /***
     * @param Application $app
     * @return mixed
     * PARTIE VENDEUR
     *
     *
     */
    public function showVendeur(Application $app){
        $this->commandeModel = new CommandeModel($app);
        $commande = $this->commandeModel->getCommandeVendeur();
        return $app["twig"]->render('backOff/Commande/showVendeur.html.twig',['data'=>$commande]);
    }

    public function valideVendeur(Application $app, Request $req){
        $this->commandeModel = new CommandeModel($app);
        $id_commande = $app->escape($req->get('id'));
        $this->commandeModel->valideCommandeVendeur($id_commande);
        return $app->redirect($app["url_generator"]->generate("commande.index"));
    }

    public function deleteVendeur(Application $app, Request $req){
        $this->commandeModel = new CommandeModel($app);
        $id=$app->escape($req->get('id'));
        $this->commandeModel->effacerCommandeVendeur($id);
        return $app->redirect($app["url_generator"]->generate("commande.index"));
    }

    /***
     * @param Application $app
     * @return mixed
     *
     * PARTIE CLIENT
     */
    public function indexClient(Application $app) {
        return $this->show($app);
    }

    public function show(Application $app){
        $this->commandeModel = new CommandeModel($app);
        $client_id=$app['session']->get('id_user');
        $commande = $this->commandeModel->getCommandeClient($client_id);
        return $app["twig"]->render('backOff/Commande/show.html.twig',['data'=>$commande]);
    }

    public function connect(Application $app) {  //http://silex.sensiolabs.org/doc/providers.html#controller-providers
        $controllers = $app['controllers_factory'];

        $controllers->get('/', 'App\Controller\CommandeController::index')->bind('commande.index');
        $controllers->get('/showVendeur', 'App\Controller\CommandeController::showVendeur')->bind('commande.showVendeur');

        $controllers->get('/', 'App\Controller\CommandeController::index')->bind('commande.index');
        $controllers->get('/valideVendeur', 'App\Controller\CommandeController::valideVendeur')->bind('commande.valideVendeur');


        $controllers->get('/', 'App\Controller\CommandeController::index')->bind('commande.index');
        $controllers->get('/deleteVendeur', 'App\Controller\CommandeController::deleteVendeur')->bind('commande.deleteVendeur');




        $controllers->get('/', 'App\Controller\CommandeController::indexClient')->bind('commande.indexClient');
        $controllers->get('/show', 'App\Controller\CommandeController::show')->bind('commande.show');



        return $controllers;
    }


}
