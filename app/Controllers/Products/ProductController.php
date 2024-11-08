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
            "quantity" => $this->request->getVar("quantity"),
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
                
                // Handle product attributes
                $attributes = $this->request->getPost('attributes'); // Get the attributes data

                foreach ($attributes as $attributeId => $attribute) {
                    $attributeData = [
                        'product_id' => $ProductID, // Assuming you have the product ID
                        'attribute_id' => $attributeId,
                        'attribute_value' => $attribute['value'],
                        'additional_price' => $attribute['additional_price'],
                        'quantity' => $attribute['quantity']
                    ];
                    
                    // Insert each attribute into the product_attributes table
                    $productAttributeModel = new ProductAttributesModel();
                    $productAttributeModel->save($attributeData);
                }
                
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
