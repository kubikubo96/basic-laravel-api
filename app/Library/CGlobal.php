<?php
namespace App\Library;

class CGlobal
{
    public static $version = 1.0;


    /**
     * ACTIVE, INACTIVE
     */
    const ACTIVE = 1;
    const INACTIVE = 0;

    /**
     * Type elasticsearch job
     */
    const ELASTIC_CREATE = 1;
    const ELASTIC_UPDATE = 2;
    const ELASTIC_DELETE = 3;

    /**
     * index = type elasticsearch
     */
    const INDEX_ELASTIC_POST = 'post';

    /**
     * Job queue
     */
    const QUEUE_ELASTICSEARCH = 'Elasticsearch';
}
