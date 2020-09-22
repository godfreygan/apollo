<?php
namespace App\Apollo\ApiControllers;


use App\Apollo\Library\Enum\ExceptionCodeEnum;
use App\Apollo\Modules\UserAuth;

class LoginBase extends ApiBase
{
    protected $currentLoginUserID = 0;

    public function __construct()
    {
        parent::__construct();
        $userID = UserAuth::getLoginUserID();
        if(!$userID) {
            $res = $this->responseError(ExceptionCodeEnum::USER_NO_LOGIN, '未登录', new \stdClass());
            echo json_encode($res, JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE);
            exit;
        }
        $this->currentLoginUserID = $userID;
    }
}