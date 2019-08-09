<?php error_reporting(0);
/*
 * @Author: Universal Programming 
 * @Date: 2018-04-25
 * @Package: Maps_updater
 * @Copywrite: 2018 Universal Programming LLC
 * @Last Modified date: 2018-05-14
 * 
 */
?>
 <form method="POST">
<table  class="table table-bordered" id="mapAttributes">
    <tbody>
        <tr>
            <th class="col-xs-6">Category</th>
            <th class="col-xs-6">Products</th>
        </tr>
        <?php
            $categories = $mysqli->query("SELECT * FROM maps_categories");
            $categories = $categories->fetch_all(MYSQLI_ASSOC);
            $products = $mysqli->query("SELECT * FROM maps_products");
            $products = $products->fetch_all(MYSQLI_ASSOC);
            $customProducts = $mysqli->query("SELECT * FROM maps_custom_products");
            $customProducts = $customProducts->fetch_all(MYSQLI_ASSOC);
            $tableLength = max(count($categories),count($products), count($customProducts));
            if($tableLength == 0){
                echo '<tr>';
                echo '<td><table class="table table-bordered" style="background:transparent;" id="mapAttributesCategories"><tbody><tr><td class="categories"><button type="button" class="btn btn-default btn-sm" onClick="addAttributeInput(this)">Add</button></td></tr></tbody></table></td>';
                echo '<td><table class="table table-bordered" style="background:transparent;" id="mapAttributesProducts"><tbody><tr><td class="products"><button type="button" class="btn btn-default btn-sm" onClick="addAttributeInput(this)">Add</button></td></tr></tbody></table></td>';
                echo '</tr>';
                echo '<tr><td></td><td><table class="table table-bordered" style="background:transparent;" id="mapAttributesStaticProducts"><tbody><tr><th>Static Products</th></tr></tbody></table></td></tr>';
            }else{
                $btn1 = false;
                $btn2 = false;
                $btn3 = false;
                echo '<tr><td><table class="table table-bordered" style="background:transparent;" id="mapAttributesCategories"><tbody>';
                for ($x = 0; $x < count($categories) + 1; $x++){
                    echo '<tr>';
                    //check status of category list and print
                    if(isset($categories[$x])){
                        echo '<td class="categories"><input type="hidden" name="categories_hidden[]" value="'.$categories[$x]["category"].'"/><input type="text" onFocus="addSaveAttr(this)" onBlur="submitAttribute(this)" name="categories[]" value="'.$categories[$x]["category"].'"/></td>';
                    }else{
                        if(!$btn1){       
                            echo '<td class="categories"><button type="button" class="btn btn-default btn-sm" onClick="addAttributeInput(this)">Add</button></td>';
                        }else{
                            echo '<td class="categories"></td>';
                        }
                        $btn1 = true;
                    }
                    echo '</tr>';
                }
                echo '</tbody></table></td>';
                echo '<td><table class="table table-bordered" style="background:transparent;" id="mapAttributesProducts"><tbody>';
                for($y = 0; $y < count($customProducts) + 1; $y++){
                    echo '<tr>';
                    //check status of products list and print
                    if(isset($customProducts[$y])){
                        echo '<td class="products"><input type="hidden" name="products_hidden[]" value="'.$customProducts[$y]["product"].'"/><input type="text" onFocus="addSaveAttr(this)" onBlur="submitAttribute(this)" name="products[]" value="'.$customProducts[$y]["product"].'"/></td>';
                    }else{
                        if(!$btn2){
                            echo '<td class="products"><button type="button" class="btn btn-default btn-sm" onClick="addAttributeInput(this)">Add</button></td>';
                        }else{
                            echo '<td class="products"></td>';
                        }
                        $btn2 = true;
                    }
                    echo '</tr>';
                }
                echo '</tbody></table></td></tr>';
                echo '<tr><td></td><th>Static Products</th></tr>';
                echo '<tr><td></td><td style="height:600px;overflow-y:scroll;display:block;"><table class="table table-bordered" style="background:transparent;" id="mapAttributesStaticProducts"><tbody>';
                for($z = 0; $z < count($products) + 1; $z++){
                    echo '<tr>';
                    //check status of products list and print
                    if(isset($products[$z])){
                        echo '<td class="staticProducts">'.$products[$z]["product"].'</td>';
                    }
                    echo '</tr>';
                }
                echo '</tbody></table></td></tr>';
            }
        ?>

    </tbody>
</table>
</form>
<script>
    var attributesPath = '<?php echo $views ?>publish.php';
</script>