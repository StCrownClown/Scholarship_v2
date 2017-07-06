// use
$this->widget('application.extensions.superfish.superfish',array(
				'maxWidth'=>'40',
				'actionMenu'=>$this->actionMenu,
				'items'=>array(	
							array("name"=>"Submission","id"=>"submission","link"=>array(
										'1'=>array("name"=>"ขั้น Auditor","link"=>array(
												'1'=>array("name"=>"ต้นแบบที่นักวิจัยส่งตรวจครั้งแรก (ขั้น Auditor) SLA ไม่เกิน 30 วัน","link"=>"submission/new"),
												'2'=>array("name"=>"ต้นแบบที่แก้ไขแล้วและนักวิจัยส่งกลับเข้ามา (ขั้น Auditor) SLA ไม่เกิน 15 วัน","link"=>"submission/reply"),
											)),
										'2'=>array("name"=>"ขั้น Reviewers","link"=>array(
												'1'=>array("name"=>"ต้นแบบที่ได้รับผลตรวจจาก Reviewers ครบทุกท่านและรอ Auditor ดำเนินการต่อรอบที่ 1","link"=>"status/tec","optionLink"=>array("id"=>1)),
												'3'=>array("name"=>"ต้นแบบที่แก้ไขจากนักวิจัยเสร็จแล้ว และส่งกลับมาตรวจใหม่","link"=>"status/waiting"),
												'2'=>array("name"=>"ต้นแบบที่ได้รับผลตรวจจาก Reviewers ครบทุกท่านและรอ Auditor ดำเนินการต่อรอบที่ 2","link"=>"status/tec","optionLink"=>array("id"=>2)),
											)),
										'3'=>array("name"=>"ต้นแบบที่ยังสรุปผลตรวจไม่ได้ (ผลคะแนนของ Reviewers บางท่านมีความขัดแย้งกัน)","link"=>"status/result"),
										'4'=>array("name"=>"ขั้นกรรมการวิชาการ","link"=>array(
												'1'=>array("name"=>"ต้นแบบรอ Approve เพื่อส่งกลับให้นักวิจัยแก้ไข","link"=>"pcOrg/list","optionLink"=>array("id"=>1)),
												'2'=>array("name"=>"ต้นแบบรอ Approve เพื่อปิดงาน (ผ่าน / ไม่ผ่าน)","link"=>"pcOrg/list","optionLink"=>array("id"=>2)),
											)),
									),
								),
							array("name"=>"Status","id"=>"status","link"=>"status"),
							array("name"=>"Assignment","id"=>"assignment/list","link"=>"assignment"),
							array("name"=>"Logout","id"=>"submission","link"=>"site/logout"),
						),
			));

// Add protected/components/Controller.php
public $actionMenu;




http://users.tpg.com.au/j_birch/plugins/superfish/