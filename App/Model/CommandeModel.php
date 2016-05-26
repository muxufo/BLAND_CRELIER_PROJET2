<?php

namespace App\Model;

use Doctrine\DBAL\Query\QueryBuilder;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
/**
 * Created by PhpStorm.
 * User: nicolas
 * Date: 12/05/2016
 * Time: 09:23
 */
class CommandeModel {
    private $db;

    public function __construct(Application $app) {
        $this->db = $app['db'];
    }

    public function getCommandeVendeur(){
        $queryBuilder = new QueryBuilder($this->db);
        $queryBuilder
            ->select('c.id', 'c.user_id', 'c.prix', 'c.date_achat', 'c.etat_id', 'e.libelle')
            ->from('commandes', 'c')
            ->innerJoin('c', 'etats', 'e', 'c.etat_id=e.id')
            ->addOrderBy('e.libelle', 'ASC');
        return $queryBuilder->execute()->fetchAll();
    }

    public function effacerCommandeVendeur($id){
        $queryBuilder = new QueryBuilder($this->db);
        $queryBuilder->delete('commandes')
            ->where('id = :id')
            ->setParameter('id', $id);
        return $queryBuilder->execute();
    }


    public function valideCommandeVendeur($id){
        $queryBuilder = new QueryBuilder($this->db);
        $queryBuilder->update('commandes')
            ->set('etat_id', '2')
            ->where('id = :id')
            ->setParameter('id', $id);
        $queryBuilder->execute();
    }


    public function getCommandeClient($client_id){
        $queryBuilder = new QueryBuilder($this->db);
        $queryBuilder
            ->select('c.id', 'c.user_id', 'c.prix', 'c.date_achat', 'c.etat_id', 'e.libelle')
            ->from('commandes', 'c')
            ->innerJoin('c', 'etats', 'e', 'c.etat_id=e.id')
            ->where('user_id = :client_id')
            ->addOrderBy('e.libelle', 'ASC')
            ->setParameter('client_id',$client_id);
        return $queryBuilder->execute()->fetchAll();
    }

//    public function createCommande($user_id, $prixTotal){
//        $conn = $this->db;
//        $conn -> beginTransaction();
//        $requestSQL = $conn-> prepare('select sum(prix*quantite) as prix from paniers where user_id = :idUser and commande_id is NULL');
//        $requestSQL->execute(['idUser'=>$user_id]);
//        $prix=$requestSQL->fetch()['prix'];
//        $conn->commit();
//        $conn->beginTransaction();
//        $requestSQL = $conn->prepare(' insert into commandes(user_id,prix,etat_id)VALUES (?,?,?) ');
//        $requestSQL->execute([$user_id,$prix,1]);
//        $lastinsertid=$conn->lastInsertId();
//        $requestSQL = $conn->prepare('update paniers set commande_id=? where user_id=? and commande_id is NULL');
//        $requestSQL->execute([$lastinsertid,$user_id]);
//        $conn->commit();
//    }
//
//    public function getAllCommandes() {
//        $queryBuilder = new QueryBuilder($this->db);
//        $queryBuilder
//            ->select('c.id', 'u.user_id', 'c.prix', 'c.date_achat', 'e.etat_id')
//            ->from('commandes', 'c')
//            ->innerJoin('c', 'users', 'u', 'c.user_id=u.user_id')
//            ->innerJoin('c', 'etats', 'e', 'c.etat_id=e.id')
//            ->addOrderBy('p.nom', 'ASC');
//        return $queryBuilder->execute()->fetchAll();
//    }
//
//    public function getCommandeClient($user_id) {
//        $queryBuilder = new QueryBuilder($this->db);
//        $queryBuilder->select('c.id', 'u.user_id', 'c.prix', 'c.date_achat', 'e.etat_id')
//            ->from('commandes', 'c')
//            ->innerJoin('c', 'users', 'u', 'c.user_id=u.user_id')
//            ->innerJoin('c', 'etats', 'e', 'c.etat_id=e.id')
//            ->where('user_id = :user_id')
//            ->addOrderBy('p.nom', 'ASC')
//            ->setParameter('user_id', $user_id);
//        return $queryBuilder->execute()->fetchAll();
//    }
}