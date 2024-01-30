<?php

namespace App\Services;

use App\Http\Resources\Admin\StatementResource;
use App\Models\Audience;
use App\Models\Reference;
use App\Models\Resource;
use App\Models\Statement;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class StatementService
{
    /** @noinspection PhpUndefinedFieldInspection */
    public function createStatement(Request $request, Statement $statement): object
    {
        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'theme_id' => 'required',
            'is_notify_all' => 'required',
            'therapy_area_id' => 'required',
        ]);

        $parent_id = $request->parent_id ?? null;
        $statement_data = [
            'parent_id' => $parent_id,
            'theme_id' => $request->theme_id,
            'title' => $request->title,
            'order_by' => $request->order,
            'therapy_area_id' => $request->therapy_area_id,
            'description' => $request->description,
            'is_notify_all' => $request->is_notify_all,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
        $statement_create = $statement->store($statement_data);
        $statement_latest = Statement::find($statement_create);

        if ($request->is_notify_all) {
            $notification_message = [
                'old_value' => null,
                'new_value' => $request->description,
                'statement_id' => $statement_latest->id,
                'created_at' => Carbon::now(),
            ];

            $this->createNotification($request->title, $notification_message);
        }

        if (isset($request->resource_id)) {
            $this->linkResourceToStatement($request->resource_id, $statement_latest);
        }
        if (isset($request->reference_id)) {
            $this->linkReferenceToStatement($request->reference_id, $statement_latest);
        }

        if (isset($request->audience_id)) {
            $this->linkAudienceToStatement($request->audience_id, $statement_latest);
        }

        return (new StatementResource($statement_latest))
            ->response()
            ->setStatusCode(ResponseAlias::HTTP_CREATED);
    }

    /** @noinspection PhpUndefinedFieldInspection */
    public function editStatement(Request $request, Statement $statement, int $id)
    {
        $request->validate([
            //'id' => 'required',
            'title' => 'required',
            'description' => 'required',

        ]);

        $statement = new Statement();

        if ($request->is_notify_all) {
            $this->editNotification($request, $id);
        }

        $statement->updateStatementById($id, $request->title, $request->description, $request->order);

        $statement_id = Statement::find($id);
        if (isset($request->resource_id)) {
            $this->linkResourceToStatement($request->resource_id, $statement_id);
        }else{
            //if resource_id not set or is set to "" then remove all resource links
            $statement_id->resources()->sync([]);
        }

        if (isset($request->reference_id)) {
            $this->linkReferenceToStatement($request->reference_id, $statement_id);
        }

        if (isset($request->audience_id)) {
            $this->linkAudienceToStatement($request->audience_id, $statement_id);
        }

        return $statement_id;
    }

    public function deleteStatement(int $id)
    {

        //fetch existing statement details
        $statement_details = Statement::find($id);

        //delete existing statement
        Statement::destroy($id);

        //create notification
        $notification_message = [
            'old_value' => $statement_details->description,
            'new_value' => null,
            'statement_id' => $statement_details->id,
            'created_at' => Carbon::now(),
        ];
        $this->createNotification('Statement deleted', $notification_message, 2);


    }

    private function createNotification($breadcrumb, $notification_message, $type = 0)
    {
        // if is_notify_all is 1, then create record in notification tables
        $statement = new Statement();
        $latest_message_id = $statement->store_notification_message($notification_message);

        $notification_data = [
            'notification_message_id' => $latest_message_id,
            'user_id' => auth()->id(),
            'is_read' => 0,
            'type' => $type, //added type
            'bread_crumb' => $breadcrumb,
            'created_at' => Carbon::now(),
        ];
        $statement->store_notify_users($notification_data);
    }


    private function editNotification($request, int $id)
    {
        $statement = new Statement();
        // get query from current statement to be used to fill the old_value column in notification_messages table
        $get_statement = $statement->getStatement($id);
        // update or insert message table
        $get_messages = $statement->get_notification_by_statement_Id($id);

        if (! $get_messages->isEmpty()) {
            //update
            $data_message = [
                'old_value' => $get_messages[0]->new_value, //now setting the past new value into old
                'new_value' => $request->description,
                'updated_at' => Carbon::now(),

            ];
            $statement->updateNotification_message($id, $data_message);

            $data_notification = [
                'notification_message_id' => $get_messages[0]->id,
                'user_id' => auth()->id(),
                'is_read' => 0,
                'type' => 1, // 0 = added, 1 = updated, 2 = delete
                'bread_crumb' => $request->title,
                'updated_at' => Carbon::now(),
            ];
            $statement->updateNotification($get_messages[0]->id, $data_notification);
        } else {

            $old_description =  (! $get_statement->isEmpty()) ? $get_statement[0]->description : null;

            //insertion process
            $notification_message = [
                'old_value' =>  $old_description,
                'new_value' => $request->description,
                'statement_id' => $id,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];

            $latest_message_id = $statement->store_notification_message($notification_message);

            $notification_data = [
                'notification_message_id' => $latest_message_id,
                'user_id' => auth()->id(),
                'is_read' => 0,
                'type' => 1, // 0 = added, 1 = updated, 2 = delete
                'bread_crumb' => $request->title,
                'created_at' => Carbon::now(),
            ];
            $statement->store_notify_users($notification_data);
        }
    }

    private function linkResourceToStatement($resources_ids, $statement_latest)
    {
        //convert form string list into int
        $string_resourceIDs = $resources_ids;
        $resourceIDs = array_map('intval', explode(',', $string_resourceIDs));

        $statement_latest->resources()->sync($resourceIDs);

        #update resource link field
        foreach ($resourceIDs as $resourceID)
        {
            Resource::where('id', $resourceID)
                ->update(['is_linked' => 1]);
        }

    }

    private function linkReferenceToStatement($references_ids, $statement_latest)
    {
        //convert form string list into int
        $string_referenceIDs = $references_ids;
        $referenceIDs = array_map('intval', explode(',', $string_referenceIDs));
        $statement_latest->references()->sync($referenceIDs);

        #updated reference link field
        foreach ($referenceIDs as $referenceID)
        {
            Reference::where('id', $referenceID)
                ->update(['is_linked' => 1]);
        }

    }

    private function linkAudienceToStatement($audience_ids, $statement_latest)
    {
        //convert form string list into int
        $string_audienceIDs = $audience_ids;
        $audienceIDs = array_map('intval', explode(',', $string_audienceIDs));
        $statement_latest->audiences()->sync($audienceIDs);
    }
}
