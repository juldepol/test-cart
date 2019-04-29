<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "cart".
 *
 * @property int $id
 * @property int $product_id
 * @property int $quantity
 * @property string $comment
 */
class Cart extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'cart';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['product_id'], 'required'],
            [['product_id', 'quantity'], 'integer'],
            [['comment'], 'string', 'max' => 200],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'product_id' => 'Product ID',
            'quantity' => 'Quantity',
            'comment' => 'Comment',
        ];
    }

    /**
     * Adds product item from product list to cart 
     *
     * @param int index of product item
     */
    public static function addToCart($id)
    {
        $productInCart = Cart::find()->where(['product_id' => $id])->one();
        if (is_null($productInCart)){
            $productInCart = new Cart();
            $productInCart->product_id = $id;
            $productInCart->quantity = 1;
            $productInCart->save(); 
        } else {
            $productInCart->quantity += 1;
            $productInCart->save(); 
        }
    }

    /**
     * Decreases the quantity of product items added to cart 
     * or deletes product from the cart table
     *
     * @param int index of item from cart
     */
    public static function removeFromCart($item)
    {
        $productInCart = Cart::find()->where(['id' => $item])->one();
        if ($productInCart->quantity > 1){
            $productInCart->quantity -= 1;
            $productInCart->save();
        } else {
            $productInCart->delete();
        }
    }

    /**
     * Deletes product from the cart table
     *
     * @param int index of item from cart
     */
    public static function deleteFromCart($item)
    {
        $productInCart = Cart::find()->where(['id' => $item])->one();
        $productInCart->delete();
    }

    /**
     * Deletes all entries in the cart table
     */
    public static function deleteAllFromCart()
    {
        $productsInCart = Cart::find()->all();
        foreach($productsInCart as $item){
            $item->delete();
        }
    }

    /**
     * Calculates total netto, brutto and tax
     *
     * @return array with netto, brutto, tax calculated totals
     */
    public static function getTotal()
    {
        $query = Yii::$app->db->createCommand('
        SELECT cart.quantity, product.price, product.tax
        FROM cart
        INNER JOIN product ON cart.product_id = product.id')->queryAll();
        $netto = 0;
        $brutto = 0;
        $tax = 0;
        foreach($query as $item){
            $brutto += $item['quantity']*$item['price'];
            $netto += round($item['quantity']*($item['price']+$item['price']*$item['tax']/100),2);
            $tax += round($item['quantity']*($item['price']*$item['tax']/100),2);
        }
        return [
            'netto' => $netto,
            'brutto' => $brutto,
            'tax' => $tax
        ];
    }
}