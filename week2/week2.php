<?php
/**
 * Created by PhpStorm.
 * User: dell
 * Date: 2019/2/26
 * Time: 8:37
 */

//echo demo1(10,3);

echo '<pre>';
//var_dump(demo2([11,33,12,30,90,75,32]));

//var_dump(demo3_1([11,33,12,30,90,75,32]));

$active_time =  [9.01, 9.10, 9.20, 9.21, 9.22];
$process_time = [0.30, 0.18, 0.22, 0.47, 0.11];
var_dump(demo4($active_time,$process_time));

/**
 * @param $n
 * @param $m
 * @return mixed
 * 1．传入n与m两个参数，生成1~n的编号，从开头的编号开始数，数到m将对应的元素删除，接下来从下一个元素开始数，数到m就删除，求最后剩下的数字
 */
function demo1($n,$m)
{
    //将n个编号转换未长度为n的数组
    $list = range(1,$n);
    //记录数到的编号
    $a = 0;
    //循环数
    while(count($list) > 1){
        for ($i = 0; $i < $n; $i++){
            if(!isset($list[$i]))continue;
            $a++;
            //当数到m时删除数组元素
            if($a == $m){
                unset($list[$i]);
                $a = 0;
            }
        }
    }
    //返回最后一个数组元素
    return array_pop($list);
}

/**
 * @param $arr
 * @return mixed
 * 2．编写一个程序，给定任意长度的数组，数组内包含n个数字，要求将数组分为三组，每组的和尽量相近：
 */
function demo2($arr)
{
    //将数组排序
    rsort($arr);
    //计算数组长度
    $len = count($arr);
    //取出最大的三个分别放入一个数组
    $arr_[0][] = $arr[0];
    $arr_[1][] = $arr[1];
    $arr_[2][] = $arr[2];
    //循环数组
    for ($i = 3; $i < $len; $i++){
        $arr_[2][] = $arr[$i];      //将当前数组元素添加到三个数组中最小的一个中
        if(array_sum($arr_[2]) > array_sum($arr_[0])){
            $tmp = $arr_[2];
            $arr_[2] = $arr_[0];
            $arr_[0] = $tmp;
        }elseif(array_sum($arr_[2]) > array_sum($arr_[1])){
            $tmp = $arr_[2];
            $arr_[2] = $arr_[1];
            $arr_[1] = $tmp;
        }
    }
    //返回数组
    return $arr_;
}


function demo3_1($arr)
{
    $data = demo3($arr);

    $rev = '';
    for ($i = 0; $i < count($data); $i++){
        for ($j = 0; $j < count($data[$i]); $j++){
            $rev .= $data[$i][$j];
        }
    }
    return $rev;
}

function demo3($arr,$pow = 0)
{
    static $return = [];
    $len = count($arr);

    //生成桶
    $t = [];
    for ($i = 0; $i < 10; $i++){
        $t[] = [];
    }

    for ($j = 0; $j < $len; $j++){
        $num = (string)$arr[$j];

        if(isset($num[$pow])){
            $t[$num[$pow]][] = $num;
        }else{
            $tt[$num[$pow-1]][] = $num;
        }
    }

    for ($k = 0; $k < 10; $k++){
        if(count($t[$k]) > 1){
            $return = demo3($t[$k],$pow+1);
        }elseif(count($t[$k]) == 1){
            array_unshift($return,$t[$k]);
        }

        if(isset($tt[$k])){
            array_merge($tt[$k],$return);
        }
    }


    return $return;
}


/**
 * @param $active_time
 * @param $process_time
 * @return int|mixed
 */
function demo4($active_time,$process_time)
{
    //柜台
    $t = [];

    //等待时间
    $wait_time = 0;

    $num = count($active_time);
    for ($i = 0; $i < $num; $i++){
        if(count($t) < 4){
            $t[] = $active_time[$i] + $process_time[$i];
            continue;
        }

        sort($t);
        $min = array_shift($t);

        if($min > $active_time[$i]){
            $wait_time += $min - $active_time[$i];
            $t[] = $min + $process_time[$i];
        }else{
            $t[] = $active_time[$i] + $process_time[$i];
        }
    }

    return $wait_time;
}



class DB{
    private static $obj;
    private static $pdo;

    private function __construct($dbconfig)
    {
        list($ip,$dbname,$user,$pwd) = $dbconfig;
        self::$pdo = new PDO("mysql:host=$ip;dbname=$dbname;charset=utf8",$user,$pwd);
    }
    private function __clone()
    {
        // TODO: Implement __clone() method.
    }

    public static function getInc(...$dbconfig)
    {
        if(self::$obj instanceof self){
            return self::$obj;
        }else{
            return self::$obj = new self($dbconfig);
        }
    }

    public function create($sql)
    {
        return self::$pdo->exec($sql);
    }

    public function select($sql)
    {
        return self::$pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function find($sql)
    {
        return self::$pdo->query($sql)->fetch(PDO::FETCH_ASSOC);
    }

    public function update($sql)
    {
        return self::$pdo->exec($sql);
    }

    public function delete($sql)
    {
        return self::$pdo->exec($sql);
    }
}

DB::getInc('127.0.0.1','ten','root','root');