<?php

class ExcelExporter {

    const CRLF = "\r\n";

    /**
     * Outputs active record resultset to an xml based excel file
     *
     * @param $filename - name of output filename
     * @param $data - active record data set
     * @param $title - title displayed on top
     * @param $header - boolean to show/hide header
     * @param $fields - array of fields to export
     */
    public static function sendAsXLS($filename, $data, $title = false, $header = false, $fields = false) {
        $export = self::xls($data, $title, $header, $fields);
        self::sendHeader($filename, strlen($export), 'vnd.ms-excel');
        echo $export;
        Yii::app()->end();
    }

    /**
     * Send file header
     *
     * @param $filename - filename for created file
     * @param $length - size of file
     * @param $type - mime type of exported data
     */
    private static function sendHeader($filename, $length, $type = 'octet-stream') {
        if (strtolower(substr($filename, -4)) != '.xls')
            $filename .= '.xls';

        header("Content-type: application/$type");
        header("Content-Disposition: attachment; filename=$filename");
        header("Content-length: $length");
        header('Pragma: no-cache');
        header('Expires: 0');
    }

    /**
     * Private method to create xls string from active record data set
     *
     * @param $data - active record data set
     * @param $title - title displayed on top
     * @param $header - boolean to show/hide header
     * @param $fields - array of fields to export
     */
    private static function xls($data, $title, $header, $fields) {
        $str = '<html>' . self::CRLF
                . '<head>' . self::CRLF
                . '<meta http-equiv="content-type" content="text/html; charset=utf-8">' . self::CRLF
                . '</head>' . self::CRLF
                . '<body style="text-align:center">' . self::CRLF;

        if ($title) {
            $date = date('m/d/Y H:i:s');
            $date = strtotime($date);
            $date = strtotime("+6 hours", $date);
            $date = date('m/d/Y H:i:s', $date);
            $str .= "<b>$title</b><br /><br />" . self::CRLF
                    . Yii::t('main', 'export_lines') . ': ' . count($data) . '<br />' . self::CRLF
                    . Yii::t('main', 'export_date') . ': ' . Yii::app()->dateFormatter->formatDateTime($date) . '<br /><br />' . self::CRLF;
        }
        if ($data) {
            $str .= '<table style="text-align:left" border="1" cellpadding="0" cellspacing="0">' . self::CRLF;

            if (!$fields)
                $fields = array_keys($data[0]->attributes);

            if ($header) {
                $str .= '<tr>' . self::CRLF;
                foreach ($fields as $key => $field)
                    $str .= '<th>' . $field . '</th>' . self::CRLF;
                $str .= '</tr>' . self::CRLF;
            }

            foreach ($data as $row) {
                $str .= '<tr>' . self::CRLF;
                foreach ($fields as $key => $field)
                    $str .= '<td>' . self::xfnc_fd($row, $key) . '</td>' . self::CRLF;
                $str .= '</tr>' . self::CRLF;
            }

            $str .= '</table>' . self::CRLF;
        }

        $str .= '</body>' . self::CRLF
                . '</html>';

        return $str;
    }

    private static function xfnc_fd($row, $key) {
        $data = '';
        if (array_key_exists($key, $row)) {
            $data = $row[$key];
        }
        switch ($key) {
            case "portfolio_all":
                $data = '';
                if($row['portfolio_journal_international'])
                    $data .= $row['portfolio_journal_international_desc'] . self::CRLF;
                if($row['portfolio_journal_incountry'])
                    $data .= $row['portfolio_journal_incountry_desc'] . self::CRLF;
                if($row['portfolio_patent'])
                    $data .= $row['portfolio_patent_desc'] . self::CRLF;
                if($row['portfolio_conference_international'])
                    $data .= $row['portfolio_conference_international_desc'] . self::CRLF;
                if($row['portfolio_conference_incountry'])
                    $data .= $row['portfolio_conference_incountry_desc'] . self::CRLF;
                if($row['portfolio_award'])
                    $data .= $row['portfolio_award_desc'] . self::CRLF;
                if($row['portfolio_other'])
                    $data .= $row['portfolio_other_desc'] . self::CRLF;
                break;
            case "ind_name2":
                $data = $row['ind_name'];
                break;
            case "status":
                $data = InitialData::STATUS($row[$key]);
                break;
            case "is_work":
                $data = '';
                if ($row['is_work'] == 1)
                    $data = "ทำ";
                else if ($row['is_work'] == 0)
                    $data = "ไม่ทำ";
                break;
            case "is_history":
                $data = '';
                if ($row['is_work'] == 1)
                    $data = "เคย";
                else if ($row['is_work'] == 0)
                    $data = "ไม่เคย";
                break;
            case "history_edu_level":
            case "history_name":
            case "history_source":
            case "history_begin":
            case "history_end":
                $sqlExportHistory = str_replace('##PERSON_ID##', $row['stu_id'] ,Yii::app()->params['sqlExportHistory']);
                $dataHistory = Yii::app()->db->createCommand($sqlExportHistory)->queryAll();
                if(!empty($dataHistory)){
                    $data = $dataHistory[0][$key];
                }
                break;
            case "stu_edu_status":
                $data = '';
                if ($row['stu_edu_status'] == 'complete')
                    $data = "จบการศึกษา";
                else if($row['stu_edu_status'] == 'studying')
                    $data = "กำลังศึกษา";
                break;
            case "stu_age":
                $age = '0 วัน';
                if ($row['stu_birthday'] !== NULL) {
                    $datetime1 = new DateTime($row['stu_birthday']);
                    $datetime2 = new DateTime();
                    if ($datetime1 < $datetime2) {
                        $diff = $datetime1->diff($datetime2);
                        $data = $diff->y . '  ปี  ' .
                                $diff->m . '  เดือน  ' .
                                ($diff->d + 1) . '  วัน  ';
                    }
                }
                break;
            case "history_begin_history_end":
                $data = '';
                $sqlExportHistory = str_replace('##PERSON_ID##', $row['stu_id'] ,Yii::app()->params['sqlExportHistory']);
                $dataHistory = Yii::app()->db->createCommand($sqlExportHistory)->queryAll();
                if(!empty($dataHistory)){
                    $history_diff = ConfigWeb::GetPeriodDate($dataHistory[0]['history_begin'], $dataHistory[0]['history_end']);
                    $data = $history_diff->y . '  ปี  ' .
                            $history_diff->m . '  เดือน  ' .
                            ($history_diff->d + 1) . '  วัน  ';
                }
                break;
            case "prj_stu_range":
                $prj_diff = ConfigWeb::GetPeriodDate($row['prj_stu_begin'], $row['prj_stu_end']);
                $data = ($prj_diff->y > 0)?$prj_diff->y . '  ปี  ':'' .
                        $prj_diff->m . '  เดือน  ' .
                        ($prj_diff->d + 1) . '  วัน  ';
                break;
            case "prj_begin_prj_end":
                $prj_diff = ConfigWeb::GetPeriodDate($row['prj_begin'], $row['prj_end']);
                $data = $prj_diff->y . '  ปี  ' .
                        $prj_diff->m . '  เดือน  ' .
                        ($prj_diff->d + 1) . '  วัน  ';
                break;
            case "prj_stu_begin_prj_end":
                $prj_diff = ConfigWeb::GetPeriodDate($row['prj_stu_begin'], $row['prj_stu_end']);
                $data = $prj_diff->y . '  ปี  ' .
                        $prj_diff->m . '  เดือน  ' .
                        ($prj_diff->d + 1) . '  วัน  ';
                break;
            case "ind_incash_other":
                $data = $row['ind_incash_salary_cost'] + $row['ind_incash_rents_cost'] + $row['ind_incash_traveling_cost'] + $row['ind_incash_other_cost'] + $row['ind_incash_other2_cost'];
                $data = number_format($data, 2, '.', ',');
                break;
            case "ind_inkind_other":
                $data = $row['ind_inkind_equipment_cost'] + $row['ind_inkind_other_cost'] + $row['ind_inkind_other2_cost'];
                $data = number_format($data, 2, '.', ',');
                break;
            case "incash_other":
                $data = $row['incash_fee_cost'] + $row['incash_monthly_cost'] + $row['incash_other_cost'] + $row['incash_other2_cost'];
                $data = number_format($data, 2, '.', ',');
                break;
            case "inkind_other":
                $data = $row['inkind_other_cost'] + $row['inkind_other2_cost'];
                $data = number_format($data, 2, '.', ',');
                break;
            case "prj_budget":
                $data = number_format($data, 2, '.', ',');
                break;
            case "prj_funding":
                if($row['prj_funding'] == 'source')
                    $data = "ได้รับอนุมัติทุนวิจัยแล้วจาก " . ConfigWeb::setWord($row['prj_funding_name']);
                else if($row['prj_funding'] == 'nstda')
                    $data = "ได้รับอนุมัติโครงการแล้วจาก สวทช. รหัสโครงการ " . ConfigWeb::setWord($row['prj_funding_code']);
                else if($row['prj_funding'] == 'other')
                    $data = "ได้รับอนุมัติทุนวิจัยแล้วจาก " . ConfigWeb::setWord($row['prj_funding_etc']);
                else if($row['prj_funding'] == 'none')
                    $data = "ไม่มี";
                break;
            case "ind_type_manufacture":
                $data = '';
                if ($row['ind_type_manufacture'] == 1)
                    $data .= CompanyForm::attributeLabels()['industrial_type_manufacture'] . self::CRLF;
                if ($row['ind_type_export'] == 1)
                    $data .= CompanyForm::attributeLabels()['industrial_type_export'] . self::CRLF;
                if ($row['ind_type_service'] == 1)
                    $data .= CompanyForm::attributeLabels()['industrial_type_service'] . self::CRLF;
                break;
            case "prj_effect":
                $data = '';
                if ($row['effect_new'] == 1)
                    $data .= StemStudentProjectForm::attributeLabels()['effect_new'] . self::CRLF;
                if ($row['effect_cost'] == 1)
                    $data .= StemStudentProjectForm::attributeLabels()['effect_cost'] . self::CRLF;
                if ($row['effect_quality'] == 1)
                    $data .= StemStudentProjectForm::attributeLabels()['effect_quality'] . self::CRLF;
                if ($row['effect_environment'] == 1)
                    $data .= StemStudentProjectForm::attributeLabels()['effect_environment'] . self::CRLF;
                if ($row['effect_other'] == 1)
                    $data .= StemStudentProjectForm::attributeLabels()['effect_other'] . "-" . $row['effect_other_text'] . self::CRLF;
                break;
            case "prj_relevance":
                if ($row['relevance_automotive'] == 1)
                    $data .= StemStudentProjectForm::attributeLabels()['relevance_automotive'] . self::CRLF;
                if ($row['relevance_electronics'] == 1)
                    $data .= StemStudentProjectForm::attributeLabels()['relevance_electronics'] . self::CRLF;
                if ($row['relevance_tourism'] == 1)
                    $data .= StemStudentProjectForm::attributeLabels()['relevance_tourism'] . self::CRLF;
                if ($row['relevance_agriculture'] == 1)
                    $data .= StemStudentProjectForm::attributeLabels()['relevance_agriculture'] . self::CRLF;
                if ($row['relevance_food'] == 1)
                    $data .= StemStudentProjectForm::attributeLabels()['relevance_food'] . self::CRLF;
                if ($row['relevance_robotics'] == 1)
                    $data .= StemStudentProjectForm::attributeLabels()['relevance_robotics'] . self::CRLF;
                if ($row['relevance_aviation'] == 1)
                    $data .= StemStudentProjectForm::attributeLabels()['relevance_aviation'] . self::CRLF;
                if ($row['relevance_biofuels'] == 1)
                    $data .= StemStudentProjectForm::attributeLabels()['relevance_biofuels'] . self::CRLF;
                if ($row['relevance_digital'] == 1)
                    $data .= StemStudentProjectForm::attributeLabels()['relevance_digital'] . self::CRLF;
                if ($row['relevance_medical'] == 1)
                    $data .= StemStudentProjectForm::attributeLabels()['relevance_medical'] . self::CRLF;
                break;
        }
        return ConfigWeb::setWord($data);
    }

}
