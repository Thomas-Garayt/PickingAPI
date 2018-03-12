<?php

namespace AppBundle\Controller\Product;

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
use AppBundle\Entity\Product\ProductPosition;
use AppBundle\Entity\Product\Product;
use AppBundle\Entity\Position\Position;


class ProductPositionController extends ControllerBase {

    /**
     * @Operation(
     *     tags={"ProductPosition"},
     *     summary="Get the list of product-position.",
     *     @SWG\Response(
     *         response="200",
     *         description="Returned when successful",
     *         @Model(type="\AppBundle\Entity\Product\ProductPosition")
     *     )
     * )
     *
     * @Rest\View(serializerGroups={"base", "product-position"})
     * @Rest\Get("/products-positions");
     */
    public function getProductPositionsAction(Request $request) {

        $em = $this->getDoctrine()->getManager();
        $productPosition = $em->getRepository(ProductPosition::class)->findAll();

        return $productPosition;
    }

    /**
     * @Operation(
     *     tags={"ProductPosition"},
     *     summary="Get the list of product-position.",
     *     @SWG\Response(
     *         response="200",
     *         description="Returned when successful",
     *         @Model(type="\AppBundle\Entity\Product\ProductPosition")
     *     )
     * )
     *
     * @Rest\View(serializerGroups={"base", "product-position"})
     * @Rest\Get("/products-positions/generate");
     */
    public function getGenerateProductPositionsAction(Request $request) {

        $this->cleanPosition();

        $em = $this->getDoctrine()->getManager();
        $products = $em->getRepository(Product::class)->findBy(array(),array('weight' => 'DESC'));

        $emptyPositions = $em->getRepository(Position::class)->findByEmpty(true);


        // BEGIN - SET ONE POSITION TO ALL PRODUCTS
        foreach($products as $product) {
            $weight = $product->getWeight();

            if($weight >= 30) {
                $a = array_keys($emptyPositions);
                $pos = end($a);
            }
            else {
                $pos = array_rand($emptyPositions);
            }

            $randPosition = $emptyPositions[$pos];

            $newProductPosition = new ProductPosition();
            $newProductPosition->setPosition($randPosition);
            $newProductPosition->setProduct($product);

            $randQuantity = $weight <= 1 ? 100 : $weight <= 5 ? 60 : $weight <= 20 ? 30 : 10;
            $newProductPosition->setQuantity(rand(5,$randQuantity));

            $randPosition->setEmpty(false);
            unset($emptyPositions[$pos]);

            $em->persist($randPosition);
            $em->persist($newProductPosition);
        }
        // END - SET ONE POSITION TO ALL PRODUCTS


        // Top 50 couples
        $topCouples = $em->getRepository(Couple::class)->findBy(array(),array('total' => 'DESC'));

        foreach($topCouples as $couple) {
            $p1 = $couple->getP1();
            $p2 = $couple->getP2();

            $positionP1 = $em->getRepository(Position::class)->findOneByProduct($p1);

            $nearestPositionEmpty = $this->getNearestPositionEmpty($positionP1);

            if($nearestPositionEmpty != false) {

            }


            $positionP2 = $em->getRepository(Position::class)->findOneByProduct($p2);


        }

        $em->flush();
    }



    private function getNearestPositionEmpty($position) {
        $idPosition = $position->getId();

        $minId = ($idPosition-5 > 0) ? $idPosition-5 : 1;
        $maxId = ($idPosition+5 < 3120) ? $idPosition+5 : 3120;

        for($i = $minId ; $i <= $maxId ; $i++) {
            $position = $em->getRepository(Position::class)->findoneById($i);
            if($position->getEmpty()) {
                return $position;
            }
        }

        return false;
    }

    /*
    * Set all position to empty
    */
    private function cleanPosition() {
        $em = $this->getDoctrine()->getManager();

        $allPositions = $em->getRepository(Position::class)->findAll();

        foreach($allPositions as $position) {
            $position->setEmpty(true);
            $em->persist($position);
        }
        $em->flush();

        $allProductPositions = $em->getRepository(ProductPosition::class)->findAll();

        foreach($allProductPositions as $productPosition) {
            $em->remove($productPosition);
        }

        $em->flush();
    }

    /**
     * @Operation(
     *     tags={"ProductPosition"},
     *     summary="Get a product-position by identifier.",
     *     @SWG\Response(
     *         response="200",
     *         description="Returned when successful",
     *         @Model(type="\AppBundle\Entity\Product\ProductPosition")
     *     )
     * )
     *
     * @Rest\View(serializerGroups={"base", "product-position"})
     * @Rest\Get("/products-positions/{id}")
     */
    public function getProductPositionAction(Request $request) {

        $em = $this->getDoctrine()->getManager();
        $product = $em->getRepository(ProductPosition::class)->find($request->get('id'));

        if (empty($product)) {
            throw new NotFoundHttpException($this->trans('product.error.notFound'));
        }

        return $product;
    }

}
