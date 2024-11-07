<?php

namespace App\Controllers\Products;

use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;
use App\Models\ProductModel;
use App\Models\ProductCategoriesModel;
use App\Models\AttributesModel;
use App\Models\ProductAttributesModel;
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
        $attributeModel = new AttributesModel();
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

    // Add a product
    public function addProduct(){
        
        //validation
        $validationRules = [
            "title" => [
                "rules" => "required|min_length[5]",
                "errors" => [
                    "required" => "Product Title is required",
                    "min_length" => "Title must be greater than 5 characters",
                ]
            ],
            "price" => [
                "rules" => "required|decimal|greater_than[0]",
                "errors" => [
                    "required" => "Please provide the price of the product",
                    "decimal" => "price must be a decimal value",
                    "greater_than" => "Product price must be greater than 0 value"
                ]
            ],
            "size" => [
                "rules" => "required",
                "errors" => [
                    "required" => "Please provide the size of the product"
                ]
            ],
            "quantity" => [
                "rules" => "required|integer|greater_than_equal_to[0]",
                "errors" => [
                    "required" => "Quantity is required",
                    "integer" => "Quantity must be an integer value",
                    "greater_than_equal_to" => "Quantity must be 0 or greater",
                ]
            ],
            "status" => [
                "rules" => "required|in_list[active,inactive]",
                "errors" => [
                    "required" => "Status is required",
                    "in_list" => "Status must be either 'active' or 'inactive'",
                ]
            ],
        ];

        if (!$this->validate($validationRules)) {
            log_message('error', 'Validation errors: ' . print_r($this->validator->getErrors(), true));
            return $this->fail($this->validator->getErrors());
        }

        $imageFile = $this->request->getfile("image");

        $productImageURL = "";

        if($imageFile){
            //file available
            $newProductImageName = $imageFile->getRandomName();
            $imageFile->move(FCPATH . "uploads", $newProductImageName);
            $productImageURL = "uploads/" . $newProductImageName;

        }

        //getPost()
        $ProductData = [
            "title" => $this->request->getVar("title"), 
            "brand" => $this->request->getVar("brand"),
            "price" => $this->request->getVar("price"), 
            "size" =>  $this->request->getVar("size"), 
            "quantity" => $this->request->getVar("quantity"),
            "color" => $this->request->getVar("color"),
            "status" => $this->request->getVar("status"),
/*          "color" => $this->request->getVar("color"),*/
            "description" => $this->request->getVar("description"),
        ];

        //Save Author 
        if($this->model->save($ProductData)){

            return $this->respond([
                "status" => true,
                "message" => "Successfully created product"
            ]);
        }else{

            return $this->respond([
                "status" => false,
                "message" => "Failed to create product"
            ]);
        }
    }

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
            $product_title = isset($updated_data["title"]) ? $updated_data["title"] : $product["title"];
            $product_price = isset($updated_data["price"]) ? $updated_data["price"] : $product["price"];
            $product_size = isset($updated_data["size"]) ? $updated_data["size"] : $product["size"];
            $product_color = isset($updated_data["color"]) ? $updated_data["color"] : $product["color"];
            $product_quantity = isset($updated_data["quantity"]) ? $updated_data["quantity"] : $product["quantity"];
            $product_status = isset($updated_data["status"]) ? $updated_data["status"] : $product["status"];
            $product_brand = isset($updated_data["brand"]) ? $updated_data["brand"] : $product["brand"];
            $product_description = isset($updated_data["description"]) ? $updated_data["description"] : $product["description"];

            if($this->model->update($product_id, [
                "title" => $product_title,
                "price" => $product_price,
                "size" => $product_size,
                "quantity" => $product_quantity,
                "color" => $product_color,
                "status" => $product_status,
                "brand" => $product_brand,
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
    public function deleteProduct($product_id){
        $product = $this->model->getProduct($product_id);

        if($product){
            if($this->model->deleteProductById($product_id)){
                return $this->respond([
                    "status" => true,
                    "message" => "Successfully deleted product",
                ]);
            }else{
                return $this->respond([
                    "status" => false,
                    "message" => "Failed to delete product",
                ]);
            }
        }else{
            return $this->respond([
                "status" => false,
                "message" => "Failed to find product",
            ]);
        }
        
    }
}
