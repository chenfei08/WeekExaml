<?php
/**
 * Created by PhpStorm.
 * User: dell
 * Date: 2019/2/19
 * Time: 8:38
 */

echo calFn(1,20);

function calFn($n, $m)
{
    $num = 0;
    for ($i = $n; $i <= $m; $i++){
        $j = $i;
        while ($j){
            $shang = floor($j/10);
            $yu = $j%10;
            if($yu == 1){
                $num++;
            }
            $j = $shang;
        }
    }
    return $num;
}