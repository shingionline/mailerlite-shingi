<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function get($id)
    {
        return Contact::where('shipper_id', $id)->orderBy('id', 'DESC')->get();
    }

    public function make_primary(Request $request): JsonResponse
    {
        $data = $request->all();

        $shipper_id = $data['shipper_id'];
        $contact_id = $data['contact_id'];

        // set all contacts to not primary
        Contact::where('shipper_id', $shipper_id)->update(['primary' => 0]);

        // make the selected contact primary
        Contact::where('id', $contact_id)->update(['primary' => 1]);

        return response()->json(['success' => true, 'message' => 'Contact updated successfully']);
    }

    public function delete($id): JsonResponse
    {

        $contact = Contact::find($id);
        $contact->delete();

        return response()->json(['success' => true, 'message' => 'Contact deleted successfully']);
    }

    public function new(Request $request): JsonResponse
    {

        $data = $request->all();

        $shipper_id = $data['shipper_id'];
        $name = $data['contact']['name'];
        $phone = $data['contact']['phone'];

        // check if there's a primary contact
        $check = Contact::where('shipper_id', $shipper_id)->where('primary', 1)->first();

        // if there is no primary contact, set this contact to primary
        $primary = ($check) ? 0 : 1;

        $contact = new Contact();
        $contact->shipper_id = $shipper_id;
        $contact->name = $name;
        $contact->phone = $phone;
        $contact->primary = $primary;
        $contact->save();

        return response()->json(['success' => true, 'message' => 'Contact added successfully', 'contact' => $contact]);
    }
}
