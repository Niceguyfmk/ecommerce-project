<?php

namespace App\Models;

use CodeIgniter\Model;

class ImagesModel extends Model
{
    protected $table            = 'images';
    protected $primaryKey       = 'image_id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        "image_url",
        "product_id",
        "image_type"
    ];

    public function insertImage($productImageURL, $ProductID){
        $Imagedata = [
            "image_url" => $productImageURL,
            "product_id" => $ProductID,
        ];
        return $this->save($Imagedata);
    }

    public function getAllImages(){
        return $this->findAll();
    }
}
