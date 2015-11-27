<?php

namespace Metrique\Index\Contracts;

use Metrique\Index\Contracts\EloquentRepositoryInterface;

interface IndexRepositoryInterface extends EloquentRepositoryInterface
{
    /**
     * Retrieve the last set namespace.
     * 
     * @return string
     */
    public function getNamespace();

    /**
     * Set the namespace, for use with multiple navigations systems.
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
     * Retrieve the last set slug.
     * 
     * @return string
     */
    public function getSlug();

    /**
     * Sets the slug, used to track which page is active.
     * 
     * @param $string $slug
     * @return $this
     */
    public function setSlug($slug = null);

    /**
     * Find index entries of a certain type by a key/value pair array.
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
     * Keys can be 'disabled', 'navigation', 'published'.
     * Values can be null, true or false, and are set to null by default.
     *
     * The following example will pull indices where 'navigation' is set
     * to false and 'published' is set to true only, it will ignore disabled.
     * 
     * findAndNestTypes([
     *     'disabled' => null,
     *     'navigation' => false,
     *     'published' => true,
     * ]);
     * 
     * @param  array  $types
     * @return array
     */
    public function findAndNestTypes(array $types);

    /**
     * Find entries that match a specific slug.
     * @param  array  $types
     * @param  string $slug
     * @return array
     */
    public function markAsActive(array $types, $slug);
}