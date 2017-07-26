<?php
namespace frontend\controllers;

use common\models\Attention;
use common\models\Base;
use frontend\components\WebController;
use Yii;
use yii\base\Exception;

/**
 * Users controller
 */
class UsersController extends WebController
{
    /**
     * Name: actionFollowCategory
     * Desc: 关注的分类
     * User: lixinxin <lixinxinlgm@fangdazhongxin.com>
     * Date: 2017-07-01
     * @SWG\Get(path="/users/follow-category",
     *   tags={"我的"},
     *   summary= "关注的分类",
     *   description="Author: lixinxin",
     *   @SWG\Response(
     *      response=200, description="successful operation"
     *   )
     * )
     */
    public function actionFollowCategory()
    {
        self::showMsg([
            [
                'id' => 1,
                'title' => '电子产品',
                'is_follow' => 1 // 已关注为1未关注为0
            ],
            [
                'id' => 2,
                'title' => '服装',
                'is_follow' => 0
            ]
        ]);
    }

    /**
     * Name: actionFollowCategoryOrCancel
     * Desc: 关注或取消关注
     * User: lixinxin <lixinxinlgm@fangdazhongxin.com>
     * Date: 2017-07-01
     * @SWG\Get(path="/users/follow-category-or-cancel",
     *   tags={"我的"},
     *   summary= "关注或取消关注",
     *   description="Author: lixinxin",
     *   @SWG\Response(
     *       response=200, description="successful operation"
     *   )
     * )
     */
    public function actionFollowCategoryOrCancel()
    {
        $attention = new Attention();
        $param['type'] = Attention::PRODUCT_TYPE;
        $typeIds = json_decode(Yii::$app->request->post('type_id'), true);
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $attention->cancelProductType();
            foreach ($typeIds as $typeId) {
                $param['type_id'] = $typeId;
                if (!$attention->create($param)) {
                    throw new Exception('关注失败');
                }
            }
            $transaction->commit();
            self::showMsg('关注成功', 1);
        } catch (Exception $e) {
            $transaction->rollBack();
            self::showMsg($e->getMessage(), -1);
        }
    }

    /**
     * Name: actionSortType
     * Desc: 首页排序类型列表
     * User: lixinxin <lixinxinlgm@fangdazhongxin.com>
     * Date: 2017-07-01
     * @SWG\Get(path="/users/sort-type",
     *   tags={"我的"},
     *   summary= "首页排序类型列表",
     *   description="Author: lixinxin",
     *   @SWG\Response(
     *     response=200, description="successful operation"
     *   )
     * )
     */
    public function actionSortType()
    {
        self::showMsg([
            [
                'id' => 1,
                'title' => '推荐'
            ],
            [
                'id' => 1,
                'title' => '进度'
            ],
            [
                'id' => 1,
                'title' => '单价'
            ],
            [
                'id' => 1,
                'title' => '总价'
            ],
            [
                'id' => 1,
                'title' => '一口价'
            ],
            [
                'id' => 1,
                'title' => '时间'
            ],
            [
                'id' => 1,
                'title' => '最新'
            ],

        ]);
    }


    /**
     * Name: actionCategory
     * Desc: 获取宝贝分类
     * User: lixinxin <lixinxinlgm@fangdazhongxin.com>
     * Date: 2017-07-01
     * @SWG\Get(path="/users/category",
     *   tags={"我的"},
     *   summary= "获取宝贝分类",
     *   description="Author: lixinxin",
     *   @SWG\Response(
     *      response=200, description="successful operation"
     *   )
     * )
     */
    public function actionCategory()
    {
        self::showMsg([
            [
                'id' => 1,
                'title' => '电子产品'
            ],
            [
                'id' => 2,
                'title' => '服装'
            ]
        ]);
    }

    public function actionTest()
    {
        $var1 = Yii::$app->redis->del("mioji2");
        // echo Yii::$app->redis->decrby('jkjk',2);
        //Yii::$app->redis->zadd('mioji2','z999f','ls','12321');
        Yii::$app->redis->zadd('mioji2', 1, 'rr', 2, 'ttt', 6, 000);
        //

        //$var3 = Yii::$app->redis->lrange("vari",0,99);
        $num = Yii::$app->redis->zcard('mioji2');
        $var81 = Yii::$app->redis->zrange('mioji2', 0, $num);
        foreach ($var81 as $i) {
            echo $i;

        }

    }
}
