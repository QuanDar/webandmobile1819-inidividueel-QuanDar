<?php
/**
 * Created by PhpStorm.
 * User: QuanDar
 * Date: 05/11/2018
 * Time: 13:54
 */

namespace App\Controller;
use App\Model\ImageModelInterface;
use Doctrine\ORM\EntityNotFoundException;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\View\View;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use FOS\RestBundle\Controller\Annotations as Rest;
use App\Exception\IllegalArgumentExceptions;

/**
 * Class ImageController
 * @package App\Controller
 * Er wordt gebruik gemaakt van FOSRestController. Dit is een beter alternatief voor werken met JSON
 * dan de standaard symfony controller.
 * https://www.thinktocode.com/2018/03/26/symfony-4-rest-api-part-1-fosrestbundle/
 */

class ImageController extends FOSRestController
{
    /**
     * @var ImageModelInterface
     */
    private $imageModel;

    /**
     * ImageController constructor.
     * @param ImageModelInterface $imageModel
     */
    public function __construct(ImageModelInterface $imageModel)
    {
        $this->imageModel = $imageModel;
    }

    /**
     * @Rest\Get("/api/images")
     */
    public function getAllImages() : View
    {
        $images = null;
        try {
            $images = $this->imageModel->getAllImages();
        } catch (IllegalArgumentExceptions $exception) {
            return View::create($exception->getMessage(), Response::HTTP_BAD_REQUEST);
        } catch (EntityNotFoundException $exception){
            return View::create($exception->getMessage(), Response::HTTP_NO_CONTENT);
        }
        return View::create($images, Response::HTTP_OK);
    }

    /**
     * @Rest\Get("/api/image/{imageId}")
     */
    public function getImageById(int $imageId) : View
    {
        $image = null;
        try {
            $image = $this->imageModel->getImageById($imageId);
        } catch (IllegalArgumentExceptions $exception) {
            return View::create($exception->getMessage(), Response::HTTP_BAD_REQUEST);
        } catch (EntityNotFoundException $exception){
            return View::create($exception->getMessage(), Response::HTTP_NO_CONTENT);
        }
        return View::create($image, Response::HTTP_OK);
    }

    /**
     * @Rest\Post("/api/image/post")
     * @param Request $request
     * @return View
     */
    public function postImage(Request $request) : View
    {
        $image = $request->get("content");
        try {
            $returnedImage = $this->imageModel->postImage($image);
        } catch (IllegalArgumentExceptions $exception) {
            return View::create($exception->getMessage(), Response::HTTP_BAD_REQUEST);
        }
        return View::create($returnedImage, Response::HTTP_CREATED);
    }

    /**
     * Removes the Article resource
     * @Rest\Delete("api/image/delete/{imageId}")
     */
    public function deleteImage(int $imageId) : View
    {
        try {
            $this->imageModel->deleteImageById($imageId);
        } catch (IllegalArgumentExceptions $exception) {
            return View::create($exception->getMessage(), Response::HTTP_BAD_REQUEST);
        }
        return View::create([], Response::HTTP_ACCEPTED);
    }
}