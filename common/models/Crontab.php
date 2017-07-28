<?php

namespace common\models;

use Yii;
use yii\base\Exception;

/**
 * This is the model class for table "crontab".
 *
 * @property integer $id
 * @property integer $type
 * @property integer $status
 * @property integer $exec_count
 * @property integer $exec_max_count
 * @property integer $exec_start_time
 * @property string $params
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $log
 */
class Crontab extends Base
{
    const WAIT = 0;//等待执行
    const IN_PROGRESS = 1; //进行中
    const SUCCESS = 2; //执行成功
    const FAIL = 3; //执行失败

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'crontab';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type', 'status', 'exec_count', 'exec_max_count', 'exec_start_time', 'created_at', 'updated_at'], 'integer'],
            [[], 'required'],
            [['params', 'log'], 'string'],
        ];
    }

    /** 加入redis缓存 */
    public function setRedis($type)
    {
        Yii::$app->redis->del($type . 'crontab_ids');
        $crontabs = Crontab::findAll(['type' => $type, 'status' => [self::WAIT, self::FAIL]]);
        foreach ($crontabs as $key => $crontab) {
            if ($crontab->exec_count < $crontab->exec_max_count) {
                Yii::$app->redis->del($type . 'crontab' . $crontab->id);
                Yii::$app->redis->del($type . 'crontab_ids');
                if ($crontab->status == self::FAIL) {
                    Yii::$app->redis->setex($type . 'crontab' . $crontab->id, 3, 1);
                    Yii::$app->redis->zadd($type . 'crontab_ids', $key, $crontab->id);
                } else if ($crontab->exec_start_time > time()) {
                    Yii::$app->redis->setex($type . 'crontab' . $crontab->id, $crontab->exec_start_time - time(), 1);
                    Yii::$app->redis->zadd($type . 'crontab_ids', $key, $crontab->id);
                }
            }
        }
    }

    /** 开始执行 */
    public function start($type)
    {
        $data = Yii::$app->redis->zrange($type . 'crontab_ids', 0, Yii::$app->redis->zcard($type . 'crontab_ids'));
        foreach ($data as $item) {
            if (!Yii::$app->redis->get($type . 'crontab' . $item)) {
                $crontab = $this->findModel($item);
                if ($crontab->exec_count < $crontab->exec_max_count && in_array($crontab->status, [0, 3])) {
                    $this->route($item, $type);
                }
            }
        }
    }

    public function route($crontabId, $type)
    {
        switch ($type) {
            case 1:
                $this->changeStatus($crontabId);
                break;
            case 2:
                $this->changeProgress($crontabId);
        }
    }

    public function changeStatus($id)
    {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $product = Product::findOne(['id' => $this->getParams($id)['id']]);
            list($code, $err) = $product->openLottery();
            $this->setCrontab($id, $code, $err);
            $transaction->commit();
        } catch (Exception $e) {
            $transaction->rollBack();
            var_dump([-1, $e->getMessage()]);
        }
    }

    public function changeProgress($id)
    {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $product = new Product();
            list($code, $err) = $product->changeProgress();
            $this->setCrontab($id, $code, $err);
            $transaction->commit();
        } catch (Exception $e) {
            $transaction->rollBack();
            var_dump([-1, $e->getMessage()]);
        }
    }

    /** 获取params数据 */
    public function getParams($id)
    {
        $crontab = self::find()->where(['id' => $id])->asArray()->one();
        return unserialize($crontab['params']);
    }


    /** 保存定时计划任务 */
    public function setCrontab($id, $code, $err = 'success')
    {
        $crontab = $this->findModel($id);
        if ($code < 0) {
            $crontab->status = Crontab::FAIL;
            $crontab->exec_count += 1;
            $crontab->log = $err;
        } else {
            $crontab->status = Crontab::SUCCESS;
            $crontab->exec_count += 1;
            $crontab->log = $err;
        }
        if (!$crontab->save()) {
            throw new Exception('定时计划保存失败!');
        }
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'type' => 'Type',
            'status' => 'Status',
            'exec_count' => 'Exec Count',
            'exec_max_count' => 'Exec Max Count',
            'exec_start_time' => 'Exec Start Time',
            'params' => 'Params',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'log' => 'Log',
        ];
    }

    public function findModel($id)
    {
        if (($model = Crontab::findOne($id))) {
            return $model;
        } else {
            throw new Exception('计划不存在!');
        }
    }
}
