<?php

namespace AppBundle\Controller\Position;

// Required dependencies for Controller and Annotations
use \AppBundle\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use Nelmio\ApiDocBundle\Annotation\Operation;
use Nelmio\ApiDocBundle\Annotation\Model;
use Swagger\Annotations as SWG;
use FOS\RestBundle\Request\ParamFetcher;
use \Doctrine\Common\Collections\ArrayCollection;

// Exception
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

// Entity
use AppBundle\Entity\Position\Position;

// Form
use AppBundle\Form\Type\Position\PositionType;

class PositionController extends ControllerBase {

    /**
     * @Operation(
     *     tags={"Position"},
     *     summary="Get the list of position.",
     *     @SWG\Response(
     *         response="200",
     *         description="Returned when successful",
     *         @Model(type="\AppBundle\Entity\Position\Position")
     *     )
     * )
     *
     * @Rest\View(serializerGroups={"base", "position"})
     * @Rest\Get("/positions");
     */
    public function getPositionsAction(Request $request) {

        $em = $this->getDoctrine()->getManager();
        $positions = $em->getRepository(Position::class)->findAll();

        return $positions;
    }

    /**
     * @Operation(
     *     tags={"Position"},
     *     summary="Get a position by identifier.",
     *     @SWG\Response(
     *         response="200",
     *         description="Returned when successful",
     *         @Model(type="\AppBundle\Entity\Position\Position")
     *     )
     * )
     *
     * @Rest\View(serializerGroups={"base", "position"})
     * @Rest\Get("/positions/{id}")
     */
    public function getPositionAction(Request $request) {

        $em = $this->getDoctrine()->getManager();
        $position = $em->getRepository(Position::class)->find($request->get('id'));

        if (empty($position)) {
            throw new NotFoundHttpException($this->trans('position.error.notFound'));
        }

        return $position;
    }

    /**
     * @Operation(
     *     tags={"Position"},
     *     summary="Add a new position.",
     *     @SWG\Parameter(
     *         name="body", in="body", required=true,
     *         @Model(type=PositionType::class)
     *     ),
     *     @SWG\Response(
     *         response="201",
     *         description="Created",
     *         @Model(type="\AppBundle\Entity\Position\Position")
     *     ),
     *     @SWG\Response(
     *         response="400",
     *         description="Bad Request"
     *     )
     * )
     *
     * @Rest\View(statusCode=Response::HTTP_CREATED, serializerGroups={"base", "position"})
     * @Rest\Post("/positions")
     */
    public function postPositionsAction(Request $request) {

        $position = new Position();
        $form = $this->createForm(PositionType::class, $position);

        $em = $this->getDoctrine()->getManager();

        $form->submit($request->request->all());

        if ($form->isValid()) {

            $em->persist($position);
            $em->flush();

            return $position;
        } else {
            return $form;
        }
    }

    /**
     * @Operation(
     *     tags={"Position"},
     *     summary="Remove a position.",
     *     @SWG\Response(
     *         response="200",
     *         description="Returned when successful"
     *     )
     * )
     *
     * @Rest\View(statusCode=Response::HTTP_NO_CONTENT)
     * @Rest\Delete("/positions/{id}")
     */
    public function removePositionAction(Request $request) {

        $em = $this->getDoctrine()->getManager();
        $position = $em->getRepository(Position::class)->find($request->get('id'));

        if ($position) {
            $em->remove($position);
            $em->flush();
        }
    }

    /**
     * @Operation(
     *     tags={"Position"},
     *     summary="Partialy update the informations of a position.",
     *     @SWG\Parameter(
     *         name="body", in="body", required=true,
     *         @Model(type=PositionType::class)
     *     ),
     *     @SWG\Response(
     *         response="200",
     *         description="OK",
     *         @Model(type="\AppBundle\Entity\Position\Position")
     *     ),
     *     @SWG\Response(
     *         response="404",
     *         description="Not Found"
     *     ),
     *     @SWG\Response(
     *         response="400",
     *         description="Bad Request"
     *     )
     * )
     *
     * @Rest\View(serializerGroups={"base", "position"})
     * @Rest\Patch("/positions/{id}")
     */
    public function patchPositionAction(Request $request) {
        $em = $this->getDoctrine()->getManager();

        $position = $em->getRepository(Position::class)
                ->find($request->get('id'));

        if (empty($position)) {
            throw new NotFoundHttpException($this->trans('position.error.notFound'));
        }

        $form = $this->createForm(PositionType::class, $position, []);

        $form->submit($request->request->all(), false);

        if ($form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $em->persist($position);
            $em->flush();

            return $position;
        } else {
            return $form;
        }
    }

}
