<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\Common\Collections\ArrayCollection;
/**
 * @ORM\Entity
 * @ORM\Table(name="user")
 * @UniqueEntity("email", message="Email already taken")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(name="email", type="string", length=191, unique=true)
     * @Assert\NotBlank()
     * @Assert\Email()
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];
    /**
     * @ORM\OneToMany(targetEntity="App\Entity\UserLike", mappedBy="user")
     */
    private $userLikes;

    public function __construct()
    {
        $this->roles = ['ROLE_USER'];
        $this->userLikes = new ArrayCollection();



    }

    /**
     * @Assert\NotBlank()
     * @Assert\Length(max=4096)
     */

    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank()
     */
    private $firstName;

    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank()
     */
    private $lastName;

    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank()
     * @Assert\Length(
     *     min = 6,
     *     max = 11,
     *     minMessage = "Your password must be at least {{ limit }} characters long",
     *     maxMessage = "Your password cannot be longer than {{ limit }} characters"
     * )
     */
    private $password;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;

    }

    /**
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @param string $firstName
     * @return User
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @param string $lastName
     * @return User
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername()
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles()
    {
        $roles = $this->roles;
        return array_unique($roles);
    }

    public function setRoles(array $roles)
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword()
    {
        return (string) $this->password;
    }

    public function setPassword( $password)
    {
        $this->password = $password;

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
            if ($likes->getUser() === $this) {
                $likes->setUser(null);
            }
        }
        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

}