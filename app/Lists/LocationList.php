<?php


namespace App\Lists;


use App\Http\Resources\LocationCollection;
use Illuminate\Support\Collection;

trait LocationList
{
    /**
     * Fetch last locations via request.
     *
     * @return LocationCollection
     */
    public function fetchLastLocationsViaRequest()
    {
        $locations = $this->searchLastLocations(input('type'), input('identifiers'), input('accuracy'));

        return new LocationCollection(
            $locations->filter(fn($location) => !is_null($location))
        );
    }

    /**
     * Fetch locations via request.
     *
     * @return Collection
     */
    public function fetchViaRequest()
    {
        $class = finder()
            ->findClass(input('type'));

        $models= $class::whereIn('uuid', einput(',', 'identifiers'))->get();

        return $this->searchLocations($models, only('type', 'start_date', 'end_date', 'accuracy'));
    }
}
