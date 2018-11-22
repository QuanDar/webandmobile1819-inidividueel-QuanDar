<?php

namespace test;

use App\Exception\IllegalArgumentExceptions;
use App\Model\PDOImageModelInterface;
use PHPUnit\Framework\TestCase;
use App\Model\Connection;
use Symfony\Component\Filesystem\Exception\InvalidArgumentException;

class PDOImageModelTest extends TestCase
{
    private $content = "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";
    private $connection;

    public function setUp()
    {
        $this->connection = new Connection('sqlite::memory:');

        $this->connection->getPDO()->exec('CREATE TABLE images(
          id INTEGER,
          content BLOB,
          PRIMARY KEY (id));
        ');

        $images = $this->providerImages();
        foreach ($images as $image) {
            $statement = $this->connection->getPDO()->prepare("INSERT INTO images (id, content) VALUES (?, ?)");
            $statement->bindValue(1, $image['id'], \PDO::PARAM_INT);
            $statement->bindValue(2, base64_decode($image['content']), \PDO::PARAM_LOB);
            $statement->execute();
        }
    }

    protected function tearDown()/* The :void return type declaration that should be here would cause a BC issue */
    {
        $this->connection = null;
    }

    public function providerImages()
    {
        return [
            ['id' => 1, 'content' => $this->content],
            ['id' => 2, 'content' => $this->content],
            ['id' => 3, 'content' => $this->content],
        ];
    }

    public function testGetById_ImagesInDatabase()
    {
        //Images ophalen uit sqlite database
        $imagesModel = new PDOImageModelInterface($this->connection);
        $actualImage = $imagesModel->getImageById(2);

        $expectedImage = $this->providerImages();
        $this->assertEquals('array', gettype($actualImage));
        $this->assertEquals(array_intersect_key($expectedImage[1], array_flip(['content'])) , $actualImage);
    }

    /**
     * @expectedException App\Exception\IllegalArgumentExceptions
     */
    public function testGetById_WrongImageId()
    {
        //Images ophalen uit sqlite database
        $imagesModel = new PDOImageModelInterface($this->connection);
        $actualImage = $imagesModel->getImageById("This is totally wrong image id");

        $this->expectException(InvalidArgumentException::class);
    }

    public function testGetAll_ImagesInDatabase()
    {
        $imagesModel = new PDOImageModelInterface($this->connection);
        $actualImage = $imagesModel->getAllImages();
        $expectedImage = $this->providerImages();
        $this->assertEquals('array', gettype($actualImage));
        $this->assertEquals($expectedImage, $actualImage);
    }

    public function testPostImage_imageInDatabase()
    {
        //Image posten in sqlite database
        $imagesModel = new PDOImageModelInterface($this->connection);
        $actualImage = $imagesModel->postImage(base64_decode($this->content));

        //Image uit de database ophalen
        $statement = $this->connection->getPDO()->prepare('SELECT * FROM images WHERE id = 4');
        $statement->execute();
        $image = $statement->fetch();


        $this->assertEquals('array', gettype($actualImage));
        $this->assertEquals(['id' => $image['id'], 'content' => base64_encode($image['content'])], $actualImage);
    }

    public function testDeleteImage_imageInDatabase()
    {
        //Image posten in sqlite database
        $imagesModel = new PDOImageModelInterface($this->connection);
        $actualImage = $imagesModel->deleteImageById(2);

        //Image uit de database ophalen
        $statement = $this->connection->getPDO()->prepare("DELETE FROM images WHERE id = 2");
        $statement->execute();
        $image = $statement->rowCount() == 0;

        $this->assertEquals('boolean', gettype($actualImage));
        $this->assertEquals($image, $actualImage);
    }

    /**
     * @expectedException App\Exception\IllegalArgumentExceptions
     */
    public function testPostImage_contentIsNull_Image()
    {
        //Image posten in sqlite database
        $imagesModel = new PDOImageModelInterface($this->connection);
        $actualImage = $imagesModel->postImage("");

        $this->expectException(IllegalArgumentExceptions::class);
    }

    /**
     * @expectedException App\Exception\IllegalArgumentExceptions
     */
    public function testPostImage_emptyContentString_Image()
    {
        //Image posten in sqlite database
        $imagesModel = new PDOImageModelInterface($this->connection);
        $actualImage = $imagesModel->postImage(null);

        $this->expectException(IllegalArgumentExceptions::class);
    }

    /**
     * @expectedException App\Exception\IllegalArgumentExceptions
     */
    public function testDeleteImage_IdIsNotNumeric_IdIsNull_Image()
    {
        //Image posten in sqlite database
        $imagesModel = new PDOImageModelInterface($this->connection);
        $actualImage = $imagesModel->deleteImageById(null);

        $this->expectException(IllegalArgumentExceptions::class);
    }

    /**
     * @expectedException App\Exception\IllegalArgumentExceptions
     */
    public function testDeleteImage_IdIsNotNumeric_IdIsString_Image()
    {
        //Image posten in sqlite database
        $imagesModel = new PDOImageModelInterface($this->connection);
        $actualImage = $imagesModel->deleteImageById("no integer");

        $this->expectException(IllegalArgumentExceptions::class);
    }
}
