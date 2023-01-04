<?php

namespace App\Http\Controllers;

use App\Models\Field;
use App\Models\FieldValue;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class FieldController extends Controller
{
    public function get_all()
    {
        return Field::all();
    }

    public function delete($id): JsonResponse
    {
        // delete all field values for this field
        FieldValue::where('field_id', $id)->delete();

        // delete the field
        $field = Field::find($id);
        $field->delete();

        return response()->json(['success' => true, 'message' => 'Field deleted successfully']);
    }

    public function new(Request $request): JsonResponse
    {
        $data = $request->all();

        $title = ucfirst($data['field']['title']);
        $type  = $data['field']['type'];

        $field = new Field();
        $field->title = $title;
        $field->type  = $type;
        $field->save();

        return response()->json(['success' => true, 'message' => 'Field created successfully']);
    }
}
