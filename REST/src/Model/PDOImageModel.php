<?php
/**
 * Created by PhpStorm.
 * User: QuanDar
 * Date: 05/11/2018
 * Time: 13:44
 */

namespace App\Model;
// http://respect.github.io/Validation/docs/
use App\Exception\IllegalArgumentExceptions;
use Doctrine\ORM\EntityNotFoundException;
use Respect\Validation\Validator as v;
use Respect\Validation\Exceptions\ValidationException;

class PDOImageModel implements ImageModelInterface
{
    private $connection = null;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function getImageById($imageId)
    {
        $imageValidator = v::numeric()->validate($imageId);
        if (!$imageValidator){
            throw new IllegalArgumentExceptions('Fill a numeric image id in: ' . $imageValidator);
        }

        $statement = $this->connection->getPDO()->prepare('SELECT * FROM images WHERE id=:id');
        $statement->bindValue(':id', $imageId);
        $statement->execute();
        $statement->bindColumn('content', $content, \PDO::PARAM_LOB);

        $image = null;
        if($statement->fetch(\PDO::FETCH_BOUND))
        {
            $image = ['content' => base64_encode($content)];
        }
        echo '<img src="data:image/jpeg;base64,'.( $image['content'] ).'"/>';

        return $image;
    }

    public function deleteImageById($imageId)
    {
        $imageValidator = v::numeric()->validate($imageId);
        if (!$imageValidator){
            throw new IllegalArgumentExceptions('Fill a numeric image id in: ' . $imageValidator);
        }

        $statement = $this->connection->getPDO()->prepare("DELETE FROM images WHERE id = ?");
        $statement->bindValue('id', $imageId, \PDO::PARAM_INT);
        $statement->execute();

        return $statement->rowCount() > 0;
    }

    public function postImage($image)
    {
        $imageValidatorNull = v::nullType()->validate($image);
        $imageValidatorEmpty = v::stringType()->notEmpty()->validate($image);

        if ($imageValidatorNull){
            throw new IllegalArgumentExceptions('Image content can not be null ' );
        }
        if (!$imageValidatorEmpty){
            throw new IllegalArgumentExceptions('Image content can not be empty ' );
        }

        $statement = $this->connection->getPDO()->prepare("INSERT INTO images (content) VALUES (?)");
        $statement->bindValue(1, base64_decode($image), \PDO::PARAM_LOB);
        $statement->execute();


        $id = $this->connection->getPDO()->lastInsertId();
        $statementLastImage = $this->connection->getPDO()->prepare('SELECT * FROM images WHERE id=:id');
        $statementLastImage->bindValue(':id', $id,\PDO::PARAM_INT);
        $statementLastImage->execute();
        $statementLastImage->bindColumn('id', $id, \PDO::PARAM_INT);
        $statementLastImage->bindColumn('content', $content, \PDO::PARAM_LOB);

        $image = null;
        if($statementLastImage->fetch(\PDO::FETCH_BOUND))
        {
            $image = ['id' => $id, 'content' => base64_encode($content)];
        }
        return $image;
    }

    public function getAllImages()
    {
        $statement = $this->connection->getPDO()->prepare('SELECT * FROM images');
        $statement->execute();
        $statement->bindColumn('id', $id, \PDO::PARAM_INT);
        $statement->bindColumn('content', $content, \PDO::PARAM_LOB);

        $images = [];
        while ($statement->fetch(\PDO::FETCH_BOUND)) {
            $images[] = ['id' => $id, 'content' => base64_encode($content)];
        }
        return $images;
    }
}