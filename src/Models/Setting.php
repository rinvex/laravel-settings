<?php

declare(strict_types=1);

namespace Rinvex\Settings\Models;

use Illuminate\Support\Str;
use Spatie\Sluggable\SlugOptions;
use Rinvex\Support\Traits\HasSlug;
use Illuminate\Database\Eloquent\Model;
use Rinvex\Support\Traits\HasTranslations;
use Rinvex\Support\Traits\ValidatingTrait;
use Spatie\EloquentSortable\SortableTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Setting extends Model
{
    use HasSlug;
    use HasFactory;
    use SoftDeletes;
    use SortableTrait;
    use HasTranslations;
    use ValidatingTrait;

    /**
     * {@inheritdoc}
     */
    protected $fillable = [
        'key',
        'type',
        'value',
        'options',
        'name',
        'description',
        'override_config',
        'sort_order',
    ];

    /**
     * {@inheritdoc}
     */
    protected $casts = [
        'key' => 'string',
        'type' => 'string',
        'value' => 'string',
        'options' => 'array',
        'description' => 'string',
        'override_config' => 'bool',
    ];

    /**
     * {@inheritdoc}
     */
    protected $observables = [
        'validating',
        'validated',
    ];

    /**
     * The attributes that are translatable.
     *
     * @var array
     */
    public $translatable = [
        'name',
        'description',
    ];

    /**
     * The sortable settings.
     *
     * @var array
     */
    public $sortable = [
        'order_column_name' => 'sort_order',
    ];

    /**
     * The default rules that the model will validate against.
     *
     * @var array
     */
    protected $rules = [];

    /**
     * Whether the model should throw a
     * ValidationException if it fails validation.
     *
     * @var bool
     */
    protected $throwValidationExceptions = true;

    /**
     * {@inheritdoc}
     */
    protected $appends = ['group'];

    /**
     * Create a new Eloquent model instance.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        $this->setTable(config('rinvex.settings.tables.settings'));

        $this->mergeRules([
            'key' => 'required|max:150|unique:'.config('rinvex.settings.models.setting').',key',
            'type' => 'required|in:'.implode(',', config('rinvex.settings.types')),
            'value' => 'nullable|string|max:191',
            'options' => 'nullable|array',
            'name' => 'required|string|strip_tags|max:150',
            'description' => 'nullable|string|max:32768',
            'override_config' => 'sometimes|boolean',
            'sort_order' => 'nullable|integer|max:100000',
        ]);

        parent::__construct($attributes);
    }

    /**
     * Get the options for generating the slug.
     *
     * @return \Spatie\Sluggable\SlugOptions
     */
    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
                          ->doNotGenerateSlugsOnUpdate()
                          ->generateSlugsFrom('name')
                          ->usingSeparator('.')
                          ->saveSlugsTo('key');
    }

    /**
     * Get setting group name (the first segment before the first dot).
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    protected function group(): Attribute
    {
        return Attribute::make(
            get: fn () => Str::before($this->key, '.'),
        );
    }
}
