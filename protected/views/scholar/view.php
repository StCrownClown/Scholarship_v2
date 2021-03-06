<?php
$this->breadcrumbs=array(
	'Scholars'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List Scholar', 'url'=>array('index')),
	array('label'=>'Create Scholar', 'url'=>array('create')),
	array('label'=>'Update Scholar', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Scholar', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Scholar', 'url'=>array('admin')),
);
?>

<h1>View Scholar #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'person_id',
		'education_id',
		'scholar_doc_path',
		'scholar_doc_path2',
		'scholar_doc_path3',
		'scholar_doc_path4',
		'scholar_doc_path5',
		'scholar_doc_path6',
		'scholar_doc_path7',
		'scholar_doc_path8',
		'thesis_name',
		'registered_result',
		'registered_year',
		'participation_decision',
		'participation_decision_other',
		'information_from',
		'department_of_app',
		'department_of_app_host',
		'interesting_research',
		'information_from_nstda_website',
		'information_from_nstda_staff',
		'information_from_university',
		'information_from_newspaper',
		'information_from_friend',
		'information_from_others',
		'education_register_level',
		'student_project_name_th',
		'student_project_name_en',
		'master_project_name',
		'register_date',
		'scholar_status',
		'scholar_type',
		'sequence_number',
		'thesis_name_eng',
		'professor_name',
		'professor_email',
		'industrial_name',
		'industrial_email',
		'mentor_id',
		'taist_research_topic_th',
		'taist_research_topic_en',
		'jstp_graduationlevel',
		'jstp_avg_gpa',
		'jstp_institute_1',
		'jstp_institute_1_district',
		'jstp_institute_1_province',
		'jstp_institute_2',
		'jstp_institute_2_district',
		'jstp_institute_2_province',
		'jstp_math_elementary_4_first',
		'jstp_math_elementary_4_second',
		'jstp_math_elementary_5_first',
		'jstp_math_elementary_5_second',
		'jstp_math_elementary_6_first',
		'jstp_math_elementary_6_second',
		'jstp_math_highschool_1_first',
		'jstp_math_highschool_1_second',
		'jstp_math_highschool_2_first',
		'jstp_math_highschool_2_second',
		'jstp_math_highschool_3_first',
		'jstp_math_highschool_3_second',
		'jstp_math_highschool_4_first',
		'jstp_math_highschool_4_second',
		'jstp_math_highschool_5_first',
		'jstp_math_highschool_5_second',
		'jstp_math_highschool_6_first',
		'jstp_math_highschool_6_second',
		'jstp_eng_elementary_4_first',
		'jstp_eng_elementary_4_second',
		'jstp_eng_elementary_5_first',
		'jstp_eng_elementary_5_second',
		'jstp_eng_elementary_6_first',
		'jstp_eng_elementary_6_second',
		'jstp_eng_highschool_1_first',
		'jstp_eng_highschool_1_second',
		'jstp_eng_highschool_2_first',
		'jstp_eng_highschool_2_second',
		'jstp_eng_highschool_3_first',
		'jstp_eng_highschool_3_second',
		'jstp_eng_highschool_4_first',
		'jstp_eng_highschool_4_second',
		'jstp_eng_highschool_5_first',
		'jstp_eng_highschool_5_second',
		'jstp_eng_highschool_6_first',
		'jstp_eng_highschool_6_second',
		'jstp_sci_elementary_4_first',
		'jstp_sci_elementary_4_second',
		'jstp_sci_elementary_5_first',
		'jstp_sci_elementary_5_second',
		'jstp_sci_elementary_6_first',
		'jstp_sci_elementary_6_second',
		'jstp_sci_highschool_1_first',
		'jstp_sci_highschool_1_second',
		'jstp_sci_highschool_2_first',
		'jstp_sci_highschool_2_second',
		'jstp_sci_highschool_3_first',
		'jstp_sci_highschool_3_second',
		'jstp_che_highschool_4_first',
		'jstp_che_highschool_4_second',
		'jstp_che_highschool_5_first',
		'jstp_che_highschool_5_second',
		'jstp_che_highschool_6_first',
		'jstp_che_highschool_6_second',
		'jstp_phy_highschool_4_first',
		'jstp_phy_highschool_4_second',
		'jstp_phy_highschool_5_first',
		'jstp_phy_highschool_5_second',
		'jstp_phy_highschool_6_first',
		'jstp_phy_highschool_6_second',
		'jstp_bio_highschool_4_first',
		'jstp_bio_highschool_4_second',
		'jstp_bio_highschool_5_first',
		'jstp_bio_highschool_5_second',
		'jstp_bio_highschool_6_first',
		'jstp_bio_highschool_6_second',
		'jstp_gpa_elementary_4_first',
		'jstp_gpa_elementary_4_second',
		'jstp_gpa_elementary_5_first',
		'jstp_gpa_elementary_5_second',
		'jstp_gpa_elementary_6_first',
		'jstp_gpa_elementary_6_second',
		'jstp_gpa_highschool_1_first',
		'jstp_gpa_highschool_1_second',
		'jstp_gpa_highschool_2_first',
		'jstp_gpa_highschool_2_second',
		'jstp_gpa_highschool_3_first',
		'jstp_gpa_highschool_3_second',
		'jstp_gpa_highschool_4_first',
		'jstp_gpa_highschool_4_second',
		'jstp_gpa_highschool_5_first',
		'jstp_gpa_highschool_5_second',
		'jstp_gpa_highschool_6_first',
		'jstp_gpa_highschool_6_second',
		'jstp_tutor_class',
		'jstp_tutor_class_reason',
		'jstp_tutor_class_hour_per_week',
		'jstp_activity_other',
		'jstp_read_book',
		'jstp_opinion_conflict',
		'jstp_tell_self_and_learn',
		'jstp_talent',
		'jstp_future_career',
		'jstp_future_career_reason',
		'jstp_invention',
		'jstp_project_name_1',
		'jstp_project_colleague_1',
		'jstp_project_responsible_1',
		'jstp_project_present_1',
		'jstp_project_year_1',
		'jstp_project_organize_by_1',
		'jstp_project_result_1',
		'jstp_project_name_2',
		'jstp_project_colleague_2',
		'jstp_project_responsible_2',
		'jstp_project_present_2',
		'jstp_project_year_2',
		'jstp_project_organize_by_2',
		'jstp_project_result_2',
		'jstp_project_name_3',
		'jstp_project_colleague_3',
		'jstp_project_responsible_3',
		'jstp_project_present_3',
		'jstp_project_year_3',
		'jstp_project_organize_by_3',
		'jstp_project_result_3',
		'jstp_project_name_4',
		'jstp_project_colleague_4',
		'jstp_project_responsible_4',
		'jstp_project_present_4',
		'jstp_project_year_4',
		'jstp_project_organize_by_4',
		'jstp_project_result_4',
		'jstp_project_like',
		'jstp_activity_sci_join_1',
		'jstp_activity_sci_year_1',
		'jstp_activity_sci_organize_by_1',
		'jstp_activity_sci_receipts_1',
		'jstp_activity_sci_join_2',
		'jstp_activity_sci_year_2',
		'jstp_activity_sci_organize_by_2',
		'jstp_activity_sci_receipts_2',
		'jstp_activity_sci_join_3',
		'jstp_activity_sci_year_3',
		'jstp_activity_sci_organize_by_3',
		'jstp_activity_sci_receipts_3',
		'jstp_project_sci_interested_name',
		'jstp_project_sci_interested_description',
		'jstp_habit',
		'jstp_activity_overtime',
		'jstp_subjects_like_1',
		'jstp_subjects_like_reason_1',
		'jstp_subjects_like_2',
		'jstp_subjects_like_reason_2',
		'jstp_subjects_like_3',
		'jstp_subjects_like_reason_3',
		'jstp_branch_project',
		'jstp_branch_engineering',
		'jstp_project_sci_name',
		'jstp_project_sci_description',
		'jstp_sci_reward_1',
		'jstp_sci_reward_organize_by_1',
		'jstp_sci_reward_year_1',
		'jstp_sci_reward_description_1',
		'jstp_sci_reward_2',
		'jstp_sci_reward_organize_by_2',
		'jstp_sci_reward_year_2',
		'jstp_sci_reward_description_2',
		'jstp_sci_reward_3',
		'jstp_sci_reward_organize_by_3',
		'jstp_sci_reward_year_3',
		'jstp_sci_reward_description_3',
		'jstp_sci_fund_1',
		'jstp_sci_fund_organize_by_1',
		'jstp_sci_fund_year_1',
		'jstp_sci_fund_description_1',
		'jstp_sci_fund_2',
		'jstp_sci_fund_organize_by_2',
		'jstp_sci_fund_year_2',
		'jstp_sci_fund_description_2',
		'jstp_sci_fund_3',
		'jstp_sci_fund_organize_by_3',
		'jstp_sci_fund_year_3',
		'jstp_sci_fund_description_3',
		'jstp_sci_project_other',
		'jstp_father_fullname',
		'jstp_father_job',
		'jstp_mother_fullname',
		'jstp_mother_job',
		'jstp_brethren',
		'jstp_rank_brethren',
		'jstp_parent_fullname',
		'jstp_parent_relationship',
		'jstp_parent_phone',
	),
)); ?>
