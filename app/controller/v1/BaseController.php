<?php
/**
 * Created by PhpStorm.
 * User: forward
 * Date: 2018/7/18
 * Time: 下午2:52
 */

namespace App\controller\v1;


use App\model\user\UserModel;
use App\model\wechat\Redis\WechatModel;
use Slim\Http\Request;
use Slim\Http\Response;

class BaseController extends \App\controller\BaseController
{
    const CODE_NEED_LOGIN = 1025;

    protected $openId = '';
    protected $sessionKey = '';
    protected $userId = 0;
    protected $userInfo = [];

    public function __construct(Request $request, Response $response)
    {
        parent::__construct($request, $response);
        $this->checkToken();    //校验是否登录
        $this->appendInfo = [
            'open_id' => $this->openId,
            'session_key' => $this->sessionKey,
            'user_id' => $this->userId
        ];
    }

    /**
     * 校验token && 赋值用户数据
     *
     * @return bool
     */
    private function checkToken()
    {
        if ($this->path === '/v1/login') {
            return true;
        }
        $token = $this->request->getHeaderLine('token');
        $session3Rd = (new WechatModel)->get3rdSession($token);
        if (empty($token) || !$session3Rd) {
            $this->renderJson(self::CODE_NEED_LOGIN, 'token无效');
        }
        list($openId, $sessionKey) = explode(' ', $session3Rd);
        $this->openId = $openId;
        $this->sessionKey = $sessionKey;
        $this->userInfo = (new UserModel())->getUserByOpenId($this->openId);
        $this->userId = $this->userInfo['id'] ?? 0;
        return true;
    }
}