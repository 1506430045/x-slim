<?php
/**
 * 个人中心
 *
 * User: forward
 * Date: 2018/7/17
 * Time: 下午2:50
 */

namespace App\controller\v1;


use App\model\asset\AssetModel;
use App\model\task\TaskModel;
use App\model\user\UserModel;
use App\model\wechat\WechatModel;
use Util\SmsUtil;

class UserController extends BaseController
{
    //登录
    public function login()
    {
        $code = $this->get('code', '');
        $signature = $this->get('signature', '');
        $rawData = $this->get('rawData', '');
        $encryptedData = $this->get('encryptedData', '');
        $iv = $this->get('iv', '');
        $inviteCode = $this->get('invite_code', '');

        $ret = WechatModel::getOpenId($code);

        if (empty($ret['status']) || empty($ret['data']['session_key'])) {
            $this->renderJson(500, $ret['message'] ?? '请稍后再试');
        }
        $sessionKey = $ret['data']['session_key'];
        $openId = $ret['data']['openid'];

        //验证signature
        if ($signature !== sha1($rawData . $ret['data']['session_key'])) {
            $this->renderJson(400, 'sign not match');
        }

        //验证加密数据
        $dataMatch = WechatModel::encryptDataMatch($sessionKey, $encryptedData, $iv, $openId);
        if ($dataMatch['status']) {
            $this->renderJson(400, 'encrypt data not match');
        }
        //保存用户数据
        (new UserModel())->addUser($rawData, $openId, $inviteCode);
        //设置token
        $data = [
            'token' => WechatModel::set3rdSession($openId, $sessionKey)
        ];
        $this->renderJson(0, 'success', $data);
    }

    //签到
    public function signIn()
    {
        $data = (new TaskModel)->createSignInTask($this->userId);
        if (in_array($data['status'], [TaskModel::SIGN_IN_STATUS_1, TaskModel::SIGN_IN_STATUS_2])) {
            $message = $data['status'] === TaskModel::SIGN_IN_STATUS_1 ? '签到成功' : '重复签到';
            $this->renderJson(0, $message, $data);
        } else {
            $this->renderJson(500, '签到失败', $data);
        }
    }

    //我的资产
    public function asset()
    {
        $assetModel = new AssetModel();
        $assetList = $assetModel->getAssetByUserId($this->userId);
        $tbAsset = $assetModel->calculateUserTotalAsset($this->userId, 'TB', $assetList);
        $data = [
            'tb_total' => [
                'currency_name' => $tbAsset['currency_name'] ?? '',
                'currency_number' => $tbAsset['currency_number'] ?? 0,
            ],
            'asset_list' => $assetList
        ];

        $this->renderJson(0, 'success', $data);
    }

    //绑定手机
    public function bindPhone()
    {
        $phone = $this->get('phone', '');
        $verifyCode = $this->get('verify_code', '');

        if (!self::checkPhoneCode($phone)) {
            $this->renderJson(400, '手机号码格式有误，请重试');
        }

        if (empty($verifyCode) || strlen($verifyCode) !== SmsUtil::SMS_CODE_LEN) {
            $this->renderJson(400, '验证码格式有误，请重试');
        }

        $userModel = new UserModel();

        $checkStatus = $userModel->checkUserSmsCode($this->userId, $phone, $verifyCode);
        if ($checkStatus === UserModel::CHECK_SMS_CODE_EXPIRED) {
            $this->renderJson(400, '验证码已过期，请重新获取');
        }
        if ($checkStatus === UserModel::CHECK_SMS_CODE_FAILED) {
            $this->renderJson(400, '验证码错误，请检查');
        }

        $rowCount = $userModel->bindPhone($this->userId, $this->openId, $phone);
        if ($rowCount === 1) {
            $message = '绑定成功';
            $stats = 1;
        } else {
            $message = '该号码已被绑定，请换号码重试';
            $stats = 2;
        }
        $this->renderJson(0, $message, ['status' => $stats]);
    }

    //获取短信验证码
    public function smsCode()
    {
        $phone = $this->get('phone', '');
        if (!self::checkPhoneCode($phone)) {
            $this->renderJson(400, '手机号码格式有误，请重试');
        }
        $userModel = new UserModel();
        if ($userModel->getUserByOpenId($this->openId)) {
            $this->renderJson(400, '已绑定手机号码，无需重复操作');
        }
        $code = SmsUtil::generateCode();
        $re = $userModel->setUserSmsCode($this->userId, $phone, $code);  //验证码默认5分钟内有效
        if ($re === 2) {
            $this->renderJson(400, '请不要短时间内重复获取验证码');
        }
        if ($re === 1) {
            SmsUtil::sendCode(SmsUtil::CARRIER_YUNPIAN, '86', $phone, $code);
            $this->renderJson(0, 'success');
        }
        $this->renderJson(500, '服务异常，请稍后再试');
    }

    /**
     * 简单校验手机号码
     *
     * @param $phone
     * @return bool
     */
    private static function checkPhoneCode($phone)
    {
        if (substr($phone, 0, 1) !== '1') {
            return false;
        }
        if (!is_numeric($phone)) {
            return false;
        }
        if (strlen($phone) !== 11) {
            return false;
        }
        return true;
    }


}