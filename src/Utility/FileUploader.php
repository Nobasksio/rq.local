<?php
/**
 * Created by PhpStorm.
 * User: artur
 * Date: 20/06/2019
 * Time: 12:42
 */

namespace App\Utility;



use Intervention\Image\ImageManager;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileUploader
{
    private $targetDirectory;

    public function __construct($targetDirectory)
    {
        $this->targetDirectory = $targetDirectory;
    }

    public function upload(UploadedFile $file)
    {
        $fileName = md5(uniqid()).'.'.$file->guessExtension();

        try {
            $file->move($this->getTargetDirectory(), $fileName);
        } catch (FileException $e) {
            // ... handle exception if something happens during file upload
        }

        $manager = new ImageManager(array('driver' => 'Gd'));
        $image = $manager->make($this->getTargetDirectory()."/".$fileName)->widen(300);

        $fileNamePreview = md5(uniqid()).'.'.$image->extension;
        try {
            $image->save($this->getTargetDirectory().'/preview/'.$fileNamePreview);

        } catch (FileException $e) {
            // ... handle exception if something happens during file upload
        }

        //$this->createPreview($this->getTargetDirectory()."/".$fileName);

        return ['img' => $fileName,
            'preview' => $fileNamePreview];
    }

    public function getTargetDirectory()
    {
        return $this->targetDirectory;
    }

    public function createPreview($filename){
        // файл

        // задание максимальной ширины и высоты
        $width = 200;
        $height = 200;

        // тип содержимого

        // получение новых размеров
        list($width_orig, $height_orig) = getimagesize($filename);

        $ratio_orig = $width_orig/$height_orig;

        if ($width/$height > $ratio_orig) {
            $width = $height*$ratio_orig;
        } else {
            $height = $width/$ratio_orig;
        }

        // ресэмплирование
        $image_p = imagecreatetruecolor($width, $height);
        $image = imagecreatefromjpeg($filename);
        imagecopyresampled($image_p, $image, 0, 0, 0, 0, $width, $height, $width_orig, $height_orig);

        // вывод
        imagejpeg($image_p, null, 100);
    }
}