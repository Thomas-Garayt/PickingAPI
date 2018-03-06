<?php
/**
 * Created by PhpStorm.
 * User: jonathan
 * Date: 05/03/18
 * Time: 18:01
 */

namespace AppBundle\Controller\Order;


use AppBundle\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use Nelmio\ApiDocBundle\Annotation\Operation;
use Nelmio\ApiDocBundle\Annotation\Model;
use Swagger\Annotations as SWG;
use FOS\RestBundle\Request\ParamFetcher;
use \Doctrine\Common\Collections\ArrayCollection;

// Entity
use AppBundle\Entity\Order\Order;
use AppBundle\Entity\Order\OrderProduct;
use AppBundle\Entity\Customer\Customer;
use AppBundle\Entity\Product\Product;

// Exception
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class OrderController extends ControllerBase {

    /**
     * @Operation(
     *     tags={"Order"},
     *     summary="Get the list of order.",
     *     @SWG\Response(
     *         response="200",
     *         description="Returned when successful",
     *         @Model(type="\AppBundle\Entity\Order\Order")
     *     )
     * )
     *
     * @Rest\View(serializerGroups={"base", "order", "customer"})
     * @Rest\Get("/orders");
     */
    public function getOrdersAction(Request $request) {

        $em = $this->getDoctrine()->getManager();
        $orders = $em->getRepository(Order::class)->findAll();

        return $orders;
    }


    /**
     * @Operation(
     *     tags={"Order"},
     *     summary="Get an order by identifier.",
     *     @SWG\Response(
     *         response="200",
     *         description="Returned when successful",
     *         @Model(type="\AppBundle\Entity\Order\Order")
     *     )
     * )
     *
     * @Rest\View(serializerGroups={"base", "order", "customer"})
     * @Rest\Get("/orders/{id}")
     */
    public function getOrderAction(Request $request) {

        $em = $this->getDoctrine()->getManager();
        $order = $em->getRepository(Order::class)->find($request->get('id'));

        if (empty($order)) {
            throw new NotFoundHttpException($this->trans('order.error.notFound'));
        }

        return $order;
    }


    /**
     * @Operation(
     *     tags={"Order"},
     *     summary="Get an order by status.",
     *     @SWG\Response(
     *         response="200",
     *         description="Returned when successful",
     *         @Model(type="\AppBundle\Entity\Order\Order")
     *     )
     * )
     *
     * @Rest\View(serializerGroups={"base", "order", "customer"})
     * @Rest\Get("/orders/status/{name}")
     */
    public function getOrdersByStatusAction(Request $request) {

        $em = $this->getDoctrine()->getManager();
        $order = $em->getRepository(Order::class)->findByStatus($request->get('name'));

        if (empty($order)) {
            throw new NotFoundHttpException($this->trans('order.error.notFound'));
        }

        return $order;
    }

    /**
     * @Operation(
     *     tags={"Order"},
     *     summary="Generate list of order.",
     *     @SWG\Response(
     *         response="200",
     *         description="Returned when successful",
     *         @Model(type="\AppBundle\Entity\Order\Order")
     *     )
     * )
     *
     * @Rest\View(serializerGroups={"base", "order", "customer"})
     * @Rest\Get("/order/generate/{numberToGenerate}");
     */
    public function getGenerateOrderAction(Request $request) {

        $em = $this->getDoctrine()->getManager();

        for($numberOrder = 0 ; $numberOrder < $request->get('numberToGenerate') ; $numberOrder++) {

            // Get a random customer
            $customer = $em->getRepository(Customer::class)->findOneById(rand(1,1000));

            // Generate new order
            $newOrder = new Order();
            $newOrder->setCustomer($customer);
            $newOrder->setReference("");
            $newOrder->setStatus("waiting");
            $newOrder->setPrice(0);
            $newOrder->setWeight(0);

            $totalPrice = 0;
            $totalWeight = 0;

            $em->persist($newOrder);

            $count = rand(1,10);

            $ids = array();
            for($c = 0 ; $c < $count ; $c++) {
                array_push($ids,rand(1,1000));
            }

            // $product = $em->getRepository(Product::class)->findOneById(rand(1,1000));
            var_dump($ids);
            $ids = implode(",",$ids);
            var_dump($ids);

            $q = $em->createQueryBuilder()
                ->select('p')
                ->from('AppBundle:Product\Product','p')
                ->where('p.id IN (:ids)')
                ->setParameter('ids',$ids);


/*
$products->OrderById
foreach($products as $p1) {
    foreach($products as $p2) {
        if($p2->getId() <= $p1->getId()) {
            if($p1->getId() < $p2->getId()) {
                $couple = $em->getRepository(Product::class)->findOneBy(array('p1' => $p1, 'p2' => $p2));
            }
            else {
                $couple = $em->getRepository(Product::class)->findOneBy(array('p1' => $p2, 'p2' => $p1));
            }

            if($couple) {
                // If the couple exist
                $couple->setTotal($couple->getTotal() + 1);
            }
            else {
                // If the couple doesnt exist
                $newCouple = new Couple();
                $newCouple->setP1($p1);
                $newCouple->setP2($p2)
                $newCouple->setTotal(1);
            }
        }
    }
}


p1 | p2 | total

*/

            $product = $q->getQuery()->getResult();

            for($c = 0 ; $c < $count ; $c++) {

                $orderProduct = $em->getRepository(OrderProduct::class)->findOneBy(array('order' => $newOrder, 'product' => $product[$count]));

                if($orderProduct) {
                    $quantity = rand(1,2);
                    $orderProduct->setQuantity($orderProduct->getQuantity() + $quantity);
                }
                else {
                    $quantity = rand(1,2);
                    $newOrderProduct = new OrderProduct();
                    $newOrderProduct->setOrder($newOrder);
                    $newOrderProduct->setProduct($product[$count]);
                    $newOrderProduct->setQuantity($quantity);
                    $newOrderProduct->setCount(0);
                    $newOrderProduct->setUncomplete(false);

                    $em->persist($newOrderProduct);

                }
                $totalPrice += $quantity*$product->getPrice();
                $totalWeight += $quantity*$product->getWeight();
            }

            $newOrder->setPrice($totalPrice);
            $newOrder->setWeight($totalWeight);
            $newOrder->setReference("PickingApp-" . date("Ym") . $newOrder->getId());

            $em->flush();
        }
    }


}
