<?php

namespace Store\FrontBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homeweb")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $product = $em->getRepository('StoreProductBundle:Product')->find(1);

        $man = $this->get('store.store_manager');

        $masterVariants = $man->getMasterVariants();

        $cart = $man->getCart();

        return array(
            'cart'  =>  $cart,
            'products'  =>  $masterVariants,
        );
    }

    /**
     * @Template()
     */
    public function showMiniCartAction()
    {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('StoreProductBundle:Promotion');
        $promotions = $repo->findIfActual();
        $promotion = array();

        $storeManager = $this->get('store.store_manager');
        $cart = $storeManager->getCart();
        $total = 0;
        foreach ($cart->getItems() as $item) {
            $total = $total + ($item->getProduct()->getPrice() * $item->getQuantity());
        }

        if ($total !== 0){
        foreach($promotions as $pro){
            $promotion['detail'] = $pro;
            $promotion['discount_amount'] = $total / $pro->getDiscount();
            $promotion['new_total'] = $total - $promotion['discount_amount'];
            break;
        }
        }

        return array(
            'cart'  =>  $cart,
            'total' => $total,
            'promotion' => $promotion,
        );
    }

    /**
     * @Template()
     */
    public function categoryMenuAction()
    {
        $man = $this->get('store.store_manager');
        $categories = $man->getCategories();
        $htmlTree = $man->getMyTree();

        return array(
            'categories' => $categories,
            'htmlTree'  => $htmlTree,
        );
    }

    /**
     * @Route("/catalog/{id}/{slug}", name="category_page")
     * @Template()
     */
    public function categoryAction($id, $slug)
    {
        $man = $this->get('store.store_manager');
        $category = $man->getCategory($id);
        if (!$category){
            throw new \InvalidParameterException('The category does not exist');
        }
        $products = $man->getProductsForCategory($id);
        return array(
            'category' => $category,
            'products' => $products,
        );
    }

    /**
     * @Route("/add_item_to_cart/{productId}", name="add_item_to_cart")
     */
    public function addItemToCartAction($productId)
    {
        $em = $this->getDoctrine()->getManager();
        $product = $em->getRepository('StoreProductBundle:Variant')->find($productId);
        if (!$product) {
            throw $this->createNotFoundException('Unable to find Product entity.');
        }

        $man = $this->get('store.store_manager');
        $man->addItemToCart($product);

        return $this->redirect($this->generateUrl('homeweb'));
    }

    /**
     * @Route("reset_cart", name="reset_cart")
     */
    public function resetCart()
    {
        $man = $this->get('store.store_manager');
        $man->resetCart();

        return $this->redirect($this->generateUrl('homeweb'));
    }
}
