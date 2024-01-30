<?php

namespace App\Http\Controllers\Api\V1\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class StatementReorderController extends Controller
{
    public function StatementsUpdate(Request $request): JsonResponse
    {
        $orders = $request->all();

        foreach ($orders as $order=>$value) {
            DB::table('statements')
                ->where('id', '=', $value['id'])
                ->update(
                    [
                        'order_by' => $value['reorder'],
                        'updated_at' =>  Carbon::now(),
                    ]);
        }

        return response()->json('Statements re-ordered', '202');
    }

    public function ThemesUpdate(Request $request): JsonResponse
    {
        $orders = $request->all();

        foreach ($orders as $order=>$value) {
            DB::table('themes')
                ->where('id', '=', $value['id'])
                ->update(
                    [
                        'order_by' => $value['reorder'],
                        'updated_at' =>  Carbon::now(),
                    ]);
        }

        return response()->json('Themes re-ordered', '202');
    }

    public function LexiconsUpdate(Request $request): JsonResponse
    {
        $orders = $request->all();

        foreach ($orders as $order=>$value) {
            DB::table('lexicons')
                ->where('id', '=', $value['id'])
                ->update(
                    [
                        'order_by' => $value['reorder'],
                        'updated_at' =>  Carbon::now(),
                    ]);
        }

        return response()->json('Lexicons re-ordered', '202');
    }

    public function GlossariesUpdate(Request $request): JsonResponse
    {
        $orders = $request->all();

        foreach ($orders as $order=>$value) {
            DB::table('glossaries')
                ->where('id', '=', $value['id'])
                ->update(
                    [
                        'order_by' => $value['reorder'],
                        'updated_at' =>  Carbon::now(),
                    ]);
        }

        return response()->json('Glossaries re-ordered', '202');
    }
}
