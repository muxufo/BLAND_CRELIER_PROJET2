<?php
/**
 * Created by PhpStorm.
 * User: nicolas
 * Date: 12/05/2016
 * Time: 09:23
 */



public function createCommande($user_id,$prixTotal){
    $conn = $this->db;
    $conn -> beginTransaction();
    $requestSQL=$conn-> prepare('select sum(prix*quantite) as prix from paniers where user_id = :idUser and commande_id is NULL');
    $requestSQL->exectue(['idUser'=>$user_id]);
    $prix=$requestSQL->fetch()['prix'];
    $conn->commit();
    $conn->beginTransaction();
    $requestSQL=$conn->prepare(' insert into commandes(user_id,prix,etat_id)VALUES (?,?,?) ');
    $requestSQL->execute([$user_id,$prix,1]);
    $lastinsertid=$conn->lastInsertId();
    $requestSQL=$conn->prepare('update paniers set commande_id=? where user_id=? and commande_id is NULL');
    $requestSQL->execute([$lastinsertid,$user_id]);
    $conn->commit();
}