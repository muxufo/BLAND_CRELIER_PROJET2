<?php
namespace App\Controller;

use Silex\Application;
use Silex\ControllerProviderInterface;

use Symfony\Component\HttpFoundation\Request;   // pour utiliser request

use App\Model\CommandeModel;

use Symfony\Component\Validator\Constraints as Assert;   // pour utiliser la validation
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Security;

class CommandeController {
    private $db;
    private $commandeModel;

    public function __construct(Application $app)
    {
        $this->db = $app['db'];
    }

    /*public function add(Application $app, Request req) {
        $this->CommandeModel = new CommandeModel($app);
    }*/

    public function index(Application $app) {
        return $this->show($app);
    }

    public function show(Application $app) {
        $this->commandeModel = new CommandeModel($app);
        $commandes = $this->commandeModel->getAllCommandes();
        return $app["twig"]->render('backOff/Commande/show.html.twig',['data'=>$commandes]);
    }

    /*public function showCommande(Application $app) {
        $this->commandeModel = new CommandeModel($app);
        $user_id = // trouver comment faire pour obtenir le user_id;
        $commande = $this->commandeModel->getCommandeClient($user_id);
        return $app["twig"]->render('backOff/Commande/show.html.twig', ['data' => $commande]); // creer une page specifique pour n'avoir qu'une seule commande affichée ?
    }*/
}