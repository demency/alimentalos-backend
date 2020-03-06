<?php


namespace App\Procedures;


use Grimzy\LaravelMysqlSpatial\Eloquent\Builder;
use Illuminate\Support\Collection;

trait LocationProcedure
{
    /**
     * Search last devices locations.
     *
     * @param $type
     * @param $identifiers
     * @param $accuracy
     * @return Collection
     */
    public function searchLastLocations($type, $identifiers, $accuracy)
    {
        return finder()
            ->findModel($type)
            ->whereIn('uuid', $identifiers)
            ->get()
            ->map(fn($model) => $this->searchModelLocations($model, $accuracy));
    }

    /**
     * Search model locations.
     *
     * @param $model
     * @param $accuracy
     * @return Builder
     */
    public function searchModelLocations($model, $accuracy)
    {
        $class = get_class($model);
        return $this->orderByColumn(
            $this->maxAccuracy(
                $this->trackableQuery(collect([$model]), $class),
                $accuracy
            ),
            $class::DEFAULT_LOCATION_DATE_COLUMN
        )->first();
    }

    /**
     * Search devices locations between two dates
     *
     * @param $devices
     * @param $parameters
     * @return Collection
     */
    public function searchLocations($devices, $parameters)
    {
        return modelLocations()->filterLocations($devices, $parameters)->get();
    }
}
