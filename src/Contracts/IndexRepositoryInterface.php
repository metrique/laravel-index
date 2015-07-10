<?php

namespace Metrique\Index\Contracts;

use Metrique\Index\Contracts\EloquentRepositoryInterface;

interface IndexRepositoryInterface extends EloquentRepositoryInterface
{
    /**
     * Set the namespace, enables multiple navigations.
     * 
     * @param string|null $namespace
     * @return $this
     */
    public function setNamespace($namespace = null);

    /**
     * Sets the default ordering.
     * 
     * @param string $column
     * @param string $order
     * @return $this
     */
    public function setOrder($column = 'order', $order = 'desc');

    /**
     * Find index entries by a key/value pair array.
     * 
     * Keys can be 'disabled', 'navigation', 'published'.
     * Values can be null, true or false, and are set to null by default.
     *
     * The following example will pull indices where 'navigation' is set
     * to false and 'published' is set to true only, it will ignore disabled.
     * 
     * findTypes([
     *     'disabled' => null,
     *     'navigation' => false,
     *     'published' => true,
     * ]);
     * 
     * @param  array  $type
     * @return array
     */
    public function findTypes(array $types);

    /**
     * Find index entries by a key/value pair array
     * Returns a nested array where there are sub indexes.
     * 
     * @param  array  $types
     * @return array
     */
    public function findAndNestTypes(array $types);
}