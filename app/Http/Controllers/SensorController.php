<?php

namespace App\Http\Controllers;

use Google\Cloud\Firestore\FieldValue;
use Google\Cloud\Firestore\FirestoreClient;
use Kreait\Firebase\Firestore;
use Illuminate\Http\Request;

class SensorController extends Controller
{
    public function add(Request $request, $id, $deviceId)
    {
        $firestore = app('firebase.firestore');
        $firestore = $firestore->database();

        $data = $request->all();

        $document = $firestore->collection('users')->document($id)->collection('devices')->document($deviceId);
        $snapshot = $document->snapshot();
        if($snapshot->exists()){
            $addData = $document->update([
                [
                    'path' => 'sensor',
                    'value' => FieldValue::arrayUnion([$data])
                ]
            ]);
            return $addData;
        }
        else {
            return printf('Document %s does not exist!' . PHP_EOL, $snapshot->id());
        }

        // die(print_r($request));
    }
    public function sync(Request $request, $id, $deviceId)
    {
        $firestore = app('firebase.firestore');
        $firestore = $firestore->database();

        $document = $firestore->collection('users')->document($id)->collection('devices')->document($deviceId);
        $snapshot = $document->snapshot();
        if($snapshot->exists()) {
            $data = $snapshot->data();
            $dataArray = [
                'wifi_ssid' => $data['wifi_ssid'],
                'wifi_password' => $data['wifi_password'],
                'ip_address' => $data['ip_address']
            ];
            return $dataArray;
        }
        else {
            return printf('Document %s does not exist!' . PHP_EOL, $snapshot->id());
        }
    }
}
