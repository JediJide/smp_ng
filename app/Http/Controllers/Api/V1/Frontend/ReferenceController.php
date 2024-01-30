<?php

namespace App\Http\Controllers\Api\V1\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Reference;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;

class ReferenceController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    /**
     * @OA\Get(
     *     tags={"references"},
     *     path="/api/v1/reference",
     *     summary="get reference list",
     *     security={{ "Bearer":{} }},
     *
     *     @OA\Response(response="401", description="fail", @OA\JsonContent(ref="#/components/schemas/ApiRequestException")),
     *     @OA\Response(response="200", description="success",@OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/ReferenceResource"))))
     * )
     */
    public function index(Reference $reference): JsonResponse
    {
        $reference = $reference->getFileNames();

        return response()->json($reference);
    }

    /**
     * Store a newly created resource in storage.
     */

    /**
     * @OA\Post  (
     *     tags={"references"},
     *     path="/api/v1/reference",
     *     summary="Upload new file to AWS S3",
     *      security={{ "Bearer":{} }},
     *     description="Update resource",
     *
     *     @OA\RequestBody(
     *
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *
     *             @OA\Schema(
     *                 type="object",
     *                 ref="#/components/schemas/Reference",
     *             )
     *         )
     *     ),
     *
     *      @OA\Response(response="401", description="fail", @OA\JsonContent(ref="#/components/schemas/ApiRequestException")),
     *     @OA\Response(response="201", description="Message", @OA\JsonContent(type="object", @OA\Property(
     *     format="string", default="You have successfully uploaded the file.", description="message", property="message"), @OA\Property(
     *     format="string", default="https://nucleusglobal-smpdev.s3.eu-west-1.amazonaws.com/cslbehring/documents/resources/vohV25qjsxJ3yvdWOAzvBZrjI9ASJv4k0rMPwMhJ.pdf", description="message", property="temporary_url"))),
     * )
     *
     * @throws Exception
     */
    public function store(Request $request): JsonResponse
    {
        $maxFileSize = strval(200 * 1048576);
        $request->validate([
            'file_name' => 'mimes:pdf,ppt,pptx,doc,docx,jpg,jpeg,png,gif,zip,mp4,xls,xlsx|max:'.$maxFileSize,
            'title' => 'required',
        ]);

        if (isset($request->file_name) && isset($request->url)) {
            throw new Exception('A reference can only be a file or a url. Please choose one.');
        }

        if (isset($request->file_name)) {
            //Upload to AWS
            $aws_path = config('app.aws_path');
            $path = Storage::disk('s3')->put('/'.$aws_path.'/documents/references/', $request->file_name);
            $path = Storage::disk('s3')->url($path);

            $fileName = basename($path);
            $file = $aws_path.'/documents/references/'.$fileName;
            //24 hours temporary url for AWS S3 file download
            $temporarySignedUrl = Storage::disk('s3')->temporaryUrl($file, now()->addMinutes(1440));
        } else {
            $fileName = null;
            $temporarySignedUrl = null;
        }

        $url = $request->url ?? null;

        // Store $fileName name in DATABASE from HERE
        $reference_model = new Reference();

        $reference[] = [
            'user_id' => auth()->id(),
            'title' => $request->title,
            'url' => $url,
            'file_name' => $fileName,
            'temporary_url' => $temporarySignedUrl,
            'ip_address' => $_SERVER['REMOTE_ADDR'],
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
        $reference_model->store($reference);

        return response()->json([
            'message' => 'You have successfully uploaded the file.',
            'temporary_url' => $temporarySignedUrl,
        ], status: 201);
    }

    /**
     * Display the specified resource.
     *
     * @return Response
     */

    /**
     * @OA\Get(
     *     tags={"references"},
     *     path="/api/v1/reference/{id}",
     *     summary="get reference by Id",
     *     security={{ "Bearer":{} }},
     *
     *     @OA\Parameter(
     *        name="id",
     *        in="path",
     *        description="Get reference by Id",
     *
     *        @OA\Schema(
     *           type="integer",
     *           format="int64"
     *        ),
     *        required=true,
     *        example=1
     *     ),
     *
     *     @OA\Response(response="401", description="fail", @OA\JsonContent(ref="#/components/schemas/ApiRequestException")),
     *     @OA\Response(response="404", description="fail", @OA\JsonContent(ref="#/components/schemas/ApiNotFoundException")),
     *     @OA\Response(response="200", description="success",@OA\JsonContent(ref="#/components/schemas/ReferenceResource")))
     * )
     */
    public function show(Reference $reference, int $id): JsonResponse
    {
        $file_object = $reference->getFileById($id);

        return response()->json([$file_object,
        ], status: 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @return mixed
     */

    /**
     * @OA\Post  (
     *     tags={"references"},
     *     path="/api/v1/reference/{id}",
     *     summary="Update and replace AWS S3",
     *      security={{ "Bearer":{} }},
     *     description="Update reference",
     *
     *      @OA\Parameter(
     *        name="id",
     *        in="path",
     *        description="Reference Id",
     *
     *        @OA\Schema(
     *           type="integer",
     *           format="int64"
     *        ),
     *        required=true,
     *        example=1
     *     ),
     *
     *     @OA\RequestBody(
     *
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *
     *             @OA\Schema(
     *                 type="object",
     *                 ref="#/components/schemas/Reference",
     *             )
     *         )
     *     ),
     *
     *      @OA\Response(response="401", description="fail", @OA\JsonContent(ref="#/components/schemas/ApiRequestException")),
     *     @OA\Response(response="201", description="Message", @OA\JsonContent(type="object", @OA\Property(
     *     format="string", default="You have successfully uploaded the file.", description="message", property="message"), @OA\Property(
     *     format="string", default="https://nucleusglobal-smpdev.s3.eu-west-1.amazonaws.com/cslbehring/documents/resources/vohV25qjsxJ3yvdWOAzvBZrjI9ASJv4k0rMPwMhJ.pdf", description="message", property="temporary_url"))),
     * )
     *
     * @throws Exception
     */
    public function update(Request $request, int $id)
    {
        $maxFileSize = strval(200 * 1048576);
        $request->validate([
            'file_name' => 'mimes:pdf,ppt,pptx,doc,docx,jpg,jpeg,png,gif,zip,mp4,xls,xlsx|max:'.$maxFileSize,
            'title' => 'required',
        ]);

        if (isset($request->file_name) && isset($request->url)) {
            throw new Exception('A reference can only be a file or a url. Please choose one');
        }

        if (isset($request->file_name)) {
            //Upload to AWS
            $aws_path = config('app.aws_path');
            $path = Storage::disk('s3')->put('/'.$aws_path.'/documents/references/', $request->file_name);
            $path = Storage::disk('s3')->url($path);

            $fileName = basename($path);
            $file = $aws_path.'/documents/references/'.$fileName;
            //24 hours temporary url for AWS S3 file download
            $temporarySignedUrl = Storage::disk('s3')->temporaryUrl($file, now()->addMinutes(1440));

            $reference_model = new Reference();
            $reference_model->updateTempReferenceUrlsById($id, $fileName, $temporarySignedUrl);
        }

        $reference = Reference::find($id);

        $reference->update($request->all());

        return $reference;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return int
     */

    /**
     * @OA\Delete(
     *     tags={"references"},
     *     path="/api/v1/reference/{id}",
     *     summary="Delete reference by Id",
     *     security={{ "Bearer":{} }},
     *
     *     @OA\Parameter(
     *        name="id",
     *        in="path",
     *        description="resource Id",
     *
     *        @OA\Schema(
     *           type="integer",
     *           format="int64"
     *        ),
     *        required=true,
     *        example=1
     *     ),
     *
     *     @OA\Response(response="401", description="fail", @OA\JsonContent(ref="#/components/schemas/ApiRequestException")),
     *     @OA\Response(response="404", description="fail", @OA\JsonContent(ref="#/components/schemas/ApiNotFoundException")),
     *     @OA\Response(response="202", description="success",@OA\JsonContent(ref="#/components/schemas/MassDestroyReferenceRequest")))
     * )
     */
    public function destroy(int $id): JsonResponse
    {
        //get file name to remove from AWS
        $reference_model = new Reference();
        $get_file_name = $reference_model->getFileById($id);

        if (count($get_file_name) > 0) {
            $file_name = $get_file_name[0]->file_name;

            $aws_path = config('app.aws_path');

            $path = $aws_path.'/documents/references/'.$file_name;

            if (Storage::disk('s3')->exists($path)) {
                Storage::disk('s3')->delete($path);
            }

            // return Reference::destroy ( $id );
        }
        Reference::destroy($id);

        return response()->json('resource has been deleted', '202');
    }
}
