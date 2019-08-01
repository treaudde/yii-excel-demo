<?php

namespace app\controllers;

use app\models\forms\ExcelUploadForm;
use yii\db\Exception;
use yii\web\UploadedFile;
use Yii;
use app\services\ParseExcelFile;
use yii\web\Controller;
use yii\filters\AccessControl;

/**
 * Class ExcelUploadController
 *
 * This controller handles the routes and processing for the excel file upload
 *
 * @package app\controllers
 */
class ExcelUploadController extends Controller
{

    /**
     * Returns access controls
     *
     * @return array
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['index'],
                'rules' => [
                    [
                        'actions' => ['index'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],

        ];
    }

    /**
     * This method displays the file upload page.  On submit it uploads the file for processing
     *
     * @return string|void
     */
    public function actionIndex()
    {
        //Instantiate the form model
        $excelModel = new ExcelUploadForm();

        //set up initial values for the frontend
        $errorMessage = null;
        $chartData = null;

        //if they are posting the form
        if (Yii::$app->request->isPost) {

            //perform the file upload here
            $excelModel->excelFile = UploadedFile::getInstance($excelModel, 'excelFile');
            // file is uploaded successfully
            if ($excelModel->upload()) {
                try {
                    $excelFileParser = new ParseExcelFile();
                    //get the processed data

                    $chartData = $excelFileParser->processSpreadSheet(
                        Yii::$app->getBasePath().'/web/uploads/'.$excelModel->excelFile->name
                    );


                    //either display an error message or the chart
                    if(is_string($chartData)) {
                        $errorMessage = $chartData;
                    }

                } catch (\Exception $exception) {
                    $errorMessage = $exception->getMessage();
                }
            }
            else {
                //throw an exception that the file upload failed
                $errorMessage = 'File failed to upload, please try again.';
            }
        }

        //the page is being displayed, show the upload form
        return $this->render('form',
            ['model' => $excelModel, 'errorMessage' => $errorMessage, 'chartData' => $chartData]
        );
    }
}
