<?php

namespace Repositories;

use Models\Ad;
use Models\Exceptions\FileManagementException;
use Models\Exceptions\InternalErrorException;
use Models\Exceptions\ObjectCreationException;
use Models\Status;
use Models\User;
use Repositories\AbstractRepository;
use Services\UserService;
use DateTime;
use Exception;


class AdRepository extends AbstractRepository
{
    private $userService;

    public function __construct()
    {
        parent::__construct();
        $this->userService = new userService();
    }

    /**
     * @throws Exception
     */
    public function getAllAdsByStatus(Status $status, $limit = NULL, $offset = NULL): ?array
    {
        $query = "SELECT id,productName,description,postedDate,price,imageURI,userID,status From Ads 
                                    WHERE status= :status ORDER BY postedDate DESC "; // latest post
        $parameters = [":status" => Status::getLabel($status)];
        if (!empty($this->buildLimitOffsetClause($limit, $offset))) {
            $query .= " LIMIT :limit OFFSET :offset";
            $parameters[":limit"] = (int)$limit;
            $parameters[":offset"] = (int)$offset;
        }
        $dbResult = $this->executeQueryAndGetResult($query, $parameters);
        if (!empty($dbResult)) {
            $ads = array();
            foreach ($dbResult as $ad) {
                $ads[] = $this->makeAnAD($ad);
            }
            return $ads;
        }
        return null;
    }

    /**
     * @throws ObjectCreationException
     */
    public function getAdByID($adId): ?Ad
    {
        $query = "SELECT id,productName,description,postedDate,price,imageURI,userID,status From Ads WHERE id= :adId";
        $result = $this->executeQueryAndGetResult($query, [":adId" => $adId], false);
        if (!empty($result)) {
            return $this->makeAnAD($result);
        }
        return null;
    }

    /**
     * @throws ObjectCreationException
     */
    public function getAdsByUser($userId, $limit = null, $offset = null): ?array
    {
        $query = "SELECT id,productName,description,postedDate,price,imageURI,userID,status From Ads 
                                WHERE UserID= :userID ORDER BY postedDate DESC"; // latest post
        $parameters = [":userID" => $userId];
        if (!empty($this->buildLimitOffsetClause($limit, $offset))) {
            $query .= " LIMIT :limit OFFSET :offset ";
            $parameters[] = [":limit" => $limit, ":offset" => $offset];
        }
        $result = $this->executeQueryAndGetResult($query, $parameters);
        if (!empty($result)) {
            $ads = array();
            foreach ($result as $row) {
                $ads[] = $this->makeAnAD($row);
            }
            return $ads;
        }
        return null;
    }

    public function updateStatusOfAd($status, $adID)
    {
        $query = "UPDATE Ads SET status= :status WHERE id= :adId";
        return $this->executeQueryAndGetResult($query, [":status" => Status::getLabel($status), ":adId" => $adID]);
        // it is update query so it will return true or false
    }

    public function deleteAd($adID): bool
    {
        $query = "DELETE FROM Ads  WHERE id= :adId";
        return $this->executeQueryAndGetResult($query, [":adId" => $adID]); // it is update query so it will return true or false
    }

    /**
     * @throws ObjectCreationException
     */
    private function makeAnAd($dBRow): Ad
    {
        try {
            $ad = new Ad();
            $ad->setId($dBRow["id"]);
            $ad->setDescription($dBRow["description"]);
            $ad->setPostedDate(new DateTime($dBRow["postedDate"]));
            $ad->setPrice($dBRow["price"]);
            $ad->setProductName($dBRow["productName"]);
            $ad->setImageUri($dBRow["imageURI"]);
            $ad->setStatus(Status::fromString($dBRow["status"]));
            $ad->setUser($this->userService->getUserById($dBRow["userID"]));
            return $ad;
        } catch (Exception) {
            throw  new  ObjectCreationException("Something went wrong while creating an ad object");
        }

    }

    /**
     * @throws InternalErrorException
     */
    public function createNewAd($ad): ?Ad
    {
        $query = "INSERT INTO Ads( productName, description,  price, userID, imageURI) VALUES (:productName,:description,:price,:userID,:imageURI)";
        $parameters = [
            ":productName" => $ad->productName,
            ":description" => $ad->description,
            ":price" => $ad->price,
            ":userID" => $ad->userId,
            ":imageURI" => $ad->imageURI
        ];
        $result = $this->executeQueryAndGetResult($query, $parameters, false, true);
        if (is_numeric($result)) { // it will return the last inserted id
            return $this->getAdByID($result);
        } else {
            throw new InternalErrorException("Something went wrong while retrieving an inserted Ad");
        }

    }

    public function getCurrentImageUriByAdId($adId)
    {
        $query = "SELECT imageURI FROM Ads WHERE id= :adId";
        $result = $this->executeQueryAndGetResult($query, [":adId" => $adId], false);
        // Statement returned exactly one row
        if (!empty($result)) {
            return $result['imageURI'];
        }
        return null;
    }

    /**
     * @throws InternalErrorException|FileManagementException
     */
    public function editAd($newImage, $productName, $description, $price, $adID)
    {
        $dbStoredName;
        if (!isset($dbStoredName)) {
            $dbStoredName = $this->getCurrentImageUriByAdId($adID);
        }
        $storingImageUri = $this->editImageFile($dbStoredName, $newImage);
        if (is_null($storingImageUri)) {
            throw  new FileManagementException("No Image file Found For previous Image");
        }
        $query = "UPDATE Ads SET productName = :productName ,description = :description ,price = :price ,imageURI =:imageURI WHERE id = :id";
        $parameters = [
            ":productName" => $productName,
            ":description" => $description,
            ":price" => $price,
            ":id" => $adID,
            ":imageURI" => $storingImageUri
        ];
        $result = $this->executeQueryAndGetResult($query, $parameters); // it is update query so it will return true or false
        return is_bool($result) ? $result : throw  new InternalErrorException("Something went wrong  in App while updating ad");
    }

    /**
     * @throws ObjectCreationException
     */
    public function searchAdsByProductName($productName, $limit, $offset): ?array
    {
        $query = "SELECT id,productName,description,postedDate,price,imageURI,userID,status FROM Ads
                                                        WHERE `productName` LIKE :productName AND status =:status";
        $parameters = [":productName" => '%' . $productName . '%', ":status"=> Status::getLabel(Status::Available())];
        $dbResult = $this->executeQueryAndGetResult($query, $parameters);
        if (!empty($dbResult)) {
            $ads = array();
            foreach ($dbResult as $row) {
                $ads[] = $this->makeAnAd($row);
            }
            return $ads;
        }
        return null;
    }

    /**
     * @throws FileManagementException
     */
    private function editImageFile($dbStoredImageName, $newImage): ?string
    {
        try {
            $imageTempName = $newImage['tmp_name'];
            $newImageName = $newImage['name'];
            $newImageArray = explode('.', $newImageName);
            $newImageExtension = end($newImageArray);
            $storedImageName = explode('.', $dbStoredImageName);
            $dbStoredNameWithoutExtension = reset($storedImageName);
            $targetDirectory = __DIR__ . '/../public';
            if ($this->deleteImageFile($dbStoredImageName)) {
                // deleting the file and renaming the new received image and returning it
                $newFileName = $dbStoredNameWithoutExtension . '.' . $newImageExtension;
                if (!move_uploaded_file($imageTempName, $targetDirectory . $newFileName)) {
                    throw new FileManagementException("error occurred while moving file ");
                };
                return $newFileName;
            }
            throw  new FileManagementException("error occurred while deleting old  file ");
        } catch (Exception $e) {
            throw new FileManagementException("error occurred while editing file ");
        }
    }

    private function buildLimitOffsetClause($limit, $offset): ?array
    {
        if (isset($limit) && isset($offset)) {
            return [
                'query' => 'LIMIT :limit OFFSET :offset',
                'parameters' => [':limit' => $limit, ':offset' => $offset]
            ];
        }
        return null;
    }


}