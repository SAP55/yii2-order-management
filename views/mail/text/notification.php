<?php

/*
 * This file is part of the Dektrium project.
 *
 * (c) Dektrium project <http://github.com/dektrium>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

/**
 * @var dektrium\user\models\User  $user
 * @var dektrium\user\models\Token $token
 */
?>
<?= Yii::t('order', 'Hello') ?>,

<?= Yii::t('order', 'Order #{invoice_no} updated to status {order_status}', ['invoice_no' => $order->invoice_no, 'order_status' => $order->orderStatus->name]) ?>.
<?= Yii::t('order', 'Please click the link below to view in dashboard') ?>.

<?= $order->url ?>

<?= Yii::t('order', 'If you cannot click the link, please try pasting the text into your browser') ?>.

<?= Yii::t('order', 'If you did not make this request you can ignore this email') ?>.
