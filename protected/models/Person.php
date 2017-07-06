<?php

/**
 * This is the model class for table "person".
 *
 * The followings are the available columns in table 'person':

 *
 * The followings are the available model relations:
 * @property Address[] $addresses
 * @property Comment[] $comments
 * @property Education[] $educations
 * @property LanguageProficiency[] $languageProficiencies
 * @property NstdamasDepartment $department
 * @property NstdamasPrefix $prefix
 * @property NstdamasOrg $org
 * @property NstdamasNationality $nationality
 * @property NstdamasReligion $religion
 * @property NstdamasMajor $major
 * @property NstdamasFaculty $faculty
 * @property NstdamasInstitute $institute
 * @property NstdamasPrefix $fatherPrefix
 * @property NstdamasPrefix $motherPrefix
 * @property NstdamasPrefix $parentPrefix
 * @property Resultreward[] $resultrewards
 * @property Scholar[] $scholars
 * @property Scholar[] $scholars1
 * @property Scholar[] $scholars2
 * @property Scholar[] $scholars3
 * @property ScholarHistory[] $scholarHistories
 * @property ScholarJstphighschool[] $scholarJstphighschools
 * @property ScholarJstpjuniorhighschool[] $scholarJstpjuniorhighschools
 * @property ScholarNuirc[] $scholarNuircs
 * @property ScholarStem[] $scholarStems
 * @property ScholarStem[] $scholarStems1
 * @property ScholarStem[] $scholarStems2
 * @property ScholarStem[] $scholarStems3
 * @property ScholarTaist[] $scholarTaists
 * @property ScholarTgist[] $scholarTgists
 * @property ScholarYstp[] $scholarYstps
 * @property WorkExperience[] $workExperiences
 */
class Person extends CActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Person the static model class
     */
    public $id;
    public $type;
    public $education_id;
    public $nationality_id;
    public $id_card;
    public $id_card_created;
    public $id_card_expired;
    public $password;
    public $prefix_id;
    public $fname;
    public $lname;
    public $en_name;
    public $en_lastname;
    public $nickname;
    public $birthdate;
    public $gender;
    public $mobile;
    public $email;
    public $phone;
    public $fax;
    public $status;
    public $religion_id;
    public $hobby;
    public $food_allergies;
    public $number_of_siblings;
    public $expert;
    public $position;
    public $academic_position;
    public $management_position;
    public $organization_name;
    public $org_id;
    public $department_id;
    public $major_id;
    public $major_other;
    public $faculty_id;
    public $faculty_other;
    public $institute_id;
    public $institute_other;
    public $current_project;
    public $father_prefix_id;
    public $father_name;
    public $father_lastname;
    public $father_email;
    public $father_phone;
    public $mother_prefix_id;
    public $mother_name;
    public $mother_lastname;
    public $mother_phone;
    public $mother_email;
    public $parent_prefix_id;
    public $parent_name;
    public $parent_lastname;
    public $parent_phone;
    public $parent_email;
    public $parent_relationship;
    public $parent_other;
    public $toefl_paper_base;
    public $toefl_internet_base;
    public $cutep;
    public $tuget;
    public $ielts;
    public $toeic;
    public $kuexam;
    public $other_exam_name;
    public $other_exam_score;
    public $current_project1;
    public $current_project2;
    public $current_project3;
    public $current_project4;
    public $current_project5;
    public $check_edit_profile;
    public $pf_ck_institute_other;
    public $pf_ck_faculty_other;
    public $pf_ck_disciplines_other;
    public $image_path;
    public $copy_id_card_path;
    public $copy_house_registration_path;
    public $cv_path;
    public $english_doc_path;
    public $attachment_other_path;
    public $odoo_id;
    public $first_created;
    public $last_updated;

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'person';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('nationality_id, prefix_id, religion_id, number_of_siblings, org_id, department_id, major_id, faculty_id, institute_id, father_prefix_id, mother_prefix_id, parent_prefix_id, toefl_paper_base, toefl_internet_base, cutep, tuget, toeic, kuexam, other_exam_score, check_edit_profile, odoo_id', 'numerical', 'integerOnly' => true),
            array('ielts', 'numerical'),
            array('type, password, fname, lname, en_name, en_lastname, nickname, email, status, hobby, food_allergies, position, academic_position, management_position, organization_name, major_other, faculty_other, institute_other, father_name, father_lastname, father_email, father_phone, mother_name, mother_lastname, mother_phone, mother_email, parent_name, parent_lastname, parent_phone, parent_email, parent_relationship, parent_other, other_exam_name, current_project1, current_project2, current_project3, current_project4, current_project5, image_path, copy_id_card_path, copy_house_registration_path, cv_path, english_doc_path, attachment_other_path', 'length', 'max' => 255),
            array('id_card, phone, fax', 'length', 'max' => 50),
            array('gender', 'length', 'max' => 1),
            array('mobile, pf_ck_institute_other, pf_ck_faculty_other, pf_ck_disciplines_other', 'length', 'max' => 10),
            array('id_card_created, id_card_expired, education_id, birthdate, expert, current_project, first_created, last_updated', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id_card_created, id_card_expired, education_id, id, type, nationality_id, id_card, password, prefix_id, fname, lname, en_name, en_lastname, nickname, birthdate, gender, mobile, email, phone, fax, status, religion_id, hobby, food_allergies, number_of_siblings, expert, position, academic_position, management_position, organization_name, org_id, department_id, major_id, major_other, faculty_id, faculty_other, institute_id, institute_other, current_project, father_prefix_id, father_name, father_lastname, father_email, father_phone, mother_prefix_id, mother_name, mother_lastname, mother_phone, mother_email, parent_prefix_id, parent_name, parent_lastname, parent_phone, parent_email, parent_relationship, parent_other, toefl_paper_base, toefl_internet_base, cutep, tuget, ielts, toeic, kuexam, other_exam_name, other_exam_score, current_project1, current_project2, current_project3, current_project4, current_project5, check_edit_profile, pf_ck_institute_other, pf_ck_faculty_other, pf_ck_disciplines_other, image_path, copy_id_card_path, copy_house_registration_path, cv_path, english_doc_path, attachment_other_path, odoo_id, first_created, last_updated', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'addresses' => array(self::HAS_MANY, 'Address', 'person_id'),
            'comments' => array(self::HAS_MANY, 'Comment', 'person_id'),
            'educations' => array(self::HAS_MANY, 'Education', 'person_id'),
            'languageProficiencies' => array(self::HAS_MANY, 'LanguageProficiency', 'person_id'),
            'education' => array(self::BELONGS_TO, 'Education', 'education_id'),
            'department' => array(self::BELONGS_TO, 'NstdamasDepartment', 'department_id'),
            'prefix' => array(self::BELONGS_TO, 'NstdamasPrefix', 'prefix_id'),
            'org' => array(self::BELONGS_TO, 'NstdamasOrg', 'org_id'),
            'nationality' => array(self::BELONGS_TO, 'NstdamasNationality', 'nationality_id'),
            'religion' => array(self::BELONGS_TO, 'NstdamasReligion', 'religion_id'),
            'major' => array(self::BELONGS_TO, 'NstdamasMajor', 'major_id'),
            'faculty' => array(self::BELONGS_TO, 'NstdamasFaculty', 'faculty_id'),
            'institute' => array(self::BELONGS_TO, 'NstdamasInstitute', 'institute_id'),
            'fatherPrefix' => array(self::BELONGS_TO, 'NstdamasPrefix', 'father_prefix_id'),
            'motherPrefix' => array(self::BELONGS_TO, 'NstdamasPrefix', 'mother_prefix_id'),
            'parentPrefix' => array(self::BELONGS_TO, 'NstdamasPrefix', 'parent_prefix_id'),
            'resultrewards' => array(self::HAS_MANY, 'Resultreward', 'person_id'),
            'scholars' => array(self::HAS_MANY, 'Scholar', 'student_id'),
            'scholars1' => array(self::HAS_MANY, 'Scholar', 'professor_id'),
            'scholars2' => array(self::HAS_MANY, 'Scholar', 'mentor_id'),
            'scholars3' => array(self::HAS_MANY, 'Scholar', 'industrial_id'),
            'scholarHistories' => array(self::HAS_MANY, 'ScholarHistory', 'person_id'),
            'scholarJstphighschools' => array(self::HAS_MANY, 'ScholarJstphighschool', 'person_id'),
            'scholarJstpjuniorhighschools' => array(self::HAS_MANY, 'ScholarJstpjuniorhighschool', 'person_id'),
            'scholarNuircs' => array(self::HAS_MANY, 'ScholarNuirc', 'person_id'),
            'scholarStems' => array(self::HAS_MANY, 'ScholarStem', 'industrial_id'),
            'scholarStems1' => array(self::HAS_MANY, 'ScholarStem', 'student_id'),
            'scholarStems2' => array(self::HAS_MANY, 'ScholarStem', 'professor_id'),
            'scholarStems3' => array(self::HAS_MANY, 'ScholarStem', 'mentor_id'),
            'scholarTaists' => array(self::HAS_MANY, 'ScholarTaist', 'person_id'),
            'scholarTgists' => array(self::HAS_MANY, 'ScholarTgist', 'person_id'),
            'scholarYstps' => array(self::HAS_MANY, 'ScholarYstp', 'person_id'),
            'workExperiences' => array(self::HAS_MANY, 'WorkExperience', 'person_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'type' => 'Type',
            'nationality_id' => 'Nationality',
            'id_card' => 'Id Card',
            'password' => 'Password',
            'prefix_id' => 'Prefix',
            'fname' => 'Fname',
            'lname' => 'Lname',
            'en_name' => 'En Name',
            'en_lastname' => 'En Lastname',
            'nickname' => 'Nickname',
            'birthdate' => 'Birthdate',
            'gender' => 'Gender',
            'mobile' => 'Mobile',
            'email' => 'Email',
            'phone' => 'Phone',
            'fax' => 'Fax',
            'status' => 'Status',
            'religion_id' => 'Religion',
            'hobby' => 'Hobby',
            'food_allergies' => 'Food Allergies',
            'number_of_siblings' => 'Number Of Siblings',
            'expert' => 'Expert',
            'position' => 'Position',
            'academic_position' => 'Academic Position',
            'management_position' => 'Management Position',
            'organization_name' => 'Organization Name',
            'org_id' => 'Org',
            'department_id' => 'Department',
            'major_id' => 'Major',
            'major_other' => 'Major Other',
            'faculty_id' => 'Faculty',
            'faculty_other' => 'Faculty Other',
            'institute_id' => 'Institute',
            'institute_other' => 'Institute Other',
            'current_project' => 'Current Project',
            'father_prefix_id' => 'Father Prefix',
            'father_name' => 'Father Name',
            'father_lastname' => 'Father Lastname',
            'father_email' => 'Father Email',
            'father_phone' => 'Father Phone',
            'mother_prefix_id' => 'Mother Prefix',
            'mother_name' => 'Mother Name',
            'mother_lastname' => 'Mother Lastname',
            'mother_phone' => 'Mother Phone',
            'mother_email' => 'Mother Email',
            'parent_prefix_id' => 'Parent Prefix',
            'parent_name' => 'Parent Name',
            'parent_lastname' => 'Parent Lastname',
            'parent_phone' => 'Parent Phone',
            'parent_email' => 'Parent Email',
            'parent_relationship' => 'Parent Relationship',
            'parent_other' => 'Parent Other',
            'toefl_paper_base' => 'Toefl Paper Base',
            'toefl_internet_base' => 'Toefl Internet Base',
            'cutep' => 'Cutep',
            'tuget' => 'Tuget',
            'ielts' => 'Ielts',
            'toeic' => 'Toeic',
            'kuexam' => 'Kuexam',
            'other_exam_name' => 'Other Exam Name',
            'other_exam_score' => 'Other Exam Score',
            'current_project1' => 'Current Project1',
            'current_project2' => 'Current Project2',
            'current_project3' => 'Current Project3',
            'current_project4' => 'Current Project4',
            'current_project5' => 'Current Project5',
            'check_edit_profile' => 'Check Edit Profile',
            'pf_ck_institute_other' => 'Pf Ck Institute Other',
            'pf_ck_faculty_other' => 'Pf Ck Faculty Other',
            'pf_ck_disciplines_other' => 'Pf Ck Disciplines Other',
            'image_path' => 'Image Path',
            'copy_id_card_path' => 'Copy Id Card Path',
            'copy_house_registration_path' => 'Copy House Registration Path',
            'cv_path' => 'Cv Path',
            'english_doc_path' => 'English Doc Path',
            'attachment_other_path' => 'Attachment Other Path',
            'odoo_id' => 'Odoo',
            'first_created' => 'First Created',
            'last_updated' => 'Last Updated',
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search() {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('type', $this->type, true);
        $criteria->compare('nationality_id', $this->nationality_id);
        $criteria->compare('id_card', $this->id_card, true);
        $criteria->compare('password', $this->password, true);
        $criteria->compare('prefix_id', $this->prefix_id);
        $criteria->compare('fname', $this->fname, true);
        $criteria->compare('lname', $this->lname, true);
        $criteria->compare('en_name', $this->en_name, true);
        $criteria->compare('en_lastname', $this->en_lastname, true);
        $criteria->compare('nickname', $this->nickname, true);
        $criteria->compare('birthdate', $this->birthdate, true);
        $criteria->compare('gender', $this->gender, true);
        $criteria->compare('mobile', $this->mobile, true);
        $criteria->compare('email', $this->email, true);
        $criteria->compare('phone', $this->phone, true);
        $criteria->compare('fax', $this->fax, true);
        $criteria->compare('status', $this->status, true);
        $criteria->compare('religion_id', $this->religion_id);
        $criteria->compare('hobby', $this->hobby, true);
        $criteria->compare('food_allergies', $this->food_allergies, true);
        $criteria->compare('number_of_siblings', $this->number_of_siblings);
        $criteria->compare('expert', $this->expert, true);
        $criteria->compare('position', $this->position, true);
        $criteria->compare('academic_position', $this->academic_position, true);
        $criteria->compare('management_position', $this->management_position, true);
        $criteria->compare('organization_name', $this->organization_name, true);
        $criteria->compare('org_id', $this->org_id);
        $criteria->compare('department_id', $this->department_id);
        $criteria->compare('major_id', $this->major_id);
        $criteria->compare('major_other', $this->major_other, true);
        $criteria->compare('faculty_id', $this->faculty_id);
        $criteria->compare('faculty_other', $this->faculty_other, true);
        $criteria->compare('institute_id', $this->institute_id);
        $criteria->compare('institute_other', $this->institute_other, true);
        $criteria->compare('current_project', $this->current_project, true);
        $criteria->compare('father_prefix_id', $this->father_prefix_id);
        $criteria->compare('father_name', $this->father_name, true);
        $criteria->compare('father_lastname', $this->father_lastname, true);
        $criteria->compare('father_email', $this->father_email, true);
        $criteria->compare('father_phone', $this->father_phone, true);
        $criteria->compare('mother_prefix_id', $this->mother_prefix_id);
        $criteria->compare('mother_name', $this->mother_name, true);
        $criteria->compare('mother_lastname', $this->mother_lastname, true);
        $criteria->compare('mother_phone', $this->mother_phone, true);
        $criteria->compare('mother_email', $this->mother_email, true);
        $criteria->compare('parent_prefix_id', $this->parent_prefix_id);
        $criteria->compare('parent_name', $this->parent_name, true);
        $criteria->compare('parent_lastname', $this->parent_lastname, true);
        $criteria->compare('parent_phone', $this->parent_phone, true);
        $criteria->compare('parent_email', $this->parent_email, true);
        $criteria->compare('parent_relationship', $this->parent_relationship, true);
        $criteria->compare('parent_other', $this->parent_other, true);
        $criteria->compare('toefl_paper_base', $this->toefl_paper_base);
        $criteria->compare('toefl_internet_base', $this->toefl_internet_base);
        $criteria->compare('cutep', $this->cutep);
        $criteria->compare('tuget', $this->tuget);
        $criteria->compare('ielts', $this->ielts);
        $criteria->compare('toeic', $this->toeic);
        $criteria->compare('kuexam', $this->kuexam);
        $criteria->compare('other_exam_name', $this->other_exam_name, true);
        $criteria->compare('other_exam_score', $this->other_exam_score);
        $criteria->compare('current_project1', $this->current_project1, true);
        $criteria->compare('current_project2', $this->current_project2, true);
        $criteria->compare('current_project3', $this->current_project3, true);
        $criteria->compare('current_project4', $this->current_project4, true);
        $criteria->compare('current_project5', $this->current_project5, true);
        $criteria->compare('check_edit_profile', $this->check_edit_profile);
        $criteria->compare('pf_ck_institute_other', $this->pf_ck_institute_other, true);
        $criteria->compare('pf_ck_faculty_other', $this->pf_ck_faculty_other, true);
        $criteria->compare('pf_ck_disciplines_other', $this->pf_ck_disciplines_other, true);
        $criteria->compare('image_path', $this->image_path, true);
        $criteria->compare('copy_id_card_path', $this->copy_id_card_path, true);
        $criteria->compare('copy_house_registration_path', $this->copy_house_registration_path, true);
        $criteria->compare('cv_path', $this->cv_path, true);
        $criteria->compare('english_doc_path', $this->english_doc_path, true);
        $criteria->compare('attachment_other_path', $this->attachment_other_path, true);
        $criteria->compare('odoo_id', $this->odoo_id);
        $criteria->compare('first_created', $this->first_created, true);
        $criteria->compare('last_updated', $this->last_updated, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    public function getID() {
        $person = person::model()->find(array(
            'select' => 'max(id) as id',
        ));
        return (isset($person->id)) ? $person->id + 1 : 1;
    }

}
