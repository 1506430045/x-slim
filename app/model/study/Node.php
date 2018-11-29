<?php
/**
 * 二叉树节点
 *
 * User: forward
 * Date: 2018/10/31
 * Time: 下午2:11
 */

namespace App\model\study;


class Node
{
    public $value;
    public $leftChild;
    public $rightChild;

    public function __construct($value = '')
    {
        $this->value = $value;
    }
}