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
                    $temp = $this->arr[$j+1];
                    $this->arr[$j+1] = $this->arr[$j];
                    $this->arr[$j] = $temp;
                }
            }
        }
        return $this->arr;
    }
}