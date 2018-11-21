<?php
/**
 * Created by PhpStorm.
 * User: QuanDar
 * Date: 05/11/2018
 * Time: 13:44
 */

namespace App\Model;


interface ImageModelInterface
{
    public function getImageById($imageId);
    public function deleteImageById($imageId);
    public function postImage($image);
    public function getAllImages();
}