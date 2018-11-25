<?php

namespace app\controllers;

use app\models\order\OrderForm;
use app\models\order\OrderItem;
use app\models\user\User;
use Yii;
use app\models\order\Order;
use app\models\order\OrderSearch;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * OrderController implements the CRUD actions for Order model.
 */
class OrderController extends Controller
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
     * Lists all Order models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new OrderSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Order model.
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
     * Creates a new Order model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Order();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Order model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Order model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    public function actionCart()
    {
        /** @var User $user */
        $user = Yii::$app->user->identity;
        $model = $user->cart;


        foreach ($model->items as $item) {
            $items[$item->id] = $item;
        }

        if (isset($items) && OrderItem::loadMultiple($items, Yii::$app->request->post()) && OrderItem::validateMultiple($items)) {
            foreach ($items as $item) {
                /** @var OrderItem $item */
                $item->save();
            }
            Yii::$app->session->setFlash('success', 'Amounts applied, checkout the new total price!');
        }

        return $this->render('cart', ['cart' => $model]);
    }

    /**
     * @param $productId
     * @return \yii\web\Response
     * @throws \Exception
     */
    public function actionAddToCart($productId)
    {
        /** @var User $user */
        $user = Yii::$app->user->identity;
        $model = $user->cart;

        if ($model->addItem($productId)) {
            Yii::$app->session->setFlash('success', 'Product added to cart');
        } else {
            Yii::$app->session->setFlash('warning', 'You already added this product');

        }

        return $this->redirect(['/site/index']);
    }

    /**
     * @param $productId
     * @return \yii\web\Response
     * @throws \Exception
     */
    public function actionRemoveFromCart($productId)
    {
        /** @var User $user */
        $user = Yii::$app->user->identity;
        $model = $user->cart;

        if ($model->removeItem($productId)) {
            Yii::$app->session->setFlash('success', 'Item removed from cart');
        } else {
            Yii::$app->session->setFlash('warning', 'Item could not be removed');
        }

        return $this->redirect(['/order/cart']);
    }

    public function actionSubmitOrder()
    {
        /** @var User $user */
        $user = Yii::$app->user->identity;
        $model = new OrderForm();


        if ($model->load(Yii::$app->request->post())) {

            if ($model->loadsDestination()) {
                return $this->render('_submit', [
                    'model' => $model,
                    'displayDestination' => ($model->delivery_type == Order::DELIVERY_SERVICE) ? true : false,
                ]);
            }

            if ($model->save($user->cart)) {
                Yii::$app->session->setFlash('success', 'Your order has been submitted. We will e-mail you soon!');
                return $this->redirect(['site/index']);
            }

        }

        return $this->render('_submit', [
            'model' => $model,
            'displayDestination' => ($model->delivery_type == Order::DELIVERY_SERVICE) ? true : false,
        ]);
    }

    public function actionUserOrders($userId)
    {
        $model = User::findOne($userId);

        return $this->render('user_orders', ['model' => $model]);
    }

    /**
     * @param $id
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionDetailed($id)
    {
        $model = $this->findModel($id);

        return $this->render('_detailed', ['model' => $model]);
    }

    /**
     * @param $id
     * @param $status
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionSwitchStatus($id, $status)
    {
        $model = $this->findModel($id);
        $model->status = $status;
        if ($model->save()) {
            Yii::$app->session->setFlash('success', 'Status switched!');
        } else {
            Yii::$app->session->setFlash('danger', 'Status did not work, model save failed!');
        }
        return $this->redirect(['order/detailed', 'id' => $id]);
    }

    /**
     * @param $id
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionSendNotificationEmail($id)
    {
        $model = $this->findModel($id);
        if ($model->sendEmailNotification()) {
            Yii::$app->session->setFlash('success', 'Notified user about ' . $model->getStatusName() . ' status!');
        } else {
            Yii::$app->session->setFlash('danger', 'Mail did not send, something went wrong.');
        }
        return $this->redirect(['order/detailed', 'id' => $id]);

    }

    /**
     * Finds the Order model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Order the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Order::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
