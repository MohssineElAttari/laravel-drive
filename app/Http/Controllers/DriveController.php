<?php

namespace App\Http\Controllers;

use Exception;
use Facade\FlareClient\Stacktrace\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DriveController extends Controller
{
    private $drive;
    public function __construct(Google_Client $client)
    {
        $this->middleware(function ($request, $next) use ($client) {
            $client->refreshToken(Auth::user()->refresh_token);
            $this->drive = new Google_Service_Drive($client);
            return $next($request);
        });
    }

    public function getDrive()
    {
        $this->ListFolders('root');
    }

    public function ListFolders($id)
    {

        $query = "mimeType='application/vnd.google-apps.folder' and '" . $id . "' in parents and trashed=false";

        $optParams = [
            'fields' => 'files(id, name)',
            'q' => $query
        ];

        $results = $this->drive->files->listFiles($optParams);

        if (count($results->getFiles()) == 0) {
            print "No files found.\n";
        } else {
            print "Files:\n";
            foreach ($results->getFiles() as $file) {
                dump($file->getName(), $file->getID());
            }
        }
    }

    function uploadFile(Request $request)
    {
        if ($request->isMethod('GET')) {
            return view('upload');
        } else {
            $this->createFile($request->file('file'));
        }
    }

    function createStorageFile($storage_path)
    {
        $this->createFile($storage_path);
    }

    
}
