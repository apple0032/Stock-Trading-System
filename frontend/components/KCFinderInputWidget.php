<?php
namespace frontend\components;

use Yii;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\web\View;
use yii\widgets\InputWidget;
use yii\bootstrap\Modal;

use iutbay\yii2fontawesome\FontAwesome;

use iutbay\yii2kcfinder\KCFinder;
use iutbay\yii2kcfinder\KCFinderWidgetAsset;

class KCFinderInputWidget extends \iutbay\yii2kcfinder\KCFinderInputWidget
{
    public $thumbTemplate = '<li class="sortable"><div class="remove"><span class="fa fa-trash"></span></div><div class="thumbnail" style="background-size: contain; background-repeat: no-repeat; background-image:url({thumbSrc}); width:150px; height:150px;"></div><input type="hidden" name="{inputName}" value="{inputValue}"><a target="_blank" href="{thumbSrc}" >{inputValue}</a></li>';

    public function init()
    {
        parent::init();

		$kcfOptions = array_merge(KCFinder::$kcfDefaultOptions, [
			'uploadURL' => Yii::getAlias('@kcfinderupload'),
			'uploadDir' => Yii::getAlias('@kcfinderuploadlocal'),
			'access' => [
				'files' => [
					'upload' => true,
					'delete' => true,
					'copy' => true,
					'move' => true,
					'rename' => true,
				],
				'dirs' => [
					'create' => true,
					'delete' => true,
					'rename' => true,
				],
			],
		]);

        // Set kcfinder session options
        Yii::$app->session->set('KCFINDER', $kcfOptions);
	}

    public function run()
    {
        $this->registerClientScript();

        $kcfinder_js = Yii::$app->assetManager->getPublishedPath((new KCFinderWidgetAsset)->sourcePath);
        $kcfinder_js .= '/kcfinder.js';

		$kcfinder_js_content = file_get_contents($kcfinder_js);

		$replace = [
			".replace('{thumbSrc}', thumbUrl)" => ".replace(/\{thumbSrc\}/g, thumbUrl)",
			".replace('{inputName}', this.options.inputName)" => ".replace(/\{inputName\}/g, this.options.inputName)",
			".replace('{inputValue}', url);" => ".replace(/\{inputValue\}/g, url);",
		];

		$kcfinder_js_content = str_replace(array_keys($replace), array_values($replace), $kcfinder_js_content);

		file_put_contents($kcfinder_js, $kcfinder_js_content);

        $button = Html::button(FontAwesome::icon('picture-o') . ' ' . $this->buttonLabel, $this->buttonOptions);

        if ($this->iframe) {
            $button.= Modal::widget([
                'id' => $this->getIFrameModalId(),
                'header' => Html::tag('h4', $this->modalTitle, ['class' => 'modal-title']),
                'size' => Modal::SIZE_LARGE,
                'options' => [
                    'class' => 'kcfinder-modal',
                ],
            ]);
        }

        $thumbs = '';
		/* fix the orig code cannot show single image thumbnail problem: */
        if ($this->hasModel() ) {
            $images = (array)$this->model->{$this->attribute};
            foreach ($images as $path) {
				$replace = [
                    '{thumbSrc}' => $this->getThumbSrc($path),
                    '{inputName}' => $this->getInputName(),
                    '{inputValue}' => $path,
                ];
                $thumbs.= str_replace(array_keys($replace), array_values($replace), $this->thumbTemplate);
            }
        }
        $thumbs = Html::tag('ul', $thumbs, ['id' => $this->getThumbsId(), 'class' => 'kcf-thumbs']);

		$replace = [
            '{button}' => $button,
            '{thumbs}' => $thumbs,
        ];
        echo Html::tag('div', str_replace(array_keys($replace), array_values($replace), $this->template), ['class' => 'kcf-input-group']);
    }
}
