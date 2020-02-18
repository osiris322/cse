<?php

namespace backend\controllers;

use Yii;
use common\models\Gallery;
use common\models\LinkGalleryTag;
use backend\models\GallerySearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use yii\filters\AccessControl;

/**
 * GalleryController implements the CRUD actions for Gallery model.
 */
class GalleryController extends Controller
{
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
     * Lists all Gallery models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new GallerySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Gallery model.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $gallery = $this->findModel($id);
        $dataProviderLinks = LinkGalleryTag::dataProviderLinks($gallery->guid);
        $linkGalleryTag = new LinkGalleryTag();
        if ($linkGalleryTag->load(Yii::$app->request->post()) && $linkGalleryTag->save()) {
            return $this->redirect(['view', 'id' => $id]);
        }
        return $this->render('view', [
            'model' => $gallery,
            'dataProviderLinks' => $dataProviderLinks,
            'linkGalleryTag' => $linkGalleryTag,
        ]);
    }

    /**
     * Creates a new Gallery model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Gallery();
        $linkGalleryTag = new LinkGalleryTag();

        if ($model->load(Yii::$app->request->post()) && $linkGalleryTag->load(Yii::$app->request->post())) {
            // грузим фото и model Gallery
            
            $file = UploadedFile::getInstance($model, 'file');
            if ($file && $file->tempName) {
                $model->file = $file;
                if ($model->validate(['file'])) {
                    $dir = Yii::getAlias('@frontend/web/files/images/');
                    if (!file_exists($dir)) {
                      \yii\helpers\FileHelper::createDirectory($dir, $mode = 0775, $recursive = true);
                    } 
                    $links = Yii::getAlias('files/images/');
                    $name = md5($file->tempName);
                    $fileName = $name . '.' . $model->file->extension;
                    $model->file->saveAs($dir . $fileName);
                    $model->file = $fileName;
                    $model->img = '/' . $links . $fileName;
                }
            }
            $transaction = Gallery::getDb()->beginTransaction();
            try {
                $model->save();
                $linkGalleryTags = [];
                foreach ($linkGalleryTag['id_tag'] as $key => $value) {
                   $temp = new LinkGalleryTag();
                   $temp->guid_gallery = $model->guid;
                   $temp->id_tag = $value;
                   $linkGalleryTags[] = $temp; 
                }
                if (\yii\base\Model::validateMultiple($linkGalleryTags)){
                    foreach ($linkGalleryTags as $modelLinkGalleryTags ) {
                        $modelLinkGalleryTags->save(false);
                    }
                    $transaction->commit();
                    return $this->redirect(['view', 'id' => $model->guid]);
                } else {
                    $transaction->rollBack();
                }
                
            } catch(\Exception $e) {
                $transaction->rollBack();
                throw $e;
            } catch(\Throwable $e) {
                $transaction->rollBack();
                throw $e;
            }
            /*if ($model->save()) {
                return $this->redirect(['view', 'id' => $model->guid]);
            }*/
        }
        return $this->render('create', [
                    'model' => $model,
                    'linkGalleryTag' => $linkGalleryTag,
        ]);
    }

    /**
     * Updates an existing Gallery model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $current_img = $model->img;
        
        if ($model->load(Yii::$app->request->post())) {

            $file = UploadedFile::getInstance($model, 'file');
            if ($file && $file->tempName) {
                $model->file = $file;
                if ($model->validate(['file'])) {

                    if ($model->del_img) {
                        $this->deleteImage($current_img);
                        $model->img = '';
                    }

                    $dir = Yii::getAlias('@frontend/web/files/images/');
                    $links = Yii::getAlias('files/images/');
                    $name = md5($file->tempName);
                    $fileName = $name . '.' . $model->file->extension;
                    $model->file->saveAs($dir . $fileName);
                    $model->file = $fileName;
                    $model->img = '/' . $links . $fileName;  
                }
            }
            if ($model->save()) {
                return $this->redirect(['view', 'id' => $model->guid]);
            }
        }
        return $this->render('update', [
                    'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Gallery model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $current_img = $model->img;
        $this->deleteImage($current_img);
        $model->delete();

        return $this->redirect(['index']);
    }
    
    public function actionDeleteTag($guidGallery, $idTag)
    {
        if (($model = LinkGalleryTag::findOne(['guid_gallery'=>$guidGallery, 'id_tag'=>$idTag])) !== null) {
            $model->delete();
            return $this->redirect(['view', 'id' => $guidGallery]);
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     * Finds the Gallery model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Gallery the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Gallery::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    
    protected function deleteImage($current_img) {
        if (file_exists(Yii::getAlias('@frontend/web' . $current_img))) {
            unlink(Yii::getAlias('@frontend/web' . $current_img));
        }
    }
}
