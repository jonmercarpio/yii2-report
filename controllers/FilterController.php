<?php

namespace jonmer09\report\controllers;

use Yii;
use jonmer09\report\models\Filter;
use jonmer09\report\models\FilterSearch;
use common\components\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * FilterController implements the CRUD actions for Filter model.
 */
class FilterController extends Controller {

    public function behaviors() {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Filter models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new FilterSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Filter model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    private function formParams($model) {
        return [
            'model' => $model
        ];
    }

    /**
     * Creates a new Filter model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $model = new Filter();
        $model->report_id = $this->get('id');

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirectToReferrer(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', $this->formParams($model));
        }
    }

    /**
     * Updates an existing Filter model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirectToReferrer(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', $this->formParams($model));
        }
    }

    /**
     * Deletes an existing Filter model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();
        return $this->redirectToReferrer(['index']);
    }

    /**
     * Finds the Filter model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Filter the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = Filter::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
