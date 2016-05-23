<?php
namespace App\Model;

use Doctrine\DBAL\Query\QueryBuilder;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

class CommandeModel {
    private $db;

    public function __construct(Application $app)
    {
        $this->db = $app['db'];
    }

    /*public function add(Application $app, Request req) {
        $this->CommandeModel = new CommandeModel($app);
    }*/
}