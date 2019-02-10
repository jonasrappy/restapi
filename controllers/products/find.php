<?php
/**
 * @author Jonas.Sørensen
 * @created 10-02-2019
 */

/** ADD headers here. We probably need some access-control-max-age and access-control-allow-headers aswell, this i did not add */
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");

//Add requires
require_once '../../config/dbConnect.php';
require_once '../product.php';

class find
{

    private function __construct() {
        $this->handleData();
    }

    private function handleData()
    {

        //Get posted data end JSON decode it
        $postedData = json_decode(file_get_contents("php://input"));

        //Check if any necessary fields empty
        if (empty($postedData) OR empty($postedData->search) OR empty($postedData->store_id)) {
            exit('One or several of the required fields were found empty');
        }

        $product = new Product();

        //Find products matching search terms
        $products = $product->find($postedData->search, $postedData->store_id);

        //If found any products
        if ($products->rowCount() > 0) {

            $result = array();
            $result["body"] = array();

            //For every product add product to array
            foreach($products as $entity) {

                //Add fields to array
                $fields = array(
                    "name" => $entity->name,
                    "picture" => $entity->picture,
                    "link" => $entity->link
                );

                //Add to array
                array_push($result["body"], $fields);
            }

            //JSON encode products array and echo it
            echo json_encode($products);

        } else {

            //JSON encode empty array
            $result =
                json_encode(
                    array(
                        "body" => array()
                    )
                );

            //Echo empty result
            echo $result;
        }
    }
}
?>