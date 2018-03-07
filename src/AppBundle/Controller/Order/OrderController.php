<?php
/**
 * Created by PhpStorm.
 * User: jonathan
 * Date: 05/03/18
 * Time: 18:01
 */

namespace AppBundle\Controller\Order;


use AppBundle\Controller\ControllerBase;
use AppBundle\Form\Type\Order\OrderType;
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
use AppBundle\Entity\Couple\Couple;
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

            sort($ids);

            $q = $em->createQueryBuilder('p')
                ->select('p')
                ->from('AppBundle:Product\Product','p')
                ->where('p.id IN (:ids)')
                ->setParameter('ids', $ids);

            $products = $q->getQuery()->getResult();

            foreach ($products as $product) {

                $orderProduct = $em->getRepository(OrderProduct::class)->findOneBy(array('order' => $newOrder, 'product' => $product));

                $quantity = rand(1,2);

                if($orderProduct) {
                    $orderProduct->setQuantity($orderProduct->getQuantity() + $quantity);
                }
                else {
                    $newOrderProduct = new OrderProduct();
                    $newOrderProduct->setOrder($newOrder);
                    $newOrderProduct->setProduct($product);
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
            $newOrder->setReference("PickingApp-" . date("YmdHis") . '-' . $customer->getId());

            $em->flush();


            $this->countCouples($products);
        }
    }


    /**
     * Update couples total sell
     * @param $products
     */
    private function countCouples($products) {

        $em = $this->getDoctrine()->getManager();

        $combinations = $this->findCombinations($products);

        foreach ($combinations as $p) {

            if(count($p) != 2) {
                continue;
            }

            $couple = $em->getRepository(Couple::class)->findOneBy(array('p1' => $p[0], 'p2' => $p[1]));

            if(!$couple) {
                $couple = new Couple();
                $couple->setP1($p[0]);
                $couple->setP2($p[1]);
            }

            $couple->setTotal($couple->getTotal() + 1);

            $em->persist($couple);

        }

        $em->flush();
    }


    /**
     * @param $products
     * @return array
     */
    private function findCombinations($products) {
        $combinations = array();

        foreach ($products as $p1) {
            foreach ($products as $p2) {

                if($p1->getId() == $p2->getId() || $p2->getId() > $p1->getId()) {
                    continue;
                }

                $key = implode(',', array($p2->getId(), $p1->getId()));
                $combination = array($p1, $p2);

                if(!in_array($key, $combinations)) {
                    $combinations[$key] = $combination;
                }
            }
        }

        return $combinations;
    }


    /**
     * @Operation(
     *     tags={"Order"},
     *     summary="Partialy update the informations of an order.",
     *     @SWG\Parameter(
     *         name="body", in="body", required=true,
     *         @Model(type=OrderType::class)
     *     ),
     *     @SWG\Response(
     *         response="200",
     *         description="OK",
     *         @Model(type="\AppBundle\Entity\Order\Order")
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
     * @Rest\View(serializerGroups={"base", "order"})
     * @Rest\Patch("/orders/{id}")
     */
    public function patchOrderAction(Request $request) {
        $em = $this->getDoctrine()->getManager();

        $order = $em->getRepository(Order::class)
            ->find($request->get('id'));

        if (empty($order)) {
            throw new NotFoundHttpException($this->trans('order.error.notFound'));
        }

        $form = $this->createForm(OrderType::class, $order, []);

        $form->submit($request->request->all(), false);

        if ($form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $em->persist($order);
            $em->flush();

            return $order;
        } else {
            return $form;
        }
    }

}
