<?php

namespace Application\Presta\CMSCoreBundle\Factory;

use Presta\CMSCoreBundle\Doctrine\Phpcr\Page;
use Presta\CMSCoreBundle\Factory\ModelFactoryInterface;
use Presta\CMSCoreBundle\Factory\PageFactory as BasePageFactory;

/**
 * @author Nicolas Bastien <nbastien@prestaconcept.net>
 */
class PageFactory extends BasePageFactory implements ModelFactoryInterface
{
    /**
     * {@inherit}
     */
    protected function configureZones(array $page)
    {
        if (!isset($page['zones']['content'])) {
            $page['zones']['content'] = array(
                'name' => 'content',
                'blocks' => array(
                    10 => array('name' => 'info', 'type' => 'presta_cms.block.simple'),
                    15 => array('name' => 'main', 'type' => 'presta_cms.block.simple')
                )
            );
        }
        if (!isset($page['zones']['left']) && $page['template'] == 'left-sidebar') {
            $page['zones']['left'] = array(
                'name' => 'left',
                'blocks' => array(
                    5 => array('name' => 'user-info', 'type' => 'presta_cms_user.block.user_info'),
                    10 => array('name' => 'admin', 'type' => 'presta_cms.block.simple'),
                    15 => array('name' => 'help', 'type' => 'presta_cms.block.simple'),
                    20 => array('type' => 'presta_cms.block.media'),
                    30 => array('type' => 'presta_cms.block.ajax'),
                )
            );
        }
        if (!isset($page['zones']['right']) && $page['template'] == 'right-sidebar') {
            $page['zones']['right'] = array(
                'name' => 'right',
                'blocks' => array(
                    5 => array('name' => 'user-info', 'type' => 'presta_cms_user.block.user_info'),
                    10 => array('name' => 'admin', 'type' => 'presta_cms.block.simple'),
                    15 => array('name' => 'help', 'type' => 'presta_cms.block.simple'),
                    20 => array('type' => 'presta_cms.block.media'),
                    30 => array('type' => 'presta_cms.block.ajax'),
                )
            );
        }

        if (count($page['children']) > 1) {
            $page['zones']['content']['blocks'][20] = array('name' => 'children', 'type' => 'presta_cms.block.page_children');
        }

        return $page;
    }

    /**
     * {@inherit}
     */
    protected function configureBlock(array $block)
    {
        $block = parent::configureBlock($block);

        if (count($block['settings'])) {
            return $block;
        }

        $block['editable']  = true;
        $block['deletable'] = true;

        switch ($block['type']) {
            case 'presta_cms.block.simple':
                if ($block['name'] == 'info') {
                    $block['settings'] = array(
                        'en' => array(
                            'title' => '',
                            'block_style' => 'info',
                            'content' => 'This is a demonstration website<br/>Content is refresh every 2 hours.',
                        ),
                        'fr' => array(
                            'title' => '',
                            'block_style' => 'info',
                            'content' => 'Ceci est une démonstration.<br/>Le contenu du site est remis à jour toutes les 2 heures.'
                        )
                    );
                } elseif ($block['name'] == 'help') {
                    $block['settings'] = array(
                        'en' => array(
                            'title' => 'Need help ?',
                            'block_style' => 'info',
                            'content' => 'If you need help or want to have some news about PrestaCMS development, please register to our <br/><a class="btn link" href="https://groups.google.com/forum/?hl=fr&fromgroups#!forum/prestacms-devs"><i class="icon-question-sign"></i>&nbsp;Google group</a>.'
                        ),
                        'fr' => array(
                            'title' => 'Besoin d\'aide ?',
                            'block_style' => 'info',
                            'content' => 'Si vous avez des questions ou que vous voulez être au courant du développement de PrestaCMS, abonner vous à notre <br/><a class="btn link" href="https://groups.google.com/forum/?hl=fr&fromgroups#!forum/prestacms-devs"><i class="icon-question-sign"></i>&nbsp;Google group</a>.'
                        )
                    );
                } elseif ($block['name'] == 'admin') {
                    $block['settings'] = array(
                        'en' => array(
                            'title' => 'Administration',
                            'block_style' => 'headline',
                            'content' => 'Discover PrestaCMS backend based on SonataAdmin to easily administrate your website <br/><a class="btn link" href="/admin"><i class="icon-wrench"></i>&nbsp;Login</a>.'
                        ),
                        'fr' => array(
                            'title' => 'Administration',
                            'block_style' => 'headline',
                            'content' => 'Découvrez le backend de PrestaCMS pour facilement administrer le site <br/><a class="btn link" href="/admin"><i class="icon-wrench"></i>&nbsp;Connexion</a>.'
                        )
                    );
                } else {
                    $block['settings'] = array(
                        'en' => array(
                            'title' => 'This is a paragraph block',
                            'content' => 'This is your text. You can edit it in the administration with a WYSIWYG editor.<br/><br/>This content is translatable and has been loaded by PrestaCMS fixtures.',
                        ),
                        'fr' => array(
                            'title' => 'Exemple de bloc paragraphe',
                            'content' => 'Ce bloc est administrable dans le backoffice avec un éditeur WYSIWYG. <br/><br/>Le contenu de ce bloc est traduisible et à été chargé par les fixtures du PrestaCMS.'
                        )
                    );
                }
                break;
            case 'presta_cms.block.page_children':
                $block['settings'] = array(
                    'en' => array(
                        'title' => 'This is a page children block',
                        'content' => 'This block displays all page children, each child rendering can be customize by taking advantage of the pages types possibilities.'
                    ),
                    'fr' => array(
                        'title' => 'Exemple de bloc page pallier',
                        'content' => 'Ce bloc vous permet de présenter la liste des pages enfants.<br/><br/>Pour chacun d\'eux le bloc affiche son titre, sa description ainsi qu\'un lien permettant d\'accéder à la page.'
                    )
                );
                break;
            case 'presta_cms.block.media':
                $media = rand(1, 30);
                $block['settings'] = array(
                    'en' => array('media' => (string)$media, 'format' => 'prestacms_page_sidebar'),
                    'fr' => array('media' => (string)$media, 'format' => 'prestacms_page_sidebar')
                );
                break;
            case 'presta_cms.block.media_advanced':
                $block['settings'] = array(
                    'en' => array(
                        'title' => 'Advanced Media Block',
                        'content' => 'This block type allow you to add a media with a tile, a content and an layout option to choose how the block should display.',
                        'media' => 4,
                        'format' => 'prestacms_page_wide'
                    ),
                    'fr' => array(
                        'title' => 'Bloc Média Avancé',
                        'content' => 'Ce bloc vous permet d\'ajouter un média (image, viadeo... suivant votre configuration projet) avec un titre et un contenu. Il est également possible de choisir le style d\'affiche à l\'aide le l\'option "layout"',
                        'media' => 2,
                        'format' => 'prestacms_page_wide'
                    )
                );
                break;
            case 'presta_cms.block.ajax':
                $routeMapping = array(
                    1 => 'paristime',
                    2 => 'latime',
                );
                $routeKey = rand(1, 2);
                $block['settings'] = array(
                    'en' => array('route' => $routeMapping[$routeKey]),
                    'fr' => array('route' => $routeMapping[$routeKey]),
                );
                $block['editable']  = false;
                $block['deletable'] = false;
                break;
            case 'presta_cms_user.block.user_info':
                $block['settings']['block_style'] = 'headline';
        }

        return $block;
    }
}
