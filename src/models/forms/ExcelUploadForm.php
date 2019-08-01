<?php
namespace app\models\forms;

use yii\base\Model;
use yii\web\UploadedFile;

/**
 * Class ExcelUploadForm
 *
 * Form model to track and store the csv upload
 * @package app\models\forms
 */
class ExcelUploadForm extends Model
{
    /**
     * @var UploadedFile
     */
    public $excelFile;

    /**
     * Validation rules for the file upload
     *
     * @return array
     */
    public function rules()
    {
        return [
            [['excelFile'], 'file', 'skipOnEmpty' => false, 'extensions' => 'xls'],
        ];
    }

    /**
     * This method handles the file upload
     *
     * @return bool
     */
    public function upload()
    {
        if ($this->validate()) { //if the file is valid
            $this->excelFile->saveAs('uploads/' . $this->excelFile->baseName . '.' . $this->excelFile->extension);
            return true;
        } else {
            return false;
        }
    }
}
