<?php

namespace Metrique\Index;

use Metrique\Index\Abstracts\EloquentRepositoryAbstract;
use Metrique\Index\Contracts\IndexRepositoryInterface;

class IndexRepositoryEloquent extends EloquentRepositoryAbstract implements IndexRepositoryInterface
{

    protected $modelClassName = 'Metrique\Index\Eloquent\Index';
    protected $namespace = null;
    protected $order = null;

    public function all(array $columns = ['*'])
    {
        $model = $this->model->all($columns);

        if(!is_null($this->namespace))
        {
            $model = $model->where('namespace', $namespace);
        }

        return $model;
    }

    public function paginate($perPage = 10, array $columns = ['*'])
    {
        $model = $this->model->paginate($perPage, $columns);

        if(!is_null($this->namespace))
        {
            $model = $model->where('namespace', $namespace);
        }

        return $model;
    }

    public function create(array $data)
    {
        $model = $this->model->create($data);

        if(!is_null($this->namespace))
        {
            $model = $model->where('namespace', $namespace);
        }

        return $model;
    }

    public function update($id, array $data)
    {
        $model = $this->model->find($id)->update($data);

        if(!is_null($this->namespace))
        {
            $model = $model->where('namespace', $namespace);
        }

        return $model;
    }

    public function find($id, array $columns = ['*'])
    {
        $model = $this->model->find($id, $columns);

        if(!is_null($this->namespace))
        {
            $model = $model->where('namespace', $namespace);
        }

        return $model;
    }

    public function destroy($id)
    {  
        $model = $this->model->destroy($id);

        if(!is_null($this->namespace))
        {
            $model = $model->where('namespace', $namespace);
        }

        return $model;
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
        $index = $this->findTypes($types);
        $indexMap = [];
        $nested = [];

        // Create index map & init children array.
        foreach($index as $key => $value)
        {
            // Map the database Id to the array Id
            $indexMap[$value['id']] = $key;

            // Initialise children.
            $index[$key]['children'] = [];
        }

        foreach($index as $key => $value)
        {
            $indicesId = $index[$key]['indices_id'];
            
            // Add any root items to the nested array.
            if(is_null($indicesId))
            {
                $nested[] = &$index[$key];
            }

            // Add any child items to their parent.
            if(!is_null($indicesId))
            {
                // Get the the array Id of the parent from the Index map.
                $indexId = $indexMap[$indicesId];
                
                // Add the child to the parent.
                $index[$indexId]['children'][] = &$index[$key];
            }
        }

        return $nested;
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