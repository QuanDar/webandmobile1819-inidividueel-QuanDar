<?php

namespace test;

use PHPUnit\Framework\TestCase;
use App\Model\PDOMessageModelInterface;
use App\Model\Connection;

class PDOMessageModelTest extends TestCase
{
    public function setUp()
    {
        $this->connection = new Connection('sqlite::memory:');

        $this->connection->getPDO()->exec('CREATE TABLE messages(
          id INT,
          content LONGTEXT,
          category VARCHAR(100),
          date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
          upVotes INT(10),
          downVotes INT(10),
          PRIMARY KEY (id)
        );
        CREATE TABLE comments(
          id INT,
          message_id INT(11),
          content LONGTEXT,
          token VARCHAR(10),
          date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
          PRIMARY KEY (id)
        );
        ');

        $messages = $this->providerMessages();
        foreach ($messages as $message) {
            $this->connection->getPDO()->exec("INSERT INTO messages (id, content, category, upVotes, downVotes, `date`)
                        VALUES (" . $message['id'] . ", '" . $message['content'] . "', '" . $message['category'] . "',
                        " . $message['upVotes'] . ", " . $message['downVotes'] . ", '" . $message['date'] . "')");
        }
    }

    protected function tearDown()/* The :void return type declaration that should be here would cause a BC issue */
    {
        $this->connection = null;
    }

    public function providerMessages()
    {
        return [
            ['id' => 1, 'content' => 'Content1', 'category' => 'Comedy',
                'date' => date("Y-m-d H:i:s", mktime(3, 0, 0, 7, 1, 2000)), 'upVotes' => 10, 'downVotes' => 30],
            ['id' => 2, 'content' => 'Content2', 'category' => 'Horror',
                'date' => date("Y-m-d H:i:s", mktime(0, 0, 0, 2, 1, 2000)), 'upVotes' => 20, 'downVotes' => 20],
            ['id' => 3, 'content' => 'Content3', 'category' => 'Thriller',
                'date' => date("Y-m-d H:i:s", mktime(0, 0, 0, 10, 30, 2010)), 'upVotes' => 30, 'downVotes' => 10]
        ];
    }

    public function testGetById_messagesInDatabase()
    {
        //Comments ophalen uit sqlite database
        $messagesModel = new PDOMessageModelInterface($this->connection);
        $actualMessage = $messagesModel->getMessageById(1);

        $expectedMessage = $this->providerMessages();
        $this->assertEquals('array', gettype($actualMessage));
        $this->assertEquals($expectedMessage[0], $actualMessage[0]);
    }

    public function testSearchByContent_messagesInDatabase()
    {
        $messagesModel = new PDOMessageModelInterface($this->connection);
        $actualMessage = $messagesModel->searchMessageByContent("content2");
        $expectedMessage = $this->providerMessages();
        $this->assertEquals('array', gettype($actualMessage));
        $this->assertEquals($expectedMessage[1], $actualMessage[0]);
    }

    public function testSearchByContent_messagesNotInDatabase()
    {
        $messagesModel = new PDOMessageModelInterface($this->connection);
        $actualMessage = $messagesModel->searchMessageByContent("no content");
        $this->assertEquals('array', gettype($actualMessage));
        $this->assertEmpty($actualMessage);
    }

    public function testSearchByCategory_messagesInDatabase()
    {
        $messagesModel = new PDOMessageModelInterface($this->connection);
        $actualMessage = $messagesModel->searchMessageByCategory("Thriller");
        $expectedMessage = $this->providerMessages();
        $this->assertEquals('array', gettype($actualMessage));
        $this->assertEquals($expectedMessage[2], $actualMessage[0]);
    }

    public function testSearchByCategory_messagesNotInDatabase()
    {
        $messagesModel = new PDOMessageModelInterface($this->connection);
        $actualMessage = $messagesModel->searchMessageByCategory("no category");
        $this->assertEquals('array', gettype($actualMessage));
        $this->assertEmpty($actualMessage);
    }

    public function testSearchByContentOrCategory_SearchByCategory_messagesInDatabase()
    {
        $messagesModel = new PDOMessageModelInterface($this->connection);
        $actualMessage = $messagesModel->searchMessageByContentAndCategory("content3", "Thriller");
        $expectedMessage = $this->providerMessages();
        $this->assertEquals('array', gettype($actualMessage));
        $this->assertEquals($expectedMessage[2], $actualMessage[0]);
        $this->connection->getPDO()->prepare('SELECT * FROM messages WHERE id=?');
    }

    public function testSearchByContentOrCategory_SearchByCategory_messagesNotInDatabase()
    {
        $messagesModel = new PDOMessageModelInterface($this->connection);
        $actualMessage = $messagesModel->searchMessageByContentAndCategory("no content", "no category");
        $this->assertEquals('array', gettype($actualMessage));
        $this->assertEmpty($actualMessage);
    }

    public function testGetAll_messagesInDatabase()
    {
        $messagesModel = new PDOMessageModelInterface($this->connection);
        $actualMessage = $messagesModel->getAllMessages();
        $expectedMessage = $this->providerMessages();
        $this->assertEquals('array', gettype($actualMessage));
        $this->assertEquals($expectedMessage, $actualMessage);
    }

    public function testUpVoteMessage_upVotesInDatabase()
    {
        $messagesModel = new PDOMessageModelInterface($this->connection);
        $messagesModel->upVoteMessage(1);

        $expectedUpVotes = $this->providerMessages()[0]['upVotes'];
        $expectedUpVotes++;

        $statement = $this->connection->getPDO()->prepare('SELECT * FROM messages WHERE id = 1');
        $statement->execute();
        $actualUpVotesMessage = $statement->fetch();
        $actualUpVotes = $actualUpVotesMessage['upVotes'];

        $this->assertEquals($expectedUpVotes, $actualUpVotes);
    }

    public function testDownVoteMessage_downVotesInDatabase()
    {
        $messagesModel = new PDOMessageModelInterface($this->connection);
        $messagesModel->downVoteMessage(1);

        $expectedDownVotes = $this->providerMessages()[0]['downVotes'];
        $expectedDownVotes++;

        $statement = $this->connection->getPDO()->prepare('SELECT * FROM messages WHERE id = 1');
        $statement->execute();
        $actualDownVotesMessage = $statement->fetch();
        $actualDownVotes = $actualDownVotesMessage['downVotes'];

        $this->assertEquals($expectedDownVotes, $actualDownVotes);
    }
    public function testPostComment_commentInDatabase()
    {
        //Comment posten in sqlite database
        $messagesModel = new PDOMessageModelInterface($this->connection);
        $actualMessage = $messagesModel->postComment(10, 'Hello');

        //Comments uit de database ophalen
        $statement = $this->connection->getPDO()->prepare('SELECT * FROM comments WHERE message_id = 10');
        $statement->execute();
        $comments = $statement->fetch();


        $expectedMessage = $comments['token'];
        $this->assertEquals('string', gettype($actualMessage));
        $this->assertEquals($expectedMessage, $actualMessage);
    }
}
