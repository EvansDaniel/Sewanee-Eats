<?php

namespace App\CustomTraits;

use File;
use Illuminate\Http\UploadedFile;
use Log;
use Storage;

trait UploadFile
{

    use GeneratesUniqueId;
    /**
     * @param $directory_to_store string Subdirectory from the baseStoragePath()
     * @param $uploaded_file UploadedFile Actual instance of uploaded file i.e. $request->file('my_image');
     * @param $file_name string The name to store the file as
     */
    public function storeFile(string $directory_to_store,
                              UploadedFile $uploaded_file,
                              string $file_name)
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
    public function deleteFile(string $sub_dir,
                               string $file_name)
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

    private function separator(string $dir)
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
    public function dbStoragePath(string $sub_dir, string $file_name)
    {
        $sep = $this->separator($sub_dir);
        return asset('app/' . $sub_dir . $sep . $file_name, env('APP_ENV') != 'local');
    }

    /**
     *
     * @param $uploaded_file \Illuminate\Http\UploadedFile An actual file object e.g. $request->file('myUploadedFile');
     * @param $directory_to_store string Directory in which $file will be stored
     * @return string a file name that is unique among all files in $this->baseStoragePath() . $dir
     */
    public function getFileName(UploadedFile $uploaded_file, string $directory_to_store)
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