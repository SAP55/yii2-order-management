<?php

namespace app\modules\order\models;

use Yii;

use vova07\fileapi\behaviors\UploadBehavior;

/**
 * This is the model class for table "order_image".
 *
 * @property integer $order_image_id
 * @property integer $order_id
 * @property string $image
 *
 * @property Order $order
 */
class OrderImage extends \yii\db\ActiveRecord
{

    public function behaviors()
    {
        return [
            'uploadBehavior' => [
                'class' => UploadBehavior::className(),
                'attributes' => [
                    'image' => [
                        'path' => \Yii::$app->params['imagePath'],
                        'tempPath' => \Yii::$app->params['imagePath'],
                        'url' => Yii::getAlias(\Yii::$app->params['imageUrl']),
                    ],
                ]
            ]
        ];
    }

    /**
     * @param string $image Имя изображения
     * @return string Путь к папке где хранятся изображения постов.
     */
    public function imagePath($image = null)
    {
        $path = '/storage/web/content/blogs/images';
        if ($image !== null) {
            $path .= '/' . $image;
        }
        return Yii::getAlias($path);
    }
    /**
     * @param string $image Имя изображения
     * @return string Путь к временной папке где хранятся изображения постов или путь к конкретному изображению
     */
    public function imageTempPath($image = null)
    {
        $path = '/storage/tmp/blogs/images';
        if ($image !== null) {
            $path .= '/' . $image;
        }
        return Yii::getAlias($path);
    }
    /**
     * @param string $image Имя изображения
     * @return string Путь к папке где хранятся мини-изображения постов.
     */
    public function previewPath($image = null)
    {
        $path = '/storage/web/content/blogs/previews';
        if ($image !== null) {
            $path .= '/' . $image;
        }
        return Yii::getAlias($path);
    }
    /**
     * @param string $image Имя изображения
     * @return string Путь к временной папке где хранятся мини-изображения постов или путь к конкретному мини-изображению
     */
    public function previewTempPath($image = null)
    {
        $path = '/storage/tmp/blogs/previews';
        if ($image !== null) {
            $path .= '/' . $image;
        }
        return Yii::getAlias($path);
    }
    /**
     * @param string $image Имя изображения.
     * @return string URL к папке где хранится/хранятся изображение/я.
     */
    public function imageUrl($image = null)
    {
        $url = '/content/blogs/images/';
        if ($image !== null) {
            $url .= $image;
        }
        if (isset(Yii::$app->params['staticsDomain'])) {
            $url = Yii::$app->params['staticsDomain'] . $url;
        }
        return $url;
    }
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'order_image';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_id'], 'required'],
            [['order_id'], 'integer'],
            [['image'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'order_image_id' => 'Order Image ID',
            'order_id' => 'Order ID',
            'image' => 'Image',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrder()
    {
        return $this->hasOne(Order::className(), ['order_id' => 'order_id']);
    }
}
