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
                     ->Where('user_id = :user_id')
                     ->andWhere('produit_id = :produit_id')
                     ->setParameter('produit_id', $produit_id)
                     ->setParameter('user_id', $user_id);
        return $queryBuilder ->execute()->fetchColumn(0);
    }

    function updateLigneAdd($prix, $user_id, $produit_id) {
        $queryBuilder = new QueryBuilder($this->db);
        $queryBuilder->update('paniers')
                     ->set('quantite', 'quantite + 1')
                     ->set('prix', 'prix + :prix')
                     ->set('dateAjoutPanier', 'CURRENT_TIMESTAMP')
                     ->where('produit_id = :idProduit')
                     ->setParameter('prix', $prix)->setParameter('idUser', $user_id)->setParameter('idProduit', $produit_id);
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

    function deleteAllArticle($user_id){
        $queryBuilder = new QueryBuilder($this->db);
        $queryBuilder->delete('paniers')
            ->where('user_id = :user_id')
            ->setParameter('user_id', $user_id);
        return $queryBuilder->execute();
    }

    function validePanier($user_id, $prix, $date_achat){
        $queryBuilder = new QueryBuilder($this->db);
        $queryBuilder->insert('commandes')
            ->values(['prix' =>':prix', 'user_id' => ':idUser', 'date_achat' => ':date_achat', 'etat_id' => '1'])
            ->setParameter('prix', $prix)->setParameter('idUser', $user_id)->setParameter('date_achat', $date_achat);
        return $queryBuilder->execute();
    }

    function inserLigne($prix, $user_id, $produit_id) {

        $queryBuilder = new QueryBuilder($this->db);
        $queryBuilder->insert('paniers')
                     ->values(['quantite' => '1', 'prix' =>':prix', 'user_id' => ':idUser', 'produit_id' => ':idProduit'])
                     ->setParameter('prix', $prix)->setParameter('idUser', $user_id)->setParameter('idProduit', $produit_id);
        return $queryBuilder->execute();
    }
}