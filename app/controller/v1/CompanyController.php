<?php
/**
 * 关于我们
 *
 * User: forward
 * Date: 2018/7/25
 * Time: 下午2:54
 */

namespace App\controller\v1;


use App\model\company\CompanyModel;

class CompanyController extends BaseController
{
    //关于我们
    public function about()
    {
        $data = (new CompanyModel())->aboutUs();
        $this->renderJson(0, 'success', $data);
    }
}