<?php

namespace App\Model\User\Entity\User\Traits;

use App\Model\User\Entity\Network\Network;
use App\Model\User\Entity\Role\Role;
use App\Model\User\Entity\Token\ResetToken;
use App\Model\User\Entity\User\Objects\Email;
use App\Model\User\Entity\User\Objects\Id;
use Doctrine\Common\Collections\ArrayCollection;

trait UserDoctrineTrait
{
    /**
     * @var Id
     * @ORM\Column(type="user_user_id")
     * @ORM\Id
     */
    private Id $id;

    /**
     * @var Email|null
     * @ORM\Column(type="user_user_email", nullable=true)
     */
    private ?Email $email;

    /**
     * @var string|null
     * @ORM\Column(type="string", nullable=true, name="password_hash")
     */
    private ?string $passwordHash;

    /**
     * @var string
     * @ORM\Column(type="string", length=16)
     */
    private string $status;

    /**
     * @var Role
     * @ORM\Column(type="user_user_role")
     */
    private Role $role;

    /**
     * @var string|null
     * @ORM\Column(type="string", nullable=true, name="confirm_token")
     */
    private ?string $confirmToken;

    /**
     * @var ResetToken|null
     * @ORM\Embedded(class="App\Model\User\Entity\Token\ResetToken", columnPrefix="reset_token_")
     */
    private ?ResetToken $resetToken;

    /**
     * @var \DateTimeImmutable
     * @ORM\Column(type="datetime_immutable")
     */
    private \DateTimeImmutable $date;

    /**
     * @var Network[]|ArrayCollection
     * @ORM\OneToMany(targetEntity="App\Model\User\Entity\Network\Network", mappedBy="user", orphanRemoval=true, cascade={"persist"})
     */
    private $networks;

    /**
     * Check whether the ResetToken object is empty, and reset it if empty data is NULL from the DB
     *
     * TODO refact to UserDoctrineTrait
     * @ORM\PostLoad()
     * @return void
     */
    public function checkEmbeds(): void
    {
        if ($this->resetToken->isEmpty()) $this->resetToken = null;
    }
}