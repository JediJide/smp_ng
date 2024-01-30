<?php

namespace App\Http\Controllers\Api\V1\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Resource;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

class ResourceController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    /**
     * @OA\Get(
     *     tags={"resource"},
     *     path="/api/v1/resource",
     *     summary="get resource list",
     *     security={{ "Bearer":{} }},
     *
     *     @OA\Response(response="401", description="fail", @OA\JsonContent(ref="#/components/schemas/ApiRequestException")),
     *     @OA\Response(response="200", description="success",@OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/ResourceResource"))))
     * )
     */
    public function index(Resource $resource): JsonResponse
    {
        $resource = $resource->getFileNames();

        return response()->json($resource);
    }

    /**
     * Store a newly created resource in storage.
     */

    /**
     * @OA\Post  (
     *     tags={"resource"},
     *     path="/api/v1/resource",
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
     *                 ref="#/components/schemas/Resource",
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
    /** @noinspection PhpUndefinedFieldInspection */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'title' => 'required',
        ]);

        if (isset($request->file_name) && isset($request->url)) {
            throw new Exception('A resource can only be a file or a url. Please choose one');
        }
        // initialized values.
        $temporarySignedUrl = null;
        $fileName = null;
        $file_size = null;
        $file_mime_type = null;
        $url = null;
        $is_header_resource = null;
        if (isset($request->file_name)) {
            // filesize is number in MB (200mb)
            // 1048576 is 1 mb converted into bytes
            $maxFileSize = strval(300 * 1048576);
            $request->validate([
                'file_name' => 'required|mimes:pdf,ppt,pptx,doc,docx,jpg,jpeg,png,gif,zip,mp4,xls,xlsx|max:'.$maxFileSize,
                'title' => 'required',
            ]);
            //Upload to AWS
            $aws_path = config('app.aws_path');
            $path = Storage::disk('s3')->put('/'.$aws_path.'/documents/resources/', $request->file_name);
            $path = Storage::disk('s3')->url($path);

            $fileName = basename($path);
            $file = $aws_path.'/documents/resources/'.$fileName;
            //24 hours temporary url for AWS S3 file download
            $temporarySignedUrl = Storage::disk('s3')->temporaryUrl($file, now()->addMinutes(1440));

            // Get file information
            $document = $request->file('file_name');
            $file_size = $document->getSize();
            $file_mime_type = $document->getMimeType();
            $is_header_resource = $request->is_header_resource;
        } else {
            $url = $request->url;
        }

        // Store $fileName name in DATABASE from HERE
        $resource_model = new Resource();

        $resource = [
            'user_id' => auth()->id(),
            'title' => $request->title,
            'url' => $url,
            'file_name' => $fileName,
            'file_size' => $file_size,
            'file_mime_type' => $file_mime_type,
            'temporary_url' => $temporarySignedUrl,
            'is_header_resource' => $is_header_resource,
            'ip_address' => $_SERVER['REMOTE_ADDR'],
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
        $last_id = $resource_model->store($resource);
        // print_r ($last_id); dd ();
        $latest_insert = $resource_model->getFileById($last_id);

        return response()->json($latest_insert);
    }

    /**
     * Display the specified resource.
     *
     * @return Response
     */

    /**
     * @OA\Get(
     *     tags={"resource"},
     *     path="/api/v1/resource/{id}",
     *     summary="get resource by Id",
     *     security={{ "Bearer":{} }},
     *
     *     @OA\Parameter(
     *        name="id",
     *        in="path",
     *        description="Get resource by Id",
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
     *     @OA\Response(response="200", description="success",@OA\JsonContent(ref="#/components/schemas/ResourceResource")))
     * )
     */
    public function show(Resource $resource, int $id): JsonResponse
    {
        $file_object = $resource->getFileById($id);

        return response()->json([$file_object,
        ], status: 200);
    }

    /**
     * Update the specified resource in storage.
     */

    /**
     * @OA\Post  (
     *     tags={"resource"},
     *     path="/api/v1/resource/{id}",
     *     summary="Update and replace AWS S3",
     *      security={{ "Bearer":{} }},
     *     description="Update resource",
     *
     *      @OA\Parameter(
     *        name="id",
     *        in="path",
     *        description="Resource Id",
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
     *                 ref="#/components/schemas/Resource",
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

    /** @noinspection PhpUndefinedFieldInspection */
    public function update(Request $request, int $id): mixed
    {
        if (! empty($request)) {
            $request->validate([
                'title' => 'required',
            ]);
        }

        if (isset($request->file_name) && isset($request->url)) {
            throw new Exception('A resource can only be a file or a url. Please choose one');
        }

        $temporarySignedUrl = null;
        $fileName = null;
        $file_size = null;
        $file_mime_type = null;
        if (isset($request->file_name)) {
            //Upload to AWS
            $aws_path = config('app.aws_path');
            $path = Storage::disk('s3')->put('/'.$aws_path.'/documents/resources/', $request->file_name);
            $path = Storage::disk('s3')->url($path);

            $fileName = basename($path);
            $file = $aws_path.'/documents/resources/'.$fileName;
            //24 hours temporary url for AWS S3 file download
            $temporarySignedUrl = Storage::disk('s3')->temporaryUrl($file, now()->addMinutes(1440));

            $resource_model = new Resource();

            // Get file information
            $document = $request->file('file_name');

            $file_size = $document->getSize();
            $file_mime_type = $document->getMimeType();

            $resource = [
                'user_id' => auth()->id(),
                'title' => $request->title,
                'url' => null,
                'file_name' => $fileName,
                'file_size' => $file_size,
                'file_mime_type' => $file_mime_type,
                'temporary_url' => $temporarySignedUrl,
                'ip_address' => $_SERVER['REMOTE_ADDR'],
                'updated_at' => Carbon::now(),
            ];

            $resource_model->updateTempResourceUrlsById($id, $resource);
        }

        $resource = Resource::find($id);
        $resource->update($request->all());

        return $resource;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return int
     */

    /**
     * @OA\Delete(
     *     tags={"resource"},
     *     path="/api/v1/resource/{id}",
     *     summary="Delete resource by Id",
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
     *     @OA\Response(response="202", description="success",@OA\JsonContent(ref="#/components/schemas/MassDestroyResourceRequest")))
     * )
     */
    public function destroy(int $id): JsonResponse
    {

        //get file name to remove from AWS
        $resource_model = new Resource();
        $get_file_name = $resource_model->getFileById($id);

        if (count($get_file_name) > 0) {
            $file_name = $get_file_name[0]->file_name;

            $aws_path = config('app.aws_path');

            $path = $aws_path.'/documents/resources/'.$file_name;

            if (Storage::disk('s3')->exists($path)) {
                Storage::disk('s3')->delete($path);
            }

            Resource::destroy($id);
        }

        return response()->json('resource has been deleted', '202');
    }
}
