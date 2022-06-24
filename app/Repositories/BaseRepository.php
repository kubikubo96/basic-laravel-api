<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;

abstract class BaseRepository
{
    /**
     * @var Model
     */
    protected $_model;

    /**
     * BaseRepository constructor.
     */
    public function __construct()
    {
        $this->setModel();
    }

    /**
     * Get model
     *
     * @return mixed
     */
    abstract public function getModel();

    /**
     * Set model
     */
    public function setModel()
    {
        $this->_model = resolve($this->getModel());
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection|Model[]
     */
    public function all()
    {
        return $this->_model->all();
    }

    /**
     * Get one
     * @param $id
     * @return mixed
     */
    public function find($id)
    {
        return $this->_model->find($id);
    }

    /**
     * Create
     * @param array $data
     * @return mixed
     */
    public function create(array $data)
    {
        $result = $this->_model->create($data);
        if ($result) {
            return $this->_model->find($result['id'] ?? $result['uuid']);
        }
        return $result;
    }

    /**
     * Insert
     * @param array $data
     * @return mixed
     */
    public function insert(array $data)
    {
        return $this->_model->insert($data);
    }

    /**
     * Update
     * @param $id
     * @param array $data
     * @return bool|mixed
     */
    public function update($id, array $data)
    {
        $result = $this->find($id);
        if ($result) {
            $result->update($data);
            return $result;
        }
        return false;
    }

    /**
     * Delete
     *
     * @param $id
     * @return bool
     */
    public function delete($id): bool
    {
        $result = $this->find($id);
        if ($result) {
            $result->delete();
            return true;
        }
        return false;
    }

    /**
     * Xóa không cần kiểm tra tồn tại
     * $ids example = (1),(1,2,3),[1, 2, 3],collect([1, 2, 3]
     * @param $ids
     * @return int
     */
    public function destroy($ids): int
    {
        return $this->_model->destroy($ids);
    }

    /**
     * Create Or Update
     * @param array $data
     * @return mixed
     */
    public function createOrUpdate(array $data)
    {
        if (isset($data['id'])) {
            $this->update($data['id'], $data);
        }
        return $this->create($data);
    }

    /**
     * Update Or Create
     *
     * Nếu tìm được column theo option, thì update data
     * Nếu không tìm được, thì tạo mới theo option và data
     *
     * @param array $option
     * @param array $data
     * @return mixed
     */
    public function updateOrCreate(array $option, array $data)
    {
        return $this->_model->updateOrCreate($option, $data);
    }

    /**
     * Restore: un-delete
     *
     * @param $instance
     * @return mixed
     */
    public function restore($instance)
    {
        return $instance->restore();
    }

    /**
     * Các lựa chọn query where data
     *
     * @param $options
     * @param $query
     * @return mixed
     */
    public function switchQuery($options, $query)
    {
        if (!empty($options)) {
            foreach ($options as $item) {
                $opera = $item['opera'] ?? '=';
                switch ($opera) {
                    case 'like':
                        $query = $query->where($item['key'], 'like', '%' . $item['value'] . '%');
                        break;
                    case 'in':
                        $query = $query->whereIn($item['key'], $item['value']);
                        break;
                    case 'null':
                        $query = $query->whereNull($item['key']);
                        break;
                    case 'notNull':
                        $query = $query->whereNotNull($item['key']);
                        break;
                    case 'between':
                        $query = $query->whereBetween($item['key'], $item['value']);
                        break;
                    default:
                        $query = $query->where($item['key'], $opera, $item['value']);
                }

            }
        }
        return $query;
    }

    /**
     * Query data
     *
     * @param array $options
     * @param array $with
     * @param array $order
     */
    public function query($options = [], $with = [], $order = [])
    {
        $query = $this->_model;
        if ($options) {
            foreach ($options as $key => $value) {
                if (is_array($value)) {
                    $query = $query->whereIn($key, $value);
                } else {
                    $query = $query->where($key, $value);
                }
            }
        }
        if ($with) {
            $query = $query->with($with);
        }
        if ($order) {
            foreach ($order as $key => $value) {
                $query = $query->orderBy($key, $value);
            }
        } else {
            $query = $query->latest();
        }
        return $query;
    }

    /**
     * Query data sâu hơn
     *
     * @param array $options
     * @return mixed
     */
    public function queryDeeper($options = [])
    {
        $query = $this->query();
        if (!empty($options['select'])) {
            $query = $query->select($options['select']);
        }

        if (!empty($options['options'])) {
            $query = $this->switchQuery($options['options'], $query);
        }

        if (!empty($options['with'])) {
            if (!empty($options['with']['relation'])) {
                $relation = $options['with']['relation'];
                $options = $options['with']['options'] ?? [];
                $query = $query->with([$relation => function ($query) use ($options) {
                    if (!empty($options)) {
                        $this->switchQuery($options, $query);
                    }
                }]);
            } else {
                $query = $query->with($options['with']);
            }
        }

        if (!empty($options['where-has'])) {
            if (!empty($options['where-has']['relation'])) {
                $relation = $options['where-has']['relation'];
                $options = $options['where-has']['options'] ?? [];
                $query = $query->whereHas($relation, function ($query) use ($options) {
                    if (!empty($options)) {
                        $this->switchQuery($options, $query);
                    }
                });
            } else {
                $query = $query->whereHas($options['where-has'], function ($query) {
                });
            }
        }

        if (isset($options['with-trash'])) {
            $query = $query->withTrashed();
        }

        if (isset($options['only-trash'])) {
            $query = $query->onlyTrashed();
        }

        return $query->orderBy($options['order_by'] ?? 'created_at', $options['sort'] ?? 'desc');
    }

    /**
     * Get first
     *
     * @param array $options
     * @param array $with
     */
    public function first($options = [], $with = [])
    {
        return $this->query($options, $with)->first();
    }

    /**
     * Get first theo query deeper
     *
     * @param array $options
     * @return mixed
     */
    public function firstDeeper($options = [])
    {
        return $this->queryDeeper($options)->first();
    }

    /**
     * Phân trang theo query
     *
     * @param $query
     * @param int $page
     * @param int $limit
     * @return mixed
     */
    public function paginatedQuery($query, $page = 1, $limit = 20)
    {
        $page = $page ? (int)$page : 1;
        $limit = $limit ? (int)$limit : 20;

        if ($page <= 0) {
            $page = 1;
        }
        if ($limit > 100) {
            $limit = 100;
        }

        $query->take($limit);
        $query->skip(($page - 1) * $limit);

        return $query->get();
    }

    /**
     * Phân trang theo function query
     *
     * @param $options
     * @param int $page
     * @param int $limit
     * @param array $with
     * @param array $order
     * @return array
     */
    public function paginate($options, $page = 1, $limit = 20, $with = [], $order = []): array
    {
        $query = $this->query($options, $with, $order);
        $data['total'] = $query->count();
        $data['data'] = $this->paginatedQuery($query, $page, $limit);

        return $data;
    }

    /**
     * Phân trang theo function query deeper
     *
     * @param $options
     * @param int $page
     * @param int $limit
     * @return array
     */
    public function paginateDeeper($options, $page = 1, $limit = 20): array
    {
        $query = $this->queryDeeper($options);
        $data['total'] = $query->count();
        $data['data'] = $this->paginatedQuery($query, $page, $limit);

        return $data;
    }
}
