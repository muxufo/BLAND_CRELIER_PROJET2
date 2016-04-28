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

    public function __construct()
    {
    }

    public function index(Application $app) {
        return $this->show($app);
    }

    public function show(Application $app) {
        $this->panierModel = new PanierModel($app);
        $panier = $this->panierModel->getAllPanier();
        return $app["twig"]->render('FrontOffice/Panier/show.html.twig',['data'=>$panier]);
    }

    public function add(Application $app, Request $req){
        $this->prorduitModel = new ProduitModel($app);
        $this->panierModel = new ProduitModel($app);
        $produit_id=$app->escape($req->get('produit_id'));
        $client_id=$app['session']->get['user_id'];
        if($this->panierModel->counNbProduitLigne($produit_id,$client_id)>0){
            $this->panierModel->udpdateLigneAdd($produit_id,$client_id);
        }else
            $this->panierModel->inserLigne($produit_id,$client_id);
        return $app->redirect($app["url_generator"]->generate("Panier.index"));
    }

    public function delete(Application $app, Request $req){
        $this->produitModel = new ProduitModel($app);
        $this->panierModel = new PanierModel($app);
        $produit_id=$app->escape($req->get('produit_id'));
        $client_id=$app['session']->get('user_id');
        $this->panierModel->delete($produit_id);
    }

    public function connect(Application $app) {  //http://silex.sensiolabs.org/doc/providers.html#controller-providers
        $controllers = $app['controllers_factory'];

        $controllers->get('/', 'App\Controller\panierController::index')->bind('panier.index');
        $controllers->get('/show', 'App\Controller\panierController::show')->bind('panier.show');



        return $controllers;
    }


}
