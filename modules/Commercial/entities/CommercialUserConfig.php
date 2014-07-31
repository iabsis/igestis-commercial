<?php





/**
 * CommercialUserConfig
 *
 * @Table(name="COMMERCIAL_USER_CONFIG")
 * @Entity
 */
class CommercialUserConfig
{
    /**
     * @var string $fileUploadType
     *
     * @Column(name="FILE_UPLOAD_TYPE", type="string", length=20)
     */
    private $fileUploadType;

    /**
     * @var string $soldType
     *
     * @Column(name="SOLD_TYPE", type="string", length=20)
     */
    private $soldType;

    /**
     * @var integer $contactId
     *
     * @Column(name="contact_id", type="integer")
     * @Id
     * @GeneratedValue(strategy="IDENTITY")
     */
    private $contactId;

    /**
     * @var CoreContacts
     *
     * @ManyToOne(targetEntity="CoreContacts")
     * @JoinColumns({
     *   @JoinColumn(name="contact_id", referencedColumnName="id")
     * })
     */
    private $contact;


    /**
     * Set fileUploadType
     *
     * @param string $fileUploadType
     * @return CommercialUserConfig
     */
    public function setFileUploadType($fileUploadType)
    {
        $this->fileUploadType = $fileUploadType;
        return $this;
    }

    /**
     * Get fileUploadType
     *
     * @return string 
     */
    public function getFileUploadType()
    {
        return $this->fileUploadType;
    }

    /**
     * Set soldType
     *
     * @param string $soldType
     * @return CommercialUserConfig
     */
    public function setSoldType($soldType)
    {
        $this->soldType = $soldType;
        return $this;
    }

    /**
     * Get soldType
     *
     * @return string 
     */
    public function getSoldType()
    {
        return $this->soldType;
    }

    /**
     * Get contactId
     *
     * @return integer 
     */
    public function getContactId()
    {
        return $this->contactId;
    }

    /**
     * Set contact
     *
     * @param CoreContacts $contact
     * @return CommercialUserConfig
     */
    public function setContact(\CoreContacts $contact = null)
    {
        $this->contact = $contact;
        return $this;
    }

    /**
     * Get contact
     *
     * @return CoreContacts 
     */
    public function getContact()
    {
        return $this->contact;
    }
}