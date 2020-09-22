<?php
namespace App\Apollo\Controllers;
/**
 * 用于提供phpunit测试第1个流程代码
 *
 */
class PhpunitController extends Base {

    /**
     * 用于提供phpunit测试第1个流程代码
     *
     * 无业务逻辑
     *
     * 提供给编写测试用例的测试
     *
     * @author 程金盛
     *
     * @return string
     * ``````````````````
     * 响应结果如下：
     *  welcome
     * ``````````````````
     */
    public function welcomeAction()
    {
        return 'welcome';
    }


}