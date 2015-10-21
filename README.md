## php-zipfile

An extension to PHP's `ZipArchive` class to make adding directories less of a pain.
Requires `ZipArchive` to be installed, obviously.

#### Usage:

This class just provides the ability to pass a filename to the constructor and a method for adding directories
recursively (conveniently titled `addDir()`). Otherwise it's identical to `ZipArchive`.

    $zip = new \ZipFile('filename.zip');
    $zip->addDir('/path/to/dir/');
    $zip->close();

#### License

I don't care, do whatever you want. I'm not responsible for anything ever. The end.
