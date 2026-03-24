<?php

namespace App\Controllers;

use App\Models\MODEL__Test;

class Test extends BaseController {

    public function __construct() {
        parent::__construct();

        $this -> MODEL__Test = new MODEL__Test();
    }

    public function index() {

        $data['categories'] = $this -> MODEL__Test -> category_menu();

        return view('test', $data);
        //dd($data['categories']);
    }
    
      public function test2() {

        $data['categories'] = $this -> MODEL__Test -> category_menu();

        return view('test2', $data);

    }
    
    public function test3() {

        $data['categories'] = $this -> MODEL__Test -> category_menu();

        return view('test3', $data);

    }

    public function save($id = null, $parent_id = null) {// ajax
        $name       = $this -> request -> getVar('doomEditElement');
        $createName = $this -> request -> getVar('categoryName');


        $catData = [
            'category_id'   => is_numeric($id) ? $id : null,
            'parent_id'     => is_numeric($parent_id) ? $parent_id : null,
            'category_name' => $name ?? $createName
        ];

        // d($catData);
         //dd($catData);

        $response = $this -> MODEL__Test -> save_category($catData);

        // return redirect() -> back();
        
         $data['categories'] = $this -> MODEL__Test -> category_menu();

        return json_encode(view('test2', $data));
        //return json_encode('');
    }

    public function delete($id) {// ajax
    
        $this -> MODEL__Test -> remove($id);
        return json_encode('');
    }

}
