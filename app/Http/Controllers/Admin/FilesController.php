<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FilesController extends Controller
{

    public function uploadFile(Request $request)
    {

        $file = $request->file('file');

        $name = sha1(rand()) . '.' . $file->extension();

        $file->move(
            storage_path('app/public'),
            $name
        );

        return '/storage/' . $name;

    }

}
