<?php

namespace jonmer09\report\controllers;

use yii\web\Controller;
use jonmer09\report\models\Report;

/**
 * Description of AdminController
 *
 * @author Jonmer Carpio <jonmer09@gmail.com>
 */
class AdminController extends Controller {

    public function actionIndex() {
        return $this->render('index', [
                    'models' => Report::find()->all()
        ]);
    }

    public function actionManage() {
        return $this->render('manage');
    }

}
