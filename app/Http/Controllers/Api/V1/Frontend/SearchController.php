<?php

namespace App\Http\Controllers\Api\V1\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Glossary;
use App\Models\Lexicon;
use App\Models\Statement;
use App\Models\Theme;
use Illuminate\Http\Request;
use ProtoneMedia\LaravelCrossEloquentSearch\Search;

/**
 * @OA\Get(
 *     path="/api/v1/search",
 *     tags={"search"},
 *     summary="Search site-wide",
 *     description="Search through header",
 *     security={{ "Bearer":{} }},
 *
 *      @OA\Parameter(
 *      name="filter",
 *      in="header",
 *      description="Header values",
 *      required=false,
 *
 *      @OA\JsonContent(ref="#/components/schemas/UpdateRoleRequest")
 *      ),
 *
 *     @OA\Response(
 *          response="200",
 *          description="Returns matching Person Object",
 *
 *          @OA\JsonContent(
 *              type="array",
 *
 *              @OA\Items(ref="#/components/schemas/UpdateRoleRequest")
 *          )
 *     )
 * )
 */
class SearchController extends Controller
{
    public function search(Request $request): array
    {
        $filter = $request->header('filter');
        // convert header content into array;
        $filter = json_decode($filter, true);

        $qry = [];
        if (isset($filter['StatementSearch'])) {
            $qry[] = 'Statement';
        }
        if (isset($filter['NarrativeSearch'])) {
            $qry[] = 'Theme';
        }
        if (isset($filter['GlossarySearch'])) {
            $qry[] = 'Glossary';
        }

        if (isset($filter['LexiconSearch'])) {
            $qry[] = 'Lexicon';
        }

        $search_term = '';
        if (isset($filter['SearchTerm'])) {
            $search_term = $filter['SearchTerm'];
        }

        if (! empty($qry)) {
            return Search::new()
                ->addWhen(in_array('Theme', $qry), Theme::class, ['name', 'description'])
                ->addWhen(in_array('Statement', $qry), Statement::class, ['title', 'description'])
                ->addWhen(in_array('Glossary', $qry), Glossary::class, ['term', 'definition'])
                ->addWhen(in_array('Lexicon', $qry), Lexicon::class, ['preferred_phrase', 'guidance_for_usage', 'non_preferred_terms'])
                ->beginWithWildcard()
                ->includeModelType()
                //->dontParseTerm()
                ->orderByRelevance()
                ->get($search_term)->toArray();
        } else {
            return Search::add(Theme::class, ['name', 'description'])
                ->add(Statement::class, ['title', 'description'])
                ->add(Glossary::class, ['term', 'definition'])
                ->add(Lexicon::class, ['preferred_phrase', 'guidance_for_usage', 'non_preferred_terms'])
                ->beginWithWildcard()
                //->dontParseTerm()
                ->includeModelType()
                ->orderByRelevance()
                ->get($search_term)->toArray();
        }
    }
}
