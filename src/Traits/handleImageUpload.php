<?php

namespace App\Traits;

use App\Core\{ ValidationMessage, Notifier };

trait handleImageUpload
{
    private function handleImageUpload(int $userId, array &$imageData, string $subfolder = ''){

        $columnName = array_key_first($imageData);
        switch ($columnName) {
            case 'background_image':
                $targetWidth = 1584;
                $targetHeight = 396;
                break;
            case 'profile_image':
                $targetWidth = 600;
                $targetHeight = 600;
                break;
            default:
                $targetWidth = 1080;
                $targetHeight = 1350;
                break;
        }
        
        $uploadDir = __DIR__ . '/../../public/uploads/users/user_' . $userId . '/' . $subfolder . '/';
        if (!$subfolder) {
            $uploadDir = __DIR__ . '/../../public/uploads/users/user_' . $userId . '/';
        }
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
 
        $originalName = basename($imageData[$columnName]['name']);
        $tmpPath = $imageData[$columnName]['tmp_name'];
        $ext = pathinfo($originalName, PATHINFO_EXTENSION);
        
        $filePrefix = match($columnName) {
            'profile_image'     => 'profile',
            'background_image'  => 'background',
            'file'              => uniqid('post_'),
            default             => uniqid('file_'),
        };
        $fileName = $filePrefix . '.' . $ext;
        $destination = $uploadDir . $fileName;
      
        if (move_uploaded_file($tmpPath, $destination)) {
            $imageData[$columnName] = $fileName;

            $this->resizeImage($destination, $targetWidth, $targetHeight);
        } else {
            Notifier::add('error', "Error al subir el archivo: $columnName");
        }
    }

    private function handleImageDelete(int $userId, string $fileName, string $subfolder = '')
    {
        if (empty($fileName)) return;
        
        $uploadDir = __DIR__ . '/../../public/uploads/users/user_' . $userId . '/' . $subfolder . '/';
        if (!$subfolder) {
            $uploadDir = __DIR__ . '/../../public/uploads/users/user_' . $userId . '/';
        }
        $filePath = $uploadDir . $fileName;

        if (file_exists($filePath)) {
            unlink($filePath);
        }
    }

    private function resizeImage(string $path, int $targetWidth, int $targetHeight): bool
    {
        $imageInfo = getimagesize($path);
        if (!$imageInfo) return false;

        list($width, $height, $type) = $imageInfo;

        switch ($type) {
            case IMAGETYPE_JPEG: $srcImage = imagecreatefromjpeg($path); break;
            case IMAGETYPE_PNG: $srcImage = imagecreatefrompng($path); break;
            case IMAGETYPE_WEBP: $srcImage = imagecreatefromwebp($path); break;
            default: return false;
        }

        $srcAspect = $width / $height;
        $targetAspect = $targetWidth / $targetHeight;

        if ($srcAspect > $targetAspect) {
            $newHeight = $height;
            $newWidth = (int)($height * $targetAspect);
            $srcX = (int)(($width - $newWidth) / 2);
            $srcY = 0;
        } else {
            $newWidth = $width;
            $newHeight = (int)($width / $targetAspect);
            $srcX = 0;
            $srcY = (int)(($height - $newHeight) / 2);
        }

        $dstImage = imagecreatetruecolor($targetWidth, $targetHeight);
        imagecopyresampled(
            $dstImage, $srcImage,
            0, 0, $srcX, $srcY,
            $targetWidth, $targetHeight,
            $newWidth, $newHeight
        );

        switch ($type) {
            case IMAGETYPE_JPEG: imagejpeg($dstImage, $path, 90); break;
            case IMAGETYPE_PNG: imagepng($dstImage, $path); break;
            case IMAGETYPE_WEBP: imagewebp($dstImage, $path, 90); break;
        }

        imagedestroy($srcImage);
        imagedestroy($dstImage);

        return true;
    }
}
