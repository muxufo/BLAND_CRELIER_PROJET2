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
            ->select('*')
            ->from('paniers', 'pan')
            ->innerJoin('pan', 'produit', 'p', 'pan.id_produit = p.id_produit')
            ->addOrderBy('pan.id_produit', 'ASC');
        return $queryBuilder->execute()->fetchAll();
    }
}