<?php

namespace App;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Http\Request;

class Access extends Model
{
    /**
     * The table name.
     *
     * @var string
     */
    protected $table = 'accesses';

    /**
     * Mass-assignable properties.
     *
     * @var array
     */
    protected $fillable = [
        'uuid',
        'accessible_id',
        'accessible_type',
        'geofence_uuid',
        'first_location_uuid',
        'last_location_uuid',
        'status',
    ];

    /**
     * Eager loading properties.
     *
     * @var array
     */
    protected $with = [
        'accessible',
        'geofence',
        'first_location',
        'last_location',
    ];

    /**
     * The properties which are hidden.
     *
     * @var array
     */
    protected $hidden = ['id'];

    /**
     * Get all of the owning commentable models.
     */
    public function accessible()
    {
        return $this->morphTo();
    }

    /**
     * The related last Location
     *
     * @return BelongsTo
     */
    public function last_location()
    {
        return $this->belongsTo(Location::class, 'last_location_uuid', 'uuid');
    }

    /**
     * The related first Location.
     *
     * @return BelongsTo
     */
    public function first_location()
    {
        return $this->belongsTo(Location::class, 'first_location_uuid', 'uuid');
    }

    /**
     * The related Geofence.
     *
     * @return BelongsTo
     */
    public function geofence()
    {
        return $this->belongsTo(Geofence::class, 'geofence_uuid', 'uuid');
    }

    /**
     * @param Request $request
     * @return LengthAwarePaginator
     * @codeCoverageIgnore
     */
    public static function resolveModels(Request $request)
    {
        return self::with('accessible')->latest()->paginate(20);
    }
}
