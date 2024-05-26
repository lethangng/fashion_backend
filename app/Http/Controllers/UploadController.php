<?php

namespace App\Http\Controllers;

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
        $name = 'fashion-image' . '-' . time();
        $file_name = $path . $name . '.' . $extension;

        $this->storage->getBucket()->upload(file_get_contents($file), ['name' => $file_name]);
        // $storageRef = $this->storage->getBucket()->upload(file_get_contents($file), ['name' => $file_name]);
        // $fileInfo = $storageRef->info();
        return $file_name;
    }

    public function destroy($name)
    {
        $this->storage->getBucket()->object("images/" + $name)->delete();
        // $imageDeleted = $this->storage->getBucket()->object("images/" + $name)->delete();
        return back()->withInput();
    }
}
