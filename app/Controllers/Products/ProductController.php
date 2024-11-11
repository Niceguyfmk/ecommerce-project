<?php

namespace App\Controllers\Products;

use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;
use App\Models\ProductModel;
use App\Models\ProductCategoriesModel;
use App\Models\AttributesModel;
use App\Models\ProductAttributesModel;
use App\Models\ImagesModel;
class ProductController extends ResourceController
{
    protected $modelName = ProductModel::class;
    protected $format = 'json';
    
    //Load product page with relevant categories and attributes
    public function addProductView()
    {
        // Load the category model
        $categoryModel = new ProductCategoriesModel;
        $categories = $categoryModel->getAllCategories();

        // Load the attribute model
        $attributeModel = new AttributesModel;
        $attributes = $attributeModel->getAllAttributes();  
        
        return view('include/header')
        . view('include/sidebar')
        . view('include/nav')
        . view('products/addProduct', [
            'categories' => $categories,
            'attributes' => $attributes
            ])
        . view('include/footer');    
    }

    public function listProductView(){

        $products = $this->model->getProducts();

        return view('include/header')
        . view('include/sidebar')
        . view('include/nav')
        . view('products/listProducts', [
            'products' => $products,  // Passing the products to the view
        ])
        . view('include/footer'); 
    }

    public function updateProductView($product_id){
        $product = $this->model->getProduct($product_id);
        return view('include/header')
        . view('include/sidebar')
        . view('include/nav')
        . view('products/updateProduct', [
            'product' => $product,  // Passing the product id to the view
        ])
        . view('include/footer');
    }

    // Add a product
    public function addProduct(){
        
        //validation
        $validationRules = [
            "name" => [
                "rules" => "required|min_length[5]",
                "errors" => [
                    "required" => "Product Title is required",
                    "min_length" => "Title must be greater than 5 characters",
                ]
            ],
            "base_price" => [
                "rules" => "required|decimal|greater_than[0]",
                "errors" => [
                    "required" => "Please provide the price of the product",
                    "decimal" => "price must be a decimal value",
                    "greater_than" => "Product price must be greater than 0 value"
                ]
            ],
        ];

        if (!$this->validate($validationRules)) {
            log_message('error', 'Validation errors: ' . print_r($this->validator->getErrors(), true));
            return $this->fail($this->validator->getErrors());
        }

        //getPost()
        $ProductData = [
            "name" => $this->request->getVar("name"), 
            "base_price" => $this->request->getVar("base_price"),
            "description" => $this->request->getVar("description"), 
            "category_id" =>  $this->request->getVar("category_id"), 
        ];
 
        $ProductID = $this->model->insert($ProductData);
        
        //if product is successfully inserted
        if($ProductID){

            //save image next to image table
            $imageFile = $this->request->getfile("image");

            $productImageURL = "";
    
            if($imageFile){
                //file available
                $newProductImageName = $imageFile->getRandomName();
                $imageFile->move(FCPATH . "uploads/products/", $newProductImageName);
                $productImageURL = "uploads/products/" . $newProductImageName;
                $imageModel = new ImagesModel();
                $imageModel->insertImage($productImageURL, $ProductID);
                
                
            }else{
                return $this->respond([
                    "status" => false,
                    "message" => "Failed to insert image",
                ]);
            }
  
        }else{
            return $this->respond([
                "status" => false,
                "message" => "Failed to insert product",
            ]);
        }
    }

/*     public function addAttributes{

        $attributes = $this->request->getPost('attributes');

        foreach ($attributes as $attributeId => $attribute) {
            $attributeData = [
                'product_id' => $ProductID, 
                'attribute_id' => $attributeId,
                'attribute_value' => $attribute['value'],
                'additional_price' => $attribute['additional_price'],
                'quantity' => $attribute['quantity']
            ];
            
            $productAttributeModel = new ProductAttributesModel();
            $productAttributeModel->save($attributeData);
        }
    } */

    // Get Products List
    public function listAllProducts(){
        $products = $this->model->getProducts();

        return $this->respond([
            "status" => true,
            "message" => "Successfully returned list of products",
            "products" => $products
        ]);
    }

    // Get Products using ID
    public function getSingleProduct($product_id){
        $product = $this->model->getProduct($product_id);
        
        if($product){
            return $this->respond([
                "status" => true,
                "message" => "Successfully found product",
                "product" => $product
            ]);
        }else{
            return $this->respond([
                "status" => false,
                "message" => "Failed to find product",
            ]);
        }
        
    }    

    // Update Product using ID
    public function updateProduct($product_id){
         
        $product = $this->model->getProduct($product_id);

        if($product){

            //product exists
            $updated_data = json_decode(file_get_contents("php://input"), true);
            $product_name = isset($updated_data["name"]) ? $updated_data["name"] : $product["name"];
            $product_price = isset($updated_data["base_price"]) ? $updated_data["base_price"] : $product["base_price"];
            $product_description = isset($updated_data["description"]) ? $updated_data["description"] : $product["description"];

            if($this->model->update($product_id, [
                "name" => $product_name,
                "price" => $product_price,
                "description"=> $product_description
            ])){
                return $this->respond([
                    "status" => true,
                    "message" => "Successfully updated product",
                ]);

            }else{
                return $this->respond([
                    "status" => false,
                    "message" => "Failed to update product",
                ]);
            }

        }else{
            return $this->respond([
                "status" => false,
                "message" => "Failed to find product",
            ]);
        }
    }

    // Delete Product
    public function deleteProduct($product_id) {
        // Fetch the product
        $product = $this->model->getProduct($product_id);
    
        if ($product) {
            // Proceed with the deletion
            if ($this->model->deleteProductById($product_id)) {
                return redirect()->to('/viewProducts')->with('status', 'Product successfully deleted.');
            } else {
                return redirect()->to('/viewProducts')->with('error', 'Failed to delete product.');
            }
        } else {
            return redirect()->to('/viewProducts')->with('error', 'Product not found.');
        }
    }
    
}
