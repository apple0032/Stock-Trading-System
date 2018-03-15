<?php
namespace backend\models;

use yii\base\Model;
use yii\base\DynamicModel;
use yii\web\UploadedFile;
use yii\imagine\Image;
use Imagine\Gd;
use Imagine\Image\Box;
use Imagine\Image\BoxInterface;

class FileUploadForm extends Model
{
    public $uploadfile, $file_path, $basename, $type;
	public $upload_folder_name = '';
	public $model;
	public static $all_errors = [];

	CONST TYPE_FILE_ALLOWED = 'png, jpg, gif, zip, 7z, rar, tgz, gz, pdf, doc, docx, xls, xlsx, ppt, pptx, ods, odt, rtf, htm, html, txt, exe, apk';
	
    CONST TYPE_IMPORT_ALLOWED = 'xls, xlsx';

	CONST FOLDER_PAGE = 'page';
	CONST FOLDER_ARTICLE = 'article';
	
    CONST FOLDER_IMPORT = 'import';


	public function rules()
    {
        return [
            [['uploadfile'], 'file'],
        ];
    }

    public function validateFile() {
		$this->model = new DynamicModel([ 'uploadfile' => $this->uploadfile ]);
		if ( $this->type == 'image' ) {
			$this->model->addRule('uploadfile', 'file', ['skipOnEmpty' => true, 'mimeTypes' => 'image/*']);
		}
		if ( $this->type == 'file' ) {
			$this->model->addRule('uploadfile', 'file', ['skipOnEmpty' => true, 'extensions' => self::TYPE_FILE_ALLOWED]);
		}
		if ( $this->type == 'import' ) {
			$this->model->addRule('uploadfile', 'file', ['skipOnEmpty' => true, 'extensions' => self::TYPE_IMPORT_ALLOWED]);
		}

		return $this->model->validate();
	}

    public function upload() {
        if ($this->validateFile() && $this->uploadfile) {
            $dir = \Yii::getAlias('@kcfinderuploadlocal/'.$this->upload_folder_name.'/');
            if ( !is_dir($dir) ) {
                mkdir($dir, 0755, true);
            }
            $n = microtime(true). '_' . uniqid();

			$this->basename = $n . '.' . $this->uploadfile->extension;

            $this->file_path = $dir . $this->basename;

            $this->uploadfile->saveAs($this->file_path);

/*
            $img = Image::getImagine()->open($this->file_path_orig);

			$size      = $img->getSize();
			$w = $size->getWidth();
			$h = $size->getHeight();
			$min = 700;
			if ( $w < $min && $h < $min ) {
				//min size is 700, if fewer than min value, need to enlarge
				$r = 700 / max($w, $h);
				$neww = $w * $r;
				$newh = $h * $r;

				$img->resize(new Box($neww, $newh))->save($this->file_path);
				$img = Image::getImagine()->open($this->file_path);
			}

            $img->thumbnail(new Box(1024, 1024))->save($this->file_path);
*/

            return true;
        } else {
            return false;
        }
    }

	public function removeFile( $file ) {
		if ( $file ) {
			$f = basename($file);
			$dir = \Yii::getAlias('@kcfinderuploadlocal/'.$this->upload_folder_name.'/');

			if ( file_exists( $dir.$f ) ) {
				unlink($dir.$f);
				return true;
			}
		}
		return false;
	}

	public static function uploadAll( $file_upload_forms ) {
		$file_upload_form_validate_all = true;

		self::$all_errors = [];

		$basenames = [];

		foreach ( $file_upload_forms as $temp_name => &$file_upload_form1 ) {
			$file_upload_form1->uploadfile = UploadedFile::getInstanceByName($temp_name.'[uploadfile]');

			if ( !$file_upload_form1->validateFile() ) {
				self::$all_errors[$temp_name] = $file_upload_form1->model->errors;
				$file_upload_form_validate_all = false;
			}
		}

		if ( !$file_upload_form_validate_all ) {
			return false;
		}
		foreach ( $file_upload_forms as $temp_name => &$file_upload_form1 ) {
			$file_post = \Yii::$app->request->post($temp_name);
			$del = isset($file_post['del']) ? true : false;

			if ( $del ) {
				$k = $file_upload_form1->removeFile($temp_name);
				$basenames[$temp_name] = '';
			}


			if ( $file_upload_form1->upload() ) {
				$basenames[$temp_name] = \Yii::getAlias('@kcfinderupload/'.$file_upload_form1->upload_folder_name.'/' . $file_upload_form1->basename);
			}
		}

		return $basenames;

	}
}
