<?php
/**
 * Created by PhpStorm.
 * User: Hetal
 * Date: 2018-06-07
 * Time: 05:34 PM
 */

namespace api\modules\v1\models;

use yii;
use yii\base\Model;
use yii\web\UploadedFile;

class UploadForm extends Model
{
    /**
     * @var UploadedFile[]
     */
    public $imageFiles;
    public $mainImage;

    public function rules()
    {
        return [
            [['imageFiles'], 'file', 'skipOnEmpty' => false, 'extensions' => 'png, jpg', 'maxFiles' => 4],
            [['mainImage'], 'file', 'skipOnEmpty' => false, 'extensions' => 'png, jpg', 'maxFiles' => 4],

        ];
    }

    public function upload()
    {
        if ($this->validate()) {
            foreach ($this->imageFiles as $file) {
                $filename = $file->baseName.uniqid().'.'.$file->extension;
                $path = Yii::getAlias('@api') .'/uploads/';
               $file->saveAs($path . $filename);
               // print_r($path . $file->baseName . '.' . $file->extension);die;
            }
            return true;
        } else {
            return false;
        }
    }
}