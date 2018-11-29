<?php
/**
 * Created by PhpStorm.
 * User: forward
 * Date: 2018/10/24
 * Time: 下午10:34
 */

namespace App\model\study;


class TestModel
{
    public function mergeSort(array $arr1, $arr2)
    {
        $count1 = count($arr1);
        $count2 = count($arr2);
        if (!$count1 || !$count2) {
            return array_merge($arr1, $arr2);
        }
        $result = [];
        $i = 0;
        while (true) {
            if (empty($arr1) || empty($arr2)) {
                break;
            }
            if ($arr1[$i] <= $arr2[$i]) {
                $result[] = array_shift($arr1);
            } else {
                $result[] = array_shift($arr2);
            }
        }
        return array_merge($result, $arr1, $arr2);
    }

    /**
     * 翻转
     *
     * @param array $arr
     * @return array
     */
    public function reverse(array $arr)
    {
        $count = count($arr);
        if ($count <= 1) {
            return $arr;
        }
        $mid = intval($count / 2);
        for ($i = 0; $i <= $mid; $i++) {
            $temp = $arr[$i];
            $arr[$i] = $arr[$count - 1 - $i];
            $arr[$count - 1 - $i] = $temp;
        }
        return $arr;
    }

    /**
     * 俩数和
     *
     * @param array $arr
     * @param int $target
     * @return array
     */
    public function k2sum(array $arr, int $target)
    {
        $count = count($arr);
        if ($count <= 1) {
            return [];
        }
        $arr = (new SortModel)->quickSort($arr);
        $start = 0;
        $end = $count - 1;
        $result = [];
        while ($start < $end) {
            $sum = $arr[$start] + $arr[$end];
            if ($sum == $target) {
                $result[] = [$arr[$start], $arr[$end]];
                $start++;
            } elseif ($sum < $target) {
                $start++;
            } else {
                $end--;
            }
        }
        return $result;
    }

    //一个数num 取出其中n位 让剩余的数最大
    public function test(int $num, int $n)
    {
        $num = strval($num);
        $len = strlen($num);
        if ($len <= $n) {
            return intval($num);
        }
        $start = 0;
        $result = '';
        for ($i = 0; $i < $n; $i++) {
            $flag = 0;
            for ($j = $start; $j <= $len - $n + $i; $j++) {
                if ($num[$j] > $flag) {
                    $flag = $num[$j];
                    $start = $j + 1;
                }
            }
            $result .= $flag;
        }
        return intval($result);
    }

    public function fibonacci(int $n)
    {
        if ($n == 1) {
            return 1;
        }
        if ($n == 2) {
            return 1;
        }
        return $this->fibonacci($n - 1) + $this->fibonacci($n - 2);
    }

    public function fibonacci2(int $n)
    {
        if ($n <= 0) {
            return [];
        }
        if ($n == 1) {
            return [0, 1];
        }
        if ($n == 2) {
            return [0, 1, 1];
        }
        $a[0] = 0;
        $a[1] = 1;
        $a[2] = 1;
        for ($i = 3; $i <= $n; $i++) {
            $a[$i] = $a[$i - 1] + $a[$i - 2];
        }
        return $a;
    }
}
