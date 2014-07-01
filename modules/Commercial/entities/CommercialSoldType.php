<?php





/**
 * CommercialSoldType
 *
 * @Table(name="COMMERCIAL_SOLD_TYPE")
 * @Entity
 */
class CommercialSoldType
{
    /**
     * @var string $label
     *
     * @Column(name="label", type="string", length=70)
     */
    private $label;

    /**
     * @var string $code
     *
     * @Column(name="code", type="string")
     * @Id
     * @GeneratedValue(strategy="IDENTITY")
     */
    private $code;


    /**
     * Set label
     *
     * @param string $label
     * @return CommercialSoldType
     */
    public function setLabel($label)
    {
        $this->label = $label;
        return $this;
    }

    /**
     * Get label
     *
     * @return string 
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * Get code
     *
     * @return string 
     */
    public function getCode()
    {
        return $this->code;
    }
}