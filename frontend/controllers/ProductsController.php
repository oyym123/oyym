<?php
namespace frontend\controllers;

use common\models\Base;
use common\models\Image;
use common\models\Product;
use common\models\Video;
use frontend\components\WebController;
use Yii;
use yii\base\Exception;

/**
 * Products controller
 */
class ProductsController extends WebController
{
    /**
     * Name: actionIndex
     * Desc: 宝贝列表页接口
     * User: lixinxin <lixinxinlgm@fangdazhongxin.com>
     * Date: 2017-07-01
     * @SWG\Get(path="/products?debug=1",
     *   tags={"产品"},
     *   summary="产品列表",
     *   description="Author: lixinxin",
     *   @SWG\Response(
     *      response=200, description="successful operation"
     *   )
     * )
     */
    public function actionIndex()
    {
        self::showMsg([
            'sort_type' => 'tuijian',
            'products' => [
                [
                    'id' => __LINE__,
                    'images' => [
                        [
                            'id' => __LINE__,
                            'url' => 'https://www.baidu.com/img/bd_logo1.png',
                        ],
                        [
                            'id' => __LINE__,
                            'url' => 'https://www.baidu.com/img/bd_logo1.png',
                        ]
                    ],
                    'title' => '保时捷跑车便宜卖了',
                    'contents' => "保时捷跑车便宜卖了,保时捷跑车便宜卖了,保时捷跑车便宜卖了 \n保时捷跑车便宜卖了 保时捷跑车便宜卖了 \n保时捷跑车便宜卖了 保时捷跑车便宜卖了 \n保时捷跑车便宜卖了 保时捷跑车便宜卖了",
                    'progress' => '80', //
                    'progress_type' => "time || number",
                    'like' => 123,
                    'comment' => '12',
                    'danjia' => '12', // 单价
                    'zongjia' => '12', // 总价
                    'yikoujia' => '12', // 一口价
                    'end_time' => '12', // 时间
                    'share_params' => [
                        'share_title' => '众筹夺宝',
                        'share_contents' => '夺宝达人!',
                        'share_link' => 'http://' . $_SERVER['HTTP_HOST'] . yii\helpers\Url::to(['invite/signup', 'invite_id' => $this->userId]),
                        'share_img_url' => 'https://www.baidu.com/img/bd_logo1.png',
                    ]
                ],
                [
                    'id' => __LINE__,
                    'images' => [
                        [
                            'id' => __LINE__,
                            'url' => 'https://www.baidu.com/img/bd_logo1.png',
                        ],
                        [
                            'id' => __LINE__,
                            'url' => 'https://www.baidu.com/img/bd_logo1.png',
                        ]
                    ],
                ]
            ]
        ]);
    }

    /**
     * @SWG\Post(path="/products/create?debug=1",
     *   tags={"产品"},
     *   summary="",
     *   description="Author: OYYM",
     *   @SWG\Parameter(
     *     name="data",
     *     in="formData",
     *     default="{'model':1,'title':'Iphone9','contents':'\u4e70\u5b8c\u5c31\u5403\u571f:)','images':[{'name':'\u6d4b\u8bd5\u56fe\u7247','url':'demo321'},{'name':'\u6d4b\u8bd5\u56fe\u72472','url':'demo456'}],'videos':[{'name':'\u6d4b\u8bd5\u89c6\u98911','url':'demo321'}],'address':{'lat':'0.232512','lng':'1.2335432','detail_address':'\u6cb3\u5317\u7701\u5eca\u574a\u5e02\u71d5\u90ca\u9547'},'quantity':{'total':10000,'unit_price':1,'a_price':8000,'type_id':2},'time':{'start_time':1499392688,'end_time':1499692688,'unit_price':1,'type_id':2}}",
     *     description= "发布产品需要的数据，可在http://www.bejson.com/jsonviewernew/上解析",
     *     required=true,
     *     type="string",
     *   ),
     *   @SWG\Parameter(
     *     name="ky-token",
     *     in="header",
     *     default="1",
     *     description="用户ky-token",
     *     required=true,
     *     type="integer",
     *   ),
     *   @SWG\Response(
     *       response=200,description="successful operation"
     *   )
     * )
     */
    public function actionCreate()
    {
        $data = [
            'model' => 1,
            'title' => 'Iphone9',
            'contents' => '买完就吃土:)',
            'images' => [
                [
                    'name' => '测试图片',
                    'url' => 'demo321',
                ],
                [
                    'name' => '测试图片2',
                    'url' => 'demo456',
                ]
            ],
            'videos' => [
                [
                    'name' => '测试视频1',
                    'url' => 'demo321',
                ],
            ],
            'address' => [
                'lat' => '0.232512',
                'lng' => '1.2335432',
                'detail_address' => '河北省廊坊市燕郊镇',
            ],
            'quantity' => [
                'total' => 10000,
                'unit_price' => 1,
                'a_price' => 8000,
                'type_id' => 2,
            ],
            'time' => [
                'start_time' => 1499392688,
                'end_time' => 1499692688,
                'unit_price' => 1,
                'type_id' => 2,
            ],
        ];
        // echo json_encode($data);exit;
        //  $data = json_encode($data);
        $data = Yii::$app->request->post('data');

        $data = json_decode($data, true);

        $transaction = Yii::$app->db->beginTransaction();
        try {
            if (count($data['images']) > 6) {
                throw new Exception('最多上传6张图片');
            }
            if (count($data['videos']) > 1) {
                throw new Exception('最多上传1个视频');
            }
            $product = new Product();
            $product->title = $data['title'];
            $product->contents = $data['contents'];
            $product->detail_address = $data['address']['detail_address'];
            $product->lat = $data['address']['lat'];
            $product->lng = $data['address']['lng'];
            $product->user_id = $this->userId;
            $product->created_by = $this->userId;
            $product->created_at = time();
            $product->updated_at = time();
            if ($data['model'] == 1) {
                $product->total = $data['quantity']['total'];
                $product->unit_price = $data['quantity']['unit_price'];
                $product->type_id = $data['quantity']['type_id'];
                $product->a_price = $data['quantity']['a_price'];
            } else {
                $product->start_time = $data['time']['start_time'];
                $product->end_time = $data['time']['end_time'];
                $product->unit_price = $data['time']['unit_price'];
                $product->type_id = $data['time']['type_id'];
            }
            if (!$product->save()) {
                print_r($product->getErrors());
                exit;
                throw new Exception('宝贝发布失败');
            }
            foreach ($data['images'] as $image) {
                $params = [
                    'name' => $image['name'],
                    'type' => Image::TYPE_PRODUCT,
                    'type_id' => $product->id,
                    'url' => $image['url'],
                    'size_type' => Image::SIZE_MEDIUM,
                    'status' => Base::STATUS_ENABLE,
                ];
                Image::setImage($params);
            }

            foreach ($data['videos'] as $video) {
                $params = [
                    'name' => $video['name'],
                    'type' => Video::TYPE_PRODUCT,
                    'type_id' => $product->id,
                    'url' => $video['url'],
                    'size_type' => Video::SIZE_HD,
                    'status' => Base::STATUS_ENABLE,
                ];
                Video::setVideo($params);
            }
            $transaction->commit();
            self::showMsg('宝贝发布成功！', -1);
        } catch (Exception $e) {
            $transaction->rollBack();
            self::showMsg($e->getMessage(), -1);
        }
    }


    public function actionView($id)
    {
        $this->showMsg([

        ]);
    }


}
