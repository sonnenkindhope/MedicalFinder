<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class EntryPointController
 * @package App\Controller
 */
class EntryPointController extends AbstractController implements \Psr\Log\LoggerAwareInterface
{
    use \Psr\Log\LoggerAwareTrait;

    /**
     * Entrypoint to Onepager
     *
     * @Route("/search")
     * @codeCoverageIgnore // ignored since internal function is return, no logic
     *
     * @param  \Symfony\Component\HttpFoundation\Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     *
     */
    public function index(Request $request): Response
    {
        return $this->render('/default/index.html.twig', []);
    }
}
