<?php

namespace Alimentalos\Contracts;

use Illuminate\Pagination\LengthAwarePaginator;

interface Resource
{
    /**
     * @return array
     */
    public function fields() : array;

    /**
     * Get resource relationships attribute using lazy loading.
     *
     * @return array
     */
    public function getLazyRelationshipsAttribute();

    /**
     * Get resource instances.
     *
     * @return array|LengthAwarePaginator
     */
    public function getInstances();

    /**
     * Get available reactions.
     *
     * @return array
     */
    public function getAvailableReactions();

    /**
     * Get store validation rules.
     *
     * @return array
     */
    public function storeRules();

    /**
     * Get update validation rules.
     *
     * @return array
     */
    public function updateRules();
}
