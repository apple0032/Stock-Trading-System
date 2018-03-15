<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "approval".
 *
 * @property integer $id
 * @property string $type
 * @property integer $record_id
 * @property integer $user_submit
 * @property string $date_submit
 * @property integer $status
 * @property integer $user_review
 * @property string $date_review
 * @property string $rejected_remarks
 */
class Approval extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
	 
	 CONST STATUS_DRAFT = 0;
	 CONST STATUS_PENDING_REVIEW = 1;
	 CONST STATUS_APPROVED = 2;
	 CONST STATUS_CANCELLED = 3;
	 CONST STATUS_REJECTED = 10;
	 CONST STATUS_REVERTED = 11;
	 
	 public static $status_desc = [ 
		0 => 'Draft',
		1 => 'Pending',
		2 => 'Published',
		3 => 'Cancelled',
		10 => 'Rejected',
		11 => 'Reverted',
	 ];
	 
    public static function tableName()
    {
        return 'approval';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type', 'record_id', 'user_submit', 'date_submit', 'status'], 'required'],
            [['record_id', 'user_submit', 'status', 'user_review'], 'integer'],
            [['date_submit', 'date_review'], 'safe'],
            [['rejected_remarks'], 'string'],
            [['type'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'type' => 'Type',
            'record_id' => 'Record ID',
            'user_submit' => 'User Submit',
            'date_submit' => 'Date Submit',
            'status' => 'Status',
            'user_review' => 'User Review',
            'date_review' => 'Date Review',
            'rejected_remarks' => 'Rejected Remarks',
        ];
    }

	public static function _update(  $type, $record_id, $status, $remarks = '' ) {
		$approval_status = self::_getStatus($type, $record_id);
		//if ( !$approval_status['id'] ) {
		//	return false;
		//}
		$approval = self::findOne($approval_status['id']);
		if ( !$approval ) {
			$approval = new self;
			$approval->type = $type;
			$approval->record_id = $record_id;
			$approval->user_submit = Yii::$app->user->id;
			$approval->date_submit = date('Y-m-d H:i:s');
			//return false;
		}
		$approval->status = $status;
		$approval->rejected_remarks = $remarks;
		
		$approval->user_review =  Yii::$app->user->id;
		$approval->date_review = date('Y-m-d H:i:s');
			
		if ( $approval->save() ) {
			return $approval->id;
		}
		
		print_r($approval->errors);
		
		die();
		return false;
	}
	
	public static function _getStatus($type, $record_id) {
		$approval = self::find()->where( "type = :type AND record_id = :record_id", [':type' => $type, ':record_id' => $record_id] )
		->orderBy('id DESC')
		->limit(1)
		->one();
		
		$result = [];
		$result['status'] = $approval ? $approval->status : self::STATUS_APPROVED;
		$result['desc'] = self::$status_desc[ $result['status'] ];
		$result['id'] = $approval ? $approval->id : null;
		$result['isDraft'] = $result['status'] == self::STATUS_DRAFT;
		$result['isPending'] = $result['status'] == self::STATUS_PENDING_REVIEW;
		$result['isApproved'] = $result['status'] == self::STATUS_APPROVED;
		$result['isRejected'] = $result['status'] == self::STATUS_REJECTED;
		$result['isCancelled'] = $result['status'] == self::STATUS_CANCELLED;
		$result['isReverted'] = $result['status'] == self::STATUS_REVERTED;
		
		return $result;
	}
	
	
	
	public static function updatePage(  $record_id, $status, $remarks = '' ) {
		return self::_update('page', $record_id, $status, $remarks);
	}	
	public static function getPageStatus( $record_id ) {
		return self::_getStatus('page', $record_id);
	}
	
	public static function updateArticle(  $record_id, $status, $remarks = '' ) {
		return self::_update('article', $record_id, $status, $remarks);
	}	
	public static function getArticleStatus( $record_id ) {
		return self::_getStatus('article', $record_id);
	}
	
	public static function updateCategory(  $record_id, $status, $remarks = '' ) {
		return self::_update('category', $record_id, $status, $remarks);
	}	
	public static function getCategoryStatus( $record_id ) {
		return self::_getStatus('category', $record_id);
	}
	
}
