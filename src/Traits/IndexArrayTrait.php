<?php

namespace Metrique\Index\Traits;

trait IndexArrayTrait
{
    public function createNestedIndex(array $index, $parentKey = 'indices_id', $childKey = 'children')
    {
        $indexMap = [];
        $nested = [];

        // Create index map & init children array.
        foreach($index as $key => $value)
        {
            // Map the database Id to the array Id
            $indexMap[$value['id']] = $key;

            // Initialise children.
            $index[$key][$childKey] = [];
        }

        foreach($index as $key => $value)
        {
            $indicesId = $index[$key][$parentKey];
            
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
                $index[$indexId][$childKey][] = &$index[$key];
            }
        }

        return $nested;
    }
}