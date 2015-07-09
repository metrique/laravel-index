<?php

namespace Metrique\Index;

use Metrique\Index\Abstracts\EloquentAbstractRepository;
use Metrique\Index\Contracts\IndexRepositoryInterface;

class EloquentIndexRepository extends EloquentAbstractRepository implements IndexRepositoryInterface
{
    protected $modelClassName = 'Metrique\Index\Eloquent\Index';

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

        foreach($types as $key => $value)
        {
            if(!is_null($value))
            {
                $model = $model->where($key, $value ? 1 : 0);
            }
        }

        return $model->get()->toArray();
    }

    public function findAndNestTypes(array $types)
    {
        $index = $this->findTypes($types);
        $indexMap = [];

        foreach ($index as $key => $value) {

            // Map the database 'id' to the model key.
            $indexMap[$value['id']] = $key;

            // Create the children container.
            $index[$key]['children'] = [];
        }

        return $index;
    }
}