<?php
if (!defined('ABSPATH')){
    exit;
}
?>

<div class="description">
    <?php 
        echo $description.'</br>';
    ?>
</div>
<?php 
    if(isset($template) && file_exists($template)){
        require_once $template;
    }
?>