<?php


namespace App\Controller;

use App\Exception\InternalException;
use App\Exception\NoContentException;
use App\Service\Symptoms\Manager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class SymptomsController
 * @package App\Controller
 */
class SymptomsController extends AbstractController implements \Psr\Log\LoggerAwareInterface
{
    use \Psr\Log\LoggerAwareTrait;
    use \App\Utils\RequestValidation;

    /**
     *
     *
     * @var \App\Service\Symptoms\Manager
     */
    private Manager $manager;

    /**
     * SymptomsController constructor.
     *
     * @param Manager $manager
     */
    public function __construct(Manager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * get symptoms by
     *
     * @Route("/symptoms/autocomplete")
     *
     * @param  \Symfony\Component\HttpFoundation\Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(Request $request) : Response
    {
        /** @var string|null $searchTerm */
        $searchTerm = $request->query->get('param');

        if ($this->validateTerm($searchTerm) === true) {
            try {
                /** @var string $term */
                $term = $searchTerm;
                $items = $this->manager->search($term);
                $result = $this->manager->mapForAcJson($items);

                $response = new JsonResponse($result);
            } catch (NoContentException $e) {
                $response = new Response('', Response::HTTP_NO_CONTENT);
            } catch (InternalException|\Throwable $e) {
                $response = new Response('', Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        } else {
            $response = new Response('', Response::HTTP_BAD_REQUEST);
        }

        return $response;
    }
}
