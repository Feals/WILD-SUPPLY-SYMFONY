<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ProductRepository;
use App\Form\ProductType;
use App\Entity\Product;
use App\Entity\User;
use App\Repository\CategoryItemRepository;
use DateTime;

#[Route('products', name: 'products_')]
class ProductController extends BaseController
{
    #[Route('/', methods: ["GET"], name: 'index')]
    public function index(
        ProductRepository $productRepository,
        Request $request,
        CategoryItemRepository $categoryRepository
    ): Response {
        $categoryObject = null;
        if ($request->query->get('category')) {
            $category = $request->query->get('category');
            $categoryObject = $categoryRepository->findOneById($category);
            $products = $productRepository->findBy(["categoryItem" => $category]);
        } else {
            $products = $productRepository->selecteverything();
        }

        return $this->render('product/index.html.twig', [
            'products' => $products,
            'category' => $categoryObject
        ]);
    }


    #[Route('/show/{id}', methods: ["GET"], name: 'show')]
    public function show(int $id, ProductRepository $productRepository): Response
    {
        $product = $productRepository->find($id);

        return $this->render('product/show.html.twig', [
        'product' => $product
        ]);
    }

    #[Route('/add', name: 'add')]
    public function add(Request $request, ProductRepository $productRepository): Response
    {

        $product = new Product();

        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);
        $product->setDate(new DateTime('now'));
        // set user à user connecté
        $product->setUser($this->getUser());
        $product->setStatusSold("en vente");
        $product->setCart(null);

        if ($form->isSubmitted() && $form->isValid()) {
            $productRepository->save($product, true);
            return $this->redirectToRoute('products_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('product/add.html.twig', [

            'form' => $form,

        ]);
    }

    #[Route('/{id}/edit', name: 'index_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Product $product, ProductRepository $productRepository): Response
    {
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $product->setDate(new DateTime('now'));
         //   dd($product);
            $productRepository->save($product, true);

            return $this->redirectToRoute('products_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('product/edit.html.twig', [
            'product' => $product,
            'form' => $form,
        ]);
    }
    #[Route('/{id}', name: 'index_delete', methods: ['POST'])]
    public function delete(Request $request, Product $product, ProductRepository $productRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $product->getId(), $request->request->get('_token'))) {
            $productRepository->remove($product, true);
        }

        return $this->redirectToRoute('products_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/book', name: 'app_book')]
    public function book(ProductRepository $productRepository): Response
    {

            $products = $productRepository->findAll();
            $user = $this->getUser();
            $productsUser = $productRepository->productuser($user);
            $productsBuy = $productRepository->productuserbuy($user);
            $productsSold = $productRepository->productsold($user);
            return $this->render('product/book.html.twig', [
              'products' => $products,
              'productsUser' => $productsUser,
              'productsBuy' => $productsBuy,
              'productsSold' => $productsSold
            ]);
    }
}
