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

    public function getPanierUtilisateur($user_id) {
        $queryBuilder = new QueryBuilder($this->db);
        $queryBuilder->select('pa.produit_id, pa.id,pr.nom, pa.quantite, pa.prix, pa.dateAjoutPanier')
                     ->from('paniers pa', ',produits pr')
                     ->where('pr.id=produit_id')
                     ->andWhere('user_id = :user_id')
                     ->orderBy('pr.nom', 'ASC')
                     ->setParameter('user_id', $user_id);
        return $queryBuilder->execute()->fetchAll();
    }



    function countNbProduitLigne($produit_id, $user_id) {
        $queryBuilder = new QueryBuilder($this->db);
        $queryBuilder->select('count(id)')
                     ->from('paniers')
                     ->where('id = :produit_id')
                     ->andWhere('user_id = :user_id')
                     ->andWhere('commande_id is Null')
                     ->setParameter('produit_id', $produit_id)
                     ->setParameter('user_id', $user_id);
        return $queryBuilder ->execute()->fetchColumn(0);
    }

    function updateLigneAdd($quantite, $prix, $user_id, $produit_id) {
        $queryBuilder = new QueryBuilder($this->db);
        $queryBuilder->update('paniers')
                     ->set('quantite', 'quantite + :quantite')
                     ->set('prix', 'prix + :prix * :quantite')
                     ->set('dateAjoutPanier', 'CURRENT_TIMESTAMP')
                     ->where('produit_id = :idProduit')
                     ->setParameter('quantite', $quantite)->setParameter('prix', $prix)->setParameter('idUser', $user_id)->setParameter('idProduit', $produit_id);
        $queryBuilder->execute();
    }

    function deleteArticle($produit_id, $user_id) {
        $queryBuilder = new QueryBuilder($this->db);
        $queryBuilder->delete('paniers')
                     ->where('id = :produit_id')
                     ->andWhere('user_id = :user_id')
                     ->setParameter('produit_id', $produit_id)
                     ->setParameter('user_id', $user_id);
        return $queryBuilder->execute();

    }

    function inserLigne($quantite, $prix, $user_id, $produit_id) {
        print_r($prix);
        $queryBuilder = new QueryBuilder($this->db);
        $queryBuilder->insert('paniers')
                     ->values(['quantite' => ':quantite', 'prix' =>':prix', 'user_id' => ':idUser', 'produit_id' => ':idProduit'])
                     ->setParameter('quantite', $quantite)->setParameter('prix', $prix)->setParameter('idUser', $user_id)->setParameter('idProduit', $produit_id);
        return $queryBuilder->execute();
    }
}