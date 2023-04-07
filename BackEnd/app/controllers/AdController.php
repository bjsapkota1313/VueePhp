<?php

namespace Controllers;

use Controllers\AbstractController;
use Models\Exceptions\FileManagementException;
use Models\Exceptions\InternalErrorException;
use Models\Exceptions\NotFoundException;
use Models\Exceptions\ObjectCreationException;
use Models\Exceptions\UnsupportedFile;
use Models\InterfaceAPIController;
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
        parse_str(file_get_contents('php://input'), $_PUT);
        $adDetails = $this->sanitize(json_decode($_PUT['adDetails']));
        try {
            $isAdUpdated = $this->adService->editAdWithNewDetails( $adDetails, $id);
            if ($isAdUpdated) {
                $this->respond(true);
            }
            $this->respondWithError(500, "Something went wrong while updating the ad");
        } catch (InternalErrorException  | FileManagementException $e) {
            $this->respondWithError(500, "Something went wrong while updating the ad");
        }

    }

    public function getAdsByUser()
    {
        $offset = NULL;
        $limit = NULL;

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

    private function getImageFileFromStringEncoded($imageData)
    {
        // Create a temporary file and write the image data to it
        $tmpFile = tmpfile();
        fwrite($tmpFile, base64_decode($imageData));

        // Get the metadata of the temporary file
        $metaData = stream_get_meta_data($tmpFile);
        $tmpFilePath = $metaData['uri'];

        // Open the temporary file for reading and return it
        return fopen($tmpFilePath, 'rb');
    }
    public function checkOut()
    {
        $adsIds= $this->getSanitizedData();
        try{
            $this->adService->checkOut($adsIds);
            $this->respond(true);
        }
        catch(InternalErrorException $e){
            $this->respondWithError(500, "Something went wrong");
        } catch (NotFoundException $e) {
            $this->respondWithError(404, $e->getMessage());
        }
    }



}