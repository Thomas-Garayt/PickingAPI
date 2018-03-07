<?php

namespace AppBundle\Controller\Preparation;

// Required dependencies for Controller and Annotations
use \AppBundle\Controller\ControllerBase;
use AppBundle\Form\Type\Preparation\PreparationType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use Nelmio\ApiDocBundle\Annotation\Operation;
use Nelmio\ApiDocBundle\Annotation\Model;
use Swagger\Annotations as SWG;
use FOS\RestBundle\Request\ParamFetcher;
use \Doctrine\Common\Collections\ArrayCollection;
use \Datetime;

// Exception
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

// Entity
use AppBundle\Entity\User;
use AppBundle\Entity\Preparation\Preparation;
use AppBundle\Entity\Preparation\PreparationOrder;
use AppBundle\Entity\Product\Product;
use AppBundle\Entity\Product\ProductPosition;
use AppBundle\Entity\Order\Order;
use AppBundle\Entity\Order\OrderProduct;
use AppBundle\Entity\Course\Course;

// Form

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
     *     summary="Add a new preparation.",
     *     @SWG\Response(
     *         response="201",
     *         description="Created",
     *         @Model(type="\AppBundle\Entity\Preparation\Preparation")
     *     ),
     *     @SWG\Response(
     *         response="400",
     *         description="Bad Request"
     *     )
     * )
     *
     * @Rest\View(statusCode=Response::HTTP_CREATED, serializerGroups={"base", "preparation"})
     * @Rest\Post("/preparations/{userId}")
     */
    public function postPreparationsAction(Request $request) {

        $preparation = new Preparation();

        $em = $this->getDoctrine()->getManager();

        $user = $em->getRepository(User::class)->findOneById($request->get('userId'));

        if (empty($user)) {
            throw new NotFoundHttpException($this->trans('userId.error.notFound'));
        }

        $preparation->setUser($user);
        $preparation->setStartTime(new DateTime());

        // TODO : Generate PreparationOrder
        // TODO : Ne pas oublier les order uncompleted

        // Get the older order
        $olderOrder = $em->getRepository(Order::class)->findOneBy(array("status" => "waiting"), array("createdAt" => "ASC"), 1);

        $olderOrder->setStatus("work");

        // Create the first PreparationOrder
        $newPreparationOrder = new PreparationOrder();
        $newPreparationOrder->setOrder($olderOrder);
        $newPreparationOrder->setPreparation($preparation);

        // We fill new course entity for the first order
        $orderProducts = $em->getRepository(OrderProduct::class)->findBy(array("order" => $olderOrder));

        foreach($orderProducts as $op) {
            $product = $op->getProduct();
            $quantity = $op->getQuantity();
            $bestPosition = $this->getProductPosition($product->getId()); // bestPosition is the position with higher quantity

            $newCourse = new Course();
            $newCourse->setPreparation($preparation);
            $newCourse->setProductPosition($bestPosition);
            $newCourse->setQuantity($quantity);

            $em->persist($newCourse);
        }

        // Get the next order while the user can carry them

        $em->persist($olderOrder);
        $em->persist($newPreparationOrder);
        $em->persist($preparation);
        $em->flush();

        return $preparation;
    }

    private function getProductPosition($productId) {
        $em = $this->getDoctrine()->getManager();

        // We take the productposition which have the greateast quantity
        return $em->getRepository(ProductPosition::class)->findOneBy(array("product" => $productId), array("quantity" => "DESC"), 1);;
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


    /**
     * @Operation(
     *     tags={"Preparation"},
     *     summary="Finish a preparation by identifier.",
     *     @SWG\Response(
     *         response="200",
     *         description="Returned when successful",
     *         @Model(type="\AppBundle\Entity\Preparation\Preparation")
     *     )
     * )
     *
     * @Rest\View(serializerGroups={"base", "preparation"})
     * @Rest\Get("/preparations/finish/{id}")
     */
    public function getFinishPreparationAction(Request $request) {
        $em = $this->getDoctrine()->getManager();

        $preparation = $em->getRepository(Preparation::class)
            ->find($request->get('id'));

        if (empty($preparation)) {
            throw new NotFoundHttpException($this->trans('preparation.error.notFound'));
        }

        $preparation->setEndTime(new DateTime());

        $em->persist($preparation);
        $em->flush();

        return $preparation;
    }
}
