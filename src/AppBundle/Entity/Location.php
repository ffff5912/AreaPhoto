<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Location
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Location
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @Assert\NotBlank(groups="location_recent")
     * @Assert\Regex(pattern="/\A[0-9]+\Z/u", groups="location_recent")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="lat", type="string", length=255)
     * @Assert\NotBlank()
     * @Assert\Regex("/\A([0-9])+\.*([0-9])*\Z/u")
     */
    private $lat;

    /**
     * @var string
     *
     * @ORM\Column(name="lng", type="string", length=255)
     * @Assert\NotBlank()
     * @Assert\Regex("/\A([0-9])+\.*([0-9])*\Z/u")
     */
    private $lng;

    /**
     * @var string
     *
     * @ORM\Column(name="distance", type="string", length=10)
     * @Assert\NotBlank()
     * @Assert\Regex("/\A([1-5]{1}00)\Z/u")
     */
    private $distance;

    /**
     * Set id
     *
     * @return integer
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set lat
     *
     * @param string $lat
     * @return Location
     */
    public function setLat($lat)
    {
        $this->lat = $lat;

        return $this;
    }

    /**
     * Get lat
     *
     * @return string
     */
    public function getLat()
    {
        return $this->lat;
    }

    /**
     * Set lng
     *
     * @param string $lng
     * @return Location
     */
    public function setLng($lng)
    {
        $this->lng = $lng;

        return $this;
    }

    /**
     * Get lng
     *
     * @return string
     */
    public function getLng()
    {
        return $this->lng;
    }
    
    /**
     * Set distance
     *
     * @param string $distance
     * @return Location
     */
    public function setDistance($distance)
    {
        $this->distance = $distance;

        return $this;
    }

    /**
     * Get distance
     *
     * @return string
     */
    public function getDistance()
    {
        return $this->distance;
    }
}
