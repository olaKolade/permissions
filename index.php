<?php
    $mysqli = new mysqli("localhost", "root", "", "permissions");
    $parentNodes = $mysqli->query("SELECT * FROM permissions WHERE parent_id = '0'");
   
    while ($rootNodes = mysqli_fetch_assoc($parentNodes)) {
        $rootNodes['children'] = getNode($rootNodes['code'], $mysqli);
        
        $permArr[] = $rootNodes;
    }

    
    function getNode($node_code, $mysqli) {
        $hasChildren = $mysqli->query("SELECT * FROM permissions WHERE parent_id = {$node_code}");
        
        if ($hasChildren->num_rows <= 0) {
            $childNode[] = '';
        } else {
            while ($c_row = mysqli_fetch_assoc($hasChildren)) {
                $c_row['children'] = getNode($c_row['code'], $mysqli);
                $childNode[] = $c_row;
            }
        } 
        return $childNode;
    }

    // $myJson = json_encode($permArr);

    function printList($array) {
        $href = 'block' . $array['id'];
        $children = $array['children'];
        $no_of_children = count($array['children']);

        if ($no_of_children <= 1 && $array['children'][0] == '') {
            echo "";
        } else {
            echo "<ul class='list-group'>";
            foreach ($children as $child) {
                printChild($child, $href);   
            }
            echo "</ul>";
        }
    }

    function printChild($child, $href) {
        $child_id = 'block' . $child['id'];
        echo "
            <div id='{$href}' class='panel-collapse collapse child_div'>
                <li class='list-group-item'>
            ";
        echo "
            <h6 class='panel-title'>
                <input type='checkbox' id='c{$child_id}'>
                <a data-toggle='collapse' href='#{$child_id}'> + {$child['name']}</a>
            </h6>
        ";
        echo "<br />";
        echo printList($child);
        echo "</li>
        </div>";
    }
?>

<!DOCTYPE html>
<html>
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="assets/bootstrap.min.css">
  <script src="assets/jquery.min.js"></script>
  <script src="assets/popper.min.js"></script>
  <script src="assets/bootstrap.min.js"></script>
</head>
<body>

    <div class="container">
        <h2>Bootstrap 4 Collapsible List Group Example</h2>
<button class="btn btn-primary btn-lg">Toggle</button>
        <div class="panel-group">
            <div class="panel panel-default">
                <ul class="list-group">
                    <?php 
                        foreach ($permArr as $rootPerm) {
                            $href = 'block' . $rootPerm['id'];
                            echo "<li class='list-group-item'>";
                            echo "
                                <div class='panel-heading'>
                                    <h4 class='panel-title' >
                                        <input type='checkbox' id='c{$href}'>
                                        <a data-toggle='collapse' href='#{$href}'> + {$rootPerm['name']}</a>
                                    </h4>
                                </div>
                                
                            ";
                            echo "<br />";
                            echo printList($rootPerm);
                            echo "</li>";
                        }
                    ?>
                </ul>

                <div class="panel-footer">Copyright @permissions list</div>
                
            </div>
        </div>
    </div>
    

    <script>
        $("button").click(function (params) {
            isChecked = $("input:checkbox").first().is(":checked");
            
            if (isChecked) {
                isChecked == false;
            } else {
                isChecked == true;
            }
            var isChecked = !isChecked;
            var child = $("input:checkbox").attr('checked', isChecked);
        });
    // $(":checkbox").change(function(){
    //             var isChecked = $(this).is(":checked");

    //             var id = this.id.substring(1);
    //         var child = $('div#' + id).find("input:checkbox").attr('checked', isChecked);
    //         location.reload;
    //         });
    </script>
</body>
</html>