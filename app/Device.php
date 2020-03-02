<?php

namespace App;

use App\Repositories\LocationRepository;
use Grimzy\LaravelMysqlSpatial\Eloquent\Builder;
use Grimzy\LaravelMysqlSpatial\Eloquent\SpatialTrait;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * Class Device
 *
 * @package App
 * @author Ian Torres
 * @license MIT
 */
class Device extends Authenticatable
{
    use SpatialTrait;

    /**
     * The mass assignment fields of the device
     *
     * @var array
     */
    protected $fillable = [
        'uuid',
        'user_id',
        'name',
        'description',
        'api_token',
        'is_public',
        'location',
    ];

    /**
     * The attributes that should be cast to spatial types.
     *
     * @var array
     */
    protected $spatialFields = [
        'location',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'is_public' => 'boolean',
    ];

    /**
     * The default location field of device.
     *
     * @var string
     */
    public const DEFAULT_LOCATION_FIELD = 'location';

    /**
     * The related Groups.
     *
     * @return BelongsToMany
     */
    public function groups()
    {
        return $this->morphToMany(Group::class, 'groupable')->withPivot([
            'is_admin',
            'status',
            'sender_id',
        ])->withTimestamps();
    }

    /**
     * The geofences that belongs to the device
     *
     * @return BelongsToMany
     */
    public function geofences()
    {
        return $this->morphToMany(Geofence::class, 'geofenceable');
    }

    /**
     * The related Locations.
     *
     * @return MorphMany
     */
    public function locations()
    {
        return $this->morphMany(Location::class, 'trackable');
    }

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'uuid';
    }

    /**
     * The user that belongs this device
     *
     * @return BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return MorphMany
     */
    public function accesses()
    {
        return $this->morphMany(Access::class, 'accessible');
    }
}
