<?php

namespace Demency\Relationships\Models;

use Demency\Contracts\CreateFromRequest;
use Demency\Contracts\Monetizer;
use Demency\Contracts\Resource;
use Demency\Contracts\UpdateFromRequest;
use Demency\Relationships\BelongsToUser;
use Demency\Relationships\Geofenceable;
use Demency\Relationships\Groupable;
use Demency\Relationships\Photoable;
use Demency\Relationships\Trackable;
use Demency\Relationships\Relationships\UserRelationships;
use Demency\Relationships\Resources\UserResource;
use Cog\Contracts\Love\Reactable\Models\Reactable as ReactableContract;
use Cog\Contracts\Love\Reacterable\Models\Reacterable as ReacterableContract;
use Cog\Laravel\Love\Reactable\Models\Traits\Reactable;
use Cog\Laravel\Love\Reacterable\Models\Traits\Reacterable;
use Grimzy\LaravelMysqlSpatial\Eloquent\SpatialTrait;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Laravel\Scout\Searchable;

class User extends Authenticatable implements MustVerifyEmail, ReacterableContract, ReactableContract, Resource, CreateFromRequest, UpdateFromRequest, Monetizer
{
    use Searchable;
    use SpatialTrait;
    use Notifiable;
    use Reacterable;
    use Reactable;
    use UserResource;
    use UserRelationships;
    use BelongsToUser;
    use Groupable;
    use Geofenceable;
    use Trackable;
    use Photoable;
    use HasApiTokens;

    /**
     * The default location field of user.
     *
     * @var string
     */
    public const DEFAULT_LOCATION_FIELD = 'location';

    /**
     * The default location date column.
     *
     * @var string
     */
    public const DEFAULT_LOCATION_DATE_COLUMN = 'generated_at';

    /**
     * The default location group by column.
     *
     * @var string
     */
    public const DEFAULT_LOCATION_GROUP_BY_COLUMN = 'uuid';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'uuid',
        'email_verified_at',
        'api_token',
        'photo_uuid',
        'photo_url',
        'user_uuid',
        'name',
        'email',
        'password',
        'free',
        'is_public',
        'location',
        'country',
        'country_name',
        'region',
        'region_name',
        'city',
        'city_name',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'id',
        'password',
        'remember_token',
        'api_token',
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
        'trial_ends_at' => 'datetime',
        'uses_two_factor_auth' => 'boolean',
        'free' => 'boolean',
        'is_public' => 'boolean',
    ];

    /**
     * The attributes that will be append.
     *
     * @var array
     */
    protected $appends = [
        'is_admin',
        'is_child'
    ];

    /**
     * This model doesn't uses increments.
     *
     * @var bool
     */
    public $incrementing = false;

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
     * Get key for the model.
     *
     * @return string
     */
    public function getKeyName()
    {
        return 'uuid';
    }

    /**
     * Get user coins.
     *
     * @return MorphMany
     */
    public function coins()
    {
        return $this->morphMany(Coin::class,'monetizer','monetizer_type','monetizer_id','uuid');
    }

    /**
     * Find user for passport.
     *
     * @param $username
     * @return mixed
     * @codeCoverageIgnore
     */
    public function findForPassport($username)
    {
        return $this->where('email', $username)->first();
    }
}
