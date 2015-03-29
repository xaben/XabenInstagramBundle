<?php

namespace Xaben\InstagramBundle\Block;

use Sonata\AdminBundle\Form\FormMapper;
use Sonata\BlockBundle\Block\BaseBlockService;
use Sonata\BlockBundle\Block\BlockContextInterface;
use Sonata\BlockBundle\Model\BlockInterface;
use Sonata\CoreBundle\Validator\ErrorElement;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Xaben\InstagramBundle\Service\InstagramBridge;

class InstagramBlock extends BaseBlockService
{
    protected $bridge;

    protected $config;

    /**
     * @param string $name
     * @param EngineInterface $templating
     * @param InstagramBridge $bridge
     * @param array $config
     */
    public function __construct($name, EngineInterface $templating, InstagramBridge $bridge, $config)
    {
        $this->name = $name;
        $this->templating = $templating;
        $this->bridge = $bridge;
        $this->config = $config;
    }

    public function setDefaultSettings(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'limit' => 6,
            'template' => 'XabenInstagramBundle:Block:instagram.html.twig',
        ));
    }

    public function buildEditForm(FormMapper $formMapper, BlockInterface $block)
    {
        $formMapper->add('settings', 'sonata_type_immutable_array', array(
            'keys' => array(
                array('userId', 'integer', array('required' => true)),
                array('limit', 'integer', array('required' => false)),
            )
        ));
    }

    public function validateBlock(ErrorElement $errorElement, BlockInterface $block)
    {
        $errorElement
            ->with('settings.userId')
                ->assertNotNull(array())
                ->assertNotBlank()
            ->end()
            ->with('settings.limit')
                ->assertNotNull(array())
                ->assertNotBlank()
            ->end();
    }

    public function execute(BlockContextInterface $blockContext, Response $response = null)
    {
        // merge settings
        $settings = $blockContext->getSettings();

        $photos = $this->bridge->getRecentPhotos($settings['userId'], $settings['limit']);
        $photos = $photos->data;

        return $this->renderResponse($blockContext->getTemplate(), array(
            'block' => $blockContext->getBlock(),
            'settings' => $settings,
            'photos' => $photos
        ), $response);
    }
}
