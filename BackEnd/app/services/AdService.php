<?php

namespace Services;

use Models\Ad;
use Models\Exceptions\FileManagementException;
use Models\Exceptions\InternalErrorException;
use Models\Exceptions\ObjectCreationException;
use Models\Status;
use Repositories\AdRepository;
use Models\ImageManager;

class AdService
{
    private AdRepository $adRepository;
    use ImageManager;
    public function __construct()
    {
        $this->adRepository = new AdRepository();
    }
    public function getAllAdsByStatus(Status $status,$offset = NULL, $limit = NULL)
    {
        return $this->adRepository->getAllAdsByStatus($status,$offset, $limit );
    }
    public function searchAdsByProductName($productName, $offset = NULL, $limit = NULL)
    {
        return $this->adRepository->searchAdsByProductName($productName, $offset, $limit);
    }
    public function getAdByID($adId)
    {
        return $this->adRepository->getAdByID($adId);
    }
    public function getAllAvailableAds($offset = NULL, $limit = NULL): ?array
    {
        return $this->getAllAdsByStatus(Status::Available(),$offset, $limit);
    }

    /**
     * @throws ObjectCreationException
     */
    public function getAdsByUser($userId,$limit,$offset): ?array
    {
        return $this->adRepository->getAdsByUser($userId,$limit,$offset);
    }

    /**
     * @throws InternalErrorException|FileManagementException
     */
    public function createNewAd($adDetails) : ?Ad
    {
        if(!empty($adDetails->image)){
            $adDetails->imageURI = $this->saveImage($adDetails->image);
        }
        print_r($adDetails);
       // return $this->adRepository->createNewAd($adDetails);
    }
    public function updateStatusOfAd($status, $adID)
    {
        $this->adRepository->updateStatusOfAd($status, $adID);
    }

    /**
     * @throws FileManagementException
     */
    public function deleteAd($adID): bool
    {
         return $this->adRepository->deleteAd($adID );
    }
    public function markAdAsSold($adId)
    {
        //Marking as sold as did not want to show the status of ad passing as string from javascript
        $this->updateStatusOfAd(status::Sold(), $adId);
    }
    public function editAdWithNewDetails($newImage, $productName, $description, $price, $adID)
    {
        $this->adRepository->editAd($newImage, $productName, $description, $price, $adID);
    }

    /**
     * @throws FileManagementException
     */
    private function saveImage($image): string
    {
        $uniqueName = $this->getUniqueImageNameByImageName($image);
        $image['name']=$uniqueName;
        $this->moveImageToSpecifiedDirectory($image, __DIR__ . "/../public/img/");
        return $uniqueName;
    }

}