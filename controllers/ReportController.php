<?php

namespace jonmer09\report\controllers;

use Yii;
use jonmer09\report\models\Report;
use jonmer09\report\models\ReportSearch;
use jonmer09\report\models\FilterSearch;
use common\components\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ReportController implements the CRUD actions for Report model.
 */
class ReportController extends Controller {

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
     * Lists all Report models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new ReportSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Report model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        $filterSearch = new FilterSearch();
        $filterSearch->report_id = $id;
        $filterProvider = $filterSearch->search($this->getRequest()->queryParams);
        return $this->render('view', [
                    'model' => $this->findModel($id),
                    'filterProvider' => $filterProvider
        ]);
    }

    private function formParams($model) {
        return [
            'model' => $model,
            'roles' => $this->getRoleList()
        ];
    }

    /**
     * Creates a new Report model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $model = new Report();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirectToReferrer(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', $this->formParams($model));
        }
    }

    /**
     * Updates an existing Report model.
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
     * Deletes an existing Report model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Report model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Report the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = Report::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function getRoleList() {
        $authManager = Yii::$app->authManager;
        return \yii\helpers\ArrayHelper::map($authManager->getRoles(), 'name', 'name');
    }

}
