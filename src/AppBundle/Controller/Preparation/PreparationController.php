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
use Symfony\Component\HttpKernel\Exception\PreconditionFailedHttpException ;

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
     *     summary="Get the list of preparation not finished yet.",
     *     @SWG\Response(
     *         response="200",
     *         description="Returned when successful",
     *         @Model(type="\AppBundle\Entity\Preparation\Preparation")
     *     )
     * )
     *
     * @Rest\View(serializerGroups={"base", "preparation"})
     * @Rest\Get("/preparations/notfinish");
     */
    public function getPreparationsNotFinishedAction(Request $request) {

        $em = $this->getDoctrine()->getManager();
        $preparations = $em->getRepository(Preparation::class)->findBy(array("endTime" => NULL));

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

        $em = $this->getDoctrine()->getManager();

        $user = $em->getRepository(User::class)->findOneById($request->get('userId'));
        $userStrength = $user->getCaracteristic()->getStrength();
        $weightOfPreparation = 0;
        $maxWeightUser = 1.5*$userStrength;

        if (empty($user)) {
            throw new NotFoundHttpException($this->trans('userId.error.notFound'));
        }

        if($user->getCaracteristic()->getCurrentStamina() == 0) {
            throw new PreconditionFailedHttpException($this->trans('user.error.notstamina'));
        }

        $preparation = new Preparation();
        $preparation->setUser($user);
        $preparation->setStartTime(new DateTime());
        $em->persist($preparation);

        // BEGIN - UNCOMPLETE ORDER
        $uncompleteOrders = $em->getRepository(Order::class)->findBy(array("status" => "uncomplete"), array("createdAt" => "ASC")); // Order[]
        if($uncompleteOrders) {

            $orderTake = false;

            foreach($uncompleteOrders as $uncompleteOrder) { // Order
                $orderProducts = $uncompleteOrder->getProducts(); // OrderProduct[]
                foreach($orderProducts as $orderProduct) {
                    if($orderProduct->getUncomplete() == true) {
                        $productToAdd = $orderProduct->getProduct();
                        $productWeight = $productToAdd->getWeight();

                        if(($weightOfPreparation + $productWeight) < $maxWeightUser) {
                            $weightOfPreparation +=  $productToAdd->getWeight();
                            $orderTake = true;
                        }
                    }
                }

                if($orderTake) {
                    // Create PreparationOrder for each uncomplete order
                    $po = new PreparationOrder();
                    $po->setOrder($uncompleteOrder);
                    $po->setPreparation($preparation);
                    $uncompleteOrder->setStatus("work");

                    $em->persist($po);
                    $em->persist($uncompleteOrder);

                    $this->generateCourseOfUncompleteOrder($uncompleteOrder,$preparation);
                }

                $orderTake = false;
            }

        }
        // END - UNCOMPLETE ORDER

        $em->flush();

        // BEGIN - OLDER ORDER
        if($weightOfPreparation < $maxWeightUser) {
            // Get the older order
            $olderOrder = $em->getRepository(Order::class)->findOneBy(array("status" => "waiting"), array("createdAt" => "ASC"), 1);

            if(empty($olderOrder)) {
                throw new NotFoundHttpException($this->trans('preparation.error.notFound'));
            }

            $olderOrder->setStatus("work");

            $newPreparationOrder = new PreparationOrder();
            $newPreparationOrder->setOrder($olderOrder);
            $newPreparationOrder->setPreparation($preparation);

            // We fill new course entity for the first order
            $this->generateCourseOfOrder($olderOrder,$preparation);
            $em->persist($newPreparationOrder);
            $em->persist($olderOrder);

            $weightOfPreparation += $olderOrder->getWeight();
        }
        // END - OLDER ORDER

        $em->flush();

        // BEGIN - OTHER ORDERS
        $productPositionOfPreparation = $this->getProductPositionsOfPreparation($preparation);
        while($weightOfPreparation < $maxWeightUser) {
            $olderOrders = $em->getRepository(Order::class)->findBy(array("status" => "waiting"), array("createdAt" => "ASC"), 30);
            if($olderOrders) {

                $orderMinDistance = $olderOrders[0];
                $productposition = $this->getProductPositionsOfOrder($orderMinDistance);
                $minDistance = $this->getDistanceForProducts($productposition);

                // We compare the current preparation with the 30 older orders
                foreach($olderOrders as $order) {
                    $productPositionOfOrder = $this->getProductPositionsOfOrder($order);
                    $listProductsPositions = array_merge($productPositionOfPreparation,$productPositionOfOrder);

                    // Ordering the productPosition list (to use getDistanceForProducts)
                    $listProductsPositions = $this->orderProductsPositions($listProductsPositions);

                    $distance = $this->getDistanceForProducts($listProductsPositions);
                    if($distance < $minDistance) {
                        $orderMinDistance = $order;
                        $minDistance = $distance;
                    }
                }

                $newPreparationOrder = new PreparationOrder();
                $newPreparationOrder->setOrder($orderMinDistance);
                $newPreparationOrder->setPreparation($preparation);

                $this->generateCourseOfOrder($orderMinDistance,$preparation);

                $weightOfPreparation += $orderMinDistance->getWeight();
                $orderMinDistance->setStatus("work");
                $em->persist($newPreparationOrder);
                $em->persist($orderMinDistance);
            }
        }
        // END - OTHER ORDERS

        $em->flush();
        return $preparation;
    }

    /**
     * Generate the Course Entity for an Order and a Preparation
     * @param $order
     * @param $preparation
     */
    private function generateCourseOfOrder($order,$preparation) {

        $em = $this->getDoctrine()->getManager();

        $orderProducts = $em->getRepository(OrderProduct::class)->findBy(array("order" => $order));

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

        $em->flush();
    }

    private function generateCourseOfUncompleteOrder($order,$preparation) {

        $em = $this->getDoctrine()->getManager();

        $orderProducts = $em->getRepository(OrderProduct::class)->findBy(array("order" => $order));

        foreach($orderProducts as $op) {
            if($op->getUncomplete() == true) {
                $product = $op->getProduct();
                $quantity = $op->getQuantity() - $op->getCount();
                $bestPosition = $this->getProductPosition($product->getId()); // bestPosition is the position with higher quantity

                $newCourse = new Course();
                $newCourse->setPreparation($preparation);
                $newCourse->setProductPosition($bestPosition);
                $newCourse->setQuantity($quantity);

                $em->persist($newCourse);
            }
        }

        $em->flush();
    }

    private function getProductPositionsOfPreparation($preparation) {
        $toReturn = array();

        $em = $this->getDoctrine()->getManager();
        // Order important here for "getDistanceFroProducts"
        $courses = $em->getRepository(Course::class)->findBy(array("preparation" => $preparation), array("productPosition" => "ASC"));

        foreach($courses as $course) {
            array_push($toReturn,$course->getProductPosition());
        }

        return $toReturn;
    }

    private function getProductPosition($productId) {
        $em = $this->getDoctrine()->getManager();

        // We take the productposition which have the greateast quantity
        return $em->getRepository(ProductPosition::class)->findOneBy(array("product" => $productId), array("quantity" => "DESC"), 1);
    }

    private function getProductPositionsOfOrder($order) {
        $toReturn = array();

        $em = $this->getDoctrine()->getManager();

        $orderProducts = $em->getRepository(OrderProduct::class)->findBy(array("order" => $order));

        foreach($orderProducts as $orderProduct) {

            $bestPosition = $this->getProductPosition($orderProduct->getProduct()->getId());
            array_push($toReturn,$bestPosition);

        }

        return $toReturn;
    }

    private function orderProductsPositions($listProductsPositions) {
        $toReturn = array();

        if($listProductsPositions) {
            $lastProductPosition = $listProductsPositions[0];
            array_push($toReturn,$lastProductPosition);
            unset($listProductsPositions[0]);
            foreach($listProductsPositions as $productPosition) {
                if($productPosition->getId() < $lastProductPosition->getId()) {
                    array_unshift($toReturn,$productPosition);
                }
                else {
                    array_push($toReturn,$productPosition);
                }
                $lastProductPosition = $productPosition;
            }
        }

        // array_unshift($toReturn,);
        // array_push($toReturn,)
        return $toReturn;
    }

    /*
    * Calculate the distance for an array of productPosition
    * productPositions must be ordered by id
    */
    private function getDistanceForProducts($productPositions) {

        // Initialize position and number of line
        // A = 65...
        $currentLane = ord($productPositions[0]->getPosition()->getLane());
        $numberLane = $currentLane%2 == 0 ? 2 : 1;
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
        // Fix : Error during the first generation (offset -1)
        $countPos = count($productPositions);

        if($countPos > 0) {
            --$countPos;
        }

        if(isset($productPositions[$countPos])) {
            $lastPosition = ord($productPositions[$countPos]->getPosition()->getLane());
            $numberLane += $lastPosition%2 == 0 ? 0 : 1;
        }

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


        $preparationOrders = $em->getRepository(PreparationOrder::class)
            ->findByPreparation($preparation);


        if(!empty($preparationOrders)) {
            foreach ($preparationOrders as $preparationOrder) {
                $order = $preparationOrder->getOrder();
                $order->setStatus("finished");
                $em->persist($order);
            }
        }

        $em->persist($preparation);
        $em->flush();

        return $preparation;
    }
}
