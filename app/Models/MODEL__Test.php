<?php

namespace App\Models;

use CodeIgniter\Model;

class MODEL__Test extends Model {

    protected $table      = '_test'; // Assuming your table is named 'category'
    protected $primaryKey = 'category_id';
    protected $returnType = 'object';

    public function category_menu() {
        $query = $this -> query("SELECT category_id, category_name,  parent_id FROM " . $this -> table);

        $categories = [
            'items'   => [],
            'parents' => []
        ];
//d($query -> getResult());
        foreach ($query -> getResult() as $category) {
            $categories['items'][$category -> category_id]   = $category;
            $categories['parents'][$category -> parent_id][] = $category -> category_id;
        }

        // dd($categories);
        return $categories;
//        if ($categories) {
//            return $this -> buildCategoryMenu(0, $categories);
//        } else {
//            return false;
//        }
    }

    public function save_category($data = []) {
        return $this -> saveData('_test', $data);
    }

    private function saveData($table, $data) {
        try {
            $this -> db -> transStart();

            $result = $this -> db -> table($table) -> upsert($data);
//dd($data);
            if ($result === 1) {
                $result = ['lastId' => $this -> db -> insertID()];
            }

            $this -> db -> transComplete(); // Complete the transaction

            return $result;
        } catch (\CodeIgniter\Database\Exceptions\DatabaseException $e) {
            return $this -> handleTransactionError("Failed to save $table: " . $e -> getMessage(), $e -> getMessage());
        }
    }

    public function remove($id = null) {
        $conditions = $id ? ['category_id' => $id] : null;
              // dd($conditions);
        return $this -> deleteFromTable('_test', $conditions);
    }

    private function deleteFromTable($table, $conditions) {
 
        if ($conditions) {

            try {
                $this -> db -> transStart();

                $affectedRows = $this -> db -> table($table)
                        -> where($conditions)
                        -> delete();

                $this -> db -> transComplete(); // Complete the transaction

                return $affectedRows > 0;
            } catch (\Exception $e) {
                return $this -> handleTransactionError("Failed to save $table: " . $e -> getMessage(), $e -> getMessage());
            }
        }

        return false;
    }

    // Menu builder function, parentId 0 is the root
//    private function buildCategoryMenu($parent, $categories) {
//       
//        $html = "";
//        $tree = [];
//        if (isset($categories['parents'][$parent])) {
//            $html .= "<ul>\n";
//
//            foreach ($categories['parents'][$parent] as $itemId) {
//
//                $category    = $categories['items'][$itemId];
//                $hasChildren = isset($categories['parents'][$itemId]);
//                $html        .= '<li>' . "\n" . '<a';
//
//                if ($hasChildren) {
//                    $html .= ' class="parent"';
//                }
//
//                $html            .= ' data-id="' . $category -> category_id . '"><span>' . $category -> category_name . '</span></a>' . "\n";
//                $tree[$itemId]['category_id'] = $itemId;
//
//                if ($hasChildren) {
//                    $tree[$itemId]['child']= $this -> buildCategoryMenu($itemId, $categories);
//                }
//
//                // $html .= '</li>' . "\n";
//            }
//            // $html .= '</ul>' . "\n";
//        }
//      
//        return $tree;
//    }
}
