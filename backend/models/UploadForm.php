<?php
/**
 * Created by PhpStorm.
 * User: Hetal
 * Date: 2018-06-12
 * Time: 11:16 AM
 */

namespace backend\models;

use yii;
use yii\base\Model;
use yii\web\UploadedFile;
class UploadForm extends Model
{
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