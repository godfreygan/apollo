<?php
/**
 * Created by PhpStorm.
 * Description:
 * User: ganqixin <godfrey.gan@handeson.com>
 * Date: 2019/3/30
 * Time: 10:04
 */

namespace App\Apollo\Controllers\Config;

use App\Apollo\Controllers\Base;
use App\Apollo\Library\Exceptions\ServiceException;
use App\Apollo\Modules\Config\Test;
use App\Util\ValidatorUtil;

class ExpressController extends Base
{
    /**
     * @OA\Post(
     *     path="/rpc.php?config_express_add",
     *     tags={"config"},
     *     summary="新增配送商",
     *     operationId="config_express_add",
     *     description="
    开发者： 甘其信
    邮箱： godfrey.gan@handeson.com
    备注：",
     *     @OA\RequestBody(
     *         description="Input data format",
     *         @OA\MediaType(
     *                  mediaType="application/json",
     *           @OA\Schema(
     *                  allOf={
     *                     @OA\Schema(ref="#/components/schemas/request_rpc_common_param"),
     *                     @OA\Schema(@OA\Property(property="params",type="array",@OA\Items(ref="#/components/schemas/config_express_addrequest"))),
     *                     @OA\Schema(@OA\Property(property="method",type="string",example="Config\Express.add")),
     *                 }
     *           )
     *         )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="successful operation",
     *         @OA\JsonContent(
     *           allOf={
     *              @OA\Schema(ref="#/components/schemas/apiprotocol"),
     *           }
     *          )
     *     )
     * )
     */
    public function addAction($param = [])
    {
        $rulesMap = [
            'express_company_no'   => ['required|string', '快递公司编号'],
            'company_name' => ['required|string', '快递公司名称'],
            'company_url'  => ['sometimes|string', '快递公司url'],
            'phone'        => ['sometimes|string', '联系电话'],
            'status'       => ['sometimes|integer', '启用状态：0-禁用，1-启用'],
            'rank'         => ['sometimes|integer', '权重排序'],
        ];
        try{
            list($rules,$message) = ValidatorUtil::formatRule($rulesMap);
            $data = $this->validate($param,$rules,$message,TRUE, __METHOD__);
            $ret = Test::add($data);
            return $this->responseSuccess(null, __METHOD__);
        } catch (ServiceException $e) {
            $this->log(__METHOD__, $e->getCode(), $e->getMessage());
            return $this->responseError($e->getCode(), $e->getMessage(), '', __METHOD__);
        }
    }

    /**
     * @OA\Post(
     *     path="/rpc.php?config_express_edit",
     *     tags={"config"},
     *     summary="编辑配送商",
     *     operationId="config_express_edit",
     *     description="
    开发者： 甘其信
    邮箱： godfrey.gan@handeson.com
    备注：",
     *     @OA\RequestBody(
     *         description="Input data format",
     *         @OA\MediaType(
     *                  mediaType="application/json",
     *           @OA\Schema(
     *                  allOf={
     *                     @OA\Schema(ref="#/components/schemas/request_rpc_common_param"),
     *                     @OA\Schema(@OA\Property(property="params",type="array",@OA\Items(ref="#/components/schemas/config_express_editrequest"))),
     *                     @OA\Schema(@OA\Property(property="method",type="string",example="Config\Express.edit")),
     *                 }
     *           )
     *         )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="successful operation",
     *         @OA\JsonContent(
     *           allOf={
     *              @OA\Schema(ref="#/components/schemas/apiprotocol"),
     *           }
     *          )
     *     )
     * )
     */
    public function editAction($param = [])
    {
        $rulesMap = [
            'express_company_no'   => ['required|string', '快递公司编号'],
            'company_name' => ['sometimes|string', '快递公司名称'],
            'company_url'  => ['sometimes|string', '快递公司url'],
            'phone'        => ['sometimes|string', '联系电话'],
            'status'       => ['sometimes|integer', '启用状态：0-禁用，1-启用'],
            'rank'         => ['sometimes|integer', '权重排序'],
        ];
        try{
            list($rules,$message) = ValidatorUtil::formatRule($rulesMap);
            $data = $this->validate($param,$rules,$message,TRUE, __METHOD__);
            $ret = Test::edit($data, $errMsg);
            if($ret === false){
                return $this->responseError($errMsg['errcode'],$errMsg['errmsg'], null, __METHOD__);
            }else{
                return $this->responseSuccess($ret, __METHOD__);
            }
        } catch (ServiceException $e) {
            $this->log(__METHOD__, $e->getCode(), $e->getMessage());
            return $this->responseError($e->getCode(), $e->getMessage(), '', __METHOD__);
        }
    }

    /**
     * @OA\Post(
     *     path="/rpc.php?config_express_delete",
     *     tags={"config"},
     *     summary="删除配送商",
     *     operationId="config_express_delete",
     *     description="
    开发者： 甘其信
    邮箱： godfrey.gan@handeson.com
    备注：",
     *     @OA\RequestBody(
     *         description="Input data format",
     *         @OA\MediaType(
     *                  mediaType="application/json",
     *           @OA\Schema(
     *                  allOf={
     *                     @OA\Schema(ref="#/components/schemas/request_rpc_common_param"),
     *                     @OA\Schema(@OA\Property(property="params",type="array",@OA\Items(ref="#/components/schemas/config_express_deleterequest"))),
     *                     @OA\Schema(@OA\Property(property="method",type="string",example="Config\Express.delete")),
     *                 }
     *           )
     *         )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="successful operation",
     *         @OA\JsonContent(
     *           allOf={
     *              @OA\Schema(ref="#/components/schemas/apiprotocol"),
     *           }
     *          )
     *     )
     * )
     */
    public function deleteAction($param = [])
    {
        $rulesMap = [
            'express_company_no' => ['required|string', '配送商编号']
        ];
        try{
            list($rules,$message) = ValidatorUtil::formatRule($rulesMap);
            $data = $this->validate($param,$rules,$message,TRUE, __METHOD__);
            $ret = Test::delete($data['express_company_no'], $errMsg);
            if($ret === FALSE){
                return $this->responseError($errMsg['errcode'],$errMsg['errmsg']);
            }else{
                return $this->responseSuccess($ret, __METHOD__);
            }
        } catch (ServiceException $e) {
            $this->log(__METHOD__, $e->getCode(), $e->getMessage());
            return $this->responseError($e->getCode(), $e->getMessage(), '', __METHOD__);
        }
    }

    /**
     * @OA\Post(
     *     path="/rpc.php?config_express_setStatus",
     *     tags={"config"},
     *     summary="启用/禁用配送商",
     *     operationId="config_express_setstatus",
     *     description="
    开发者： 甘其信
    邮箱： godfrey.gan@handeson.com
    备注：",
     *     @OA\RequestBody(
     *         description="Input data format",
     *         @OA\MediaType(
     *                  mediaType="application/json",
     *           @OA\Schema(
     *                  allOf={
     *                     @OA\Schema(ref="#/components/schemas/request_rpc_common_param"),
     *                     @OA\Schema(@OA\Property(property="params",type="array",@OA\Items(ref="#/components/schemas/config_express_setstatusrequest"))),
     *                     @OA\Schema(@OA\Property(property="method",type="string",example="Config\Express.setstatus")),
     *                 }
     *           )
     *         )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="successful operation",
     *         @OA\JsonContent(
     *           allOf={
     *              @OA\Schema(ref="#/components/schemas/apiprotocol"),
     *           }
     *          )
     *     )
     * )
     */
    public function setStatusAction($param = [])
    {
        $rulesMap = [
            'express_company_no' => ['required|string', '配送商编号'],
            'status'             => ['required|integer', '状态']
        ];
        try{
            list($rules,$message) = ValidatorUtil::formatRule($rulesMap);
            $data = $this->validate($param,$rules,$message,TRUE, __METHOD__);
            $ret = Test::setStatus($data, $errMsg);
            if($ret === FALSE){
                return $this->responseError($errMsg['errcode'],$errMsg['errmsg']);
            }else{
                return $this->responseSuccess($ret, __METHOD__);
            }
        } catch (ServiceException $e) {
            $this->log(__METHOD__, $e->getCode(), $e->getMessage());
            return $this->responseError($e->getCode(), $e->getMessage(), '', __METHOD__);
        }
    }

    /**
     * @OA\Post(
     *     path="/rpc.php?config_express_getList",
     *     tags={"config"},
     *     summary="配送商列表",
     *     operationId="config_express_getList",
     *     description="
    开发者： 甘其信
    邮箱： godfrey.gan@handeson.com
    备注：",
     *     @OA\RequestBody(
     *         description="Input data format",
     *         @OA\MediaType(
     *                  mediaType="application/json",
     *           @OA\Schema(
     *                  allOf={
     *                     @OA\Schema(ref="#/components/schemas/request_rpc_common_param"),
     *                     @OA\Schema(@OA\Property(property="params",type="array",@OA\Items(ref="#/components/schemas/config_express_getlistrequest"))),
     *                     @OA\Schema(@OA\Property(property="method",type="string",example="Config\Express.getList")),
     *                 }
     *           )
     *         )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="successful operation",
     *         @OA\JsonContent(
     *           allOf={
     *              @OA\Schema(ref="#/components/schemas/apiprotocol"),
     *              @OA\Schema(
     *                 @OA\Property(property="data",ref="#components/schemas/apidatalist",),
     *              ),
     *              @OA\Schema(
     *                 @OA\Property(property="data",ref="#components/schemas/config_express_getlistresponse",),
     *              )
     *           }
     *          )
     *     )
     * )
     */
    public function getListAction($param = [])
    {
        $rulesMap = [
            'express_company_nos' => ['sometimes|array', '快递公司编号数组'],
            'company_name'        => ['sometimes|string', '快递公司名称'],
            'status'              => ['sometimes|integer', '启用状态：0-全部，1-启用，2-禁用'],
            'orderby'             => ['sometimes|array', '排序'],
            'page'                => ['sometimes|integer', '页码'],
            'page_size'           => ['sometimes|integer', '分页量'],
        ];
        try{
            list($rules,$message) = ValidatorUtil::formatRule($rulesMap);
            $data = $this->validate($param,$rules,$message,TRUE, __METHOD__);
            $ret = Test::getList($data);
            return $this->responseSuccess($ret, __METHOD__);
        } catch (ServiceException $e) {
            $this->log(__METHOD__, $e->getCode(), $e->getMessage());
            return $this->responseError($e->getCode(), $e->getMessage(), '', __METHOD__);
        }
    }

    /**
     * @OA\Post(
     *     path="/rpc.php?config_express_info",
     *     tags={"config"},
     *     summary="配送商详情",
     *     operationId="config_express_info",
     *     description="
    开发者： 甘其信
    邮箱： godfrey.gan@handeson.com
    备注：",
     *     @OA\RequestBody(
     *         description="Input data format",
     *         @OA\MediaType(
     *                  mediaType="application/json",
     *           @OA\Schema(
     *                  allOf={
     *                     @OA\Schema(ref="#/components/schemas/request_rpc_common_param"),
     *                     @OA\Schema(@OA\Property(property="params",type="array",@OA\Items(ref="#/components/schemas/config_express_inforequest"))),
     *                     @OA\Schema(@OA\Property(property="method",type="string",example="Config\Express.info")),
     *                 }
     *           )
     *         )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="successful operation",
     *         @OA\JsonContent(
     *           allOf={
     *              @OA\Schema(ref="#/components/schemas/apiprotocol"),
     *              @OA\Schema(
     *                 @OA\Property(property="data",ref="#components/schemas/config_express_inforesponse",),
     *              )
     *           }
     *          )
     *     )
     * )
     */
    public function infoAction($param = [])
    {
        $rulesMap = [
            'express_company_no' => ['string|string', '配送商编号'],
        ];
        try{
            list($rules,$message) = ValidatorUtil::formatRule($rulesMap);
            $data = $this->validate($param,$rules,$message,TRUE, __METHOD__);
            $ret = Test::getOne($data['express_company_no']);
            return $this->responseSuccess($ret, __METHOD__);
        } catch (ServiceException $e) {
            $this->log(__METHOD__, $e->getCode(), $e->getMessage());
            return $this->responseError($e->getCode(), $e->getMessage(), '', __METHOD__);
        }
    }
}