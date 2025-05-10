<?php 
require_once('Subject.php');
class Newsletter extends Subject
{
    private $id;
    private $title;
    private $body;
    private $creatorId;
    private $state;
    public function __construct($creatorId = null, $title = null, $body = null,State  $state = new DraftState(), $id = null)
    { 
        //can you @ someone in the comments?
        // @aliehab ? 
        // why the fuck can a state be null just draft it?
        // why did you even change this
        $this->title = $title;
        $this->body = $body;
        $this->creatorId = $creatorId;
        $this->state = $state;
        $this->id = $id;

    }
    public function getId()
    {
        return $this->id;
    }
    public function getTitle()
    {
        return $this->title;
    }
    public function getBody()
    {
        return $this->body;
    }
    public function getCreatorId()
    {
        return $this->creatorId;
    }
    public function getIntState()
    {
        if ($this->state instanceof DraftState) {
            return 0;
        } elseif ($this->state instanceof PublishedState) {
            return 1;
        }
    }
    public function getStringState()
    {
        if ($this->state instanceof DraftState) {
            return 'DraftState';
        } else if ($this->state instanceof PublishedState) {
            return 'PublishedState';
        }
    }
    public function editTitle($title)
    {
        $this->state->edit($this, 'title', $title);
    }
    public function editBody($body)
    {
        $this->state->edit($this, 'body', $body);
    }
    public function publish()
    {
        $this->state->publish($this);
    }
    protected function setBody($body)
    {
        $this->body = $body;
    }
    protected function setTitle($title)
    {
        $this->title = $title;
    }
    public function setState(State $state)
    {
        $this->state = $state;
    }
    public static function delete($id)
    {
        try {        
            $dbCnx = require('db.php');
            $stmt = $dbCnx->prepare("DELETE FROM Newsletter WHERE Newsletter_id = (?)");
            $stmt->execute([$id]);
            echo "Deleting newsletter.\n";}
            catch (Exception $e) {
                echo "Failed to delete newsletter: " . $e->getMessage() . $id;
            }
    }
    public function save()
    {
        if ($this->getId() != null) {
            try {        
                $dbCnx = require('db.php');
                $stmt = $dbCnx->prepare("UPDATE Newsletter SET title = ?, body = ?, publishedState = ? WHERE newsletter_id = ?");
                $stmt->execute([$this->title, $this->body, $this->getIntState(), $this->id]);
                
                if ($this->getIntState() == 1) {
                    $this->notify("Newsletter has been published: " . $this->title);
                } 
                return $this->id;
            } catch (Exception $e) {
                return "Failed to update newsletter: " . $e->getMessage();
            }
        } else {
            try {        
                $dbCnx = require('db.php');
                $stmt = $dbCnx->prepare("INSERT INTO Newsletter (title, body, creatorId, publishedstate) VALUES (?, ?, ?, ?)");
                $stmt->execute([$this->title, $this->body, $this->creatorId,$this->getIntState()]);
                $this->id = $dbCnx->lastInsertId();
                
                if ($this->getIntState() == 1) {
                    $this->notify("Newsletter has been published: " . $this->title);
                }
                return $dbCnx->lastInsertId();
                }
                catch (Exception $e) {
                    return "Failed to save newsletter: " . $e->getMessage();
                }
        }
    }

    
    public static function getNewsletter($id)
    {
        try {        
            $dbCnx = require('db.php');
            $stmt = $dbCnx->prepare("SELECT * FROM Newsletter WHERE newsletter_id = ? order by newsletter_id DESC");
            $stmt->execute([$id]);
            $newsletter = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($newsletter['publishedState'] == 0) {
                $state = new DraftState();
            } else {
                $state = new PublishedState();
            }
            if ($newsletter) {
                return new Newsletter($newsletter['creatorId'], $newsletter['title'], $newsletter['body'], $state, $newsletter['newsletter_id']);
            } else {
                throw new Exception("Newsletter not found.");
            }
        } catch (Exception $e) {
            echo "Failed to get newsletter: " . $e->getMessage();
        }
    }


    public static function getPublishedNewsletters()
    {
        try {        
            $dbCnx = require('db.php');
            $stmt = $dbCnx->prepare("SELECT * FROM Newsletter where publishedState = 1 order by newsletter_id DESC ");
            $stmt->execute();
            $newsletters = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return array_map(function ($newsletter) {
                return new Newsletter($newsletter['creatorId'], $newsletter['title'], $newsletter['body'], new PublishedState(), $newsletter['newsletter_id']);
            }, $newsletters);
        } catch (Exception $e) {
            echo "Failed to get newsletters: " . $e->getMessage();
        }
    }


    public static function getDraftedNewsletters()
    {
        try {        
            $dbCnx = require('db.php');
            $stmt = $dbCnx->prepare("SELECT * FROM Newsletter where publishedState = 0 order by newsletter_id DESC ");
            $stmt->execute();
            $newsletters = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return array_map(function ($newsletter) {
                return new Newsletter($newsletter['creatorId'], $newsletter['title'], $newsletter['body'], new DraftState(), $newsletter['newsletter_id']);
            }, $newsletters);
        } catch (Exception $e) {
            echo "Failed to get newsletters: " . $e->getMessage();
        }
    }

}




interface State
{
    public function edit($newsletter,$field,$data);
    public function publish($newsletter);
}


class DraftState implements State
{
    public function __construct()
    {
    }
    public function edit($newsletter,$field,$data)
    {
        // Code to edit the newsletter in draft state
        echo "Editing newsletter in draft state.\n";
        if ($field == 'title') {
            $newsletter->setTitle($data);
        } elseif ($field == 'body') {
            $newsletter->setBody($data);
        } else {
            echo "Invalid field to edit.\n";
        }
    }
    public function publish($newsletter)
    {
        // Code to publish the newsletter from draft state
        echo "Publishing newsletter from draft state.\n";
        if ($newsletter->title == null || $newsletter->body == null) {
            echo "Cannot publish newsletter without title or body.\n";
            return;
        }
        $newsletter->setState(new PublishedState());
    }
}
class PublishedState implements State
{
    public function __construct()
    {
    }
    public function edit($newsletter,$field,$data)
    {
        echo "Cannot edit a published newsletter.\n";
    }
    public function publish($newsletter)
    {
        echo "Newsletter is already published.\n";
    }
}



?>