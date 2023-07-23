<?php

namespace Plugin\ProductReview42\Entity;

use Doctrine\ORM\Mapping as ORM;
use Eccube\Entity\Member;

if (!class_exists('\Plugin\ProductReview42\Entity\ProductReviewImage')) {
    /**
     * ProductReviewImage
     *
     * @ORM\Table(name="plg_product_review_image")
     * @ORM\InheritanceType("SINGLE_TABLE")
     * @ORM\DiscriminatorColumn(name="discriminator_type", type="string", length=255)
     * @ORM\HasLifecycleCallbacks()
     * @ORM\Entity(repositoryClass="Plugin\ProductReview42\Repository\ProductReviewImageRepository")
     */
    class ProductReviewImage extends \Eccube\Entity\AbstractEntity
    {
        /**
         * @return string
         */
        public function __toString()
        {
            return (string) $this->getFileName();
        }

        /**
         * @var int
         *
         * @ORM\Column(name="id", type="integer", options={"unsigned":true})
         * @ORM\Id
         * @ORM\GeneratedValue(strategy="IDENTITY")
         */
        private $id;

        /**
         * @var string
         *
         * @ORM\Column(name="file_name", type="string", length=255)
         */
        private $file_name;

        /**
         * @var int
         *
         * @ORM\Column(name="sort_no", type="smallint", options={"unsigned":true})
         */
        private $sort_no;

        /**
         * @var \DateTime
         *
         * @ORM\Column(name="create_date", type="datetimetz")
         */
        private $create_date;

        /**
         * @var \Plugin\ProductReview42\Entity\ProductReview
         *
         * @ORM\ManyToOne(targetEntity="Plugin\ProductReview42\Entity\ProductReview", inversedBy="ProductReviewImage")
         * @ORM\JoinColumns({
         *   @ORM\JoinColumn(name="product_review_id", referencedColumnName="id")
         * })
         */
        private $ProductReview;

        /**
         * @var \Eccube\Entity\Member
         *
         * @ORM\ManyToOne(targetEntity="Eccube\Entity\Member")
         * @ORM\JoinColumns({
         *   @ORM\JoinColumn(name="creator_id", referencedColumnName="id")
         * })
         */
        private $Creator;

        /**
         * Get id.
         *
         * @return int
         */
        public function getId()
        {
            return $this->id;
        }

        /**
         * Set fileName.
         *
         * @param string $fileName
         *
         * @return ProductReviewImage
         */
        public function setFileName($fileName)
        {
            $this->file_name = $fileName;

            return $this;
        }

        /**
         * Get fileName.
         *
         * @return string
         */
        public function getFileName()
        {
            return $this->file_name;
        }

        /**
         * Set sortNo.
         *
         * @param int $sortNo
         *
         * @return ProductReviewImage
         */
        public function setSortNo($sortNo)
        {
            $this->sort_no = $sortNo;

            return $this;
        }

        /**
         * Get sortNo.
         *
         * @return int
         */
        public function getSortNo()
        {
            return $this->sort_no;
        }

        /**
         * Set createDate.
         *
         * @param \DateTime $createDate
         *
         * @return ProductReviewImage
         */
        public function setCreateDate($createDate)
        {
            $this->create_date = $createDate;

            return $this;
        }

        /**
         * Get createDate.
         *
         * @return \DateTime
         */
        public function getCreateDate()
        {
            return $this->create_date;
        }

        /**
         * Set ProductReview.
         *
         * @param \Plugin\ProductReview42\Entity\ProductReview|null $ProductReview
         *
         * @return ProductReviewImage
         */
        public function setProductReview(ProductReview $ProductReview = null)
        {
            $this->ProductReview = $ProductReview;

            return $this;
        }

        /**
         * Get ProductReview.
         *
         * @return \Plugin\ProductReview42\Entity\ProductReview|null
         */
        public function getProductReview()
        {
            return $this->ProductReview;
        }

        /**
         * Set creator.
         *
         * @param \Eccube\Entity\Member|null $creator
         *
         * @return ProductReviewImage
         */
        public function setCreator(Member $creator = null)
        {
            $this->Creator = $creator;

            return $this;
        }

        /**
         * Get creator.
         *
         * @return \Eccube\Entity\Member|null
         */
        public function getCreator()
        {
            return $this->Creator;
        }
    }
}
