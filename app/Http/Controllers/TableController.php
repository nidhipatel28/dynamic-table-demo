<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\Request;
use App\Models\Organization;
use Session;
use Illuminate\Support\Facades\DB;

class TableController extends Controller
{
    public function createTable($table_name, $fields = [])
    {
        // laravel check if table is not already exists
        if (!Schema::hasTable($table_name)) {
            Schema::create($table_name, function (Blueprint $table) use ($fields, $table_name) {
                $table->increments('id');
                if (count($fields) > 0) {
                    foreach ($fields as $field) {
                        $table->{$field['type']}($field['name']);
                    }
                }
                $table->timestamps();
            });

            return redirect()->back()->with('message', 'Given organization and table has been successfully created!');
        }

        return redirect()->back()->with('message', 'Given organization table is already exist.');
    }

    public function operate($table_name)
    {
        // set your dynamic fields (you can fetch this data from database this is just an example)
        $fields = [
            ['name' => 'field_1', 'type' => 'string'],
            ['name' => 'field_2', 'type' => 'text'],
            ['name' => 'field_3', 'type' => 'integer'],
            ['name' => 'field_4', 'type' => 'longText']
        ];

        return $this->createTable($table_name, $fields);
    }

    public function addGroup(Request $request)
    {
        $this->validateForm($request);
        
        $organization = new Organization;
        $organization->org_name = $request->org_name;
        $organization->org_code = $request->org_code;
        $organization->save();

        // set dynamic table name according to your requirements
        $table_name = $organization->org_code.'_logs';

        // set your dynamic fields (you can fetch this data from database this is just an example)
        $fields = [
            ['name' => 'field_1', 'type' => 'string'],
            ['name' => 'field_2', 'type' => 'text'],
            ['name' => 'field_3', 'type' => 'integer'],
            ['name' => 'field_4', 'type' => 'longText']
        ];

        return $this->createTable($table_name, $fields);
    }

     //Form validation for companies
     public function validateForm(Request $request)
     {
         $messages = [
             "org_name.required" => "Please enter organization name",
             "org_name.max" => "The name entered exceeds the maximum length ",
             "org_code.required" => "Please enter organization code.",
             "org_code.unique" => "The organization code already exits.",
         ];
 
         $validateAtt = $request->validate([
             'org_name' => 'required|max:191',
             'org_code' => 'required|unique:oraganization'
         ],$messages);

         return $validateAtt;
     }

    public function updateGroup(Request $request)
     {
         $organization = Organization::where('id', '=', $request->orgId)->first();
       
         if($request->status){
            $organization->status = ($request->status === 'true') ? 1 : 0;
         } else {
            $organization->org_name = $request->name;
         }
         $organization->save();
         Session::flash('success', 'Organization successfully updated!');
         return response()->json(['message' => 'Organization successfully updated!'], 200);
    }

    public function deleteGroup($id=0){
        $orgData = Organization::where('id', '=', $id)->first();
        $table_name = $orgData->org_code.'_logs';

        $records = DB::table($table_name)->first();
        if(!$records){
            $orgData->forceDelete();
            $this->removeTable($table_name);
        } else {
            $orgData->delete();
        }
        return response()->json(['message' => 'Organization successfully deleted!'], 200);
    }
    //To delete the tabel from the database

    public function removeTable($table_name)
    {
        Schema::dropIfExists($table_name); 

        return true;
    }

    // list organization 
    public function index()
    {
      return view('orgList', [
       'orgList' => Organization::all()
      ]);
    }
}
