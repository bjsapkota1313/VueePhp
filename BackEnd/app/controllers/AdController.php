<?php

namespace Controllers;

use Controllers\AbstractController;
use Models\Exceptions\FileManagementException;
use Models\Exceptions\InternalErrorException;
use Models\Exceptions\NotFoundException;
use Models\Exceptions\ObjectCreationException;
use Models\Exceptions\UnsupportedFile;
use Models\InterfaceAPIController;
use Models\Status;
use mysql_xdevapi\Exception;
use Services\AdService;
use Models\Ad;
use DateTime;
use SplFileObject;

class AdController extends AbstractController implements InterfaceAPIController
{
    private $adService;

    public function __construct()
    {
        $this->adService = new AdService();
    }

    function getAll()
    {
        $offset = null;
        $limit = null;

        if (isset($_GET["offset"]) && is_numeric($_GET["offset"])) {
            $offset = $_GET["offset"];
        }
        if (isset($_GET["limit"]) && is_numeric($_GET["limit"])) {
            $limit = $_GET["limit"];
        }
        if (isset($_GET['name'])) {
            $this->handleSearchRequest($_GET['name'], $limit, $offset);
            return;

        }
        $ads = $this->adService->getAllAvailableAds($limit, $offset);
        if (empty($ads)) {
            return $this->respondWithError(204, "No ads anymore");
        }
        $this->respond($ads);


    }

    /**
     * @throws ObjectCreationException
     */
    function getOne($id)
    {
        try {
            $ad = $this->adService->getAdByID($id);
            if (empty($ad)) {
                $this->respondWithError(404, "Ad not found");
            } else {
                $this->respond($ad);
            }
        } catch (InternalErrorException|ObjectCreationException $e) {
            $this->respondWithError(500, "Something went wrong while fetching the ad");
        }
    }

    function create()
    {
        $token = $this->checkForJwt();
        if (empty($token)) {
            return;
        }
        $adDetails = $this->sanitize(json_decode($_POST['adDetails']));
        $adDetails->userId = $token->data->id;
        $adDetails->image = $_FILES['adImage'];
        try {
            $createdAd = $this->adService->createNewAd($adDetails);
            if (!empty($createdAd)) {
                $this->respond($createdAd);
            } else {
                $this->respondWithError(500, "Please try again something went wrong while adding your error");
            }
        } catch (UnsupportedFile $e) {
            $this->respondWithError(415, $e->getMessage());
        } catch (FileManagementException|InternalErrorException $e) {
            $this->respondWithError(500, $e->getMessage());
        }
    }

    function delete($id)
    {
        $token = $this->checkForJwt();
        if (empty($token)) {
            return;
        }
        try {
            if ($this->adService->deleteAd($id)) {
                $this->respond(true);
            } else {
                $this->respondWithError(500, "Internal Error ");
            }
        } catch (InternalErrorException $e) {
            $this->respondWithError(503, "Ad not deleted");
        } catch (FileManagementException $e) {
            $this->respondWithError(500, "Something went wrong while deleting the ad");
        }
    }

    private function handleSearchRequest($name, $limit, $offset): void
    {
        $ads = $this->adService->searchAdsByProductName($name, $limit, $offset);
        if (empty($ads)) {
            $this->respondWithError(404, "No ads found with this name {$name}");
            return;
        }
        $this->respond($ads);
    }

    function update($id)
    {
        $token = $this->checkForJwt();
        if (empty($token)) {
            return;
        }
        // Retrieve the request body
        $requestBody = file_get_contents('php://input');
        parse_str($requestBody, $requestParams);
        $adDetails = $this->sanitize(json_decode($requestParams['adDetails']));
        if (!empty($requestParams['image'])) {
            $adDetails->image = $requestParams['image'];
        }
        try {

            $this->adService->editAdWithNewDetails($adDetails, $id);
            $this->respond(true);
        } catch (InternalErrorException|FileManagementException $e) {
            $this->respondWithError(500, "Something went wrong while updating the ad");
        }
    }

    public function markAsSold($id)
    {
        $token = $this->checkForJwt();
        if (empty($token)) {
            return;
        }
        try {
            $this->adService->markAdAsSold($id);
            $this->respond(true);
        } catch (Exception $e) {
            $this->respondWithError(500, "Something went wrong while updating the ad");
        }
    }


    public function getAdsByUser()
    {
        $offset = null;
        $limit = null;

        if (isset($_GET["offset"]) && is_numeric($_GET["offset"])) {
            $offset = $_GET["offset"];
        }
        if (isset($_GET["limit"]) && is_numeric($_GET["limit"])) {
            $limit = $_GET["limit"];
        }

        $token = $this->checkForJwt();
        if (empty($token)) {
            return;
        }
        try {
            $ads = $this->adService->getAdsByUser($token->data->id, $limit, $offset);
        } catch (InternalErrorException $e) {
            $this->respondWithError(500, "Something went wrong");
        }
        if (empty($ads)) {
            $this->respondWithCode(204, null);
            return;
        }
        $this->respond($ads);

    }
    public function checkOut()
    {
        $adsIds = $this->getSanitizedData();
        try {
            $this->adService->checkOut($adsIds);
            $this->respond(true);
        } catch (InternalErrorException $e) {
            $this->respondWithError(500, "Something went wrong");
        } catch (NotFoundException $e) {
            $this->respondWithError(404, $e->getMessage());
        }
    }

}