<?php

namespace backend\controllers;

use common\helpers\Helper;
use backend\components\WebController;
use common\models\City;
use common\models\UserInfo;
use Yii;
use common\models\User;
use app\models\UserYearSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * UsersController implements the CRUD actions for User model.
 */
class UserController extends WebController
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all User models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new UserYearSearch();
        $infoSearchModel = new UserInfo();
        $infoSearchModel->load(['UserInfo[phone_system]']);
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'infoSearchModel' => $infoSearchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single User model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        Yii::$app->session->setFlash('error', '不允许创建用户');
        return $this->redirect(['index']);


        $model = new User();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        // Yii::$app->session->setFlash('error', '不允许修改用户');
        // return $this->redirect(['index']);

        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        Yii::$app->session->setFlash('error', '不允许删除用户');
        return $this->redirect(['index']);

        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /** 数据导出 */
    public function actionExport()
    {
        $limitStart = Yii::$app->request->post('limitStart', 0);
        $limitEnd = Yii::$app->request->post('limitEnd', 0);

        header("Content-type:application/vnd.ms-excel");
        header("Content-Disposition:attachment;filename=用户数据.xls");

        $title = [
            '用户id',
            '手机号码',
            '姓名',
            '角色',
            '注册时间',
        ];

        echo iconv('utf-8', 'gbk', implode("\t", $title) . "\n");

        $limit = 500;

        $i = 0;

        while (1) {
            $offset = $limit * $i;
            $i++;

            $query = User::find()->where(['status' => User::STATUS_ACTIVE]);

            if ($limitStart && !$limitEnd) {
                $query->andWhere(['>=', 'id', $limitStart]);
            } elseif (($limitStart && $limitEnd) || $limitEnd) {
                $query->andWhere(['between', 'id', $limitStart, $limitEnd]);
            }

            $query->orderBy('id asc')->offset($offset)->limit($limit);
            $users = $query->all();

            if (!$users) {
                break;
            }

            foreach ($users as $user) {
                if (!Helper::isMobile($user->username)) {
                    continue;
                }
                $line = [];
                $line[] = $user->id;
                $line[] = $user->username;
                $line[] = $user->info ? $user->info->name : '';
                $line[] = $user->info ? $user->info->getIdent() : '';
                $line[] = date('Y-m-d H:i:s', $user->created_at);
                echo iconv('utf-8', 'gbk', implode("\t", $line)), "\n";
            }
        }
    }
}
