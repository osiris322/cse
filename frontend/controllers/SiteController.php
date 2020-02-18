<?php
namespace frontend\controllers;


use Yii;
use frontend\models\NewsSearch;
use frontend\models\GallerySearch;

/**
 * Site controller
 */
class SiteController extends \yii\rest\Controller {   

    public function behaviors() {
        $behaviors['corsFilter'] = [

            'class' => \yii\filters\Cors::className(),
            'cors' => [
                'Origin' => ['*'],
                'Access-Control-Allow-Headers' => ['*'],
                
                'Access-Control-Request-Method' => ['GET'],
                
                ],
        ];
        return $behaviors;
    }
    
    public function verbs() {
        $verbs = parent::verbs();
        $verbs = [
                'index-news' => ['get'],
                'index-gallery' => ['get'],
        ];
        return $verbs;
    }
    
    /**
     * curl –X GET "http://localhost/site/index-news?created_at=1581939686"
     * curl –X GET "http://localhost/site/index-news?header=md"
     * curl –X GET "http://localhost/site/index-news"
     * 
     * @param int $created_at 
     * @param string $header
     * 
     * @return mixed
     * @throws \yii\web\HttpException 404
     **/
    public function actionIndexNews() {
        $searchModel = new NewsSearch();
        $params['NewsSearch'] = Yii::$app->request->queryParams;
        $dataProvider = $searchModel->search($params);
        $apiFormat = [];
        foreach ($dataProvider->models as $value) {
            unset($value['updated_at']);
            $value['created_at']= date("Y-m-d",strtotime($value['created_at']));
            $apiFormat[] = $value;
        }
        return $apiFormat;
    }
    
    /**
     * curl –X GET "http://localhost/site/index-gallery?tag=2"
     * curl –X GET "http://localhost/site/index-gallery"
     *  
     * @param string $tag 
     *  
     * @return mixed
     * @throws \yii\web\HttpException 404
     **/
    public function actionIndexGallery() {
        $searchModel = new GallerySearch();
        $paramTag = Yii::$app->request->queryParams;
        $params['GallerySearch']['linkGalleryTags.tag.name'] = isset($paramTag['tag']) ? $paramTag['tag'] : '';
        $dataProvider = $searchModel->search($params);
        $apiFormat = [];
        foreach ($dataProvider->models as $key => $value) {
            $tags = [];
            foreach ( $value->linkGalleryTags as $k=>$v ){
                        $tags[]  = ['id' => $v['tag']['id'], 'name' => $v['tag']['name']];
                    }
            $gallery = [
                'guid' => $value['guid'],
                'tags' => $tags,
                'img' => $value['img'],
                'description' => $value['description'],
            ];
            $apiFormat[] = $gallery;
        }
        return $apiFormat;
    }
    
    
}
