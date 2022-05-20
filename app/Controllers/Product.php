<?php

use App\Models\ProductModel;

namespace App\Controllers;

use App\Models\ProductModel;

use function PHPUnit\Framework\fileExists;

class Product extends BaseController
{
    public function index()
    {
        return view('product/index');
    }


    public function store(){
        
        $product = new ProductModel();
        $file = $this->request->getFile('file');
        $newName = $file->getRandomName();
        if($file->isValid() && ! $file->hasMoved()){
            $file->move("uploads/", $newName);
        }
        $data = [
                'title' => $this->request->getVar('title'),
                'pro_image' => $newName,
                'description' => $this->request->getVar('description'),
        ];

        $result = $product->save($data);
        if($result){
            echo 1;
        }else {
            echo 0;
        }
    }


    public function getData(){
        
        $products = new ProductModel();
        $results = $products->orderBy('id','DESC')->findAll();
        $output = "";
        foreach($results as $product){
            $output .= "<tr>
            <td>{$product['id']}</td>
            <td>{$product['title']}</td>
            <td>{$product['description']}</td>
            <td><img src='uploads/{$product['pro_image']}' alt='{$product['pro_image']}' style=width:100px;height:70px;'></td>
            <td><button id='edit-product' data-id='{$product['id']}' class='btn btn-success' data-toggle='modal' data-target='#update-product'>Edit</button></td>
            <td><button id='delete-product' data-id='{$product['id']}' data-toggle='modal' class='btn btn-danger'>Delete</button></td>
            <td><button class='btn btn-success' data-toggle='modal' data-target='#myModal'>Publish</button></td>
            </tr>";
        }
        echo $output;
    }


    public function delete(){
        $id = $this->request->getVar('id');

        $products = new ProductModel();
        $get = $products->find($id);
        $path = "uploads/".$get['pro_image'];
        if(file_exists($path)){
            unlink($path);
        }
        $result = $products->delete($id);
        if($result){
            echo 1;
        }
        else{
            echo 0;
        }       
    }

//<textarea value='{$product['description']}' class='form-control' rows='5' id='description'></textarea>

    public function edit(){
        $id = $this->request->getVar('id');
        $products = new ProductModel();
        $product = $products->find($id);
        $output = "
        <div class='form-group'>
            <label for='title'>Title</label>
        <input type='text' value='{$product['title']}' class='form-control' placeholder='Enter title' name='title' id='edit_title'>
        <input type='text' value='{$product['id']}' class='form-control' placeholder='Enter title' name='id' id='id'>
    </div>
    <div class='form-group'>
        <label for='description'>Description</label>
        <input type='text' value='{$product['description']}' class='form-control' placeholder='Enter description' name='description' id='edit_description'>
                
    </div>
    <div class='form-group'>
        <label for='file'>Image:</label>
        <input type='file' class='form-control-file border' name='new_image' id='new_image'>
        <img src='uploads/{$product['pro_image']}' alt='{$product['pro_image']}' style=width:100px;height:70px;'>       
        <input type='text' value='{$product['pro_image']}' class='form-control' name='old_image' id='old_image'>
    </div>";
        

    // at 95 <input type='text' value='{$product['description']}' class='form-control' placeholder='Enter description' id='edit_description'>
        echo $output;
    }




    public function update(){

        $products = new ProductModel();
        
        // $product = $products->find('id');
        $new_image = $this->request->getFile('new_image');
        $old_image = $this->request->getVar('old_image');
        $newImage = "";
        if($new_image != ""){
            $path = "uploads/".$old_image;
            if(fileExists($path)){
                unlink($path);
            }
            if($new_image->isValid() && ! $new_image->hasMoved()){
                $newImage = $new_image->getRandomName();
                $new_image->move("uploads/", $newImage);
                
            }
        } 
        else {
            $newImage = $old_image;
        }

        $data = [
            'id' => $this->request->getVar('id'),
            'title' => $this->request->getVar('title'),
            'pro_image' => $newImage,
            'description' => $this->request->getVar('description')
        ];

        $result = $products->save($data);
        if ($result) {
            echo 1;
        } else {
            echo 0;
        }
    }


}