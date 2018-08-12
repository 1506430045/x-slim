<?php
/**
 * 排行
 *
 * User: forward
 * Date: 2018/8/12
 * Time: 下午2:15
 */

namespace App\controller\v1;


class RankController extends BaseController
{
    //星钻排行
    public function reward()
    {
        $this->renderJson();
    }

    //邀请排行
    public function invite()
    {
        $this->renderJson();
    }
}