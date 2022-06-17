<?php

namespace App\Services;

use Elasticsearch\ClientBuilder;

class ElasticsearchService
{
    private $client;

    public function __construct()
    {
        $this->client = ClientBuilder::create()->build();
    }

    public function create()
    {
        $params = [
            'index' => 'my_post',
            'type' => 'my_post',
            'body' => [
                'id' => "id",
                'user_id' => "user_id",
                'title' => "title",
                'slug' => "slug",
                'content' => "content",
                'image' => "image",
                'delete_at' => "delete_at",
                'created_at' => "created_at",
                'updated_at' => "updated_at",

            ]
        ];

        $this->client->index($params);
    }

    public function search($index, $string = ''): array
    {
        if ($string) {
            $fields = ['title'];
            $params = [
                'index' => $index,
                'body' => [
                    'query' => [
                        'multi_match' => [
                            'query' => $string,
                            'fields' => $fields
                        ]
                    ]
                ]
            ];
        } else {
            $params = [
                'index' => $index,
                'body' => [
                    'query' => [
                        'match_all' => (object)[],
                    ]
                ]
            ];
        }
        $data = $this->client->search($params);

        $res['data'] = collect($data['hits']['hits'])->pluck('_source')->toArray();
        $res['total'] = $data['hits']['total'];

        return $res;
    }

    public function find($index, $id)
    {
        $params = [
            'index' => $index,
            'body' => [
                'query' => [
                    'bool' => [
                        'must' => [
                            [
                                'match' => ['id' => $id]
                            ],
                        ],
                    ]
                ],
            ]
        ];

        return $this->client->search($params);
    }
}
