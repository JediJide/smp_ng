<?php

namespace App\Http\Controllers\Api\V1\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

/**
 * @OA\Get(
 *     tags={"notification"},
 *     path="/api/v1/notification/my/{user_id}",
 *     summary="get statement by category_id",
 *     security={{ "Bearer":{} }},
 *     @OA\Parameter(
 *        name="user_id",
 *        in="path",
 *        description="Get notififcation by user_id",
 *        @OA\Schema(
 *           type="integer",
 *           format="int64"
 *        ),
 *        required=true,
 *        example=1
 *     ),
 *     @OA\Response(response="401", description="fail", @OA\JsonContent(ref="#/components/schemas/ApiRequestException")),
 *     @OA\Response(response="404", description="fail", @OA\JsonContent(ref="#/components/schemas/ApiNotFoundException")),
 *     @OA\Response(response="200", description="success",@OA\JsonContent(ref="#/components/schemas/NotificationResource")))
 * )
 */
class NotificationController extends Controller
{
    public function getNotification(Notification $notification, $user_id): Collection
    {
        return $notification->getMyNotification($user_id);
    }

    /**
     * @OA\Delete(
     *     tags={"notification"},
     *     path="/api/v1/notification/{id}",
     *     summary="Delete notification by Id",
     *     security={{ "Bearer":{} }},
     *     @OA\Parameter(
     *        name="id",
     *        in="path",
     *        description="notification Id",
     *        @OA\Schema(
     *           type="integer",
     *           format="int64"
     *        ),
     *        required=true,
     *        example=1
     *     ),
     *     @OA\Response(response="401", description="fail", @OA\JsonContent(ref="#/components/schemas/ApiRequestException")),
     *     @OA\Response(response="404", description="fail", @OA\JsonContent(ref="#/components/schemas/ApiNotFoundException")),
     *     @OA\Response(response="202", description="success",@OA\JsonContent(ref="#/components/schemas/MassDestroyStatementRequest")))
     * )
     * @param Notification $notification
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(Notification $notification, int $id): JsonResponse
    {
        // grab notification id
        $notification = $notification->getNotificationById($id);
        // delete message table
        if (! $notification->isEmpty()) {
            DB::table('notification_messages')
                ->where('id', '=', $notification[0]->notification_message_id)
                ->delete();
        }

        // then delete notification
        Notification::destroy($id);

        return response()->json('Notification has been removed', '202');
    }
}
