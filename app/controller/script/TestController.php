<?php
/**
 * 脚本
 *
 * User: forward
 * Date: 2018/7/31
 * Time: 下午1:42
 */

namespace App\controller\script;

use App\model\study\Node;
use App\model\study\SortModel;
use App\model\study\TreeModel;

class TestController extends BaseController
{

    //php public/script.php -c Test -a test
    public function test()
    {
        $arr = [
            3, 6, 5, 0, 9, -1, 2, 88, 67
        ];
        $model = new SortModel();
        $res = $model->mergeSort($arr);
        var_dump($res);
        die;
        $a = new Node('a');
        $b = new Node('b');
        $c = new Node('c');
        $d = new Node('d');
        $e = new Node('e');
        $f = new Node('f');
        $g = new Node('g');
        $h = new Node('h');
        $i = new Node('i');

        $a->leftChild = $b;
        $a->rightChild = $c;
        $b->leftChild = $d;
        $b->rightChild = $g;
        $c->leftChild = $e;
        $c->rightChild = $f;
        $d->leftChild = $h;
        $d->rightChild = $i;

        TreeModel::postOrder2($a);
    }
}