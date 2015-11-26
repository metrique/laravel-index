<?php

namespace Metrique\Index\Traits;

trait IndexArrayTrait
{
    public function createNestedIndex(array $index, array $options = [])
    {
        $activeScan = true;
        $defaults = [
            'active_key' => 'active',
            'active_scan' => true,
            'parent_key' => 'indices_id',
            'child_key' => 'children',
            'slug' => null,
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

            // Initialise params.
            if( ! is_null($index[$key]['params']))
            {
                $index[$key]['params'] = json_decode($index[$key]['params'], true);
            }

            // Mark as active
            if( ! is_null($options['slug']))
            {
                $active = ($options['slug'] == $index[$key]['slug']) ? true : false;
                $index[$key][$options['active_key']] = $active;

                // Catch if we need to perform an active scan.
                if($active) {
                    $activeScan = false;
                }
            }
        }

        // Active Scan - if no slug is marked as active, we scan the first section of the slug.
        if( ! is_null($options['slug']))
        {
            $slug = explode('_', $options['slug'], 2);
            
            foreach($index as $key => $value)
            {
                $active = ($slug[0] == $index[$key]['slug']) ? true : false;
                $index[$key][$options['active_key']] = $active;
            }
        }

        // Nest index
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

                // If the child is active, also mark the parent as active.
                if($index[$key][$options['active_key']] === true)
                {
                    $index[$indexId][$options['active_key']] = $index[$key][$options['active_key']];
                }
            }
        }

        return $nested;
    }
}