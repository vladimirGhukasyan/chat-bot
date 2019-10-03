<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class VideoUploadController extends Controller
{
    public function upload()
    {
        return view('videoUpload');
    }

    public function uploadPost()
    {
        request()->validate([
            'video' => 'required|mimes:mp4|max:4096',
        ]);

        $videoName = time().'.'.request()->video->getClientOriginalExtension();
        request()->video->move(public_path('videos'), $videoName);

        return response()->json(
            [
                "path" => 'videos/'.$videoName
            ]
        );
    }
}
