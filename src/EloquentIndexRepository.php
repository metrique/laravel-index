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
}