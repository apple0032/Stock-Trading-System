<?php
namespace frontend\controllers;

use common\models\ApplicationForm;
use common\models\Article;
use common\models\ArticleLive;
use common\models\ArticleSearch;
use common\models\Category;
use common\models\CategoryLive;
use frontend\components\ECphplib;
use Yii;
use common\models\LoginForm;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use frontend\models\ContactForm;
use frontend\models\ArticleSearchForm;
use common\models\Calendar;
use common\models\Ecatalogue;
use common\models\EcatalogueSearch;
use common\models\Ecategorylist;
use common\models\Collectionlist;
use common\models\Saying;
use common\models\Location;
use common\models\Enquiry;
use common\models\Career;
use common\models\Portfolio;

use yii\base\InvalidParamException;
use yii\helpers\ArrayHelper;
use yii\httpclient\Exception;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\UploadedFile;
use yii\data\Pagination;

use common\models\Page;
use common\models\PageLive;
use common\models\Template;
use common\components\Functions;

use frontend\models\OpportunityForm;
use frontend\models\UploadForm;

use yii\helpers\Url;


class SiteController extends Controller
{
    public function actionIndex()
    {
        return Yii::$app->response->redirect(Url::to(['/admin']));

        //return $this->render('index');
    }

}
