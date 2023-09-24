<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

// use Google\Cloud\Firestore\FirestoreClient;

class MessagingController extends Controller
{
    function index(Request $request) {

        // $projectId = 'laytoapp-9cea5';
        // if (empty($projectId)) {
        //     // The `projectId` parameter is optional and represents which project the
        //     // client will act on behalf of. If not supplied, the client falls back to
        //     // the default project inferred from the environment.
        //     $db = new FirestoreClient();
        //     printf('Created Cloud Firestore client with default project ID.' . PHP_EOL);
        // } else {
        //     $db = new FirestoreClient([
        //         'projectId' => $projectId,
        //     ]);
        //     printf('Created Cloud Firestore client with project ID: %s' . PHP_EOL, $projectId);
        // }

        // dd(11);
        return view('messaging.index');
    }
}
