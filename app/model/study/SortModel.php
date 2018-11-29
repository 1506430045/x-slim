<?php
/**
 * Created by PhpStorm.
 * User: forward
 * Date: 2018/10/20
 * Time: 下午6:40
 */

namespace App\model\study;


class SortModel
{
    private $arr = [];
    private $num = 0;

    public function __construct(array $arr = [])
    {
        $this->arr = $arr;
        $this->num = count($arr);
    }

    /**
     * 冒泡排序
     *
     * @return array
     */
    public function bubbleSort()
    {
        for ($i = 0; $i < $this->num; $i++) {
            for ($j = 0; $j < $this->num - 1 - $i; $j++) {
                if ($this->arr[$j] > $this->arr[$j + 1]) {
                    $temp = $this->arr[$j];
                    $this->arr[$j] = $this->arr[$j + 1];
                    $this->arr[$j + 1] = $temp;
                }
            }
        }
        return $this->arr;
    }

    /**
     * 选择排序
     *
     * @return array
     */
    public function selectSort()
    {
        for ($i = 0; $i < $this->num; $i++) {
            $min = $i;
            for ($j = $i + 1; $j < $this->num; $j++) {
                if ($this->arr[$j] < $this->arr[$min]) {
                    $min = $j;
                }
            }
            if ($min != $i) {
                $temp = $this->arr[$i];
                $this->arr[$i] = $this->arr[$min];
                $this->arr[$min] = $temp;
            }
        }
        return $this->arr;
    }

    /**
     * 插入排序
     *
     * @return array
     */
    public function insertSort()
    {
        for ($i = 1; $i < $this->num; $i++) {
            for ($j = $i - 1; $j >= 0; $j--) {
                if ($this->arr[$j + 1] < $this->arr[$j]) {
                    $temp = $this->arr[$j + 1];
                    $this->arr[$j + 1] = $this->arr[$j];
                    $this->arr[$j] = $temp;
                }
            }
        }
        return $this->arr;
    }

    /**
     * 快速排序
     *
     * @return array
     */
    public function quickSort(array $arr)
    {
        $count = count($arr);
        if ($count > 1) {
            $middle = $arr['0'];
            $left = [];
            $right = [];
            for ($i = 1; $i < $count; $i++) {
                if ($arr[$i] <= $middle) {
                    $left[] = $arr[$i];
                } else {
                    $right[] = $arr[$i];
                }
            }
            $left = $this->quickSort($left);
            $right = $this->quickSort($right);
            return array_merge($left, [$middle], $right);
        } else {
            return $arr;
        }
    }

    /**
     * 交换数组中的俩个数
     *
     * @param array $arr
     * @param $x
     * @param $y
     */
    public function swap(array &$arr, $x, $y)
    {
        if ($arr[$x] == $arr[$y]) {
            return;
        }
        $arr[$x] = $arr[$x] ^ $arr[$y];
        $arr[$y] = $arr[$x] ^ $arr[$y];
        $arr[$x] = $arr[$x] ^ $arr[$y];
    }

    /**
     * 构建最大堆
     *
     * @param array $arr
     * @param int $count
     */
    public function buildMaxHeap(array &$arr, int $count)
    {
        for ($i = floor($count / 2); $i >= 0; $i--) {
            $this->heapify($arr, $i, $count);
        }
    }

    /**
     * 堆化
     *
     * @param array $arr
     * @param int $i
     * @param int $count
     */
    public function heapify(array &$arr, int $i, int $count)
    {
        $left = 2 * $i + 1;
        $right = 2 * $i + 2;

        $largest = $i;
        if ($left < $count && $arr[$left] > $arr[$largest]) {
            $largest = $left;
        }
        if ($right < $count && $arr[$right] > $arr[$largest]) {
            $largest = $right;
        }

        if ($largest != $i) {
            $this->swap($arr, $largest, $i);
            $this->heapify($arr, $largest, $count);
        }
    }

    /**
     * 堆排序
     *
     * @param array $arr
     * @return array
     */
    public function heapSort(array $arr)
    {
        $count = count($arr);
        if ($count <= 1) {
            return $arr;
        }
        $this->buildMaxHeap($arr, $count);
        for ($i = $count - 1; $i >= 0; $i--) {
            $this->swap($arr, 0, $i);
            $count--;
            $this->heapify($arr, 0, $count);
        }
        return $arr;
    }
}