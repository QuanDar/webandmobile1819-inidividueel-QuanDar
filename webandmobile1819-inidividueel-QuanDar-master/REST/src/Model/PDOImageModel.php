<?php
/**
 * Created by PhpStorm.
 * User: QuanDar
 * Date: 05/11/2018
 * Time: 13:44
 */

namespace App\Model;


class PDOImageModel implements ImageModelInterface
{
    private $connection = null;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function getImageById($imageId)
    {
        $statement = $this->connection->getPDO()->prepare('SELECT * FROM images WHERE id=?');
        $statement->bindValue(1, $imageId);
        $statement->execute();
        $statement->bindColumn(2, $content, \PDO::PARAM_LOB);

        $result = $statement->fetch($content);
        echo '<img src="data:image/jpeg;base64,'.base64_encode( $result['content'] ).'"/>';

        return ['id' => $imageId, 'content' => base64_encode($content)];
    }

    public function deleteImageById($imageId)
    {
        $statement = $this->connection->getPDO()->prepare("DELETE FROM images WHERE id = ?");
        $statement->bindValue(1, $imageId, \PDO::PARAM_INT);
        $statement->execute();

        return $statement->rowCount() > 0;
    }

    public function postImage($image)
    {
        $statement = $this->connection->getPDO()->prepare("INSERT INTO images (content) VALUES (?)");
        $statement->bindValue(1, base64_decode($image), \PDO::PARAM_LOB);
        $statement->execute();

        return $statement->rowCount() > 0;
    }
}