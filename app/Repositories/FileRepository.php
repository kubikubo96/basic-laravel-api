<?php

namespace App\Repositories;

use App\Models\File;

class FileRepository extends BaseRepository
{
    public function getModel(): string
    {
        return File::class;
    }
}
