<?php
/**
 * 商城
 *
 * User: forward
 * Date: 2018/7/18
 * Time: 下午5:24
 */

namespace App\controller\v1;


use App\model\mall\MallModel;

class MallController extends BaseController
{

    //商城列表
    public function list()
    {
        $list = (new MallModel())->getGoodsList();
        $this->renderJson(0, 'success', $list);
    }
}