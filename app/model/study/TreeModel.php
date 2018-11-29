<?php
/**
 * 二叉树
 *
 * User: forward
 * Date: 2018/10/31
 * Time: 下午2:11
 */

namespace App\model\study;


class TreeModel
{
    /**
     * 先序遍历
     *
     * @param Node $root
     */
    public static function preOrder(Node $root)
    {
        $stack = [];
        array_push($stack, $root);
        while (!empty($stack)) {
            $centerNode = array_pop($stack);
            echo $centerNode->value . PHP_EOL;

            if (!empty($centerNode->rightChild)) {
                array_push($stack, $centerNode->rightChild);
            }
            if (!empty($centerNode->leftChild)) {
                array_push($stack, $centerNode->leftChild);
            }
        }
    }

    public static function preOrder2($root)
    {
        if (!empty($root)) {
            echo $root->value . PHP_EOL;
            self::preOrder2($root->leftChild);
            self::preOrder2($root->rightChild);
        }
    }

    public static function midOrder2($root)
    {
        if (!empty($root)) {
            self::midOrder2($root->leftChild);
            echo $root->value . PHP_EOL;
            self::midOrder2($root->rightChild);
        }
    }

    public static function postOrder2($root)
    {
        if (!empty($root)) {
            self::postOrder2($root->leftChild);
            self::postOrder2($root->rightChild);
            echo $root->value . PHP_EOL;
        }
    }
}