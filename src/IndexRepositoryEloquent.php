<?php

namespace Metrique\Index;

use Metrique\Index\Abstracts\EloquentRepositoryAbstract;
use Metrique\Index\Contracts\IndexRepositoryInterface;
use Metrique\Index\Traits\IndexArrayTrait;

class IndexRepositoryEloquent extends EloquentRepositoryAbstract implements IndexRepositoryInterface
{
    use IndexArrayTrait;

    protected $modelClassName = 'Metrique\Index\Eloquent\Index';
    protected $namespace = null;
    protected $order = null;
    protected $slug = null;

    public function all(array $columns = ['*'])
    {
        $model = $this->model->all($columns);
        $model = $this->applyNamespace($model);
        $model = $this->applyOrder($model);

        return $model;
    }

    public function paginate($perPage = 10, array $columns = ['*'])
    {
        $model = $this->model->paginate($perPage, $columns);
        $model = $this->applyNamespace($model);
        $model = $this->applyOrder($model);

        return $model;
    }

    public function create(array $data)
    {
        $model = $this->model->create($data);

        return $model;
    }

    public function update($id, array $data)
    {
        $model = $this->model->find($id)->update($data);

        return $model;
    }

    public function find($id, array $columns = ['*'])
    {
        $model = $this->model->find($id, $columns);

        return $model;
    }

    public function destroy($id)
    {  
        $model = $this->model->destroy($id);

        return $model;
    }

    /**
     * {@inheritdocs}
     */
    public function getNamespace($namespace = null)
    {
        return $this->namespace;
    }

    /**
     * {@inheritdocs}
     */
    public function setNamespace($namespace = null)
    {
        $this->namespace = $namespace;

        return $this;
    }

    /**
     * {@inheritdocs}
     */
    public function setOrder($column = 'order', $order = 'desc')
    {
        $this->order = [
            'column' => $column,
            'order' => $order,
        ];

        return $this;
    }

    /**
     * {@inheritdocs}
     */
    public function getSlug($slug = null)
    {
        return $this->slug;
    }

    /**
     * {@inheritdocs}
     */
    public function setSlug($slug = null)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * {@inheritdocs}
     */
    public function findTypes(array $types)
    {
        $defaults = [
            'disabled' => null,
            'navigation' => null,
            'published' => null,
        ];

        $types = array_intersect_key($types, $defaults);
        $types = array_merge($defaults, $types);

        $model = $this->model;

        // Apply filters
        $model = $this->applyNamespace($model);
        $model = $this->applyOrder($model);

        foreach($types as $key => $value)
        {
            if(!is_null($value))
            {
                $model = $model->where($key, $value ? 1 : 0);
            }
        }

        return $model->get()->toArray();
    }

    /**
     * {@inheritdocs}
     */
    public function findAndNestTypes(array $types)
    {
        return $this->createNestedIndex($this->findTypes($types), ['slug' => $this->slug]);
    }

    /**
     * Helper function to apply namespace filters.
     */
    private function applyNamespace($model)
    {
        if(is_null($this->namespace))
        {
            return $model;
        }

        return $model->where('namespace', $this->namespace);
    }

    /**
     * Helper function to apply order
     */
    private function applyOrder($model)
    {
        if(is_null($this->order))
        {
            return $model;
        }

        return $model->orderBy($this->order['column'], $this->order['order']);
    }
}