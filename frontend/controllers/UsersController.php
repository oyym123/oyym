<?php
namespace frontend\controllers;

use common\models\Attention;
use common\models\Base;
use common\models\Image;
use common\models\Product;
use common\models\ProductType;
use common\models\UserInfo;
use frontend\components\WebController;
use Yii;
use yii\base\Exception;

/**
 * Users controller
 */
class UsersController extends WebController
{

    public function init()
    {
        parent::init();
        if (empty($this->userId) && in_array(Yii::$app->requestedRoute, [
                'users/follow-category', 'users/follow-category-or-cancel',
                'users/upload-user-photo', 'users/setting', 'users/update-info'
            ])
        ) {
            self::needLogin();
        }
    }

    /**
     * Name: actionFollowCategory
     * Desc: 关注的分类
     * User: lixinxin <lixinxinlgm@fangdazhongxin.com>
     * Date: 2017-07-01
     * @SWG\Get(path="/users/follow-category",
     *   tags={"我的"},
     *   summary= "关注的分类",
     *   description="Author: lixinxin",
     *  @SWG\Parameter(
     *     name="ky-token", in="header", required=true, type="integer", default="1",
     *     description="用户ky-token",
     *    ),
     *   @SWG\Response(
     *      response=200, description="successful operation"
     *   )
     * )
     */
    public function actionFollowCategory()
    {
        $attention = Attention::getAttention(['type' => Attention::PRODUCT_TYPE]);
        $attentionIds = array_column($attention, 'type_id');
        $productTypes = ProductType::findAll(['level' => 1]);
        $data['list'] = [];
        foreach ($productTypes as $item) {
            $data['list'][] = [
                'id' => $item->id,
                'title' => $item->name,
                'is_follow' => in_array($item->id, $attentionIds) ? 1 : 0
            ];
        }
        self::showMsg($data);
    }

    /**
     * Name: actionFollowCategoryOrCancel
     * Desc: 关注或取消关注
     * User: lixinxin <lixinxinlgm@fangdazhongxin.com>
     * Date: 2017-07-01
     * @SWG\Post(path="/users/follow-category-or-cancel",
     *   tags={"我的"},
     *   summary= "关注或取消关注",
     *   description="Author: lixinxin",
     *  @SWG\Parameter(
     *     name="ky-token", in="header", required=true, type="integer", default="1",
     *     description="用户ky-token",
     *    ),
     *   @SWG\Parameter(name="type_ids", in="formData", required=true, type="string", default="[1,2,3,4]",
     *     description="json数组格式的宝贝id"
     *   ),
     *   @SWG\Response(
     *       response=200, description="successful operation"
     *   )
     * )
     */
    public function actionFollowCategoryOrCancel()
    {
        $attention = new Attention();
        $param['type'] = Attention::PRODUCT_TYPE;
        $typeIds = json_decode(Yii::$app->request->post('type_ids'), true);
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

    /**
     * @SWG\Get(path="/users/upload-user-photo",
     *   tags={"设置"},
     *   summary="上传用户头像接口",
     *   description="Author: OYYM",
     *   @SWG\Parameter(name="ky-token", in="header", required=true, type="integer", default="1",
     *     description="用户ky-token",
     *    ),
     *   @SWG\Parameter(name="url", in="query", default="abc.jpg", description="图片地址", required=true,
     *     type="string",
     *   ),
     *   @SWG\Parameter(name="name", in="query", default="abc.jpg", description="图片地址", required=true,
     *     type="string",
     *   ),
     *   @SWG\Response(
     *       response=200,description="successful operation"
     *   )
     * )
     */
    public function actionUploadUserPhoto()
    {
        $url = Yii::$app->request->post('url');
        $params = [
            'name' => $url,
            'type' => Image::TYPE_USER_PHOTO,
            'type_id' => $this->userId,
            'url' => Yii::$app->request->post('url'),
            'size_type' => Yii::$app->request->post('size', Image::SIZE_BIG),
            'status' => Base::STATUS_ENABLE,
            'sort' => Yii::$app->request->post('sort', 0),
        ];
        $delete['img_type'] = Image::TYPE_USER_PHOTO;
        $delete['type_id'] = $this->userId;
        $delete['img_urls'] = [$url];

        Image::deleteImages($delete);  //进行伪删除操作
        Image::setImage($params);
        self::showMsg('图片上传成功', 1);
    }

    /**
     * @SWG\Get(path="/users/setting",
     *   tags={"设置"},
     *   summary="设置主界面",
     *   description="Author: OYYM",
     *   @SWG\Parameter(name="ky-token", in="header", required=true, type="integer", default="1",
     *     description="用户ky-token",
     *    ),
     *   @SWG\Response(
     *       response=200,description="successful operation"
     *   )
     * )
     */
    public function actionSetting()
    {
        $model = UserInfo::findOne(['user_id' => $this->userId]);
        self::showMsg([
            'user_photo' => $model->photoUrl($model->id),
            'zhima' => '700'
        ]);
    }

    /**
     * @SWG\Post(path="/users/update-info",
     *   tags={"设置"},
     *   summary="更新用户信息",
     *   description="Author: OYYM",
     *   @SWG\Parameter(name="ky-token", in="header", required=true, type="integer", default="1",
     *     description="用户ky-token",
     *    ),
     *   @SWG\Parameter(name="intro", in="formData", default="1", description="用户简介，不能超过255字符", required=false,
     *     type="string",
     *   ),
     *   @SWG\Parameter(name="province", in="formData", default="1", description="省", required=false,
     *     type="integer",
     *   ),
     *   @SWG\Parameter(name="city", in="formData", default="1", description="市", required=false,
     *     type="integer",
     *   ),
     *   @SWG\Parameter(name="area", in="formData", default="1", description="区", required=false,
     *     type="integer",
     *   ),
     *   @SWG\Response(
     *       response=200,description="successful operation"
     *   )
     * )
     */
    public function actionUpdateInfo()
    {
        $model = UserInfo::findOne(['user_id' => $this->userId]);
        $model->province = Yii::$app->request->post('province');
        $model->city = Yii::$app->request->post('city');
        $model->area = Yii::$app->request->post('area');
        $model->intro = Yii::$app->request->post('intro');
        if (!$model->save()) {
            self::showMsg('更新信息失败', -1);
        }
        self::showMsg('更新信息成功', 1);
    }


    public function actionTest()
    {
        $t = serialize(['id' => 1]);
        echo $t;
        exit;
        var_dump(unserialize('a:1:{s:2:"id";i:8;}')['id']);
    }
}
