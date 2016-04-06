<?php
namespace App\Model;

use Doctrine\DBAL\Query\QueryBuilder;
use Silex\Application;

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
            ->where('pr.id=produit_id');
            //->orderBy('pan.id_produit', 'ASC');
        return $queryBuilder->execute()->fetchAll();
    }
}