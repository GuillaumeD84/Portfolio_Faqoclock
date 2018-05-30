<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use Doctrine\Common\Collections\ArrayCollection;

use AppBundle\Entity\User;
use AppBundle\Entity\Answer;
use AppBundle\Entity\Tag;

/**
 * Question
 *
 * @ORM\Table(name="question")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\QuestionRepository")
 */
class Question
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="body", type="text")
     */
    private $body;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;

    /**
     * @var string
     *
     * @ORM\ManyToOne(targetEntity="User", inversedBy="questions")
     * @ORM\JoinColumn(name="author_id", referencedColumnName="id", nullable=false)
     */
    private $author;

    /**
     * @ORM\OneToMany(targetEntity="Answer", mappedBy="question")
     */
    private $answers;

    /**
     * @ORM\OneToOne(targetEntity="Answer")
     * @ORM\JoinColumn(name="validated_answer_id", referencedColumnName="id", nullable=true)
     */
    private $validatedAnswer;

    /**
     * @ORM\ManyToMany(targetEntity="Tag", inversedBy="questions")
     * @ORM\JoinTable(name="question_tag")
     */
    private $tags;

    /**
     * @ORM\ManyToMany(targetEntity="User", mappedBy="voteQuestions")
     */
    private $voteUsers;

    public function __construct()
    {
        $this->answers = new ArrayCollection();
        $this->tags = new ArrayCollection();
        $this->voteUsers = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->getTitle();
    }



    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return Question
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set body
     *
     * @param string $body
     *
     * @return Question
     */
    public function setBody($body)
    {
        $this->body = $body;

        return $this;
    }

    /**
     * Get body
     *
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Question
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }



    /**
     * Get the value of Author
     *
     * @return string
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * Set the value of Author
     *
     * @param string author
     *
     * @return self
     */
    public function setAuthor($author)
    {
        $this->author = $author;

        return $this;
    }

    /**
     * Get the value of Answers
     *
     * @return mixed
     */
    public function getAnswers()
    {
        return $this->answers;
    }

    /**
     * Add an Answer to the ArrayCollection
     *
     * @param Answer answer
     *
     * @return self
     */
    public function addAnswer(Answer $answer)
    {
        $this->answers[] = $answer;

        return $this;
    }

    /**
     * Get the value of ValidatedAnswer
     *
     * @return Answer
     */
    public function getValidatedAnswer()
    {
        return $this->validatedAnswer;
    }

    /**
     * Set the value of Answer
     *
     * @param Answer validatedAnswer
     *
     * @return self
     */
    public function setValidatedAnswer($validatedAnswer)
    {
        $this->validatedAnswer = $validatedAnswer;

        return $this;
    }

    /**
     * Get the value of Tags
     *
     * @return mixed
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * Add a Tag to the ArrayCollection
     *
     * @param Tag tag
     *
     * @return self
     */
    public function addTag(Tag $tag)
    {
        $tag->addQuestion($this);
        $this->tags[] = $tag;

        return $this;
    }

    /**
     * Get the value of voteUsers
     *
     * @return mixed
     */
    public function getVoteUsers()
    {
        return $this->voteUsers;
    }

    /**
     * Add a vote for an User to the ArrayCollection
     *
     * @param User user
     *
     * @return self
     */
    public function addVoteUser(User $user)
    {
        $this->voteUsers[] = $user;

        return $this;
    }
}
