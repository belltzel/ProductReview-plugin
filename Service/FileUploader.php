<?php

namespace Plugin\ProductReview42\Service;

use Eccube\Common\EccubeConfig;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileUploader
{
    /**
     * @var EccubeConfig
     */
    private $eccubeConfig;

    public function __construct(
        EccubeConfig $eccubeConfig
    ) {
        $this->eccubeConfig = $eccubeConfig;
    }

    public function upload(UploadedFile $file)
    {
        $filename = date('mdHis') . uniqid('_') . '.' . $file->guessExtension();

        $file->move(
            $this->eccubeConfig["product_review_temp_image_dir"],
            $filename
        );

        return $filename;
    }
}
