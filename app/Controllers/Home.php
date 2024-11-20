<?php

namespace App\Controllers;
use App\Models\ProductModel;
use App\Models\ProductCategoriesModel;
use App\Models\AttributesModel;
use App\Models\ProductAttributesModel;
use App\Models\ImagesModel;
use Firebase\JWT\JWT;
class Home extends BaseController
{
    //Shop related pages
    public function index(): string
    {
        $message = session()->getFlashdata('message');
        $pageTitle = 'Organic Home';

        $imagesModel = new ImagesModel();
        $images = $imagesModel->getAllImages();

        $categoryModel = new ProductCategoriesModel;
        $categories = $categoryModel->getAllCategories();

        $productModel = new ProductModel();
        $products = $productModel->getProducts();

        $attributesModel = new AttributesModel();
        $attributes = $attributesModel->getAllAttributes();

        $productAttributesModel = new ProductAttributesModel();
        $productAttributes = $productAttributesModel->getAllProductAttributes();
    
        return  view('shop-Include/header', ['pageTitle' => $pageTitle])
          . view('shop/index', ['message' => $message, 'categories' => $categories, 'products' => $products, 'images' => $images])
           .view('shop-Include/footer');
            
    }

    public function shop(): string
    {
        $message = session()->getFlashdata('message');
        $pageTitle = 'Organic Shop';

        $imagesModel = new ImagesModel();
        $images = $imagesModel->getAllImages();

        $categoryModel = new ProductCategoriesModel;
        $categories = $categoryModel->getAllCategories();

        $productModel = new ProductModel();
        $products = $productModel->getProducts();

        $attributesModel = new AttributesModel();
        $attributes = $attributesModel->getAllAttributes();

        $productAttributesModel = new ProductAttributesModel();
        $productAttributes = $productAttributesModel->getAllProductAttributes();

        return  view('shop-Include/header', ['pageTitle' => $pageTitle])
          . view('shop/shop', ['message' => $message, 'categories' => $categories, 'products' => $products, 'images' => $images])
          . view('shop-Include/footer');
            
    }

    public function detail(): string
    {
        $message = session()->getFlashdata('message');
        $pageTitle = 'Organic Shop-Detail';

        $imagesModel = new ImagesModel();
        $images = $imagesModel->getAllImages();

        $categoryModel = new ProductCategoriesModel;
        $categories = $categoryModel->getAllCategories();

        $productModel = new ProductModel();
        $products = $productModel->getProducts();

        $attributesModel = new AttributesModel();
        $attributes = $attributesModel->getAllAttributes();

        $productAttributesModel = new ProductAttributesModel();
        $productAttributes = $productAttributesModel->getAllProductAttributes();
    
        return  view('shop-Include/header', ['pageTitle' => $pageTitle])
          . view('shop/shop-detail', ['message' => $message, 'categories' => $categories, 'products' => $products, 'images' => $images])
           .view('shop-Include/footer');
            
    }

    public function cart(): string
    {
        $message = session()->getFlashdata('message');
        $pageTitle = 'Cart';

        $imagesModel = new ImagesModel();
        $images = $imagesModel->getAllImages();

        $categoryModel = new ProductCategoriesModel;
        $categories = $categoryModel->getAllCategories();

        $productModel = new ProductModel();
        $products = $productModel->getProducts();

        $attributesModel = new AttributesModel();
        $attributes = $attributesModel->getAllAttributes();

        $productAttributesModel = new ProductAttributesModel();
        $productAttributes = $productAttributesModel->getAllProductAttributes();
    
        return  view('shop-Include/header', ['pageTitle' => $pageTitle])
          . view('shop/cart', ['message' => $message, 'categories' => $categories, 'products' => $products, 'images' => $images])
           .view('shop-Include/footer');
            
    }

    //Auth Related Pages
    public function login(){
        
        return view('login');
    }
    
    public function adminDashboard() {
        $message = session()->getFlashdata('message');
        $pageTitle = 'Dashboard';
    
        return view('include/header', ['pageTitle' => $pageTitle]) 
            . view('include/sidebar') 
            . view('include/nav') 
            . view('index', ['message' => $message]) 
            . view('include/footer');
    }   
   
    public function register(){
        $pageTitle = 'Add User';
        return view('include/header', ['pageTitle' => $pageTitle]) . view('include/sidebar') . view('include/nav') . view('register')
         . view('include/footer');
    }
}
