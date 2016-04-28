<?php
namespace App\Model;

use Doctrine\DBAL\Query\QueryBuilder;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

class PanierModel {
    private $db;

    public function __construct(Application $app) {
        $this->db = $app['db'];
    }

    public function getAllPanier() {
        $queryBuilder = new QueryBuilder($this->db);
        $queryBuilder
            ->select('pa.produit_id,pa.id,pr.nom,pa.quantite,pa.prix,pa.dateAjoutPanier')
            ->from('paniers pa', ',produits pr' )
            ->where('pr.id=produit_id')
            ->orderBy('pr.nom', 'ASC');
        return $queryBuilder->execute()->fetchAll();
    }

    function countNbProduitLigne($produit_id,$user_id){
        $queryBuilder = new QueryBuilder($this->db);
        $queryBuilder
            ->update('count (produit_id');
    }


}