<?php
namespace common\models;

/**
 * 快递公司
 */
class Shipping extends Base
{

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
        ];
    }

    public static function shippingCompany()
    {
        return [
            [
                'id' => 1,
                'title' => '',
            ]
        ];
    }

    /** 快递日志 */
    public static function shippingLogs($company, $number)
    {
        return [
            [
                'tile' => '已签收',
                'date_time' => '2017-07-12 12:12',
            ],
            [
                'tile' => '已收件',
                'date_time' => '2017-07-02 12:12',
            ]
        ];
    }
}
