<?php


namespace App\Repositories;


use App\Contracts\Resource;
use App\Device;
use App\Pet;
use App\Procedures\ResourceLocationProcedure;
use App\Queries\LocationQuery;
use App\User;
use Grimzy\LaravelMysqlSpatial\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ModelLocationsRepository
{
    use LocationQuery;
    use ResourceLocationProcedure;

    /**
     * Update current model location.
     *
     * @param Resource $model
     * @return Resource
     */
    public function update(Resource $model)
    {
        $model->update([
            'location' => parser()->pointFromCoordinates(input('coordinates')),
        ]);
        return $model;
    }

    /**
     * Resolve current model for location insert.
     *
     * @return mixed
     */
    public function resolveLocationModel()
    {
        $resource = explode('.', request()->route()->getName())[0];
        switch ($resource) {
            case 'device':
                return Device::where('uuid', authenticated('devices')->uuid)->firstOrFail();
                break;
            case 'pet':
                return Pet::where('uuid', authenticated('pets')->uuid)->firstOrFail();
                break;
            default:
                return User::where('uuid', authenticated('api')->uuid)->firstOrFail();
                break;
        }
    }

    /**
     * Create a device location.
     *
     * @param Model $model
     * @param $data
     * @return Model
     */
    public function createLocation(Model $model, $data)
    {
        if ($model instanceof User || $model instanceof Device) {
            return $model->locations()->create([
                "device" => $data["device"],
                "uuid" => $data["location"]["uuid"],
                "location" => parser()->point($data),
                "accuracy" => $data["location"]["coords"]["accuracy"],
                "altitude" => $data["location"]["coords"]["altitude"],
                "speed" => $data["location"]["coords"]["speed"],
                "heading" => $data["location"]["coords"]["heading"],
                "odometer" => $data["location"]["odometer"],
                "event" => parser()->event($data),
                "activity_type" => $data["location"]["activity"]["type"],
                "activity_confidence" => $data["location"]["activity"]["confidence"],
                "battery_level" => $data["location"]["battery"]["level"],
                "battery_is_charging" => $data["location"]["battery"]["is_charging"],
                "is_moving" => $data["location"]["is_moving"],
                "generated_at" => parser()->timestamp($data),
            ]);
        } else {
            return $model->locations()->create([
                "uuid" => $data["location"]["uuid"],
                "location" => parser()->point($data),
                "accuracy" => $data["location"]["coords"]["accuracy"],
            ]);
        }
    }
}
