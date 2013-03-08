<?php
/**
 * This file is part of the Presta Bundle project.
 *
 * @author     Nicolas Bastien <nbastien@prestaconcept.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Application\Presta\CMSCoreBundle\DataFixtures\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Gaufrette\Filesystem;
use Gaufrette\Adapter\Local as LocalAdapter;
use Application\Sonata\MediaBundle\Entity\Media;
use Application\Sonata\MediaBundle\Entity\Gallery;
use Application\Sonata\MediaBundle\Entity\GalleryHasMedia;

/**
 * Media fixtures
 */
class LoadMedia implements FixtureInterface
{
    /**
     * Création des pages de contenu
     *
     * @param \Doctrine\Common\Persistence\ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $adapter = new LocalAdapter(__DIR__ . '/../data/media');
        $filesystem = new Filesystem($adapter);

        $listKeys = $filesystem->listKeys();
        $medias = $listKeys['keys'];
        $galleries = array_unique($listKeys['dirs']);
        $galleryMedia = array();

        $path = __DIR__ . '/../data/media/';
        //Media Creation
        foreach ($medias as $file) {
            $media = new Media();
            $media->setBinaryContent($path . $file);
            $media->setEnabled(true);
            $media->setContext('prestacms');
            $media->setProviderName('sonata.media.provider.image');

            $manager->persist($media);

            $galleryName = substr($file, 0, strpos($file, '/'));
            if (!isset($galleryMedia[$galleryName])) {
                $galleryMedia[$galleryName] = array();
            }
            $galleryMedia[$galleryName][] = $media;
        }

        //Gallery creation
        foreach ($galleries as $galleryName) {
            $gallery = new Gallery();
            $gallery->setEnabled(true);
            $gallery->setContext('prestacms');
            $gallery->setDefaultFormat('prestacms_gallery_horizontal_thumb');
            $gallery->setName($galleryName);

            $manager->persist($gallery);

            //Add medias
            foreach ($galleryMedia[$galleryName] as $media) {
                $galleryHasMedia = new GalleryHasMedia();
                $galleryHasMedia->setEnabled(true);
                $galleryHasMedia->setGallery($gallery);
                $galleryHasMedia->setMedia($media);
                $manager->persist($galleryHasMedia);
            }

            $manager->flush();
        }
    }
}
