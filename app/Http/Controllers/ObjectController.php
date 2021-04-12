<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use App\Models\dataEntry;

class ObjectController extends Controller
{

  public function list(Request $request)
  {
    $entries = New dataEntry();

    $page = $request['page'] ?? 1;
    $limit = $request['limit'] ?? 1;

    if( isset($request['order_by']) ) {
      $entries = $entries->orderByRaw($request['order_by'] . " " . $request['asc_desc']);
    }

    $totalRecords = $entries->count();

    if( ($totalRecords < ($page * $limit)) || $page < 1 ) {
      $page = 1;
    }

    if( isset($request['key_name']) ) {
      $entries = $entries->where("key_name", $request['key_name']);
    }
   
    $result = $entries->offset(($page-1))->limit($limit)->get();

    if( count($result) == 0 ) {
      return response()->json([ "success" => false, "message" => "No record found." ]);
    }

    return response()->json([ "success" => true, "message" => "Success.", "data" => $result ]);
  }

  public function create(Request $request)
  {
    $data = $request->all();

    $validator = Validator::make($request->all(), [
      'json' => 'required|json'
    ]);

    if ( $validator->fails() ) {
      return response()->json([ "success" => false, "message" => $validator->errors() ]);
    }

    $json = json_decode($data['json'], true);

    $arrEntries = [];

    if( isset($json[0]) ) { // Multiple key
      foreach ($json as $key => $value) {

        $entriesParams = [
          "key_name" => substr(strtolower(preg_replace("/[^a-zA-Z0-9]+/", "", array_keys($value)[0])), 0, 40), // Remove symbol, Accept alphanumeric 
          "value_name" => is_array(array_values($value)) ? substr(array_values($value)[0], 0, 80) : substr($value, 0, 80),
          "store_timestamp" => time()
        ];

        array_push($arrEntries, $entriesParams);
      }
    } else {  // Single key
      $entriesParams = [
        "key_name" => substr(strtolower(preg_replace("/[^a-zA-Z0-9]+/", "", key($json))), 0, 40), // Remove symbol, Accept alphanumeric 
        "value_name" => substr($json[key($json)], 0, 80),
        "store_timestamp" => time()
      ];

      array_push($arrEntries, $entriesParams);
    }

    if( !empty($arrEntries) ) {
      $keyArr = array_map(function ($ar) {return $ar['key_name'];}, $arrEntries);

      $entries = New dataEntry();

      $entries->upsert($arrEntries, ['key_name'], ['value_name']);

      $result = $entries->whereIn("key_name", $keyArr)->get();

      return response()->json([ "success" => true, "message" => "Success.", "data" => $result ]);
    }

    return response()->json([ "success" => false, "message" => "No record have been created." ]);

  }

  public function retrieve($key, Request $request)
  {

    if( !isset($key) ) {
      return response()->json([ "success" => false, "message" => "Search key is required." ]);
    }

    $entries = New dataEntry();

    $result = $entries->where("key_name", $key);

    if( isset($request['timestamp']) ) {

      $result = $result->where("store_timestamp", "<=", $request['timestamp']);

    }

    $result = $result->first();

    if( empty($result) ) {
      return response()->json([ "success" => false, "message" => "No record found." ]);
    }

    return response()->json([ "success" => true, "message" => "Success.", "data" => $result ]);
  }
}































