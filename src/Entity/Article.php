<?php


namespace App\Entity;


use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity
 * @ORM\Table(name="article")
 */
class Article
{
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $title;
   
    /**
     * @ORM\Column(type="text")
     */
    private $body;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Comment", mappedBy="article", orphanRemoval=true)
     */
    private $comments;
    /**
     * @var Tag[]|ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="App\Entity\Tag", cascade={"persist"})
     * @ORM\JoinTable(name="aricle_tag")
     * @ORM\OrderBy({"name": "ASC"})
     */
    private $tags;
    /**
     * @ORM\OneToMany(targetEntity="App\Entity\UserLike", mappedBy="article")
     */
    private $userLikes;

    /**
     * @var string $image
     * @Assert\File( maxSize = "1024k", mimeTypesMessage = "Please upload a valid Image")
     * @ORM\Column(name="image", type="string", length=255, nullable=true)
     */
    private $image;
    


    public function __construct()
    {
        $this->comments = new ArrayCollection();
        $this->tags = new ArrayCollection();
        $this->userLikes = new ArrayCollection();
    }
    public function getImage()
{
return $this->image;
}

public function setImage( $image)
{
    $this->image = $image;

    return $this;
}
    /**
     * @return int|null
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     *  @return null|string
     */

    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $text
     *
     * @return Article
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }
    public function getBody()
    {
        return $this->body;
    }

    public function setBody( $body)
    {
        $this->body = $body;

        return $this;
    }
    /**
     * @return Tag[]|ArrayCollection
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * @param Tag[]|ArrayCollection $tags
     * @return Article
     */
    public function setTags($tags)
    {
        $this->tags = $tags;

        return $this;
    }


    /**
     * @return Collection|Comment[]
     */
    public function getComments()
    {
        return $this->comments;
    }
    public function addComment(Comment $comment)
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setArticle($this);
        }
        return $this;
    }
    public function removeComment(Comment $comment)
    {
        if ($this->comments->contains($comment)) {
            $this->comments->removeElement($comment);
            // set the owning side to null (unless already changed)
            if ($comment->getArticle() === $this) {
                $comment->setArticle(null);
            }
        }
        return $this;
    }
    /**
     * @return ArrayCollection|UserLike[]
     */
    public function getUserLike()
    {
        return $this->userLikes;
    }
    public function addUserLike(UserLike $likes)
    {
        if (!$this->userLikes->contains($likes)) {
            $this->userLikes[] = $likes;
            $likes->setUser($this);
        }
        return $this;
    }
    public function removeUserLike(UserLike $likes)
    {
        if ($this->userLikes->contains($likes)) {
            $this->userLikes->removeElement($likes);
            // set the owning side to null (unless already changed)
            if ($likes->getUser() === $this) {
                $likes->setUser(null);
            }
        }
        return $this;
    }


}
