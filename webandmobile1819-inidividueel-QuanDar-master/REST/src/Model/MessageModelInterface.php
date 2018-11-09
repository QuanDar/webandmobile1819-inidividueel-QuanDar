<?php
namespace App\Model;

interface MessageModelInterface
{
    public function getAllMessages();
    public function getAllMessagesWithComments();
    public function getAllCategories();
    public function getMessageById($messageId);
    public function searchMessageByContent($search);
    public function searchMessageByCategory($category);
    public function searchMessageByContentAndCategory($content, $category);
    public function getCommentsByMessageId($messageId);
    public function postComment($messageId, $comment);
    public function upVoteMessage($messageId);
    public function downVoteMessage($messageId);
    public function deleteMessage($messageId);
}
