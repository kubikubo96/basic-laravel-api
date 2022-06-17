<?php

namespace App\Repositories;

use App\Models\Todo;

class TodoRepository extends BaseRepository
{
    public function getModel(): string
    {
        return Todo::class;
    }

    public function index($request): array
    {
        $query = $this->query([], [], ['done' => 'ASC']);
        $res['total'] = $query->count();
        $res['data'] = $this->paginatedQuery($query, $request->page, $request->limit);
        return $res;
    }
}
