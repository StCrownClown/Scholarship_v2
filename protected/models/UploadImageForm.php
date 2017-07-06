<?php

/**
 * ContactForm class.
 * ContactForm is the data structure for keeping
 * contact form data. It is used by the 'contact' action of 'SiteController'.
 */
class UploadImageForm extends CActiveRecord {

    public $image;
    public $image_path;

    public function tableName() {
        return 'person';
    }

    /**
     * Declares the validation rules.
     */
    public function rules() {
        return array(
            array('image_path', 'required'),
            array('image','file', 'types'=>'jpg, jpeg', 'maxSize'=>1024 * 1024 * 1, 'tooLarge'=>'ขนาดต้องไม่เกิน 1MB / File has to be smaller than 1MB',
                'message' => 'กรุณาอัพโหลดภาพ</br>'
                . 'Please upload a photo.'),
        );
    }

    /**
     * Declares customized attribute labels.
     * If not declared here, an attribute would have a label that is
     * the same as its name with the first letter in upper case.
     */
    public function attributeLabels() {
        return array(
            'image_path' => 'Image Path',
        );
    }

}
