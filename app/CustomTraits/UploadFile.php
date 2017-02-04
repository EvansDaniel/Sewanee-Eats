<?php

namespace App\CustomTraits;

use File;
use Storage;

trait UploadFile
{

    public function storeFile($directory_to_store,
                              $uploaded_file,
                              $file_name)
    {

        if (!$uploaded_file || !$file_name)
            return;
        if (!file_exists($directory_to_store))
            mkdir($directory_to_store);

        Storage::put($directory_to_store . $file_name,
            File::get($uploaded_file->getRealPath()));
    }

    /**
     * @param $sub_dir string the subdirectory under storage_path()/app/public/
     *                 where you would store $file_name
     * @param $file_name string name of the file that would be saved
     * @return string returns the fully qualified url to be stored in DB
     */
    public function dbStoragePath($sub_dir, $file_name)
    {
        // check if sub_dir exists
        $len = strlen($sub_dir);
        $sep = substr($sub_dir, $len - 1, $len) == '/' ? "" : "/";
        return asset('storage/' . $sub_dir . $sep . $file_name);
    }

    public function deleteFile($file_to_delete)
    {
        if (!$file_to_delete || !file_exists($file_to_delete))
            return;
        Storage::delete($file_to_delete);
    }

    /**
     *
     * @param $file \Illuminate\Http\UploadedFile An actual file object e.g. $request->file('myUploadedFile');
     * @param $directory_to_store string Directory in which $file will be stored
     * @return string a file name that is unique among all files in $this->getStorageDir() . $dir
     */
    public function getFileName($file, $directory_to_store)
    {
        $extension = "." . $file->getClientOriginalExtension();
        $fileName = $this->generateUniqueName() . $extension;
        while (file_exists($directory_to_store . $file)) {
            $fileName = $this->generateUniqueName() . $extension;
        }
        return $fileName;
    }

    private function generateUniqueName()
    {
        $parts = explode('.', uniqid('', true));

        $id = str_pad(base_convert($parts[0], 16, 2), 56, mt_rand(0, 1), STR_PAD_LEFT)
            . str_pad(base_convert($parts[1], 10, 2), 32, mt_rand(0, 1), STR_PAD_LEFT);
        $id = str_pad($id, strlen($id) + (8 - (strlen($id) % 8)), mt_rand(0, 1), STR_PAD_BOTH);

        $chunks = str_split($id, 8);

        $id = array();
        foreach ($chunks as $key => $chunk) {
            if ($key & 1) {  // odd
                array_unshift($id, $chunk);
            } else {         // even
                array_push($id, $chunk);
            }
        }

        // add random seeds
        $prefix = str_pad(base_convert(mt_rand(), 10, 36), 6, self::_nextChar(), STR_PAD_BOTH);
        $id = str_pad(base_convert(implode($id), 2, 36), 19, self::_nextChar(), STR_PAD_BOTH);
        $suffix = str_pad(base_convert(mt_rand(), 10, 36), 6, self::_nextChar(), STR_PAD_BOTH);

        return substr($prefix . self::_nextChar() . $id . $suffix, 0, 7);
    }

    private function _nextChar()
    {
        return base_convert(mt_rand(0, 35), 10, 36);
    }
}