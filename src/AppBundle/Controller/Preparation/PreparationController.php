<?php

namespace AppBundle\Controller\Preparation;

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
use AppBundle\Entity\Preparation\Preparation;
use AppBundle\Entity\Product\Product;
use AppBundle\Entity\Product\Position;

// Form
use AppBundle\Form\Type\Preparation\PreparationType;

class PreparationController extends ControllerBase {

    /**
     * @Operation(
     *     tags={"Preparation"},
     *     summary="Get the list of preparation.",
     *     @SWG\Response(
     *         response="200",
     *         description="Returned when successful",
     *         @Model(type="\AppBundle\Entity\Preparation\Preparation")
     *     )
     * )
     *
     * @Rest\View(serializerGroups={"base", "preparation"})
     * @Rest\Get("/preparations");
     */
    public function getPreparationsAction(Request $request) {

        $em = $this->getDoctrine()->getManager();
        $preparations = $em->getRepository(Preparation::class)->findAll();

        return $preparations;
    }

    /**
     * @Operation(
     *     tags={"Preparation"},
     *     summary="Get a preparation by identifier.",
     *     @SWG\Response(
     *         response="200",
     *         description="Returned when successful",
     *         @Model(type="\AppBundle\Entity\Preparation\Preparation")
     *     )
     * )
     *
     * @Rest\View(serializerGroups={"base", "preparation"})
     * @Rest\Get("/preparations/{id}")
     */
    public function getPreparationAction(Request $request) {

        $em = $this->getDoctrine()->getManager();
        $preparation = $em->getRepository(Preparation::class)->find($request->get('id'));

        if (empty($preparation)) {
            throw new NotFoundHttpException($this->trans('preparation.error.notFound'));
        }

        return $preparation;
    }

    /**
     * @Operation(
     *     tags={"Preparation"},
     *     summary="Remove a preparation.",
     *     @SWG\Response(
     *         response="200",
     *         description="Returned when successful"
     *     )
     * )
     *
     * @Rest\View(statusCode=Response::HTTP_NO_CONTENT)
     * @Rest\Delete("/preparations/{id}")
     */
    public function removePreparationAction(Request $request) {

        $em = $this->getDoctrine()->getManager();
        $preparation = $em->getRepository(Preparation::class)->find($request->get('id'));

        if ($preparation) {
            $em->remove($preparation);
            $em->flush();
        }
    }

    /**
     * @Operation(
     *     tags={"Preparation"},
     *     summary="Partialy update the informations of a preparation.",
     *     @SWG\Parameter(
     *         name="body", in="body", required=true,
     *         @Model(type=PreparationType::class)
     *     ),
     *     @SWG\Response(
     *         response="200",
     *         description="OK",
     *         @Model(type="\AppBundle\Entity\Preparation\Preparation")
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
     * @Rest\View(serializerGroups={"base", "preparation"})
     * @Rest\Patch("/preparations/{id}")
     */
    public function patchPreparationAction(Request $request) {
        $em = $this->getDoctrine()->getManager();

        $preparation = $em->getRepository(Preparation::class)
                ->find($request->get('id'));

        if (empty($preparation)) {
            throw new NotFoundHttpException($this->trans('preparation.error.notFound'));
        }

        $form = $this->createForm(PreparationType::class, $preparation, []);

        $form->submit($request->request->all(), false);

        if ($form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $em->persist($preparation);
            $em->flush();

            return $preparation;
        } else {
            return $form;
        }
    }

    /*
    * Calculate the distance for an array of productPosition
    * productPositions must be ordered by id
    */
    private function getDistanceForProducts($productPositions) {


        // Initialize position and number of line
        // A = 65...
        $currentLane = ord($productPositions[0]->getPosition()->getLane());
        $numberlane = $currentLane%2 == 0 ? 2 : 1;
        unset($productPositions[0]);

        foreach($productPositions as $productPosition) {

            // A = 65
            $numLane = ord($productPosition->getPosition()->getLane());

            if($numLane != $currentLane) {

                $diff = $numLane - $currentLane;

                // $numberlane += $diff%2 == 0 ? 2 : 1;
                if($diff%2 == 0) {
                    // Si changement de lane paire => +2 number lane
                    // On doit entrer dans une lane mais on n'est pas dans le bon sens, on a 1 lane en plus à parcourir
                    $numberLane += 2;
                }
                else {
                    // Si changement de lane impaire => +1 number lane
                    $numberLane += 1;
                }

                $currentLane = $numLane;
            }
        }

        // Going to the exit of the picking zone
        $lastPosition = ord($productPositions[count($productPositions)-1]->getPosition()->getLane());
        $numberlane += $lastPosition%2 == 0 ? 0 : 1;

        return $numberLane;
    }

}
