<?php

namespace App\Models;

use App\Traits\Auditable;
use DateTimeInterface;
//use http\Env\Request;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Spatie\Image\Exceptions\InvalidManipulation;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Staudenmeir\LaravelAdjacencyList\Eloquent\HasRecursiveRelationships;
use Symfony\Component\HttpFoundation\Response;

/**
 * @OA\Schema(),
 * required={"title","theme_id","therapy_area_id"}
 *
 * Statement Class
 * @method static find(int $statement)
 * @method static tree()
 */
class Statement extends Model implements HasMedia
{
    use HasRecursiveRelationships;

    /**
     * @OA\Property(format="string", title="title", default="Pillar 1: Gene therapy overview", description="title", property="title"),
     * @OA\Property(format="string", title="description", default="<p>Strategic objective: Provide an overview of gene therapy and its potential benefits</p><p><strong>Core statement:</strong> Gene therapy is an innovative transformative treatment that modifies a person's genes to treat or cure a disease, with several agents already approved for use.</p>", description="description", property="description"),
     * @OA\Property(format="int64", title="theme_id", default="12", description="theme_id", property="theme_id"),
     * @OA\Property(format="int64", title="parent_id", default="12", description="parent_id", property="parent_id"),
     * @OA\Property(format="int64", title="therapy_area_id", default="1", description="therapy_area_id", property="therapy_area_id"),
     * @OA\Property(format="int64", title="is_notify_all", default="0", description="is_notify_all", property="is_notify_all"),
     */
    use SoftDeletes;
    use InteractsWithMedia;
    use Auditable;
    use HasFactory;

    public $table = 'statements';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'therapy_area_id',
        'parent_id',
        'theme_id',
        'title',
        'description',
        'is_notify_all',
        'status_id',
        'order_by',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $hidden = ['pivot'];

    /**
     * @throws InvalidManipulation
     */
    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')->fit('crop', 50, 50);
        $this->addMediaConversion('preview')->fit('crop', 120, 120);
    }

    public function getParentKeyName(): string
    {
        return 'parent_id';
    }

    public function recursionStatement($theme_id)
    {
        $statement = self::tree()
            ->with(['resources', 'references'])
            ->whereNull('parent_id')
            ->where('theme_id', '=', $theme_id)
            ->get(['id', 'title', 'description', 'is_notify_all', 'parent_id', 'status_id', 'theme_id', 'order_by']);

        return $statement->toTree();
    }

    public function parentStatements(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id', 'id')
            ->with('parentStatements')
            ->with('resources:id,resources.title,resources.url,resources.file_mime_type,temporary_url,is_header_resource')
            ->with('references:id,references.title,references.temporary_url,references.url')
            ->orderBy ('order_by')
            ->select(['id', 'title', 'description', 'is_notify_all', 'parent_id', 'status_id', 'theme_id', 'order_by']);
    }

    // Jide Just added this
    public function statement(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id', 'id');
    }

    public function statementTree($id): \Illuminate\Database\Eloquent\Collection|array
    {
        // return Cache::remember  ('statement_tree',60*60*24,function () use ($id) {
        return self::query()
        ->whereNull('parent_id')
        ->where('theme_id', $id)
        ->with([
            'resources' => fn ($query) => $query->select('resources.id', 'resources.title', 'resources.url', 'resources.file_mime_type', 'temporary_url', 'is_header_resource'),
        ])
        ->with([
            'references' => fn ($query) => $query->select('references.id', 'references.title','references.temporary_url', 'references.url'),
        ])
        ->with([
            'audiences' => fn ($query) => $query->select('audience.id', 'audience.name') ,
        ])
        ->with(['parentStatements' => fn ($query) => $query
            ->with([
                'audiences' => fn ($query) => $query->select('audience.id','audience.name'),
            ])
            ->orderBy('order_by', 'asc'),
        ])
        ->get(['id', 'title', 'description', 'is_notify_all', 'parent_id', 'status_id', 'theme_id', 'order_by',
            DB::raw('( select count(resource_statement.resource_id)
            from resource_statement where resource_statement.statement_id = statements.id)
            as ResourceCount')
        ]);

    }

    public function therapy_area(): BelongsTo
    {
        return $this->belongsTo(TherapyArea::class, 'therapy_area_id');
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function theme(): BelongsTo
    {
        return $this->belongsTo(Theme::class, 'theme_id');
    }

    public function audiences(): BelongsToMany
    {
        return $this->belongsToMany(Audience::class);
    }

    public function resources(): BelongsToMany
    {
        return $this->belongsToMany(Resource::class);
    }

    public function resourcesThemes(): BelongsToMany
    {
        return $this->belongsToMany(Resource::class)
            ->where ('is_header_resource','=','1');

    }

    public function references(): BelongsToMany
    {
        return $this->belongsToMany(Reference::class);
    }

    public function store($statement): int
    {
        // clear statement cache from statement
        Cache::forget('statement_tree');

        return  DB::table('statements')->insertGetId($statement);
    }

    public function store_notify_users($data): bool
    {
        return DB::table('notifications')->insert($data);
    }

    public function store_notification_message($data): int
    {
        return DB::table('notification_messages')->insertGetId($data);
    }

    public function updateNotification_message($statement_id, $data): int
    {
        return DB::table('notification_messages')
        ->where('statement_id', '=', $statement_id)
        ->update($data);
    }

    public function updateNotification($notifcation_message_id, $data): int
    {
        return DB::table('notifications')
        ->where('notification_message_id', '=', $notifcation_message_id)
        ->update($data);
    }

    public function get_notification_by_statement_Id($statement_id): Collection
    {
        return DB::table('notification_messages')
        ->where('statement_id', '=', $statement_id)
        ->select('id', 'old_value', 'new_value', 'statement_id')
        ->get();
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(StatementStatus::class, 'status_id');
    }

    public function updateStatementById(int $id, $title, $description, $order): JsonResponse
    {
        DB::table('statements')
            ->where('id', '=', $id)
            ->update(
                [
                    'title' => $title,
                    'description' => $description,
                    'order_by' => $order,
                    'updated_at' =>  Carbon::now(),
                ]);

        $statement = DB::table('statements')
            ->where('id', '=', $id)
            ->select('id', 'title', 'description', 'parent_id', 'theme_id', 'status_id')
            ->get()->toArray();

        return response()->json($statement)->setStatusCode(Response::HTTP_ACCEPTED);
    }

    private mixed $children = [];

    public function getStatementsById($id): Collection
    {
        return DB::table('statements')
            ->where('statements.theme_id', $id)
            ->select('statements.id','statements.parent_id', 'statements.order_by',
                'statements.title', 'statements.description', 'statements.theme_id', 'statements.status_id')
            ->orderBy('statements.id')
            ->get();
    }

    public function getStatement($id): Collection
    {
        return DB::table('statements')
            ->where('id', '=', $id)
            ->select('id', 'title', 'description', 'parent_id', 'theme_id', 'status_id')
            ->get();
    }

    public function getResourcesById($statement_id): array
    {
        return DB::table('resources')
            ->join('resource_statement', 'resource_statement.resource_id', '=', 'resources.id')
            ->whereNull('deleted_at')
            ->where('resource_statement.statement_id', '=', $statement_id)
            ->select('id', 'title', 'file_mime_type', 'temporary_url as Url', 'is_header_resource')
            ->get()->toArray();
    }


    public function getReferencesById($statement_id): array
    {
        return DB::table('references')
            ->join('reference_statement', 'reference_statement.reference_id', '=', 'references.id')
            ->whereNull('deleted_at')
            ->where('reference_statement.statement_id', '=', $statement_id)
            ->select('references.id', 'references.title', 'references.url')
            ->get()->toArray();
    }

    public function buildTree(array $elements, $parentId = 0): array
    {
        $branch = [];

        foreach ($elements as $element) {
            $references['References'] = $this->getReferencesById($element->id);
            $StatementResources['StatementResources'] = $this->getResourcesById($element->id);

            if (empty($references)) {
                $references['References'] = [];
            }

            if ($element->parent_id == $parentId) {
                $children['SubStatement'] = $this->buildTree($elements, $element->id);
                $children = array_merge($children, $references, $StatementResources);
                if ($children) {
                    $element->children = $children;
                }
                $branch[] = $element;
            }
        }

        return $branch;
    }

    public function getThemeResources($id): Collection
    {
        return DB::table('resources')
            ->join('resource_theme', 'resource_theme.resource_id', '=', 'resources.id')
            ->where('resource_theme.theme_id', '=', $id)
            ->whereNull('deleted_at')
            ->select('resources.id', 'resources.title', 'resources.file_mime_type', 'resources.temporary_url as Url', 'resources.is_header_resource')
            ->get();
    }

    public function getResourcesByStatement($statement_id): Collection
    {
        return DB::table('resources')
            ->join('resource_statement', 'resource_statement.resource_id', '=', 'resources.id')
            ->join('statements', 'statements.id', '=', 'resource_statement.statement_id')
            ->where('resource_statement.statement_id', '=', $statement_id)
            ->whereNull('resources.deleted_at')
            ->select('resources.id', 'resources.title', 'resources.temporary_url as Url', 'resources.is_header_resource')
            ->get();
    }

    public function getStatementsIds($statement_tree): array
    {
        $ids = [$this->id];
        foreach ($this->Statment as $child) {
            $ids = array_merge($ids, $child->getIds());
        }

        return $ids;
    }

    public function audienceStatements(): BelongsToMany
    {
        return $this->belongsToMany(Audience::class);
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
