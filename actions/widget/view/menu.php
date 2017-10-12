<ul>
    <?php
    $html = '';
    $i = 0; $c = count($items);
    foreach ($items as $item) {
        ++$i;
        if($i == 1){
            $class = ' class="first"';
        }elseif($i == $c){
            $class = ' class="last"';
        }else{
            $class = '';
        }
        $html .= <<<STR
        <li{$class}><a href="{$item[1]}">{$item[0]}</a></li>
STR;
    };

    echo $html;
    ?>
</ul>