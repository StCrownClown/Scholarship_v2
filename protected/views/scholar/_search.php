<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="row">
		<?php echo $form->label($model,'id'); ?>
		<?php echo $form->textField($model,'id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'person_id'); ?>
		<?php echo $form->textField($model,'person_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'education_id'); ?>
		<?php echo $form->textField($model,'education_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'scholar_doc_path'); ?>
		<?php echo $form->textField($model,'scholar_doc_path',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'scholar_doc_path2'); ?>
		<?php echo $form->textField($model,'scholar_doc_path2',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'scholar_doc_path3'); ?>
		<?php echo $form->textField($model,'scholar_doc_path3',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'scholar_doc_path4'); ?>
		<?php echo $form->textField($model,'scholar_doc_path4',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'scholar_doc_path5'); ?>
		<?php echo $form->textField($model,'scholar_doc_path5',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'scholar_doc_path6'); ?>
		<?php echo $form->textField($model,'scholar_doc_path6',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'scholar_doc_path7'); ?>
		<?php echo $form->textField($model,'scholar_doc_path7',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'scholar_doc_path8'); ?>
		<?php echo $form->textField($model,'scholar_doc_path8',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'thesis_name'); ?>
		<?php echo $form->textField($model,'thesis_name',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'registered_result'); ?>
		<?php echo $form->textField($model,'registered_result',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'registered_year'); ?>
		<?php echo $form->textField($model,'registered_year',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'participation_decision'); ?>
		<?php echo $form->textField($model,'participation_decision',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'participation_decision_other'); ?>
		<?php echo $form->textField($model,'participation_decision_other',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'information_from'); ?>
		<?php echo $form->textField($model,'information_from',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'department_of_app'); ?>
		<?php echo $form->textField($model,'department_of_app',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'department_of_app_host'); ?>
		<?php echo $form->textField($model,'department_of_app_host',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'interesting_research'); ?>
		<?php echo $form->textArea($model,'interesting_research',array('rows'=>6, 'cols'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'information_from_nstda_website'); ?>
		<?php echo $form->textField($model,'information_from_nstda_website',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'information_from_nstda_staff'); ?>
		<?php echo $form->textField($model,'information_from_nstda_staff',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'information_from_university'); ?>
		<?php echo $form->textField($model,'information_from_university',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'information_from_newspaper'); ?>
		<?php echo $form->textField($model,'information_from_newspaper',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'information_from_friend'); ?>
		<?php echo $form->textField($model,'information_from_friend',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'information_from_others'); ?>
		<?php echo $form->textField($model,'information_from_others',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'education_register_level'); ?>
		<?php echo $form->textField($model,'education_register_level',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'student_project_name_th'); ?>
		<?php echo $form->textField($model,'student_project_name_th',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'student_project_name_en'); ?>
		<?php echo $form->textField($model,'student_project_name_en',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'master_project_name'); ?>
		<?php echo $form->textField($model,'master_project_name',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'register_date'); ?>
		<?php echo $form->textField($model,'register_date'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'scholar_status'); ?>
		<?php echo $form->textField($model,'scholar_status',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'scholar_type'); ?>
		<?php echo $form->textField($model,'scholar_type',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'sequence_number'); ?>
		<?php echo $form->textField($model,'sequence_number'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'thesis_name_eng'); ?>
		<?php echo $form->textField($model,'thesis_name_eng',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'professor_name'); ?>
		<?php echo $form->textField($model,'professor_name',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'professor_email'); ?>
		<?php echo $form->textField($model,'professor_email',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'industrial_name'); ?>
		<?php echo $form->textField($model,'industrial_name',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'industrial_email'); ?>
		<?php echo $form->textField($model,'industrial_email',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'mentor_id'); ?>
		<?php echo $form->textField($model,'mentor_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'taist_research_topic_th'); ?>
		<?php echo $form->textField($model,'taist_research_topic_th',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'taist_research_topic_en'); ?>
		<?php echo $form->textField($model,'taist_research_topic_en',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'jstp_graduationlevel'); ?>
		<?php echo $form->textField($model,'jstp_graduationlevel',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'jstp_avg_gpa'); ?>
		<?php echo $form->textField($model,'jstp_avg_gpa'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'jstp_institute_1'); ?>
		<?php echo $form->textField($model,'jstp_institute_1',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'jstp_institute_1_district'); ?>
		<?php echo $form->textField($model,'jstp_institute_1_district',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'jstp_institute_1_province'); ?>
		<?php echo $form->textField($model,'jstp_institute_1_province',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'jstp_institute_2'); ?>
		<?php echo $form->textField($model,'jstp_institute_2',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'jstp_institute_2_district'); ?>
		<?php echo $form->textField($model,'jstp_institute_2_district',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'jstp_institute_2_province'); ?>
		<?php echo $form->textField($model,'jstp_institute_2_province',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'jstp_math_elementary_4_first'); ?>
		<?php echo $form->textField($model,'jstp_math_elementary_4_first'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'jstp_math_elementary_4_second'); ?>
		<?php echo $form->textField($model,'jstp_math_elementary_4_second'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'jstp_math_elementary_5_first'); ?>
		<?php echo $form->textField($model,'jstp_math_elementary_5_first'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'jstp_math_elementary_5_second'); ?>
		<?php echo $form->textField($model,'jstp_math_elementary_5_second'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'jstp_math_elementary_6_first'); ?>
		<?php echo $form->textField($model,'jstp_math_elementary_6_first'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'jstp_math_elementary_6_second'); ?>
		<?php echo $form->textField($model,'jstp_math_elementary_6_second'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'jstp_math_highschool_1_first'); ?>
		<?php echo $form->textField($model,'jstp_math_highschool_1_first'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'jstp_math_highschool_1_second'); ?>
		<?php echo $form->textField($model,'jstp_math_highschool_1_second'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'jstp_math_highschool_2_first'); ?>
		<?php echo $form->textField($model,'jstp_math_highschool_2_first'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'jstp_math_highschool_2_second'); ?>
		<?php echo $form->textField($model,'jstp_math_highschool_2_second'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'jstp_math_highschool_3_first'); ?>
		<?php echo $form->textField($model,'jstp_math_highschool_3_first'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'jstp_math_highschool_3_second'); ?>
		<?php echo $form->textField($model,'jstp_math_highschool_3_second'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'jstp_math_highschool_4_first'); ?>
		<?php echo $form->textField($model,'jstp_math_highschool_4_first'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'jstp_math_highschool_4_second'); ?>
		<?php echo $form->textField($model,'jstp_math_highschool_4_second'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'jstp_math_highschool_5_first'); ?>
		<?php echo $form->textField($model,'jstp_math_highschool_5_first'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'jstp_math_highschool_5_second'); ?>
		<?php echo $form->textField($model,'jstp_math_highschool_5_second'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'jstp_math_highschool_6_first'); ?>
		<?php echo $form->textField($model,'jstp_math_highschool_6_first'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'jstp_math_highschool_6_second'); ?>
		<?php echo $form->textField($model,'jstp_math_highschool_6_second'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'jstp_eng_elementary_4_first'); ?>
		<?php echo $form->textField($model,'jstp_eng_elementary_4_first'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'jstp_eng_elementary_4_second'); ?>
		<?php echo $form->textField($model,'jstp_eng_elementary_4_second'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'jstp_eng_elementary_5_first'); ?>
		<?php echo $form->textField($model,'jstp_eng_elementary_5_first'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'jstp_eng_elementary_5_second'); ?>
		<?php echo $form->textField($model,'jstp_eng_elementary_5_second'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'jstp_eng_elementary_6_first'); ?>
		<?php echo $form->textField($model,'jstp_eng_elementary_6_first'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'jstp_eng_elementary_6_second'); ?>
		<?php echo $form->textField($model,'jstp_eng_elementary_6_second'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'jstp_eng_highschool_1_first'); ?>
		<?php echo $form->textField($model,'jstp_eng_highschool_1_first'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'jstp_eng_highschool_1_second'); ?>
		<?php echo $form->textField($model,'jstp_eng_highschool_1_second'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'jstp_eng_highschool_2_first'); ?>
		<?php echo $form->textField($model,'jstp_eng_highschool_2_first'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'jstp_eng_highschool_2_second'); ?>
		<?php echo $form->textField($model,'jstp_eng_highschool_2_second'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'jstp_eng_highschool_3_first'); ?>
		<?php echo $form->textField($model,'jstp_eng_highschool_3_first'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'jstp_eng_highschool_3_second'); ?>
		<?php echo $form->textField($model,'jstp_eng_highschool_3_second'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'jstp_eng_highschool_4_first'); ?>
		<?php echo $form->textField($model,'jstp_eng_highschool_4_first'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'jstp_eng_highschool_4_second'); ?>
		<?php echo $form->textField($model,'jstp_eng_highschool_4_second'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'jstp_eng_highschool_5_first'); ?>
		<?php echo $form->textField($model,'jstp_eng_highschool_5_first'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'jstp_eng_highschool_5_second'); ?>
		<?php echo $form->textField($model,'jstp_eng_highschool_5_second'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'jstp_eng_highschool_6_first'); ?>
		<?php echo $form->textField($model,'jstp_eng_highschool_6_first'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'jstp_eng_highschool_6_second'); ?>
		<?php echo $form->textField($model,'jstp_eng_highschool_6_second'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'jstp_sci_elementary_4_first'); ?>
		<?php echo $form->textField($model,'jstp_sci_elementary_4_first'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'jstp_sci_elementary_4_second'); ?>
		<?php echo $form->textField($model,'jstp_sci_elementary_4_second'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'jstp_sci_elementary_5_first'); ?>
		<?php echo $form->textField($model,'jstp_sci_elementary_5_first'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'jstp_sci_elementary_5_second'); ?>
		<?php echo $form->textField($model,'jstp_sci_elementary_5_second'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'jstp_sci_elementary_6_first'); ?>
		<?php echo $form->textField($model,'jstp_sci_elementary_6_first'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'jstp_sci_elementary_6_second'); ?>
		<?php echo $form->textField($model,'jstp_sci_elementary_6_second'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'jstp_sci_highschool_1_first'); ?>
		<?php echo $form->textField($model,'jstp_sci_highschool_1_first'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'jstp_sci_highschool_1_second'); ?>
		<?php echo $form->textField($model,'jstp_sci_highschool_1_second'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'jstp_sci_highschool_2_first'); ?>
		<?php echo $form->textField($model,'jstp_sci_highschool_2_first'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'jstp_sci_highschool_2_second'); ?>
		<?php echo $form->textField($model,'jstp_sci_highschool_2_second'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'jstp_sci_highschool_3_first'); ?>
		<?php echo $form->textField($model,'jstp_sci_highschool_3_first'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'jstp_sci_highschool_3_second'); ?>
		<?php echo $form->textField($model,'jstp_sci_highschool_3_second'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'jstp_che_highschool_4_first'); ?>
		<?php echo $form->textField($model,'jstp_che_highschool_4_first'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'jstp_che_highschool_4_second'); ?>
		<?php echo $form->textField($model,'jstp_che_highschool_4_second'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'jstp_che_highschool_5_first'); ?>
		<?php echo $form->textField($model,'jstp_che_highschool_5_first'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'jstp_che_highschool_5_second'); ?>
		<?php echo $form->textField($model,'jstp_che_highschool_5_second'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'jstp_che_highschool_6_first'); ?>
		<?php echo $form->textField($model,'jstp_che_highschool_6_first'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'jstp_che_highschool_6_second'); ?>
		<?php echo $form->textField($model,'jstp_che_highschool_6_second'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'jstp_phy_highschool_4_first'); ?>
		<?php echo $form->textField($model,'jstp_phy_highschool_4_first'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'jstp_phy_highschool_4_second'); ?>
		<?php echo $form->textField($model,'jstp_phy_highschool_4_second'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'jstp_phy_highschool_5_first'); ?>
		<?php echo $form->textField($model,'jstp_phy_highschool_5_first'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'jstp_phy_highschool_5_second'); ?>
		<?php echo $form->textField($model,'jstp_phy_highschool_5_second'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'jstp_phy_highschool_6_first'); ?>
		<?php echo $form->textField($model,'jstp_phy_highschool_6_first'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'jstp_phy_highschool_6_second'); ?>
		<?php echo $form->textField($model,'jstp_phy_highschool_6_second'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'jstp_bio_highschool_4_first'); ?>
		<?php echo $form->textField($model,'jstp_bio_highschool_4_first'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'jstp_bio_highschool_4_second'); ?>
		<?php echo $form->textField($model,'jstp_bio_highschool_4_second'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'jstp_bio_highschool_5_first'); ?>
		<?php echo $form->textField($model,'jstp_bio_highschool_5_first'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'jstp_bio_highschool_5_second'); ?>
		<?php echo $form->textField($model,'jstp_bio_highschool_5_second'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'jstp_bio_highschool_6_first'); ?>
		<?php echo $form->textField($model,'jstp_bio_highschool_6_first'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'jstp_bio_highschool_6_second'); ?>
		<?php echo $form->textField($model,'jstp_bio_highschool_6_second'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'jstp_gpa_elementary_4_first'); ?>
		<?php echo $form->textField($model,'jstp_gpa_elementary_4_first'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'jstp_gpa_elementary_4_second'); ?>
		<?php echo $form->textField($model,'jstp_gpa_elementary_4_second'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'jstp_gpa_elementary_5_first'); ?>
		<?php echo $form->textField($model,'jstp_gpa_elementary_5_first'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'jstp_gpa_elementary_5_second'); ?>
		<?php echo $form->textField($model,'jstp_gpa_elementary_5_second'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'jstp_gpa_elementary_6_first'); ?>
		<?php echo $form->textField($model,'jstp_gpa_elementary_6_first'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'jstp_gpa_elementary_6_second'); ?>
		<?php echo $form->textField($model,'jstp_gpa_elementary_6_second'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'jstp_gpa_highschool_1_first'); ?>
		<?php echo $form->textField($model,'jstp_gpa_highschool_1_first'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'jstp_gpa_highschool_1_second'); ?>
		<?php echo $form->textField($model,'jstp_gpa_highschool_1_second'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'jstp_gpa_highschool_2_first'); ?>
		<?php echo $form->textField($model,'jstp_gpa_highschool_2_first'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'jstp_gpa_highschool_2_second'); ?>
		<?php echo $form->textField($model,'jstp_gpa_highschool_2_second'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'jstp_gpa_highschool_3_first'); ?>
		<?php echo $form->textField($model,'jstp_gpa_highschool_3_first'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'jstp_gpa_highschool_3_second'); ?>
		<?php echo $form->textField($model,'jstp_gpa_highschool_3_second'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'jstp_gpa_highschool_4_first'); ?>
		<?php echo $form->textField($model,'jstp_gpa_highschool_4_first'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'jstp_gpa_highschool_4_second'); ?>
		<?php echo $form->textField($model,'jstp_gpa_highschool_4_second'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'jstp_gpa_highschool_5_first'); ?>
		<?php echo $form->textField($model,'jstp_gpa_highschool_5_first'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'jstp_gpa_highschool_5_second'); ?>
		<?php echo $form->textField($model,'jstp_gpa_highschool_5_second'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'jstp_gpa_highschool_6_first'); ?>
		<?php echo $form->textField($model,'jstp_gpa_highschool_6_first'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'jstp_gpa_highschool_6_second'); ?>
		<?php echo $form->textField($model,'jstp_gpa_highschool_6_second'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'jstp_tutor_class'); ?>
		<?php echo $form->textField($model,'jstp_tutor_class',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'jstp_tutor_class_reason'); ?>
		<?php echo $form->textField($model,'jstp_tutor_class_reason',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'jstp_tutor_class_hour_per_week'); ?>
		<?php echo $form->textField($model,'jstp_tutor_class_hour_per_week',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'jstp_activity_other'); ?>
		<?php echo $form->textArea($model,'jstp_activity_other',array('rows'=>6, 'cols'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'jstp_read_book'); ?>
		<?php echo $form->textArea($model,'jstp_read_book',array('rows'=>6, 'cols'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'jstp_opinion_conflict'); ?>
		<?php echo $form->textArea($model,'jstp_opinion_conflict',array('rows'=>6, 'cols'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'jstp_tell_self_and_learn'); ?>
		<?php echo $form->textArea($model,'jstp_tell_self_and_learn',array('rows'=>6, 'cols'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'jstp_talent'); ?>
		<?php echo $form->textArea($model,'jstp_talent',array('rows'=>6, 'cols'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'jstp_future_career'); ?>
		<?php echo $form->textField($model,'jstp_future_career',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'jstp_future_career_reason'); ?>
		<?php echo $form->textArea($model,'jstp_future_career_reason',array('rows'=>6, 'cols'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'jstp_invention'); ?>
		<?php echo $form->textField($model,'jstp_invention',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'jstp_project_name_1'); ?>
		<?php echo $form->textField($model,'jstp_project_name_1',array('size'=>60,'maxlength'=>100)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'jstp_project_colleague_1'); ?>
		<?php echo $form->textField($model,'jstp_project_colleague_1'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'jstp_project_responsible_1'); ?>
		<?php echo $form->textField($model,'jstp_project_responsible_1',array('size'=>60,'maxlength'=>100)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'jstp_project_present_1'); ?>
		<?php echo $form->textField($model,'jstp_project_present_1',array('size'=>60,'maxlength'=>100)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'jstp_project_year_1'); ?>
		<?php echo $form->textField($model,'jstp_project_year_1',array('size'=>4,'maxlength'=>4)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'jstp_project_organize_by_1'); ?>
		<?php echo $form->textField($model,'jstp_project_organize_by_1',array('size'=>60,'maxlength'=>100)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'jstp_project_result_1'); ?>
		<?php echo $form->textField($model,'jstp_project_result_1',array('size'=>60,'maxlength'=>100)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'jstp_project_name_2'); ?>
		<?php echo $form->textField($model,'jstp_project_name_2',array('size'=>60,'maxlength'=>100)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'jstp_project_colleague_2'); ?>
		<?php echo $form->textField($model,'jstp_project_colleague_2'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'jstp_project_responsible_2'); ?>
		<?php echo $form->textField($model,'jstp_project_responsible_2',array('size'=>60,'maxlength'=>100)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'jstp_project_present_2'); ?>
		<?php echo $form->textField($model,'jstp_project_present_2',array('size'=>60,'maxlength'=>100)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'jstp_project_year_2'); ?>
		<?php echo $form->textField($model,'jstp_project_year_2',array('size'=>4,'maxlength'=>4)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'jstp_project_organize_by_2'); ?>
		<?php echo $form->textField($model,'jstp_project_organize_by_2',array('size'=>60,'maxlength'=>100)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'jstp_project_result_2'); ?>
		<?php echo $form->textField($model,'jstp_project_result_2',array('size'=>60,'maxlength'=>100)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'jstp_project_name_3'); ?>
		<?php echo $form->textField($model,'jstp_project_name_3',array('size'=>60,'maxlength'=>100)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'jstp_project_colleague_3'); ?>
		<?php echo $form->textField($model,'jstp_project_colleague_3'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'jstp_project_responsible_3'); ?>
		<?php echo $form->textField($model,'jstp_project_responsible_3',array('size'=>60,'maxlength'=>100)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'jstp_project_present_3'); ?>
		<?php echo $form->textField($model,'jstp_project_present_3',array('size'=>60,'maxlength'=>100)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'jstp_project_year_3'); ?>
		<?php echo $form->textField($model,'jstp_project_year_3',array('size'=>4,'maxlength'=>4)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'jstp_project_organize_by_3'); ?>
		<?php echo $form->textField($model,'jstp_project_organize_by_3',array('size'=>60,'maxlength'=>100)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'jstp_project_result_3'); ?>
		<?php echo $form->textField($model,'jstp_project_result_3',array('size'=>60,'maxlength'=>100)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'jstp_project_name_4'); ?>
		<?php echo $form->textField($model,'jstp_project_name_4',array('size'=>60,'maxlength'=>100)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'jstp_project_colleague_4'); ?>
		<?php echo $form->textField($model,'jstp_project_colleague_4'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'jstp_project_responsible_4'); ?>
		<?php echo $form->textField($model,'jstp_project_responsible_4',array('size'=>60,'maxlength'=>100)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'jstp_project_present_4'); ?>
		<?php echo $form->textField($model,'jstp_project_present_4',array('size'=>60,'maxlength'=>100)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'jstp_project_year_4'); ?>
		<?php echo $form->textField($model,'jstp_project_year_4',array('size'=>4,'maxlength'=>4)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'jstp_project_organize_by_4'); ?>
		<?php echo $form->textField($model,'jstp_project_organize_by_4',array('size'=>60,'maxlength'=>100)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'jstp_project_result_4'); ?>
		<?php echo $form->textField($model,'jstp_project_result_4',array('size'=>60,'maxlength'=>100)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'jstp_project_like'); ?>
		<?php echo $form->textArea($model,'jstp_project_like',array('rows'=>6, 'cols'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'jstp_activity_sci_join_1'); ?>
		<?php echo $form->textField($model,'jstp_activity_sci_join_1',array('size'=>60,'maxlength'=>100)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'jstp_activity_sci_year_1'); ?>
		<?php echo $form->textField($model,'jstp_activity_sci_year_1',array('size'=>4,'maxlength'=>4)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'jstp_activity_sci_organize_by_1'); ?>
		<?php echo $form->textField($model,'jstp_activity_sci_organize_by_1',array('size'=>60,'maxlength'=>100)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'jstp_activity_sci_receipts_1'); ?>
		<?php echo $form->textField($model,'jstp_activity_sci_receipts_1',array('size'=>60,'maxlength'=>100)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'jstp_activity_sci_join_2'); ?>
		<?php echo $form->textField($model,'jstp_activity_sci_join_2',array('size'=>60,'maxlength'=>100)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'jstp_activity_sci_year_2'); ?>
		<?php echo $form->textField($model,'jstp_activity_sci_year_2',array('size'=>4,'maxlength'=>4)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'jstp_activity_sci_organize_by_2'); ?>
		<?php echo $form->textField($model,'jstp_activity_sci_organize_by_2',array('size'=>60,'maxlength'=>100)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'jstp_activity_sci_receipts_2'); ?>
		<?php echo $form->textField($model,'jstp_activity_sci_receipts_2',array('size'=>60,'maxlength'=>100)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'jstp_activity_sci_join_3'); ?>
		<?php echo $form->textField($model,'jstp_activity_sci_join_3',array('size'=>60,'maxlength'=>100)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'jstp_activity_sci_year_3'); ?>
		<?php echo $form->textField($model,'jstp_activity_sci_year_3',array('size'=>4,'maxlength'=>4)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'jstp_activity_sci_organize_by_3'); ?>
		<?php echo $form->textField($model,'jstp_activity_sci_organize_by_3',array('size'=>60,'maxlength'=>100)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'jstp_activity_sci_receipts_3'); ?>
		<?php echo $form->textField($model,'jstp_activity_sci_receipts_3',array('size'=>60,'maxlength'=>100)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'jstp_project_sci_interested_name'); ?>
		<?php echo $form->textField($model,'jstp_project_sci_interested_name',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'jstp_project_sci_interested_description'); ?>
		<?php echo $form->textArea($model,'jstp_project_sci_interested_description',array('rows'=>6, 'cols'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'jstp_habit'); ?>
		<?php echo $form->textArea($model,'jstp_habit',array('rows'=>6, 'cols'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'jstp_activity_overtime'); ?>
		<?php echo $form->textArea($model,'jstp_activity_overtime',array('rows'=>6, 'cols'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'jstp_subjects_like_1'); ?>
		<?php echo $form->textField($model,'jstp_subjects_like_1',array('size'=>50,'maxlength'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'jstp_subjects_like_reason_1'); ?>
		<?php echo $form->textField($model,'jstp_subjects_like_reason_1',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'jstp_subjects_like_2'); ?>
		<?php echo $form->textField($model,'jstp_subjects_like_2',array('size'=>50,'maxlength'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'jstp_subjects_like_reason_2'); ?>
		<?php echo $form->textField($model,'jstp_subjects_like_reason_2',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'jstp_subjects_like_3'); ?>
		<?php echo $form->textField($model,'jstp_subjects_like_3',array('size'=>50,'maxlength'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'jstp_subjects_like_reason_3'); ?>
		<?php echo $form->textField($model,'jstp_subjects_like_reason_3',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'jstp_branch_project'); ?>
		<?php echo $form->textField($model,'jstp_branch_project',array('size'=>50,'maxlength'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'jstp_branch_engineering'); ?>
		<?php echo $form->textField($model,'jstp_branch_engineering',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'jstp_project_sci_name'); ?>
		<?php echo $form->textField($model,'jstp_project_sci_name',array('size'=>60,'maxlength'=>100)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'jstp_project_sci_description'); ?>
		<?php echo $form->textArea($model,'jstp_project_sci_description',array('rows'=>6, 'cols'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'jstp_sci_reward_1'); ?>
		<?php echo $form->textField($model,'jstp_sci_reward_1',array('size'=>60,'maxlength'=>100)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'jstp_sci_reward_organize_by_1'); ?>
		<?php echo $form->textField($model,'jstp_sci_reward_organize_by_1',array('size'=>60,'maxlength'=>100)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'jstp_sci_reward_year_1'); ?>
		<?php echo $form->textField($model,'jstp_sci_reward_year_1',array('size'=>4,'maxlength'=>4)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'jstp_sci_reward_description_1'); ?>
		<?php echo $form->textField($model,'jstp_sci_reward_description_1',array('size'=>60,'maxlength'=>100)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'jstp_sci_reward_2'); ?>
		<?php echo $form->textField($model,'jstp_sci_reward_2',array('size'=>60,'maxlength'=>100)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'jstp_sci_reward_organize_by_2'); ?>
		<?php echo $form->textField($model,'jstp_sci_reward_organize_by_2',array('size'=>60,'maxlength'=>100)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'jstp_sci_reward_year_2'); ?>
		<?php echo $form->textField($model,'jstp_sci_reward_year_2',array('size'=>4,'maxlength'=>4)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'jstp_sci_reward_description_2'); ?>
		<?php echo $form->textField($model,'jstp_sci_reward_description_2',array('size'=>60,'maxlength'=>100)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'jstp_sci_reward_3'); ?>
		<?php echo $form->textField($model,'jstp_sci_reward_3',array('size'=>60,'maxlength'=>100)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'jstp_sci_reward_organize_by_3'); ?>
		<?php echo $form->textField($model,'jstp_sci_reward_organize_by_3',array('size'=>60,'maxlength'=>100)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'jstp_sci_reward_year_3'); ?>
		<?php echo $form->textField($model,'jstp_sci_reward_year_3',array('size'=>4,'maxlength'=>4)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'jstp_sci_reward_description_3'); ?>
		<?php echo $form->textField($model,'jstp_sci_reward_description_3',array('size'=>60,'maxlength'=>100)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'jstp_sci_fund_1'); ?>
		<?php echo $form->textField($model,'jstp_sci_fund_1',array('size'=>60,'maxlength'=>100)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'jstp_sci_fund_organize_by_1'); ?>
		<?php echo $form->textField($model,'jstp_sci_fund_organize_by_1',array('size'=>60,'maxlength'=>100)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'jstp_sci_fund_year_1'); ?>
		<?php echo $form->textField($model,'jstp_sci_fund_year_1',array('size'=>4,'maxlength'=>4)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'jstp_sci_fund_description_1'); ?>
		<?php echo $form->textField($model,'jstp_sci_fund_description_1',array('size'=>60,'maxlength'=>100)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'jstp_sci_fund_2'); ?>
		<?php echo $form->textField($model,'jstp_sci_fund_2',array('size'=>60,'maxlength'=>100)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'jstp_sci_fund_organize_by_2'); ?>
		<?php echo $form->textField($model,'jstp_sci_fund_organize_by_2',array('size'=>60,'maxlength'=>100)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'jstp_sci_fund_year_2'); ?>
		<?php echo $form->textField($model,'jstp_sci_fund_year_2',array('size'=>4,'maxlength'=>4)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'jstp_sci_fund_description_2'); ?>
		<?php echo $form->textField($model,'jstp_sci_fund_description_2',array('size'=>60,'maxlength'=>100)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'jstp_sci_fund_3'); ?>
		<?php echo $form->textField($model,'jstp_sci_fund_3',array('size'=>60,'maxlength'=>100)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'jstp_sci_fund_organize_by_3'); ?>
		<?php echo $form->textField($model,'jstp_sci_fund_organize_by_3',array('size'=>60,'maxlength'=>100)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'jstp_sci_fund_year_3'); ?>
		<?php echo $form->textField($model,'jstp_sci_fund_year_3',array('size'=>4,'maxlength'=>4)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'jstp_sci_fund_description_3'); ?>
		<?php echo $form->textField($model,'jstp_sci_fund_description_3',array('size'=>60,'maxlength'=>100)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'jstp_sci_project_other'); ?>
		<?php echo $form->textArea($model,'jstp_sci_project_other',array('rows'=>6, 'cols'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'jstp_father_fullname'); ?>
		<?php echo $form->textField($model,'jstp_father_fullname',array('size'=>60,'maxlength'=>100)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'jstp_father_job'); ?>
		<?php echo $form->textField($model,'jstp_father_job',array('size'=>60,'maxlength'=>100)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'jstp_mother_fullname'); ?>
		<?php echo $form->textField($model,'jstp_mother_fullname',array('size'=>60,'maxlength'=>100)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'jstp_mother_job'); ?>
		<?php echo $form->textField($model,'jstp_mother_job',array('size'=>60,'maxlength'=>100)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'jstp_brethren'); ?>
		<?php echo $form->textField($model,'jstp_brethren',array('size'=>60,'maxlength'=>100)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'jstp_rank_brethren'); ?>
		<?php echo $form->textField($model,'jstp_rank_brethren',array('size'=>60,'maxlength'=>100)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'jstp_parent_fullname'); ?>
		<?php echo $form->textField($model,'jstp_parent_fullname',array('size'=>60,'maxlength'=>100)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'jstp_parent_relationship'); ?>
		<?php echo $form->textField($model,'jstp_parent_relationship',array('size'=>60,'maxlength'=>100)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'jstp_parent_phone'); ?>
		<?php echo $form->textField($model,'jstp_parent_phone',array('size'=>60,'maxlength'=>100)); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->