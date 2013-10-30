<?php
/**
 * This file is part of the PrestaCMS Sandbox
 *
 * (c) PrestaConcept <http://www.prestaconcept.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Application\Presta\CMSCoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Translation\Translator;

/**
 * @author Mathieu Cottet <mcottet@prestaconcept.net>
 */
class AjaxController extends Controller
{
    /**
     * display time in Paris
     *
     * @return Response
     */
    public function parisTimeAction()
    {
        return new Response(
            $this->getMessage(
                'Paris',
                'Europe/Paris'
            )
        );
    }

    /**
     * Display time in Los Angeles
     *
     * @return Response
     */
    public function laTimeAction()
    {
        return new Response(
            $this->getMessage(
                'El Pueblo de Nuestra Señora la Reina de Los Ángeles del Río de Porciúncula',
                'America/Los_Angeles'
            )
        );
    }

    /**
     * @param string $timeZone
     *
     * @return string
     */
    private function getTime($timeZone)
    {
        $date = new \DateTime();
        $date->setTimezone(new \DateTimeZone($timeZone));

        return $date->format("H:i:s");
    }

    /**
     * @param string $city
     * @param string $timeZone
     *
     * @return string
     */
    private function getMessage($city, $timeZone)
    {
        /** @var Translator $translator */
        $translator = $this->get('translator');

        return $translator->trans(
            'time.message',
            array(
                '%city%' => $city,
                '%time%' => $this->getTime($timeZone),
            ),
            'sandbox'
        );
    }
}
