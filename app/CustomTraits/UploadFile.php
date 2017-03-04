<?php

namespace App\CustomTraits;

use File;
use Illuminate\Http\UploadedFile;
use Log;
use Storage;

trait UploadFile
{

    /**
     * @param $directory_to_store string Subdirectory from the baseStoragePath()
     * @param $uploaded_file UploadedFile Actual instance of uploaded file i.e. $request->file('my_image');
     * @param $file_name string The name to store the file as
     */
    public function storeFile($directory_to_store,
                              $uploaded_file,
                              $file_name)
    {

        if (!$uploaded_file || !$file_name)
            return;
        // Possible problem: If subdirs don't exist, mkdir will fail
        // Possible solution: use recursive flag on mkdir() call
        // Probably best solution: Don't call storeFile on with subdirs that don't exist
        if (!file_exists($this->baseStoragePath() . $directory_to_store))
            mkdir($this->baseStoragePath() . $directory_to_store);

        Storage::put($directory_to_store . $file_name,
            File::get($uploaded_file->getRealPath()));
    }

    public function baseStoragePath()
    {
        return storage_path() . '/app/';
    }

    /**
     * @param $sub_dir string The subdirectory under the base
     * @param $file_name
     */
    public function deleteFile($sub_dir,
                               $file_name)
    {
        if (!$sub_dir || !$file_name)
            return;
        $sep = $this->separator($sub_dir);
        $path = $this->baseStoragePath() . $sub_dir . $sep . $file_name;
        Log::info($path);
        if (!file_exists($path)) {
            Log::info('Tried to delete file that didn\'t exist. Path: ' . $path);
            return;
        }
        // Storage::delete('file.jpg') doesn't work for some reason
        unlink($path);
    }

    private function separator($dir)
    {
        // check if sub_dir exists
        $len = strlen($dir);
        return substr($dir, $len - 1, $len) == '/' ? "" : "/";
    }

    /**
     * @param $sub_dir string the subdirectory under baseStoragePath()
     *                 where you would store $file_name
     * @param $file_name string name of the file that would be saved
     * @return string returns the fully qualified url to be stored in DB
     */
    public function dbStoragePath($sub_dir, $file_name)
    {
        $sep = $this->separator($sub_dir);
        return asset('app/' . $sub_dir . $sep . $file_name, env('APP_ENV') === 'production');
    }

    /**
     *
     * @param $uploaded_file \Illuminate\Http\UploadedFile An actual file object e.g. $request->file('myUploadedFile');
     * @param $directory_to_store string Directory in which $file will be stored
     * @return string a file name that is unique among all files in $this->baseStoragePath() . $dir
     */
    public function getFileName($uploaded_file, $directory_to_store)
    {
        $sep = $this->separator($directory_to_store);
        $extension = "." . $uploaded_file->getClientOriginalExtension();
        $file_name = $this->generateUniqueName() . $extension;
        while (file_exists($this->baseStoragePath() .
            $directory_to_store
            . $sep . $file_name)) {
            $file_name = $this->generateUniqueName() . $extension;
        }
        return $file_name;
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

    /**
     * @param $DB_stored_path string assumes that the pic path was stored to the
     *        database using this function: $this->dbStoragePath($sub_dir, $file_name);
     * @return string returns the file name of the uploaded file
     *         as it exists in the database
     */
    public function getFileNameFromDB($DB_stored_path)
    {
        return pathinfo($DB_stored_path)['filename'] . "." .
            pathinfo($DB_stored_path)['extension'];
    }
}