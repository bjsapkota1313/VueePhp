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
    function getImagesNameByMovingToDirectory($images, $pathToDir): array
    {
        try {
            $imageNames = [];
            foreach ($images as $key => $image) {
                $imageName = $this->getUniqueImageNameByImageName($image);
                $this->moveImageToSpecifiedDirectory($image, $pathToDir . $imageName);
                // Check if the key already exists in $imageNames
                if (isset($imageNames[$key])) {
                    // If the key exists, append the new value to the existing value in the array
                    if (is_array($imageNames[$key])) {
                        $imageNames[$key][] = $imageName;
                    } else {
                        $imageNames[$key] = [$imageNames[$key], $imageName];
                    }
                } else {
                    // If the key doesn't exist, add a new key-value pair to the array
                    $imageNames[$key] = $imageName;
                }
            }
            return $imageNames;
        } catch (Exception $exception) {
            throw new FileManagementException($exception->getMessage());
        }

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
    function renameImageFromDirectory($oldImageName, $newImageName, $directory): void
    {
        if(!rename($directory . $oldImageName, $directory . $newImageName)){
            throw new FileManagementException("File rename Failed:");
        }
    }

    /**
     * @throws FileManagementException
     */
    function editNewImageAccordanceToCurrent($currentImageName, $newImage, $directory): void
    { //delete existing Image and rename previous One
        $this->deleteImageFromDirectory($directory . $currentImageName);
        $this->moveImageToSpecifiedDirectory($newImage, $directory . $currentImageName);
    }

    /**
     * @throws FileManagementException
     */
    function makeImageFromBase64($imageData,$imageName): void
    { //imageName will come without Extension
        $imageFormat = explode(',', $imageData)[0];
        $replacingImageFormat = $imageFormat . ','; //replace the image format
        $decodedImage = base64_decode(str_replace($replacingImageFormat, '', $imageData));
        $imageExtension=explode(';', explode('/', $imageFormat)[1])[0];
        if($imageExtension != "jpg" && $imageExtension != "png" && $imageExtension != "jpeg"){
            throw new FileManagementException("Invalid Image Format");
        }
        $imageName = $imageName . '.' . $imageExtension;
        file_put_contents( $imageName, $decodedImage);
    }



}