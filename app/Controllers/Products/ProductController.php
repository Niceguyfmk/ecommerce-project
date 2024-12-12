<?php

namespace App\Controllers\Products;

use App\Models\CouponModel;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;
use App\Models\ProductModel;
use App\Models\ProductCategoriesModel;
use App\Models\AttributesModel;
use App\Models\ProductAttributesModel;
use App\Models\ProductMetaModel;
use App\Models\ImagesModel;
use App\Config\Validation;
class ProductController extends ResourceController
{
    protected $modelName = ProductModel::class;
    protected $format = 'json';
    
    //Load create product page with relevant categories
    public function addProductView()
    {
        // Load the category model
        $categoryModel = new ProductCategoriesModel;
        $categories = $categoryModel->getAllCategories();
        $pageTitle = 'Add Product';
    
        return view('include/header', ['pageTitle' => $pageTitle]) 
        . view('include/sidebar')
        . view('include/nav')
        . view('products/addProduct', [
            'categories' => $categories,
            ])
        . view('include/footer');    
    }
    //Load Product List Page
    public function listProductView(){

        $products = $this->model->getProducts();

        $imagesModel = new ImagesModel();
        $images = $imagesModel->getAllImages();

        $categoryModel = new ProductCategoriesModel;
        $categories = $categoryModel->getAllCategories();

        $message = session()->getFlashdata('message');
        $pageTitle = 'Product Table';
    
        return view('include/header', ['pageTitle' => $pageTitle]) 
        . view('include/sidebar')
        . view('include/nav')
        . view('products/listProducts', [
            'products' => $products,
            'categories' => $categories,  
            'images' => $images,
            'message' => $message
        ])
        . view('include/footer'); 
    }
    //Load Attributes View
    public function updateAttributesView($product_id){
        
        $attributesModel = new AttributesModel();
        $attributes = $attributesModel->getAllAttributes();

        $productAttributesModel = new ProductAttributesModel();
        $enumValues  = $productAttributesModel->getEnum();

        $productAttributes = $productAttributesModel->where('product_id', $product_id)->getAllProductAttributes();
        $product = $this->model->getProduct($product_id);

        $pageTitle = 'Product Attributes';
    
        return view('include/header', ['pageTitle' => $pageTitle]) 
        . view('include/sidebar')
        . view('include/nav')
        . view('products/addProductAttributes', [
            'product' => $product,
            'attributes' => $attributes,
            'enumValues' => $enumValues,
            'productAttributes' => $productAttributes
        ])
        . view('include/footer');
    }
    //Load Meta Table View
    public function updateMetaTableView($product_id){
        
        $productMetaModel = new ProductMetaModel();
        $productMeta = $productMetaModel->where('product_id', $product_id)->allValues();
        
        $product = $this->model->getProduct($product_id);

        $pageTitle = 'Meta Table';
    
        return view('include/header', ['pageTitle' => $pageTitle]) 
        . view('include/sidebar')
        . view('include/nav')
        . view('products/addProductMeta', [
            'product' => $product,
            'productMeta' => $productMeta
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
            "category_id" =>  $this->request->getVar("category"), 
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
                
                session()->setFlashdata('message', 'Product added successfully');
                return redirect()->to('auth/admin');
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

    public function saveAttributes($product_id)
    {
        $productAttributesModel = new ProductAttributesModel();
        $attributesModel = new AttributesModel(); // Load your Attributes model
        $attributes = $this->request->getPost('attributes');
    
        foreach ($attributes as $attribute) {
            // Validate attribute_id exists
            $isValidAttribute = $attributesModel->where('attribute_id', $attribute['attribute_id'])->first();
    
            if (!$isValidAttribute) {
                // Skip this attribute or handle the error
                return redirect()->back()->with('error', 'Invalid attribute selected.');
            }
    
            // Check if a record with this product_id and attribute_id already exists
            $existingAttribute = $productAttributesModel->where('product_id', $product_id)
                                                        ->where('attribute_id', $attribute['attribute_id'])
                                                        ->first();
    
            $data = [
                'product_id' => $product_id,
                'attribute_id' => $attribute['attribute_id'],
                'unit_type' => $attribute['unit_type'],
                'unit_quantity' => $attribute['unit_quantity'],
                'price' => $attribute['price'],
                'discount_price' => $attribute['discount_price'] ?? null,
                'stock' => $attribute['stock'],
                'is_default' => $attribute['is_default'],
            ];
    
            if ($existingAttribute) {
                // Update the existing record
                $data['product_attribute_id'] = $existingAttribute['product_attribute_id']; 
                $productAttributesModel->save($data);
            } else {
                // Insert a new record
                $productAttributesModel->insert($data);
            }
        }
    
        return redirect()->to('product/viewProducts')->with('success', 'Attributes saved successfully.');
    }

    public function deleteAttribute($attribute_id) {
        $productAttributeModel = new ProductAttributesModel();
        
        if ($productAttributeModel->deletebyID($attribute_id)) {
            return $this->response->setStatusCode(200)->setBody('Attribute deleted successfully.');
        } else {
            return $this->response->setStatusCode(500)->setBody('Failed to delete attribute.');
        }
    }    
    public function saveMetaValues($product_id)
    {
        $productMetaModel = new ProductMetaModel();
        $productMeta = $this->request->getPost('attributes');
        
        // Loop through each meta data in the post values array
        foreach ($productMeta as $meta) {
            // Check if the meta_key already exists for the given product_id
            $existingMeta = $productMetaModel->where('product_id', $product_id)
                                             ->where('meta_key', $meta['meta_key'])
                                             ->first();
            
            $data = [
                'product_id' => $product_id, // Ensure product_id is included in both cases
                'meta_key' => $meta['meta_key'],
                'meta_value' => $meta['meta_value']
            ];
            
            // If existingMeta is found, update the record
            if ($existingMeta) {
                
                $data['meta_id'] = $existingMeta['meta_id']; // Ensure the meta_id is included for updating
                $productMetaModel->save($data);  
            } else {
                // Add a new record if no existing meta is found
                $productMetaModel->addValues($data); 
            }
        }
    
        // After processing all the data, redirect to the viewProducts page
        return redirect()->to('product/viewProducts');
    }
    public function deleteMeta($meta_id)
    {
        $productMetaModel = new ProductMetaModel();
    
        // Find the meta record
        $meta = $productMetaModel->getValuebyID($meta_id);
    
        if (!$meta) {
            return $this->response->setStatusCode(404)->setJSON(['message' => 'Meta not found']);
        }
    
        // Delete the meta record
        if ($productMetaModel->deletebyID($meta_id)) {
            return $this->response->setStatusCode(200)->setJSON(['message' => 'Meta deleted successfully']);
        } else {
            return $this->response->setStatusCode(500)->setJSON(['message' => 'Failed to delete meta']);
        }
    }
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

    if ($product) {
        $updated_data = [];
        $updated_data["product_id"] = $this->request->getPost("product_id") ? $this->request->getPost("product_id") : $product["product_id"]; 
        $updated_data["name"] = $this->request->getPost("name") ? $this->request->getPost("name") : $product["name"];        
        $updated_data["base_price"] = $this->request->getPost("base_price") ? $this->request->getPost("base_price") : $product["base_price"];
        $updated_data["category_id"] = $this->request->getPost("category_id") ? $this->request->getPost("category_id") : $product["category_id"];     
        $updated_data["description"] = $this->request->getPost("description") ? $this->request->getPost("description") : $product["description"];
        $updated_data["long_description"] = $this->request->getPost("long_description") ? $this->request->getPost("long_description") : $product["long_description"];
        
        if(!empty($updated_data)){
            $this->model->update($updated_data["product_id"], $updated_data);

            $imageFile = $this->request->getfile("image");
            $productImageURL = "";
    
            if(!empty($imageFile)){
                //file available
                $newProductImageName = $imageFile->getRandomName();
                $imageFile->move(FCPATH . "uploads/products/", $newProductImageName);
                $productImageURL = "uploads/products/" . $newProductImageName;
                $imageModel = new ImagesModel();
                $imageModel->insertImage($productImageURL, $product_id);
                session()->setFlashdata('message', 'Product updated successfully');
                return redirect()->to('product/viewProducts');
            }else{
                session()->setFlashdata('message', 'Product updated successfully, no image was inserted or changed');
                return redirect()->to('product/viewProducts');
            }
        }else{
            return $this->respond([
                "status" => false,
                "message" => "Failed to update product information",
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
                return redirect()->to('product/viewProducts')->with('status', 'Product successfully deleted.');
            } else {
                return redirect()->to('product/viewProducts')->with('error', 'Failed to delete product.');
            }
        } else {
            return redirect()->to('/viewProducts')->with('error', 'Product not found.');
        }
    }
    //coupons view
    public function couponsView(){
        // Load the category model
        $categoryModel = new ProductCategoriesModel;
        $categories = $categoryModel->getAllCategories();
        $pageTitle = 'Coupons';
    
        return view('include/header', ['pageTitle' => $pageTitle]) 
        . view('include/sidebar')
        . view('include/nav')
        . view('products/couponForm', [
            'categories' => $categories,
            ])
        . view('include/footer'); 
    }
    //Add Coupon
    public function addCoupon()
    {
        $couponModel = new CouponModel();
    
        // Validation rules
        $validationRules = [
            "code" => [
                "rules" => "required|is_unique[coupons.code]",
                "errors" => [
                    "required" => "Coupon Code is required.",
                    "is_unique" => "Coupon Code must be unique.",
                ]
            ],
            "discount_type" => [
                "rules" => "required|in_list[percentage,fixed]",
                "errors" => [
                    "required" => "Discount Type is required.",
                    "in_list" => "Discount Type must be either 'percentage' or 'fixed'.",
                ]
            ],
            "discount_value" => [
                "rules" => "required|greater_than[0]|decimal",
                "errors" => [
                    "required" => "Discount Value is required.",
                    "greater_than" => "Discount Value must be greater than 0.",
                    "decimal" => "Discount Value must be a valid number.",
                ]
            ],
            "max_discount_value" => [
                "rules" => "permit_empty|decimal|greater_than[0]",
                "errors" => [
                    "decimal" => "Maximum Discount Value must be a valid number.",
                    "greater_than" => "Maximum Discount Value must be greater than 0.",
                ]
            ],
            "expiry_date" => [
                "rules" => "required|valid_date[Y-m-d]|check_future_date",
                "errors" => [
                    "required" => "Expiry Date is required.",
                    "valid_date" => "Expiry Date must be in a valid format (YYYY-MM-DD).",
                    "check_future_date" => "Expiry Date must be a future date.",
                ]
            ],
            "min_order_amount" => [
                "rules" => "permit_empty|decimal|greater_than_equal_to[0]",
                "errors" => [
                    "decimal" => "Minimum Order Amount must be a valid number.",
                    "greater_than_equal_to" => "Minimum Order Amount cannot be negative.",
                ]
            ],
            "max_usage" => [
                "rules" => "permit_empty|integer|greater_than[0]",
                "errors" => [
                    "integer" => "Maximum Usage must be a whole number.",
                    "greater_than" => "Maximum Usage must be greater than 0.",
                ]
            ],
        ];
    
        // Validate input
        if (!$this->validate($validationRules)) {
            log_message('error', 'Validation errors: ' . print_r($this->validator->getErrors(), true));
            return $this->fail($this->validator->getErrors());
        }
    
        // Prepare data
        $couponData = [
            "code" => $this->request->getPost("code"),
            "discount_type" => $this->request->getPost("discount_type"),
            "discount_value" => $this->request->getPost("discount_value"),
            "max_discount_value" => $this->request->getPost("discount_type") === "percentage"
                ? $this->request->getPost("max_discount_value")
                : null, // Only store max_discount_value if discount_type is percentage
            "expiry_date" => $this->request->getPost("expiry_date"),
            "min_order_amount" => $this->request->getPost("min_order_amount"),
            "max_usage" => $this->request->getPost("max_usage"),
        ];
        // var_dump($couponData); die();
        // Insert coupon
        $couponID = $couponModel->insert($couponData);
    
        if ($couponID) {
            session()->setFlashdata('message', 'Coupon added successfully');
            return redirect()->to('auth/admin');
        } else {
            return $this->respond([
                "status" => false,
                "message" => "Failed to insert coupon.",
            ]);
        }
    }
    //Apply Coupon
    public function applyCoupon()
    {
        $couponModel = new CouponModel();

        $couponCode = $this->request->getPost('coupon_code');
        $total = $this->request->getPost('sub-total');
        
        // Validate coupon code from the database
        $coupon = $couponModel->getCouponByCode($couponCode);
        if (!$coupon) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid coupon code.']);
        }
        // Check if the coupon is valid
        $currentDate = date('Y-m-d');
        if ($coupon['expiry_date'] < $currentDate) {
            return $this->response->setJSON(['success' => false, 'message' => 'Coupon has expired.']);
        }
        //Validation for max_usage of coupons
        if (isset($coupon['max_usage']) && $coupon['max_usage'] <= 0) {
            return $this->response->setJSON(['success' => false, 'message' => "This coupon has reached its usage limit."]);
        }

        //Validation for min_order_amount
        if ($total < $coupon['min_order_amount']) {
            return $this->response->setJSON(['success' => false,
             'message' => "Order does not meet the minimum amount of {$coupon['min_order_amount']}."]);
        }
        
        // Calculate the discount value based on the coupon type (percentage or fixed)
        $discount = 0;
        if ($coupon['discount_type'] === 'percentage') {
            $discount = $total * ($coupon['discount_value']);
            
            //keep % discount in check using max_discount_value
            if (($discount) > $coupon['max_discount_value']){
                $discount = $coupon['max_discount_value']; 
                 
            }else {
            $discount = $total *$coupon['discount_value'];
            }
        }else {
            $discount = $coupon['discount_value'];
            }
        // Return the discount value
        return $this->response->setJSON(['success' => true, 'discount' => $discount]);
    }

    public function getCouponId()
    {
        $couponModel = new CouponModel();
        $couponCode = $this->request->getPost('coupon_code');

        if (!$couponCode) {
            return $this->response->setJSON(['success' => false, 'message' => 'Missing coupon code']);
        }

        $couponID = $couponModel->getCouponId($couponCode);

        if (!$couponCode) {
            return $this->response->setJSON(['success' => false, 'message' => 'Error getting coupon ID']);
        }
        return $this->response->setJSON(['success' => true, 'couponID' => $couponID]);
    }

}