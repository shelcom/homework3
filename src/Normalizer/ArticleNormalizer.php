<?php

namespace App\Normalizer;

use App\Entity\Article;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class ArticleNormalizer implements NormalizerInterface
{
    /**
     * {@inheritdoc}
     */
    public function normalize($object, $format = null, array $context = [])
    {
        return [
            'id' => $object->getId(),
            'text' => $object->getText(),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function supportsNormalization($data, $format = null)
    {
        return $data instanceof Article;
    }
}
//
//class TopicNormalizer implements NormalizerInterface
//{
//    private $router;
//    private $normalizer;
//
//    public function __construct(UrlGeneratorInterface $router, ObjectNormalizer $normalizer)
//    {
//        $this->router = $router;
//        $this->normalizer = $normalizer;
//    }
//
//    public function normalize($topic, $format = null, array $context = array())
//    {
//        $data = $this->normalizer->normalize($topic, $format, $context);
//
//        // Here, add, edit, or delete some data:
//        $data['href']['self'] = $this->router->generate('topic_show', array(
//            'id' => $topic->getId(),
//        ), UrlGeneratorInterface::ABSOLUTE_URL);
//
//        return $data;
//    }
//
//    public function supportsNormalization($data, $format = null)
//    {
//        return $data instanceof Topic;
//    }
//}
