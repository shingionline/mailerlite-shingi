<?php

namespace App\Http\Controllers;

use App\Models\Field;
use App\Models\FieldValue;
use App\Models\Subscriber;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class SubscriberController extends Controller
{
    public function get_single($id)
    {
        // get subscriber
        $subscriber = Subscriber::find($id);

        $fields = Field::all();

        $fieldValues = $subscriber->field_values;

        foreach ($fields as $field) {

            // check if field value exists for this subscriber
            $check = FieldValue::where('subscriber_id', $subscriber->id)->where('field_id', $field->id)->first();

            $value = (!empty($check)) ? $check->value : null;
            $fieldValueId = (!empty($check)) ? $check->id : null;

            $fieldValues[] = [
                'id' => $fieldValueId,
                'subscriber_id' => $subscriber->id,
                'field_id' => $field->id,
                'value' => $value,
                'field' => [
                    'id' => $field->id,
                    'title' => $field->title,
                    'type' => $field->type,
                ]
            ];
        }

        $subscriber->field_values = $fieldValues;

        return $subscriber;
    }

    public function get_all()
    {
        // get all subscribers
        return Subscriber::orderBy('id', 'DESC')->get();
    }

    public function new(Request $request): JsonResponse
    {
        $data = $request->all();

        $email = $data['subscriber']['email'];
        $name = $data['subscriber']['name'];
        $custom_fields = $data['custom_fields'];

        // validate email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return response()->json(['success' => false, 'message' => 'Invalid email address']);
        }

        // check if subscriber already exists
        $check = Subscriber::where('email', $email)->first();
        if (!empty($check)) {
            return response()->json(['success' => false, 'message' => 'Subscriber email already exists']);
        }

        // save subscriber
        $subscriber = new Subscriber();
        $subscriber->email = $email;
        $subscriber->name = $name;
        $subscriber->save();

        // save custom fields
        foreach ($custom_fields as $title => $value) {

            // get field info
            $field = Field::where('title', $title)->first();

            // get field type
            $type = $field->type;

            // validate field value - date, number, boolean
            if (!empty($value)) {
                if ($type == 'date' && !strtotime($value)) {
                    return response()->json(['success' => false, 'message' => 'Invalid date format for ' . $title]);
                } else if ($type == 'number' && !is_numeric($value)) {
                    return response()->json(['success' => false, 'message' => 'Invalid number format for ' . $title]);
                } else if ($type == 'boolean' && !is_bool($value)) {
                    return response()->json(['success' => false, 'message' => 'Invalid boolean format for ' . $title]);
                }
            }

            $fieldValue = new FieldValue();
            $fieldValue->subscriber_id = $subscriber->id;
            $fieldValue->field_id = $field->id;
            $fieldValue->value = $value;
            $fieldValue->save();
        }

        return response()->json(['success' => true, 'message' => 'Subscriber created successfully']);
    }

    public function update(Request $request): JsonResponse
    {
        $data = $request->all();

        $id = $data['subscriber']['id'];
        $email = $data['subscriber']['email'];
        $name = $data['subscriber']['name'];
        $state = $data['subscriber']['state'];
        $custom_fields = $data['custom_fields'];

        // validate email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return response()->json(['success' => false, 'message' => 'Invalid email address']);
        }

        // update subscriber
        $subscriber = Subscriber::find($id);
        $subscriber->email = $email;
        $subscriber->name = $name;
        $subscriber->state = $state;
        $subscriber->save();

        // update custom fields
        foreach ($custom_fields as $data) {

            $title = $data['field']['title'];
            $value = $data['value'];

            // get field id
            $field = Field::where('title', $title)->first();

            // get field type
            $type = $field->type;

            // validate field value - date, number, boolean
            if (!empty($value)) {
                if ($type == 'date' && !strtotime($value)) {
                    return response()->json(['success' => false, 'message' => 'Invalid date format for ' . $title]);
                } else if ($type == 'number' && !is_numeric($value)) {
                    return response()->json(['success' => false, 'message' => 'Invalid number format for ' . $title]);
                } else if ($type == 'boolean' && !is_bool($value)) {
                    return response()->json(['success' => false, 'message' => 'Invalid boolean format for ' . $title]);
                }
            }

            // check if field value exists for this subscriber
            $check = FieldValue::where('subscriber_id', $subscriber->id)
                ->where('field_id', $field->id)->first();

            if (!empty($check)) {
                // update field value
                $fieldValue = FieldValue::find($check->id);
                $fieldValue->value = $value;
                $fieldValue->save();
            } else {
                // create field value
                $fieldValue = new FieldValue();
                $fieldValue->subscriber_id = $subscriber->id;
                $fieldValue->field_id = $field->id;
                $fieldValue->value = $value;
                $fieldValue->save();
            }
        }

        return response()->json(['success' => true, 'message' => 'Subscriber updated successfully']);
    }

    public function delete($id): JsonResponse
    {
        // delete the subscriber
        $subscriber = Subscriber::find($id);
        $subscriber->delete();

        // delete all field values for this subscriber
        FieldValue::where('subscriber_id', $id)->delete();

        return response()->json(['success' => true, 'message' => 'Subscriber deleted successfully']);
    }
}
