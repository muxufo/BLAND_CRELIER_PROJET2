<?php
namespace App\Controller;

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

class PanierController implements ControllerProviderInterface
{
    private $panierModel;
    private $typePanierModel;

    public function __construct(){}

    public function index(Application $app) {
        return $this->show($app);
    }

    public function show(Application $app) {
        $this->panierModel = new PanierModel($app);
        $client_id=$app['session']->get('id_user');
        $panier = $this->panierModel->getPanierUtilisateur($client_id);
        return $app["twig"]->render('FrontOffice/Panier/show.html.twig',['data'=>$panier]);
    }

    public function add(Application $app, Request $req){
        $this->produitModel = new ProduitModel($app);
        $this->panierModel = new PanierModel($app);
        $prix = $app->escape($req->get('prix'));
        $produit_id=$app->escape($req->get('id'));
        $client_id=$app['session']->get('id_user');

        if($this->panierModel->countNbProduitLigne($produit_id, $client_id)>0) {
            $this->panierModel->updateLigneAdd($prix,$client_id, $produit_id);
        } else {
            $this->panierModel->inserLigne($prix, $client_id, $produit_id);
        }
        return $app->redirect($app["url_generator"]->generate("panier.index"));
    }

    public function delete(Application $app, Request $req){
        $this->produitModel = new ProduitModel($app);
        $this->panierModel = new PanierModel($app);
        $produit_id=$app->escape($req->get('id'));
        $client_id=$app['session']->get('id_user');
        $this->panierModel->deleteArticle($produit_id,$client_id);
        return $app->redirect($app["url_generator"]->generate("panier.index"));
    }

    public function valide(Application $app, Request $req){
        $this->produitModel = new ProduitModel($app);
        $this->panierModel = new PanierModel($app);
        $prix = $app->escape($req->get('prix'));
        $user_id = $app['session']->get('id_user');
        $date_achat = $app->escape($req->get('date_achat'));
        $this->panierModel->validePanier($user_id,$prix,$date_achat);
//  pas fini
        return $app->redirect($app["url_generator"]->generate("panier.index"));
    }

    public function connect(Application $app) {  //http://silex.sensiolabs.org/doc/providers.html#controller-providers
        $controllers = $app['controllers_factory'];

        $controllers->get('/', 'App\Controller\panierController::index')->bind('panier.index');
        $controllers->get('/show', 'App\Controller\panierController::show')->bind('panier.show');


        $controllers->get('/', 'App\Controller\panierController::index')->bind('panier.index');
        $controllers->get('/valide', 'App\Controller\panierController::valide')->bind('panier.valide');


        $controllers->get('/', 'App\Controller\panierController::index')->bind('panier.index');
        $controllers->get('/add', 'App\Controller\panierController::add')->bind('panier.add');

        $controllers->get('/', 'App\Controller\panierController::index')->bind('panier.index');
        $controllers->get('/delete', 'App\Controller\panierController::delete')->bind('panier.delete');


        return $controllers;
    }


}
