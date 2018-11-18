<?php

namespace app\controllers;


use app\models\category\Category;
use app\models\product\ProductSearch;
use app\models\product\ProductFeatureForm;
use app\models\product\ProductForm;
use Yii;
use app\models\product\Product;
use yii\base\Model;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;


/**
 * ProductController implements the CRUD actions for Product model.
 */
class ProductController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
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
     * Lists all Product models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ProductSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Product model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a create Product model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     * @throws \Exception
     */
    public function actionCreate()
    {
        $productForm = new ProductForm();
        $featureForm = new ProductFeatureForm();

        if ($productForm->load(Yii::$app->request->post())) {
            //Check if the submit was partial, only to load a different category.
            if ($productForm->loadsCategory()) {
                $featureForm = new ProductFeatureForm($productForm->category);
                return $this->render('create', [
                    'product' => $productForm,
                    'features' => $featureForm,
                ]);
            }

            if ($featureForm->load(Yii::$app->request->post()) && $product_id = $productForm->save($featureForm)) {
                return $this->redirect(['view', 'id' => $product_id]);
            }

        }

        return $this->render('create', [
            'product' => $productForm,
            'features' => $featureForm,
        ]);
    }

    /**
     * Updates an existing Product model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     * @throws \Exception
     */
    public function actionUpdate($id)
    {
        $productForm = new ProductForm($this->findModel($id));
        $featureForm = new ProductFeatureForm($productForm->category, $productForm->source);

        if ($productForm->load(Yii::$app->request->post())) {
            //Check if the submit was partial, only to load a different category.
            if ($productForm->loadsCategory()) {
                $featureForm = $productForm->loadsSourceCategory()
                    ? new ProductFeatureForm($productForm->category, $productForm->source)
                    : new ProductFeatureForm($productForm->category);
                return $this->render('update', [
                    'product' => $productForm,
                    'features' => $featureForm,
                ]);
            }

            //TODO: Fix update bug >> Updating requires two times to actually update the features.
            $featureForm->load(Yii::$app->request->post());
            if ($product_id = $productForm->update($featureForm)) {
                return $this->redirect(['view', 'id' => $product_id]);
            }

        }

        return $this->render('update', [
            'product' => $productForm,
            'features' => $featureForm,
        ]);
    }


    /**
     * Deletes an existing Product model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     * @throws \Exception
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Product model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Product the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Product::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
