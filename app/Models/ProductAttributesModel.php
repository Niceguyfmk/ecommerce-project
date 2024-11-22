<?php

namespace App\Models;

use CodeIgniter\Model;

class ProductAttributesModel extends Model
{
    protected $table            = 'product_attributes';
    protected $primaryKey       = 'product_attribute_id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        "product_id",
        "attribute_id",
        "unit_type",
        "unit_quantity",
        "price",
        "discount_price",
        "stock",
        "is_default"
    ];

    public function getAllProductAttributes(){
        return $this->findAll();
    }

    public function addAttributes($data){
        return $this->insert($data);
    }

    public function getEnum(){
        $query = $this->db->query("SHOW COLUMNS FROM product_attributes LIKE 'unit_type'");
        $result = $query->getRow(); 

        if ($result) {
            // Use a regular expression to extract the ENUM values
            preg_match("/^enum\((.*)\)$/", $result->Type, $matches);

            if (!empty($matches[1])) {
                // Convert the string of values into an array
                $enumValues = str_getcsv($matches[1], ",", "'");
            }
        }
        return($enumValues);

    }
}
