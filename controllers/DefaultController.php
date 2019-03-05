<?php

namespace jonmer09\report\controllers;

use common\components\Controller;
use jonmer09\report\components\ReportHelper;
use jonmer09\report\models\Report;
use Yii;

/**
 * Description of DefaultController
 *
 * @author Jonmer Carpio <jonmer09@gmail.com>
 */
class DefaultController extends Controller
{

    public function actionIndex()
    {
        $models = ReportHelper::getUserReport();
        $sessions = ReportHelper::getUserReportSessionUrl();
        $id = $this->get('id');
        $report = null;
        if ($id)
        {
            $report = isset($models[$id]) ? $models[$id] : null;
        }
            
        return $this->render('index', [
                    'models' => $models,
                    'report' => $report,
                    'sessions' => $sessions
        ]);
    }

    /**
     * @return Report
     */
    public function findReport($id)
    {
        return $this->findModelFromClass($id, Report::className());
    }

    public function actionDeleteReportSession($id, $name)
    {
        $cache = Yii::$app->cache;
        $key = ReportHelper::$session_key . $id;
        $data = $cache->get($key);
        if ($data)
        {
            unset($data[$name]);
            $cache->set($key, $data, 0);
        }
    }

    public function actionSaveReportSession()
    {
        $post = Yii::$app->request->post(ReportHelper::$session_key);
        if (!$post)
        {
            return $this->redirectToReferrer([]);
        }

        $cache = Yii::$app->cache;
        $key = ReportHelper::$session_key . $post['id'];
        $data = $cache->get($key);
        if ($data)
        {
            $data[$post['name']] = $post['data'];
            $cache->set($key, $data, 0, ReportHelper::getReportSessionDependency());
        } else
        {
            $cache->set($key, [$post['name'] => $post['data']], 0, ReportHelper::getReportSessionDependency());
        }

        return $this->redirectToReferrer([]);
    }

}
