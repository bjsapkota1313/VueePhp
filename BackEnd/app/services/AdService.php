<?php

namespace Services;

use Models\Ad;
use Models\Exceptions\FileManagementException;
use Models\Exceptions\InternalErrorException;
use Models\Exceptions\NotFoundException;
use Models\Exceptions\ObjectCreationException;
use Models\Exceptions\UnsupportedFile;
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

    public function getAllAdsByStatus(Status $status, $limit = NULL, $offset = NULL): ?array
    {
        return $this->adRepository->getAllAdsByStatus($status, $limit, $offset);
    }

    public function searchAdsByProductName($productName, $limit = NULL, $offset = NULL)
    {
        return $this->adRepository->searchAdsByProductName($productName, $limit, $offset = NULL);
    }

    /**
     * @throws ObjectCreationException
     */
    public function getAdByID($adId): ?Ad
    {
        return $this->adRepository->getAdByID($adId);
    }

    public function getAllAvailableAds($limit = NULL, $offset = NULL,): ?array
    {
        return $this->getAllAdsByStatus(Status::Available(), $limit, $offset);
    }

    /**
     * @throws ObjectCreationException
     */
    public function getAdsByUser($userId, $limit, $offset): ?array
    {
        return $this->adRepository->getAdsByUser($userId, $limit, $offset);
    }

    /**
     * @throws InternalErrorException|FileManagementException
     */
    public function createNewAd($adDetails): ?Ad
    {
        if (!empty($adDetails->image)) {
            $adDetails->imageURI = $this->saveImage($adDetails->image);
        }
        return $this->adRepository->createNewAd($adDetails);
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
        $this->deleteAdImage($adID);
        return $this->adRepository->deleteAd($adID);
    }

    public function markAdAsSold($adId): void
    {
        //Marking as sold as did not want to show the status of ad passing as string from javascript
        $this->updateStatusOfAd(status::Sold(), $adId);
    }

    /**
     * @throws FileManagementException
     * @throws InternalErrorException
     */
    public function editAdWithNewDetails($adDetails, $adId): bool
    {
        return $this->adRepository->editAd($adDetails, $adId);
    }

    /**
     * @throws FileManagementException
     */
    private function deleteAdImage($adID): void
    {
        $presentAdImage = $this->adRepository->getCurrentImageUriByAdId($adID);
        if (!empty($presentAdImage)) {
            $this->deleteImageFromDirectory(__DIR__ . "/../public" . $presentAdImage);
        }

    }

    /**
     * @throws FileManagementException
     */
    private function saveImage($image): string
    {
        if ($this->checkValidImageOrNot($image) === false) {
            throw new UnsupportedFile("Invalid Image File");
        }
        $uniqueName = $this->getUniqueImageNameByImageName($image);
        $this->moveImageToSpecifiedDirectory($image, __DIR__ . "/../public/img/" . $uniqueName);
        return "/img/" . $uniqueName;
    }

    /**
     * @throws ObjectCreationException
     * @throws NotFoundException
     */
    private function checkAvailabilityOfAds($adIds):void
    {
        foreach ($adIds as $adId){
            $dbAd = $this->getAdByID($adId);
            if (empty($dbAd)) {
                throw new NotFoundException("Ad is does not exist Anymore");
            }
            if(!$dbAd->getStatus()->equals(Status::Available())){
                throw new NotFoundException("Ad is already Sold ");
            }
        }
    }

    /**
     * @throws NotFoundException
     * @throws ObjectCreationException
     */
    public function checkOut($adIds): void
    {
        $this->checkAvailabilityOfAds($adIds);
        foreach ($adIds as $adId){ // when no error is thrown then mark all ads as sold
            $this->markAdAsSold($adId);
        }
    }

}