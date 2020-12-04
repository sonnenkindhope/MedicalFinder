<?php
namespace App\Controller;

use App\Exception\InternalException;
use App\Exception\NoContentException;
use App\Service\Medicals\Manager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class MedicalController
 * @package App\Controller
 */
class MedicalController extends AbstractController implements \Psr\Log\LoggerAwareInterface
{
    use \Psr\Log\LoggerAwareTrait;
    use \App\Utils\RequestValidation;

    /**
     *
     *
     * @var \App\Service\Medicals\Manager
     */
    private \App\Service\Medicals\Manager $manager;

    /**
     * MedicalController constructor.
     *
     * @param \App\Service\Medicals\Manager $service
     */
    public function __construct(Manager $service)
    {
        $this->manager = $service;
    }

    /**
     * get medical praticioner by symptoms
     *
     * @Route("/medicals")
     *
     * @param  \Symfony\Component\HttpFoundation\Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(Request $request): Response
    {
        $searchTerm = $request->query->get('param');

        if ($this->validateTerm($searchTerm) === true) {
            $this->logger->debug('search term validated as true', ['term' => $searchTerm]);
            try {
                /** @var string $term */
                $term = $searchTerm;
                $items = $this->manager->search($term);
                $result = $this->manager->mapForJson($items);

                $response = new JsonResponse($result);
            } catch (NoContentException $e) {
                $this->logger->info('no medical found for term', ['term' => $searchTerm]);
                $response = new Response('', Response::HTTP_NO_CONTENT);
            } catch (InternalException|\Throwable $e) {
                $response = new Response('', Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        } else {
            $this->logger->debug('search term validated as false', ['term' => $searchTerm]);
            $response = new Response('', Response::HTTP_BAD_REQUEST);
        }

        return $response;
    }
}
