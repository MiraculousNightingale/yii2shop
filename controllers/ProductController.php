<?php

namespace app\controllers;


use app\models\category\Category;
use app\models\comment\Comment;
use app\models\comment\CommentSearch;
use app\models\product\ProductSearch;
use app\models\product\ProductFeatureForm;
use app\models\product\ProductForm;
use app\models\rating\Rating;
use app\models\user\User;
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
        $searchModel = new CommentSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('view', [
            'model' => $this->findModel($id),
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
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

            $featureForm = new ProductFeatureForm($productForm->category);
            if ($featureForm->load(Yii::$app->request->post()) && $product_id = $productForm->update($featureForm)) {
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
     * @param $id
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionDetaliedView($id)
    {
        $product = $this->findModel($id);
        if (!$rating = $product->getRatingFromUser(Yii::$app->user->getId())) {
            $rating = new Rating();
        }
        $comment = new Comment();
        return $this->render('_detailed', ['product' => $product, 'comment' => $comment, 'rating' => $rating]);
    }

    /**
     * @param $id
     * @param $userId
     * @param $value
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionRate($id, $userId)
    {
        $product = $this->findModel($id);
        $user = User::findOne($userId);
        if (!$rating = $product->getRatingFromUser($userId)) {
            $rating = new Rating();
        }
        if ($rating->load(Yii::$app->request->post())) {
            if (!$rating->isUnrated()) {
                $rating->link('product', $product);
                $rating->link('user', $user);
            } elseif ($product->ratedByUser($userId)) {
                $rating->delete();
            }
            $value = ($rating->value) ? $rating->value : 0;
            Yii::$app->session->setFlash('success', "Rated successfully as $value!");
        } else {
            Yii::$app->session->setFlash('danger', "Rating failed . ");
        }
        return $this->redirect(['product/detalied-view', 'id' => $id]);
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
