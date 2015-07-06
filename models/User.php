<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

use dektrium\user\models\User as UserBase;

use sap55\orde\models\Store;

/**
 * AssignmentSearch represents the model behind the search form about Assignment.
 * 
 * @author Misbahul D Munir <misbahuldmunir@gmail.com>
 * @since 1.0
 */
class User extends \yii\db\ActiveRecord
{

    public function getStores() {
        return $this->hasMany(Store::className(), ['store_id' => 'store_id'])
            ->viaTable('user_to_store', ['user_id' => 'id']);
    }

    public function getDevices() {
        return $this->hasMany(Device::className(), ['user_id' => 'user_id']);
    }

    /**
     * Create data provider for Assignment model.
     * @param  array                        $params
     * @param  \yii\db\ActiveRecord         $class
     * @param  string                       $usernameField
     * @return \yii\data\ActiveDataProvider
     */
    public function search($params, $class, $usernameField)
    {
        $query = $class::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere(['like', $usernameField, $this->username]);

        return $dataProvider;
    }

    /**
     * @return [type] [description]
     */
    public static function tableName()
    {
        return \Yii::$app->authManager->itemTable;
    }
}
