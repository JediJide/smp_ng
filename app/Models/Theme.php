<?php

namespace App\Models;

use App\Traits\Auditable;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use PDO;
use Spatie\Image\Exceptions\InvalidManipulation;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Staudenmeir\LaravelAdjacencyList\Eloquent\HasRecursiveRelationships;

/**
 * @OA\Schema(),
 * required={"name", "therapy_area_id","category_id"}
 *
 * Category Class
 * @method static where(string $string, string $string1, $category_id)
 * @method static find(int $theme_latest_id)
 */
class Theme extends Model implements HasMedia
{
    use HasRecursiveRelationships;
    /**
     * @OA\Property(format="string", title="name", default="Category Name", description="name", property="name"),
     * @OA\Property(format="string", title="description", default="Description body", description="description", property="description"),
     * @OA\Property(format="int64", title="resource_id", default="1", description="description", property="resource_id"),
     * @OA\Property(format="int64", title="therapy_area_id", default="1", description="therapy_area_id", property="therapy_area_id"),
     * @OA\Property(format="int64", title="category_id", default="1", description="category_id", property="category_id"),
     */
    use SoftDeletes;
    use InteractsWithMedia;
    use Auditable;
    use HasFactory;

    public $table = 'themes';

    protected $fillable = [
        'therapy_area_id',
        'category_id',
        'name',
        'description',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $hidden = ['pivot'];

    public function getParentKeyName()
    {
        return 'theme_id';
    }

    /**
     * @throws InvalidManipulation
     */
    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')->fit('crop', 50, 50);
        $this->addMediaConversion('preview')->fit('crop', 120, 120);
    }

    public function themeStatements(): HasMany
    {
        return $this->hasMany(Statement::class, 'theme_id', 'id');
    }
    public function resourcesThemes(): BelongsToMany
    {
        return $this->belongsToMany(Resource::class)->where ('is_header_resource','=','1');
    }

    public function themeStatementTree($therapy_area_id,$category_id, $audience_id): \Illuminate\Database\Eloquent\Collection|array
    {


       // return Cache::remember  ('parent_statements',60*60*24,function () use ($category_id){
        return self::query ()
            ->with (['themeStatements'
            =>  fn ($query)
                => $query->select(['id','theme_id','parent_id','title','description','order_by','status_id','is_notify_all',

                    DB::raw('(select count(resources.id)
                          FROM resources
                            JOIN resource_statement on resource_statement.resource_id = resources.id
                           WHERE resource_statement.statement_id = statements.id) As StatementResourceTotal,

                            (with recursive cte as (
                            select id root_id, id from statements
                            where deleted_at is null
                            union all
                            select c.root_id, t.id from cte c inner join statements t on t.parent_id = c.id
                            where t.deleted_at is null
                            )
                        select
                            (select count(*) - 1 from cte c where c.root_id = t.id) no_children
                        from statements t
                        Where t.id = statements.id
                        AND t.deleted_at is null) as SubStatementsTotal'
                    )
                ])

                ->when($audience_id > 0, function ($query) use ($audience_id){
                    $query->leftJoin ('audience_statement','audience_statement.statement_id','=','statements.id')
                        ->whereIn ('audience_statement.audience_id',[$audience_id]);
                })

                ->with([
                    'audiences' => fn ($query) => $query->select('audience.id' ,'audience.name'),
                ])


                ->whereNull('parent_id')
                ->orderBy('statements.order_by', 'asc'),

                'themeStatements.resourcesThemes'
                    =>  fn ($query)
                    =>$query
                     ->select(['id','temporary_url','is_header_resource','file_mime_type']),
            ])
            ->where ('therapy_area_id','=',$therapy_area_id)
            ->where ('category_id','=',$category_id)
            ->orderBy('order_by', 'asc')
            ->get (['id','name','description','order_by',
                DB::raw ('(select count(resources.id)
                        FROM resources
                        JOIN resource_theme on resource_theme.resource_id =resources.id
                        WHERE resource_theme.theme_id=themes.id ) as themesResourceTotal ')
            ]);
    }

    public function buildThemesTree(array $elements, $parent_id = 0): array
    {
        $branch = array();

        foreach ($elements as $element) {
            if ($element->parent_id == $parent_id) {
                $children = $this->buildThemesTree ( $elements, $element->id );
                if ($children) {
                    $element->children = $children;
                }
                $branch[$element->id] = $element;
                unset($elements[$element->id]);
            }
        }
        return $branch;
    }

    public function parentStatements(): HasMany
    {
       // return Cache::remember  ('parent_statements',60*60*24,function () {
            return $this->hasMany ( Statement::class, 'theme_id', 'id' )
                ->with ( 'parentStatements' )->whereNull ( 'parent_id' )
                ->select ( ['id', 'title', 'description', 'is_notify_all', 'parent_id', 'status_id', 'theme_id', 'order_by'] );
      //  });
    }

    public function therapy_area(): BelongsTo
    {
        return $this->belongsTo(TherapyArea::class, 'therapy_area_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function resources(): BelongsToMany
    {
        return $this->belongsToMany(Resource::class);
    }

    public function resources_statement(): HasManyThrough
    {
        return $this->hasManyThrough(Statement::class, Resource::class);
    }

    public function references(): BelongsToMany
    {
        return $this->belongsToMany(Reference::class);
    }

    public function getThemes($category_id): Collection
    {
        return DB::table('themes')
            ->where('themes.category_id', '=', $category_id)
            ->select('id', 'name', 'description', 'therapy_area_id', 'category_id', 'order_by')
            ->whereNull('deleted_at')
            //->paginate ();
            ->orderBy('themes.order_by', 'asc')
            ->get();
    }

    public function getStatementsByThemeId($theme_id): Collection
    {
        return DB::table('statements')
            ->where('theme_id', '=', $theme_id)
            ->select('id', 'title', 'description')
            ->whereNull('deleted_at')
            ->get();
    }

    public function getStatementApi($category_id): Collection
    {
        return DB::table('themes')
            ->select('themes.id','themes.name as title','themes.description',
                DB::raw('( select count(resource_theme.resource_id)
                from resource_theme where resource_theme.theme_id = themes.id)
                as ResourceCount, (select count(id) from statements where theme_id = themes.id)
                as SubStatementCount'))
            ->where('themes.category_id', '=', $category_id)
            ->get();
    }

    public function getThemesTopLevel($therapy_area_id, $category_id, $audience_id): Collection
    {
        if ( $audience_id == 0)
        {
           return $this->getThemesTopLevelNoFilter ($therapy_area_id, $category_id);
        }
        else
        {
            return  $this->getThemesTopLevelFilteredByAudience ($therapy_area_id, $category_id, $audience_id);
        }
    }

    public function getThemesTopLevelNoFilter($therapy_area_id, $category_id): Collection
    {
        return DB::table('themes')
            ->select('themes.id','themes.name as title','themes.description','themes.order_by',
                DB::raw('( select count(resource_theme.resource_id)
                from resource_theme where resource_theme.theme_id = themes.id) as ResourceCount,
                (select count(id) from statements
                  where theme_id = themes.id)
                as SubStatementCount'))
            ->where('themes.therapy_area_id', '=', $therapy_area_id)
            ->where('themes.category_id', '=', $category_id)
            ->whereNull('themes.deleted_at')
            ->orderBy('themes.order_by', 'asc')
            ->get();
    }
    public function getThemesTopLevelFilteredByAudience($therapy_area_id, $category_id, $audience): Collection
    {
        //convert the parameter into an array, separated by commas
        $audience_id = explode (",", $audience);
        $audience_id = [implode(',',$audience_id)];
        return DB::table('themes')
            ->select('themes.id','themes.name as title','themes.description','themes.order_by',
                DB::raw('( select count(resource_theme.resource_id)
                from resource_theme where resource_theme.theme_id = themes.id) as ResourceCount,
                (select count(s.id) from statements s
                left join audience_statement ast on ast.statement_id = s.id
                   where s.theme_id = themes.id and ast.audience_id in ( ? ))
                as SubStatementCount'))
            ->where('themes.therapy_area_id', '=', '?')
            ->where('themes.category_id', '=', '?')
            ->whereNull('themes.deleted_at')
            ->orderBy('themes.order_by', 'asc')
            ->setBindings ([$audience_id, $category_id])
            ->get();
    }

    public function rawStatements(int $theme_id): bool|array
    {
        $qry = "SELECT id, title, description
                FROM smp.statements
                where theme_id =$theme_id
                and deleted_at is null;";

        return DB::getPdo()->query($qry)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getThemesStatement($therapy_area_id, $category_id): Collection
    {
        return  DB::table('themes')
            ->select('themes.id as parent_id', 'statements.id', 'themes.name', 'themes.description')
            ->whereNull('themes.deleted_at')
            ->where('themes.therapy_area_id', '=', $therapy_area_id,)
            ->where('themes.category_id', '=', $category_id)
            ->groupBy('themes.id', 'statements.id')
            ->leftJoin('statements', 'statements.theme_id', '=', 'themes.id')
            ->get();
    }

    public function getStatementResourcesById($statement_id): array
    {
        return DB::table('resources')
            ->join('resource_statement', 'resource_statement.resource_id', '=', 'resources.id')
            ->whereNull('deleted_at')
            ->where('resource_statement.statement_id', '=', $statement_id)
            ->select('id', 'title', 'temporary_url as Url')
            ->get()->toArray();
    }

    public function getThemeById($id): Collection
    {
        return DB::table('themes')
            ->select('id', 'name as title', 'description')
            ->whereNull('deleted_at')
            ->where('id', '=', $id)
            ->get();

        // print_r ($result); dd ();
    }

    public function getThemeByCatId($cat_id): Collection
    {
        return DB::table('themes')
            ->select('id', 'name', 'description', 'created_at', 'updated_at', 'order_by')
            ->whereNull('deleted_at')
            ->where('category_id', '=', $cat_id)
            ->orderBy('order_by', 'asc')
            ->get();
    }

    public function insertTheme($theme): int
    {
        return  DB::table('themes')->insertGetId($theme);
    }

   public function updateThemeById($id, $name, $description): int
    {
        return  DB::table('themes')
            ->where('id', '=', $id)
            ->update(
                [
                    'name' => $name,
                    'description' => $description,
                    'updated_at' =>  Carbon::now(),
                ]
            );
    }

    public function getResourcesByThemes($id): Collection
    {
        return DB::table ('resources')
            ->join ('resource_theme','resource_theme.resource_id','=', 'resources.id')
            ->where ('resource_theme.theme_id','=',$id)
            ->get (['resources.id','resources.title','resources.temporary_url',
                'resources.is_header_resource','resources.file_mime_type','resources.file_size']);
    }

    protected function serializeDate(DateTimeInterface $date): string
    {
        return $date->format('Y-m-d H:i:s');
    }
}
