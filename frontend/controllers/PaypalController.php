<?php 
namespace frontend\controllers; 
use Yii;
use yii\base\ErrorException;
use yii\helpers\ArrayHelper;
use yii\base\Component; 

use PayPal\Api\Address;
use PayPal\Api\CreditCard;
use PayPal\Api\Amount;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\Transaction;
use PayPal\Api\FundingInstrument;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Api\Details;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\RedirectUrls;
use PayPal\Rest\ApiContext; 
use PayPal\Api\PaymentExecution; 
use PayPal\Exception\PayPalConnectionException; 

use common\models\TransactionPaypal;
//use common\models\Product;

class PaypalController extends \yii\web\Controller 
{ 
	public function actionCancel() 
	{ 
		return $this->render('cancel'); 
	} 
	
	public function actionError() 
	{ 
		return $this->render('error'); 
	}
	
	public function actionIndex()
	{ 
		return $this->render('index'); 
		
	} 
	
	public function actionPay($approved=null,$PayerID=null) 
	{ 
		$payment = new Payment();
		if($approved==='true'){ 
			$transactionPayment = TransactionPaypal::findOne(['hash'=>Yii::$app->session['paypal_hash']]); 
			//var_dump($transactionPayment); 
			// Get the Paypal payment 
			$apiContext = new \PayPal\Rest\ApiContext(
						new \PayPal\Auth\OAuthTokenCredential(
							'ClientID',     // ClientID
							'ClientSecret'      // ClientSecret
						)
					);
			//var_dump($transactionPayment); 	
			$payment = Payment::get($transactionPayment->payment_id,$apiContext); 
			//var_dump($payment); 
			
			$execution = new PaymentExecution(); 
			$execution->setPayerId($PayerID); 
			
			//Execute Paypal payment (charge) 
			$payment->execute($execution,$apiContext);
			
			// Update transaction 
			$transactionPayment->complete = 1; 
			$transactionPayment->create_time = $payment->create_time; 
			$transactionPayment->update_time = $payment->update_time; 
			$transactionPayment->save(); 
			Yii::$app->session->remove('paypal_hash'); 
			
			//SEND Email 
			/*
				$text = ' You will download sourcode'.$transactionPayment->product->name.' in https://programemrthailand.com/account/download '; 
				Yii::$app->mail->compose(
					[
						'html' => '@app/mail-templates/html-email-01']) 
									->setFrom('support@programemrthailand.com') 
									->setTo($transactionPayment->user->email) 
									->setSubject('YesBootstrap - '.$transactionPayment->product->name) ->send(); 
			*/ 
			return $this->redirect(['success']); 
	}else{
				//if approved !== true return 
				$this->redirect(['cancel']); 
	} 
	} 
	
	public function actionSuccess() 
	{ 
		return $this->render('success'); 
	} 
	
}