<?php

/*
 * This file is part of EC-CUBE
 *
 * Copyright(c) EC-CUBE CO.,LTD. All Rights Reserved.
 *
 * http://www.ec-cube.co.jp/
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Plugin\ProductReview42\Form\Type;

use Eccube\Common\EccubeConfig;
use Eccube\Form\Type\Master\SexType;
use Plugin\ProductReview42\Entity\ProductReview;
use Plugin\ProductReview42\Service\FileUploader;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class ProductReviewType
 * [商品レビュー]-[レビューフロント]用Form.
 */
class ProductReviewType extends AbstractType
{
    /**
     * @var EccubeConfig
     */
    protected $eccubeConfig;

    /**
     * @var FileUploader
     */
    private $fileUploader;

    /**
     * ProductReviewType constructor.
     *
     * @param EccubeConfig $eccubeConfig
     * @param FileUploader $fileUploader
     */
    public function __construct(EccubeConfig $eccubeConfig, FileUploader $fileUploader)
    {
        $this->eccubeConfig = $eccubeConfig;
        $this->fileUploader = $fileUploader;
    }

    /**
     * build form.
     *
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $config = $this->eccubeConfig;
        $builder
            ->add('reviewer_name', TextType::class, [
                'label' => 'product_review.form.product_review.reviewer_name',
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Length(['max' => $config['eccube_stext_len']]),
                ],
                'attr' => [
                    'maxlength' => $config['eccube_stext_len'],
                ],
            ])
            ->add('reviewer_url', TextType::class, [
                'label' => 'product_review.form.product_review.reviewer_url',
                'required' => false,
                'constraints' => [
                    new Assert\Url(),
                    new Assert\Length(['max' => $config['eccube_mltext_len']]),
                ],
                'attr' => [
                    'maxlength' => $config['eccube_mltext_len'],
                ],
            ])
            ->add('sex', SexType::class, [
                'required' => false,
            ])
            ->add('recommend_level', ChoiceType::class, [
                'label' => 'product_review.form.product_review.recommend_level',
                'choices' => array_flip([
                    '5' => '★★★★★',
                    '4' => '★★★★',
                    '3' => '★★★',
                    '2' => '★★',
                    '1' => '★',
                ]),
                'expanded' => true,
                'multiple' => false,
                'placeholder' => false,
                'constraints' => [
                    new Assert\NotBlank(),
                ],
            ])
            ->add('title', TextType::class, [
                'label' => 'product_review.form.product_review.title',
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Length(['max' => 50]),
                ],
                'attr' => [
                    'maxlength' => $config['eccube_stext_len'],
                ],
            ])
            ->add('comment', TextareaType::class, [
                'label' => 'product_review.form.product_review.comment',
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Length(['max' => $config['eccube_ltext_len']]),
                ],
                'attr' => [
                    'maxlength' => $config['eccube_ltext_len'],
                ],
            ])
            ->add('youtube_video_player', TextType::class, [
                'required' => false,
                'label' => 'product_review.form.product_review.youtube_video_player',
                'constraints' => [
                    new Assert\Length(['max' => $config['eccube_ltext_len']]),
                ],
            ])
            ->add('file', FileType::class, [
                'mapped' => false,
                'required' => false,
                'label' => 'product_review.form.product_review.image',
                'constraints' => [
                    new \Symfony\Component\Validator\Constraints\File([
                        'mimeTypes' => [
                            'image/*',
                        ]
                    ])
                ]
            ])
            ->add('filename', HiddenType::class, [
                'mapped' => false,
            ]);

        $builder
            ->addEventListener(FormEvents::POST_SET_DATA, function (FormEvent $event){
                $form = $event->getForm();
                $ProductReview = $event->getData();

                if($ProductReview instanceof ProductReview) {
                    if($ProductReview->getFilename()) {
                        // アップロード済みのファイルをFileTypeにセット
                        $form["file"]->setData(
                            new File(
                                $this->eccubeConfig['product_review_temp_image_dir'].'/'.$ProductReview->getFilename()
                            )
                        );
                    }
                }
            })
            ->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event){
                $data = $event->getData();

                if (!empty($data['file'])) {
                    $data['filename'] = $this->fileUploader->upload($data['file']);
                    unset($data['file']);
                    $event->setData($data);
                }
            })
            ->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event){
                $form = $event->getForm();
                $ProductReview = $event->getData();

                if($ProductReview instanceof ProductReview) {
                    $filename = $form['filename']->getData();

                    if($filename) {
                        $ProductReview->setFilename($filename);
                    }
                }
            });
    }
}
