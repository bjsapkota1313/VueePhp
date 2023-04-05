<?php

namespace Controllers;

use Controllers\AbstractController;
use Models\Exceptions\FileManagementException;
use Models\Exceptions\InternalErrorException;
use Models\Exceptions\ObjectCreationException;
use Models\Exceptions\UnsupportedFile;
use Models\InterfaceAPIController;
use mysql_xdevapi\Exception;
use Services\AdService;
use Models\Ad;
use DateTime;

class AdController extends AbstractController implements InterfaceAPIController
{
    private $adService;

    public function __construct()
    {
        $this->adService = new AdService();
    }

    function getAll()
    {
        $offset = NULL;
        $limit = NULL;

        if (isset($_GET["offset"]) && is_numeric($_GET["offset"])) {
            $offset = $_GET["offset"];
        }
        if (isset($_GET["limit"]) && is_numeric($_GET["limit"])) {
            $limit = $_GET["limit"];
        }
        if (isset($_GET['name'])) {
            return $this->handleSearchRequest($_GET['name'], $limit, $offset);
        }
        $ads = $this->adService->getAllAvailableAds( $limit, $offset);
        if (empty($ads)) {
            return $this->respondWithError(404, "No ads found");
        }
        $this->respond($ads);


    }

    /**
     * @throws ObjectCreationException
     */
    function getOne($id)
    {
        try{
            $ad = $this->adService->getAdByID($id);
            if (empty($ad)) {
                $this->respondWithError(404, "Ad not found");
            } else {
                $this->respond($ad);
            }
        }catch (InternalErrorException | ObjectCreationException $e){
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
        $adImage = $_FILES['adImage'];
        $adDetails->userId = $token->data->id;
        $adDetails->image = $adImage;
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
                $this->respondWithError(500, "Ad not deleted");
            }
        } catch (InternalErrorException $e) {
            $this->respondWithError(503, "Ad not deleted");
        } catch (FileManagementException $e) {
            $this->respondWithError(500, "Something went wrong while deleting the ad");
        }
    }

    private function handleSearchRequest($name, $limit, $offset): ?array
    {
        $ads = $this->adService->searchAdsByProductName($name, $limit, $offset);
        if (empty($ads)) {
            return $this->respondWithError(404, "No ads found with this name {$name}");
        }
        return $this->respond($ads);
    }

    function update($id)
    {
        $token = $this->checkForJwt();
        if (empty($token)) {
            return;
        }
        if(empty($adDetails->status)){

        }
        echo print_r($adDetails);
//        $adDetails = $this->sanitize($_POST['adDetails']);
//        $adImage = $_FILES['adImage'];
//        echo print_r($adDetails);
    }

    /**
     * @throws ObjectCreationException
     */
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
            $ads = $this->adService->getAdsByUser($token->data->id, $offset, $limit);
        } catch (InternalErrorException $e) {
            $this->respondWithError(500, "Something went wrong");
        }
        if (empty($ads)) {
            $this->respondWithCode(204, null);
        } else {
            $this->respond($ads);
        }
    }
}