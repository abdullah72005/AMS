<?php 
class Newsletter
{
    private $id;
    private $title;
    private $body;
    private $creatorId;
    private $state;
    public function __construct($id, $title = null, $body = null, $creatorId)
    {
        $this->id = $id;
        $this->title = $title;
        $this->body = $body;
        $this->creatorId = $creatorId;
        $this->state = new DraftState();
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
    public function setTitle($title)
    {
        $this->title = $title;
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