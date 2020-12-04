<?php


namespace App\Mapper\Connector;


interface ConnectorInterface
{
    /**
     * @return mixed
     */
    public function getConnection();

}