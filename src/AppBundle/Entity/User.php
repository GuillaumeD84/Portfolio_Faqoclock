<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

use Doctrine\Common\Collections\ArrayCollection;

use AppBundle\Entity\Role;
use AppBundle\Entity\Question;
use AppBundle\Entity\Answer;

/**
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserRepository")
 */
class User implements UserInterface, \Serializable
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100, unique=true)
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=64)
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(name="is_active", type="boolean")
     */
    private $isActive;

    /**
     * @ORM\ManyToOne(targetEntity="Role", inversedBy="users")
     * @ORM\JoinColumn(name="role_id", referencedColumnName="id", nullable=false)
     */
    private $role;

    /**
     * @ORM\OneToMany(targetEntity="Question", mappedBy="author")
     */
    private $questions;

    /**
     * @ORM\OneToMany(targetEntity="Answer", mappedBy="author")
     */
    private $answers;

    /**
     * @ORM\ManyToMany(targetEntity="Question", inversedBy="voteUsers")
     * @ORM\JoinTable(name="user_question_vote")
     */
    private $voteQuestions;

    /**
     * @ORM\ManyToMany(targetEntity="Answer", inversedBy="voteUsers")
     * @ORM\JoinTable(name="user_answer_vote")
     */
    private $voteAnswers;

    public function __construct()
    {
        $this->isActive = true;
        $this->questions = new ArrayCollection();
        $this->answers = new ArrayCollection();
        $this->voteQuestions = new ArrayCollection();
        $this->voteAnswers = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->getUsername();
    }



    public function getUsername()
    {
        return $this->username;
    }

    public function getSalt()
    {
        return null;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function getRoles()
    {
        return array($this->role->getName());
    }

    public function eraseCredentials()
    {
    }

    /** @see \Serializable::serialize() */
    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->username,
            $this->password,
        ));
    }

    /** @see \Serializable::unserialize() */
    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->username,
            $this->password,
        ) = unserialize($serialized);
    }

    /**
     * Get the value of Id
     *
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the value of Username
     *
     * @param mixed username
     *
     * @return self
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Set the value of Password
     *
     * @param mixed password
     *
     * @return self
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get the value of Email
     *
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set the value of Email
     *
     * @param mixed email
     *
     * @return self
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get the value of Is Active
     *
     * @return mixed
     */
    public function getIsActive()
    {
        return $this->isActive;
    }

    /**
     * Set the value of Is Active
     *
     * @param mixed isActive
     *
     * @return self
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;

        return $this;
    }



    /**
     * Get the value of Role
     *
     * @return mixed
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * Set the value of Role
     *
     * @param mixed role
     *
     * @return self
     */
    public function setRole($role)
    {
        $this->role = $role;

        return $this;
    }

    /**
     * Get the value of Questions
     *
     * @return mixed
     */
    public function getQuestions()
    {
        return $this->questions;
    }

    /**
     * Add a Question to the ArrayCollection
     *
     * @param Question question
     *
     * @return self
     */
    public function addQuestion(Question $question)
    {
        $this->questions[] = $question;

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
     * Get the value of voteQuestions
     *
     * @return mixed
     */
    public function getVoteQuestions()
    {
        return $this->voteQuestions;
    }

    /**
     * Add a vote for a Question to the ArrayCollection
     *
     * @param Question question
     *
     * @return self
     */
    public function addVoteQuestion(Question $question)
    {
        $question->addVoteUser($this);
        $this->voteQuestions[] = $question;

        return $this;
    }

    /**
     * Get the value of voteAnswers
     *
     * @return mixed
     */
    public function getVoteAnswers()
    {
        return $this->voteAnswers;
    }

    /**
     * Add a vote for an Answer to the ArrayCollection
     *
     * @param Answer answer
     *
     * @return self
     */
    public function addVoteAnswer(Answer $answer)
    {
        $answer->addVoteUser($this);
        $this->voteAnswers[] = $answer;

        return $this;
    }
}
