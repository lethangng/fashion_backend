<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;

class UploadController extends Controller
{
    private $storage;

    public function __construct()
    {
        $this->storage = app('firebase.storage');
    }

    public function getImage($name)
    {
        $expiresAt = new \DateTime('tomorrow');
        $imageReference = $this->storage->getBucket()->object($name);

        if ($imageReference->exists()) {
            $image_url = $imageReference->signedUrl($expiresAt);
        } else {
            $image_url = null;
        }

        return $image_url;
    }

    public function upload($file, $path)
    {
        $extension = $file->getClientOriginalExtension();
        $name = Str::before($file->getClientOriginalName(), '.') . '-' . time();
        $file_name = $path . $name . '.' . $extension;

        $storageRef = $this->storage->getBucket()->upload(file_get_contents($file), ['name' => $file_name]);
        // $fileInfo = $storageRef->info();
        return $file_name;
    }

    public function destroy($name)
    {
        $imageDeleted = $this->storage->getBucket()->object("images/" + $name)->delete();
        return back()->withInput();
    }
}
