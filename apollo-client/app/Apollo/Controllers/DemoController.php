<?php
/**
 * Created by PhpStorm.
 * author: xiaolong.zhang
 * Date: 2019-04-08 11:02
 * descption: handeson.com
 */

namespace App\Apollo\Controllers;

use App\Apollo\Controllers\Order\QueryController;
use App\Apollo\Library\Enum\NotifyEnum;
use App\Apollo\Library\Exceptions\ServiceException;
use App\Apollo\Library\Util\DBUtil;
use App\Apollo\Models\OrderDB\Order;
use App\Apollo\Models\OrderDB\OrderItem;
use App\Apollo\Modules\Activity\ActivityFactory;
use App\Apollo\Modules\Config\FreightTemplate;
use App\Apollo\Modules\Config\TransferOrder as TransferOrderConfig;
use App\Apollo\Modules\Dict\Region;
use App\Apollo\Modules\GoDutch\AA;
use App\Apollo\Modules\Notify\Notify;
use App\Apollo\Modules\Order\Business;
use App\Apollo\Modules\Order\OrderActivityPlacingBase;
use App\Apollo\Modules\Order\OrderImport;
use App\Apollo\Modules\Order\OrderQuery;
use App\Apollo\Modules\Order\OrderSubListJoinType;
use App\Apollo\Modules\Queue\Kafka\KafkaResend;
use App\Apollo\Modules\Queue\Kafka\OrderCreateSuccess;
use App\Apollo\Modules\ReturnOrder\ReturnOrderEdit;
use App\Apollo\Modules\ReturnOrder\ReturnOrderQuery;
use App\Apollo\Modules\Order\WmsOrder;
use App\Apollo\Modules\Queue\Kafka\OrderDelivery;
use App\Apollo\Service\ActivityService\Activity;
use App\Apollo\Service\StockService\SFOrder;
use App\Apollo\Service\UserService\User as Userservice;
use CjsRedis\Sequence;
use App\Apollo\Modules\Order\OrderSubmitException;
use App\Util\ValidatorUtil;
use App\Apollo\Models\OrderDB\OrderSubList as OrderSubModel;
use CjsLsf\Facades\DB;
use Log;

/**
 * Class DemoController
 * @package App\Apollo\Controllers
 * @remark  此文件仅可用于开发环境、测试环境。禁止生产环境调用。
 */
class DemoController extends Base
{

    public function __construct()
    {
        parent::__construct();
      //  parent::denyProductionExec();   //  禁止生产环境执行
    }

}
