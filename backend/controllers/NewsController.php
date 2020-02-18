<?php

namespace backend\controllers;

use Yii;
use common\models\News;
use backend\models\NewsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use yii\imagine\Image;
use yii\filters\AccessControl;

/**
 * NewsController implements the CRUD actions for News model.
 */
class NewsController extends Controller {

    /**
     * {@inheritdoc}
     */
    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all News models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new NewsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single News model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new News model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $model = new News();

        if ($model->load(Yii::$app->request->post())) {
            $file = UploadedFile::getInstance($model, 'file');
            if ($file && $file->tempName) {
                $model->file = $file;
                if ($model->validate(['file'])) {
 
                    $dir = Yii::getAlias('@frontend/web/files/images/');
                    if (!file_exists($dir.'thumbs/')) {
                      \yii\helpers\FileHelper::createDirectory($dir.'thumbs/', $mode = 0775, $recursive = true);
                    } 
                    $links = Yii::getAlias('files/images/');
                    //$model->file->baseName
                    $name = md5($file->tempName);
                    $fileName = $name . '.' . $model->file->extension;
                    $model->file->saveAs($dir . $fileName);
                    $model->file = $fileName;
                    $model->main_image = '/' . $links . $fileName;
                    $model->preview = '/' . $links . 'thumbs/' . $fileName;
                    
                    Image::thumbnail($dir . $fileName, 150, 70)
                            ->save(Yii::getAlias($dir . 'thumbs/' . $fileName), ['quality' => 80]);
                }
            }
            if ($model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }
        return $this->render('create', [
                    'model' => $model,
        ]);
    }

    /**
     * Updates an existing News model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id);
        $current_main_image = $model->main_image;
        $current_preview = $model->preview;

        if ($model->load(Yii::$app->request->post())) {

            $file = UploadedFile::getInstance($model, 'file');
            if ($file && $file->tempName) {
                $model->file = $file;
                if ($model->validate(['file'])) {

                    if ($model->del_img) {
                        $this->deleteImage($current_main_image);
                        $this->deleteImage($current_preview);
                        $model->main_image = '';
                        $model->preview = '';
                    }

                    $dir = Yii::getAlias('@frontend/web/files/images/');
                    $links = Yii::getAlias('files/images/');
                    $name = md5($file->tempName);
                    $fileName = $name . '.' . $model->file->extension;
                    $model->file->saveAs($dir . $fileName);
                    $model->file = $fileName;
                    $model->main_image = '/' . $links . $fileName;
                    $model->preview = '/' . $links . 'thumbs/' . $fileName;
                    Image::thumbnail($dir . $fileName, 150, 70)
                            ->save(Yii::getAlias($dir . 'thumbs/' . $fileName), ['quality' => 80]);
                }
            }
            if ($model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }
        return $this->render('update', [
                    'model' => $model,
        ]);
    }

    /**
     * Deletes an existing News model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id) {
        $model = $this->findModel($id);
        $current_main_image = $model->main_image;
        $current_preview = $model->preview;
        $this->deleteImage($current_main_image);
        $this->deleteImage($current_preview);
        $model->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the News model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return News the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = News::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    protected function deleteImage($current_image) {
        if (file_exists(Yii::getAlias('@frontend/web' . $current_image))) {
            unlink(Yii::getAlias('@frontend/web' . $current_image));
        }
    }

}
