<?php
namespace ZipFile;

class Zipfile extends \ZipArchive
{
    /**
     * Seriously ZipArchive has no shortcut for this, you have to create a new ZipArchive and then use open().
     * @param string $name The filename for the archive. If it does not exist it will be created.
     */
    public function __construct($name = "")
    {
        if($name !== "") {
            if(file_exists($name)) {
                $this->open($name);
            } else {
                $this->open($name, parent::CREATE);
            }
        }
    }

    /**
     * Add a directory to the zip file including all of its contents.
     * @param $path string The directory to add. If $path does not exist, an empty directory will be added
     * to the zip archive.
     * @return ZipFile\ZipFile $this for method chaining.
     */
    public function addDir($path)
    {
        $dir = new \SplFileInfo($path);
        if($dir->isDir()) {
            $contents = $this->getDirectoryContents($path);
            $baseDir = $dir->getPathInfo()->getRealPath();
            // basedir is the complete path of the given path's parent
            // e.g. $baseDir for /home/user/directory is /home/user
            // this way we can remove the /home/user portion of all
            // directory contents so the zip only contains directory/file1, directory/file2 etc
        } else {
            $contents = [];
        }
        if(count($contents) === 0) {
            $this->addEmptyDir($dir->getBasename());
        } else {
            foreach($contents as $c) {
                if(is_dir($c)) {
                    // safe to do because directories will always come before their contents
                    // in the array returned by getDirectoryContents()
                    $this->addEmptyDir($dir->getBasename());
                } else {
                    $this->addFile($c, str_replace($baseDir . DIRECTORY_SEPARATOR, "", $c));
                }
            }
        }
        return $this;
    }

    /**
     * Return the full complete paths of the entire contents of a directory including all subdirectories.
     * @param $path string The path of the directory whose contents you want.
     * @return array An array of the full paths of those contents.
     */
    public function getDirectoryContents($path)
    {
        $results = [];
        try {
            $iterator = new \RecursiveDirectoryIterator($path, \FilesystemIterator::SKIP_DOTS);
            foreach($iterator as $i) {
                $results[] = $i->getRealPath();
                if($i->isDir()) {
                    $results = array_merge($results, $this->getDirectoryContents($i->getRealPath()));
                }
            }
        } catch(\UnexpectedValueException $e) {
            // $results is already an empty array so nothing to do here, we'll just return it as is.
        }
        return $results;
    }
}
