<?php
/**
 * 脚本
 *
 * User: forward
 * Date: 2018/7/31
 * Time: 下午1:42
 */

namespace App\controller\script;


use App\model\study\SearchModel;
use App\model\study\SortModel;

class TestController extends BaseController
{
    //php public/script.php -c Test -a test
    public function test()
    {
        $arr = [2, 4, 1, 88, 22, 33, 55, 86];
        $result = (new SortModel($arr))->insertSort();
        $searchIndex = (new SearchModel($result, 0))->binarySearch();
        var_dump($result, $searchIndex);
    }
}