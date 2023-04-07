<?php

namespace Models;

use Models\Exceptions\FileManagementException;

trait ImageManager
{
    /**
     * @throws FileManagementException
     */
    function moveImageToSpecifiedDirectory($image, $directory): void
    {
        if (!move_uploaded_file($image['tmp_name'], $directory)) {
            throw new FileManagementException("File upload Failed");
        }
    }

    function getUniqueImageNameByImageName($image): string
    {
        $imageExtension = pathinfo($image['name'], PATHINFO_EXTENSION);
        return uniqid() . '.' . $imageExtension;
    }

    /**
     * @throws FileManagementException
     */
    function deleteImageFromDirectory($imagePath): void
    {
        if (file_exists($imagePath)) {
            if (!unlink($imagePath)) {
                throw new FileManagementException("File deletion Failed:");
            }
        } else {
            throw new FileManagementException("File deletion Failed: File does not exist");
        }
    }

    /**
     * @throws FileManagementException
     */
    function deleteImagesFromDirectory($imagesNames, $directory): void
    {
        foreach ($imagesNames as $imageName) {
            $this->deleteImageFromDirectory($directory . $imageName);
        }
    }

    function checkValidImageOrNot($image): bool
    {
        $imageFileType = strtolower(pathinfo($image['name'], PATHINFO_EXTENSION));
        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
            return false;
        }
        return true;
    }

    /**
     * @throws FileManagementException
     */
    function makeImageFromBase64($imageData, $path): string
    { //imageName will come without Extension
        $imageFormat = explode(',', $imageData)[0];
        $replacingImageFormat = $imageFormat . ','; //replace the image format
        $decodedImage = base64_decode(str_replace($replacingImageFormat, '', $imageData));
        $imageExtension = explode(';', explode('/', $imageFormat)[1])[0];
        if ($imageExtension != "jpg" && $imageExtension != "png" && $imageExtension != "jpeg") {
            throw new FileManagementException("Invalid Image Format");
        }
        $imageName = '/img/'.uniqid() . '.' . $imageExtension;
        file_put_contents($path.$imageName, $decodedImage);
        return $imageName;
    }


}