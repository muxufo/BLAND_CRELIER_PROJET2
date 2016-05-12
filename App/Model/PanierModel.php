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



    function counNbProduitLigne($produit_id,$user_id){
        $queryBuilder = new QueryBuilder($this->db);

        $queryBuilder
            ->select('count(produit_id')->from('paniers')
            ->where('produit_id= :idProduit')->andWhere('user_id = :id_User')
            ->andWhere('commande_id is Null')
            ->setParameter('idProduit',$produit_id)->setParameter('user_id',$user_id);
        return $queryBuilder ->execute()->fetchColumn(0);
    }

    function updateLigneAdd($produit_id,$user_id){

    }

    function deleteArticle($produit_id, $user_id) {
        $queryBuilder = new QueryBuilder($this->db);
        $queryBuilder
            ->delete('paniers')
            ->setParameter('produit_id',$produit_id)
            ->setParameter('user_id',$user_id)
        ;
        return $queryBuilder->execute();
    }

    function inserLigne($quantite, $prix, $user_id, $produit_id){
        $queryBuilder = new QueryBuilder($this->db);
        $queryBuilder->insert('paniers')
            ->values(['$quantite' => ':quantite', '$prix' =>':prix', '$user_id' => ':idUser', 'produit_id' => ':idProduit'])
            ->setParameter('quantite', $quantite)->setParameter('prix', $prix)->setParameter('idUser', $user_id)->setParameter('idProduit', $produit_id);
        return $queryBuilder->execute();
    }
}