<?php

namespace App\Http\Controllers\Api\V1\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Glossary;
use App\Models\Lexicon;
use App\Models\Reference;
use App\Models\Resource;
use App\Models\Theme;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class ImportController extends Controller
{
    public function importLexicon(): JsonResponse
    {
        $path = storage_path().'/json/lexicon.json';

        $lexicon_details = json_decode(file_get_contents($path), true);

        //  print_r ($lexicon_details[0]['NonPreferredTerms'][0]['Name']);
        //  dd ();

        foreach ($lexicon_details as $lexicon_detail) {
            Lexicon::create([
                'preferred_phrase' => $lexicon_detail['PreferredTerm'],
                'guidance_for_usage' => $lexicon_detail['Guidance'],
                'non_preferred_terms' => $lexicon_detail['NonPreferredTerms'][0]['Name'],
                'therapy_area_id' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }

        // return $user_details[0]['Email'];

        return response()->json([
            'message' => 'Lexicon Import Done',
        ], status: 422);
    }

    public function getAwsFiles(): JsonResponse
    {
        $directory = storage_path().'/app/public/csl-smp/';

        $files = Storage::allFiles('public/csl-smp');

        $aws_exported_files = DB::table('aws_table')->get()->toArray();

        //        print_r($aws_exported_files);
        //        dd ();
        $reference_model = new Reference();

        $i = 0;
        foreach ($aws_exported_files as $aws_exported_file) {
            $i++;
            $file_name_without_ext = $aws_exported_file->file_name;

            $fileName = $file_name_without_ext.'.pdf';

            $aws_path = config('app.aws_path');

            // if (Storage::exists('public/csl-smp/'. $fileName))
            //  {
            $path = Storage::disk('s3')->put('/'.$aws_path.'/documents/references/'.$fileName, fopen($directory.$fileName, 'r+'));

            Storage::disk('s3')->url($path);

            $file = $aws_path.'/documents/references/'.$fileName;
            //24 hours temporary url for AWS S3 file download
            $temporarySignedUrl = Storage::disk('s3')->temporaryUrl($file, now()->addMinutes(1440));

            $image = Reference::create([
                'user_id' => auth()->id(),
                'title' => $aws_exported_file->file_title,
                'url' => null,
                'file_name' => $fileName,
                'temporary_url' => $temporarySignedUrl,
                'ip_address' => $_SERVER['REMOTE_ADDR'],
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);

        }

        return response()->json([
            'message' => 'Loop = '.$i.'AWS Export Done',
        ], status: 202);

    }

    public function importGlossary(): JsonResponse
    {
        $path = storage_path().'/json/glossary.json';

        $glossaries = json_decode(file_get_contents($path), true);

        // print_r ($glossaries[0]['Term']);
        //  dd ();
        foreach ($glossaries as $glossary) {
            Glossary::create([
                'term' => $glossary['Term'],
                'definition' => $glossary['Description'],
                'therapy_area_id' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }

        return response()->json([
            'message' => 'Glossary Import Done',
        ], status: 422);
    }

    public function importThemes(): JsonResponse
    {
        $path = storage_path().'/json/ScientificPlatform.json';

        $pillars = json_decode(file_get_contents($path), true);

        $statements = $pillars;
        // $Themes_Description = $pillars[2]['Description'];

        print_r(json_encode($statements['SubStatements']));
        dd();

        foreach ($pillars as $pillar) {
            print_r($pillar['Name'].'<br />-------------');
        }
        //  print_r ($Themes);
        dd();
        // print_r ($pillars);
        // dd ();
        foreach ($pillars as $pillar) {
            Theme::create([
                'term' => $pillar['Name'],
                'definition' => $pillar['Description'],
                'therapy_area_id' => 1,
                'category_id' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }

        return response()->json([
            'message' => 'Glossary Import Done',
        ], status: 422);
    }

    public function removeUnlinkedResources(Resource $resource): JsonResponse
    {
        $resource->delete_unlinked_Resources();

        return response()->json([
            'message' => 'Unlinked Resources cleared from both Themes and Statements',
        ], status: 201);
    }

    public function clearStatementCache(): JsonResponse
    {
        // clear statement cache from statement
        Cache::forget('statement_tree');
        Cache::forget('themes_statement_tree');

        return response()->json([
            'message' => 'Statement Tree Cache Cleared. Next Statement call will create a new cache',
        ], status: 422);
    }
}
