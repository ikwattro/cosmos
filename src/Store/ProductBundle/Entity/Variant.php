<?php

namespace Store\ProductBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class Variant
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Store\ProductBundle\Entity\Product", inversedBy="variants")
     */
    protected $product;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $is_master;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $sku;

    /**
     * @ORM\Column(type="integer")
     */
    protected $price;

    /**
     * @ORM\ManyToMany(targetEntity="Store\ProductBundle\Entity\OptionValue", inversedBy="variants")
     * @ORM\JoinTable(name="value_variant")
     */
    protected $values;

    /**
     * @ORM\OneToMany(targetEntity="Store\ProductBundle\Entity\VariantImage", mappedBy="variant")
     */
    protected $images;

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
     * Set is_master
     *
     * @param boolean $isMaster
     * @return Variant
     */
    public function setIsMaster($isMaster)
    {
        $this->is_master = $isMaster;
    
        return $this;
    }

    /**
     * Get is_master
     *
     * @return boolean 
     */
    public function getIsMaster()
    {
        return $this->is_master;
    }

    /**
     * Set sku
     *
     * @param string $sku
     * @return Variant
     */
    public function setSku($sku)
    {
        $this->sku = $sku;
    
        return $this;
    }

    /**
     * Get sku
     *
     * @return string 
     */
    public function getSku()
    {
        return $this->sku;
    }

    /**
     * Set price
     *
     * @param integer $price
     * @return Variant
     */
    public function setPrice($price)
    {
        $this->price = $price;
    
        return $this;
    }

    /**
     * Get price
     *
     * @return integer 
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set product
     *
     * @param \Store\ProductBundle\Entity\Product $product
     * @return Variant
     */
    public function setProduct(\Store\ProductBundle\Entity\Product $product = null)
    {
        $this->product = $product;
    
        return $this;
    }

    /**
     * Get product
     *
     * @return \Store\ProductBundle\Entity\Product 
     */
    public function getProduct()
    {
        return $this->product;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->values = new \Doctrine\Common\Collections\ArrayCollection();
        $this->images = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function getImages()
    {
        return $this->images;
    }
    
    /**
     * Add values
     *
     * @param \Store\ProductBundle\Entity\OptionValue $values
     * @return Variant
     */
    public function addValue(\Store\ProductBundle\Entity\OptionValue $values)
    {
        $this->values[] = $values;
    
        return $this;
    }

    /**
     * Remove values
     *
     * @param \Store\ProductBundle\Entity\OptionValue $values
     */
    public function removeValue(\Store\ProductBundle\Entity\OptionValue $values)
    {
        $this->values->removeElement($values);
    }

    /**
     * Get values
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getValues()
    {
        return $this->values;
    }

    protected $vals;

    public function getVals()
    {
        return $this->vals;
    }

    public function setVals(\Doctrine\Common\Collections\ArrayCollection $vals)
    {
        $this->vals = $vals;
    }

    public function addVal($val, $c)
    {
        $this->vals[$c] = $val;
    }

    public function getValFor($i)
    {
        return $this->vals[$i];
    }

    public function __toString()
    {
        $values = $this->getValues();
        $str = '';
        $options = array();
        foreach ($values as $val) {
            $str .= $val->getOption()->getName().' : '.$val->getName().' - ';
        }
        return $str;

    }
}