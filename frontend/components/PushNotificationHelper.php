<?php
namespace frontend\components;

use Yii;
use yii\base\Component;
use yii\base\InvalidConfigException;

use Aws\Common\Aws;

use frontend\models\ApiLog;

class PushNotificationHelper extends Component
{
	/**
	* Register device token to service provider
	*/
	public function registerDeviceToken($profile, $deviceType, $supplier, $deviceToken, $param = null)
	{
		switch ($deviceType)
		{
			case 'iPhone':
				switch ($supplier)
				{
					case 'aws':						
						try
						{
							$aws = Aws::factory(Yii::getAlias('@common') . '/config/aws.php');
							$client = $aws->get('Sns');
							$result = $client->createPlatformEndpoint([
								'PlatformApplicationArn'	=>	Yii::$app->params['AWS']['SNS']['APNS']['iPhone']['PlatformApplicationArn'][$profile],
								'Token'						=>	$deviceToken,
								'CustomUserData'			=>	$profile,
							]);
							
							$return['status']	=	'Y';
							$return['msg']		=	'';
							$return['data']		=	$result->get('EndpointArn');
						}
						catch (\Exception $e)
						{
							ApiLog::error('php', $e->getMessage() . ' [' . $e->getFile() . ' line ' . $e->getLine() . ']');
							$return['status']	=	'E';
							$return['msg']		=	$e->getMessage();
						}
						break;
						
					default:
						$return['status']	=	'E';
						$return['msg']		=	'INVALID_PARAM';
						break;
				}
				return $return;
				break;
				
			case 'Android':
				switch ($supplier)
				{
					case 'aws':						
						try
						{
							$aws = Aws::factory(Yii::getAlias('@common') . '/config/aws.php');
							$client = $aws->get('Sns');
							$result = $client->createPlatformEndpoint([
								'PlatformApplicationArn'	=>	Yii::$app->params['AWS']['SNS']['APNS']['Android']['PlatformApplicationArn'][$profile],
								'Token'						=>	$deviceToken,
								'CustomUserData'			=>	$profile,
							]);
							
							$return['status']	=	'Y';
							$return['msg']		=	'';
							$return['data']		=	$result->get('EndpointArn');
						}
						catch (\Exception $e)
						{
							ApiLog::error('php', $e->getMessage() . ' [' . $e->getFile() . ' line ' . $e->getLine() . ']');
							$return['status']	=	'E';
							$return['msg']		=	$e->getMessage();
						}
						break;
						
					default:
						$return['status']	=	'E';
						$return['msg']		=	'INVALID_PARAM';
						break;
				}
				return $return;
				break;
			
			default:
				$return['status']	=	'E';
				$return['msg']		=	'INVALID_PARAM';
				return $return;
				break;
		}
	}
	
	/**
	* Publish message to registered mobile
	*/
	public function publishMessage($profile, $deviceType, $supplier, $deviceData, $msg)
	{
		switch ($deviceType)
		{
			case 'iPhone':
				switch ($supplier)
				{
					case 'aws':						
						try
						{
							$aws = Aws::factory(Yii::getAlias('@common') . '/config/aws.php');
							$client = $aws->get('Sns');
							$result = $client->publish([
								'TargetArn'	=>	$deviceData,
								'Message'	=>	json_encode([
									'default'	=>	$msg,
									'GCM'		=>	json_encode([
										'data'	=>	[
											'message'	=>	$msg,
										]
									]),
								]),
            					'MessageStructure'	=>	'json',
							]);
							
							$return['status']		=	'Y';
							$return['msg']			=	'';
							$return['MessageId']	=	$result->get('MessageId');
						}
						catch (\Exception $e)
						{
							ApiLog::error('php', $e->getMessage() . ' [' . $e->getFile() . ' line ' . $e->getLine() . ']');
							$return['status']	=	'E';
							$return['msg']		=	$e->getMessage();
						}
						break;
						
					default:
						$return['status']	=	'E';
						$return['msg']		=	'INVALID_PARAM';
						break;
				}
				return $return;
				break;
				
			case 'Android':
				switch ($supplier)
				{
					case 'aws':						
						try
						{
							$aws = Aws::factory(Yii::getAlias('@common') . '/config/aws.php');
							$client = $aws->get('Sns');
							$result = $client->publish([
								'TargetArn'	=>	$deviceData,
								'Message'	=>	json_encode([
									'default'	=>	$msg,
									'GCM'		=>	json_encode([
										'data'	=>	[
											'message'	=>	$msg,
										]
									]),
								]),
            					'MessageStructure'	=>	'json',
							]);
							
							$return['status']		=	'Y';
							$return['msg']			=	'';
							$return['MessageId']	=	$result->get('MessageId');
						}
						catch (\Exception $e)
						{
							ApiLog::error('php', $e->getMessage() . ' [' . $e->getFile() . ' line ' . $e->getLine() . ']');
							$return['status']	=	'E';
							$return['msg']		=	$e->getMessage();
						}
						break;
						
					default:
						$return['status']	=	'E';
						$return['msg']		=	'INVALID_PARAM';
						break;
				}
				return $return;
				break;
			
			default:
				$return['status']	=	'E';
				$return['msg']		=	'INVALID_PARAM';
				return $return;
				break;
		}
	}
}