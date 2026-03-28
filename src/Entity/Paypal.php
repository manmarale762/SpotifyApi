<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Paypal
 *
 * @ORM\Table(name="paypal", uniqueConstraints={@ORM\UniqueConstraint(name="username_paypal_UNIQUE", columns={"username_paypal"})}, indexes={@ORM\Index(name="fk_paypal_forma_pago1_idx", columns={"forma_pago_id"})})
 * @ORM\Entity
 */
class Paypal
{
    /**
     * @var string
     *
     * @ORM\Column(name="username_paypal", type="string", length=150, nullable=false)
     */
    private $usernamePaypal;

    /**
     * @var FormaPago
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\OneToOne(targetEntity="FormaPago")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="forma_pago_id", referencedColumnName="id")
     * })
     */
    private $formaPago;

    public function getUsernamePaypal(): string
    {
        return $this->usernamePaypal;
    }

    public function setUsernamePaypal(string $usernamePaypal): void
    {
        $this->usernamePaypal = $usernamePaypal;
    }

    public function getFormaPago(): FormaPago
    {
        return $this->formaPago;
    }

    public function setFormaPago(FormaPago $formaPago): void
    {
        $this->formaPago = $formaPago;
    }




}
