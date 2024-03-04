<?php

namespace MicroCMS\DAO;

use MicroCMS\Domain\Article;

abstract class DAO 
{
    /**
     * Database connection
     *
     * @var \Doctrine\DBAL\Connection
     */
    private $pdo;

    /**
     * Constructor
     *
     * @param \Doctrine\DBAL\Connection The database connection object
     */
    public function __construct(\PDO $pdo) {
        $this->pdo = $pdo;
    }

    /**
     * Grants access to the database connection object
     *
     * @return \Doctrine\DBAL\Connection The database connection object
     */
    protected function getDb() {
        return $this->pdo;
    }

    /**
     * Builds a domain object from a DB row.
     * Must be overridden by child classes.
     */
    protected abstract function buildDomainObject(array $row);
}
