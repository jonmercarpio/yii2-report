<?php

namespace jonmer09\report\controllers;

use common\components\Controller;
use jonmer09\report\models\Report;
use jonmer09\report\components\ReportHelper;

/**
 * Description of DefaultController
 *
 * @author Jonmer Carpio <jonmer09@gmail.com>
 */
class DefaultController extends Controller {

    public function actionIndex() {
        $models = ReportHelper::getUserReport();
        $id = $this->get('id');
        $report = null;
        if ($id) {
            $report = isset($models[$id]) ? $models[$id] : null;
        }
        return $this->render('index', [
                    'models' => $models,
                    'report' => $report
        ]);
    }

    /**
     * @return Report
     */
    public function findReport($id) {
        return $this->findModelFromClass($id, Report::className());
    }

}
