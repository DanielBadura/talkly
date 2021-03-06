<?php
/*
 * @author Tobias Olry <tobias.olry@gmail.com>
 */
namespace TobiasOlry\Talkly\Entity;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * @Entity(repositoryClass="TobiasOlry\Talkly\Repository\TopicRepository")
 * @Table
 */
class Topic
{
    /**
     * @Id
     * @Column(type="integer")
     * @GeneratedValue
     */
    private $id;

    /**
     * @ManyToOne(targetEntity="User", inversedBy="topics")
     */
    private $createdBy;

    /**
     * @Column(type="datetime", nullable=true)
     */
    private $createdAt;

    /**
     * @Column(type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @Column(type="string", nullable=true)
     */
    private $title;

    /**
     * @Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @OneToMany(targetEntity="Vote", mappedBy="topic", cascade="all")
     */
    private $votes;

    /**
     * @OneToMany(targetEntity="Comment", mappedBy="topic", cascade="all")
     */
    private $comments;

    /**
     * @Column(type="text", nullable=true)
     */
    private $lectureNote;

    /**
     * @ManyToOne(targetEntity="User", inversedBy="lectures")
     */
    private $lectureUser;

    /**
     * @Column(type="date", nullable=true)
     */
    private $lectureDate;

    /**
     *
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();

        $this->createdBy = $user;

        $this->votes    = new ArrayCollection();
        $this->comments = new ArrayCollection();
    }

    /**
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     *
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     *
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     *
     * @return Vote[]
     */
    public function getVotes()
    {
        return $this->votes;
    }

    /**
     *
     * @return Comment[]
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     *
     * @param User $user
     */
    public function castVote(User $user)
    {
        if ($this->voteCastBy($user)) {
            return;
        }

        $vote = new Vote($this, $user);
        $this->votes->add($vote);
    }

    /**
     *
     * @param User $user
     * @param string $text
     */
    public function comment(User $user, $text)
    {
        $this->comments->add(new Comment($user, $this, $text));
    }

    /**
     *
     * @param User $user
     * @return Vote
     */
    public function getVote(User $user)
    {
        if (! $this->voteCastBy($user)) {
            return null;
        }

        foreach ($this->votes as $vote) {
            if ($vote->getVoter() == $user) {
                return $vote;
            }
        }

        return null;
    }

    /**
     *
     * @param User $user
     * @return bool
     */
    public function voteCastBy(User $user)
    {
        foreach ($this->votes as $vote) {
            if ($vote->getVoter() == $user) {
                return true;
            }
        }

        return false;
    }

    /**
     *
     * @return bool
     */
    public function isArchived()
    {
        return $this->lectureDate <> null;
    }

    /**
     *
     * @return User
     */
    public function getCreatedBy()
    {
        return $this->createdBy;
    }

    /**
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     *
     * @param \DateTime $date
     */
    public function setLectureDate(\DateTime $date)
    {
        $this->lectureDate = $date;
    }

    /**
     *
     * @return \DateTime
     */
    public function getLectureDate()
    {
        return $this->lectureDate;
    }

    /**
     *
     * @param string $note
     */
    public function setLectureNote($note)
    {
        $this->lectureNote = $note;
    }

    /**
     *
     * @return string
     */
    public function getLectureNote()
    {
        return $this->lectureNote;
    }

    /**
     *
     * @param User $user
     */
    public function setLectureUser(User $user)
    {
        $this->lectureUser = $user;
    }

    /**
     *
     * @return User
     */
    public function getLectureUser()
    {
        return $this->lectureUser;
    }
}
