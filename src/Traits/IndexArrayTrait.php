<?php

namespace Metrique\Index\Traits;

trait IndexArrayTrait
{
    public function createNestedIndex(array $index, array $options = [])
    {
        $defaults = [
            'active_key' => 'active',
            'parent_key' => 'indices_id',
            'child_key' => 'children',
            'slug' => null
        ];

        $options = array_intersect_key($options, $defaults);
        $options = array_merge($defaults, $options);

        $indexMap = [];
        $nested = [];

        // Create index map & init children array.
        foreach($index as $key => $value)
        {
            // Map the database Id to the array Id
            $indexMap[$value['id']] = $key;

            // Initialise children.
            $index[$key][$options['child_key']] = [];

            // Initialise active.
            $index[$key][$options['active_key']] = false;

            // Mark as active
            if( ! is_null($options['slug']))
            {
                $index[$key][$options['active_key']] = ($options['slug'] == $index[$key]['slug']) ? true : false;
            }
        }

        foreach($index as $key => $value)
        {
            $indicesId = $index[$key][$options['parent_key']];
            
            // Add any root items to the nested array.
            if(is_null($indicesId))
            {
                $nested[] = &$index[$key];
            }

            // Add any child items to their parent.
            if( ! is_null($indicesId))
            {
                // Get the the array Id of the parent from the Index map.
                $indexId = $indexMap[$indicesId];
                
                // Add the child to the parent.
                $index[$indexId][$options['child_key']][] = &$index[$key];

                // Mark the parent as active
                $index[$indexId][$options['active_key']] = &$index[$key][$options['active_key']];
            }
        }

        return $nested;
    }
}