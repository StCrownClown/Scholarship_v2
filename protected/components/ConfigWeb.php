<?php

class ConfigWeb {
    
    public function formatDataViewToDB($date) {
        if (!empty($date)) {
            if (preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $date))
                return $date;
            else {
                $myDateTime = DateTime::createFromFormat('d/m/Y', $date);
                return $myDateTime->format('Y-m-d');
            }
        } else {
            return NULL;
        }
    }
    
    public function GetCalProjectPeriodMsg($begin, $end, $mode) {
        $data = 'ไม่สามารถคำนวนได้ / Begin date or End date invalid.';
        $diff = ConfigWeb::GetPeriodDate($begin, $end);
        if ($diff) {
            if($mode == 'primary'){
                $int_begin = str_replace('-', '', $begin);
                $int_end = str_replace('-', '', $end);
                $begin_from = Yii::app()->params['stem_project_begin_min'];
                $begin_to = date('Y-m-d', strtotime(Yii::app()->params['stem_project_sub_begin_min'] . ' +6 month'));
                
                $end_last = Yii::app()->params['stem_project_sub_begin_min'];
                $end_last = date('Y-m-d', strtotime($end_last . ' +6 month -1 day'));
                
                $int_from = str_replace('-', '', $begin_from);
                $int_to = str_replace('-', '', $begin_to);
                $int_last = str_replace('-', '', $end_last);

                $isOk = false;
                if($int_begin >= $int_from && $int_begin<= $int_to && $int_end >= $int_last){
                    $isOk = true;
                }
                if($isOk){
                    if($diff->y >= 1 || $diff->m >= 6)
                        $data = $diff->y . ' ปี  ' . $diff->m . ' เดือน  ' . $diff->d . ' วัน  ';
                    else
                        $data = 'ระยะเวลาโครงการไม่ตรงกับข้อกำหนด (ระยะเวลาต้องไม่ต่ำกว่า 6 เดือน)';
                }else{
                    if($int_begin < $int_from || $int_begin > $int_to){
                        $data = 'วันเริ่มโครงการหลักต้องไม่เกินวันที่ '
                            . ''.date("d/m/Y", strtotime($begin_to));
                    }else if($int_end < $int_last){
                        $data = 'วันสิ้นสุดโครงการหลักต้องมากกว่าวันที่ '.date("d/m/Y", strtotime($end_last))
                            . ' เป็นต้นไป';
                    }
                }
            }else if($mode == 'nonprimary'){
                if($diff->m >= 6 && $diff->m <= 11 || ($diff->y == 1 && $diff->m == 0 && $diff->d == 0)){
                    $cur_start = new DateTime($begin);
                    $cur_end = new DateTime($end);

                    $criteria = new CDbCriteria ();
                    $criteria->condition = "id = " . ConfigWeb::getActiveScholarId();
                    $criteria->limit = 1;
                    $Scholar = Scholar::model()->find($criteria);

                    $criteria->condition = "id = " . $Scholar->scholarStem->project_id;
                    $criteria->limit = 1;
                    $Project = Project::model()->find($criteria);

                    $pk_begin = $Project->begin;
                    $pk_end = $Project->end;

                    $pk_begin = ConfigWeb::formatDataViewToDB($pk_begin);
                    $pk_end = ConfigWeb::formatDataViewToDB($pk_end);
                    $datetime_pk_begin = new DateTime($pk_begin);
                    $datetime_pk_end = new DateTime($pk_end);

                    $begin_to = Yii::app()->params['stem_project_sub_begin_min'];
                    $begin_max = date('Y-m-d', strtotime(Yii::app()->params['stem_project_sub_begin_min'] . ' +6 month'));
                    $end_last = Yii::app()->params['stem_project_end_max'];
                    $datetime_begin_to = new DateTime($begin_to);
                    $datetime_begin_max = new DateTime($begin_max);
                    $datetime_end_last = new DateTime($end_last);

                    if($datetime_pk_begin <= $cur_start && $datetime_pk_end >= $cur_end){
                        if($cur_start < $datetime_begin_to || $cur_start > $datetime_begin_max){
                            $data = 'วันเริ่มโครงการย่อยต้องอยู่ในช่วง '
                                    . ''.date("d/m/Y", strtotime($begin_to)).''
                                    . ' ถึง '
                                    . ''.date("d/m/Y", strtotime($begin_max));
                        }else if($cur_end < $end_last){
                            $data = 'วันเริ่มโครงการย่อยต้องไม่เกินวันที่ '
                                    . ''.date("d/m/Y", strtotime($end_last));
                        }else{
                            if($diff->d == 0)
                                $data = $diff->y . '  ปี  ' . $diff->m . '  เดือน  ' . $diff->d . '  วัน  ';
                            else
                                $data = "กรุณากรอกวันที่เริ่มต้นและสิ้นสุดของโครงการฯ ย่อยให้ครบเต็มจำนวนเดือน";
                        }
                    }else
                        $data = 'ระยะเวลาโครงการไม่ตรงกับข้อกำหนด (โครงการย่อยระยะเวลาต้องไม่เกินโครงการหลัก '.date('d/m/Y', strtotime($pk_begin)).' - '.date('d/m/Y', strtotime($pk_end)).')';
                }else
                    $data = 'ระยะเวลาโครงการไม่ตรงกับข้อกำหนด (ระยะเวลาต้องไม่ต่ำกว่า 6 เดือนหรือมากกว่า 12 เดือน)';
            }else{
                $data = $diff->y . '  ปี  ' . $diff->m . '  เดือน  ' . $diff->d . '  วัน  ';
            }
        }
        return $data;
    }
    
    public function GetPeriodDate($begin, $end) {
        date_default_timezone_set('UTC');
        if ($begin != NULL) {
            $begin = ConfigWeb::formatDataViewToDB($begin);
        }
        if ($end != NULL) {
            $end = ConfigWeb::formatDataViewToDB($end);
        }
            
        $endAdd1d = date('Y-m-d', strtotime($end . ' +1 day'));
        $tmp_start = new DateTime($begin);
        $tmp_end = new DateTime($end);
        if ($tmp_start <= $tmp_end) {
            $end_y = date('Y', strtotime($end . ' +1 day'));
            $end_m = date('m', strtotime($end . ' +1 day'));
            $end_d = date('d', strtotime($end . ' +1 day'));

            $begin_y = date('Y', strtotime($begin));
            $begin_m = date('m', strtotime($begin));
            $begin_d = date('d', strtotime($begin));

            $time_start = mktime(0,0,0,$begin_m, $begin_d,$begin_y);
            $time_end = mktime(0,0,0,$end_m,$end_d,$end_y);

            $start_date = new DateTime();
            $end_date = new DateTime();

            $start_date->setTimestamp($time_start);
            $end_date->setTimestamp($time_end);

            $diff = $start_date->diff($end_date);
    //        echo $diff->y . 'y ' . $diff->m . 'm ' . $diff->d . 'd';
            return $diff;
        }else{
            return false;
        }
    }
    
    public function getCheckURL($url) {
        if (preg_match("(http|https)", $url))
            $link = $url;
        else {
            if (is_array($url)) {
                $link = Yii::app()->createUrl($url[0], (isset($url[1])) ? $url[1] : array());
            } else {
                $link = $url;
            }
        }
        return $link;
    }

    public function getCheckURLHttp($url) {
        if (preg_match("(http|https)", $url))
            $link = $url;
        else {
            $link = 'http://' . $url;
        }
        return $link;
    }
    
    public function ClearSessionTemp() {
        Yii::app()->session['tmpActiveScholarId'] = NULL;
        Yii::app()->session['tmpReadOnly'] = NULL;
        Yii::app()->session['tmpPass'] = NULL;
        Yii::app()->session['tmpEmail'] = NULL;
        Yii::app()->session['tmpActivePersonId'] = NULL;
        Yii::app()->session['tmpActivePersonType'] = NULL;
        Yii::app()->session['tmpActiveLoginByToken'] = NULL;
    }
    
    public function PageAdminOnly(){
        if (Yii::app()->session['user_type'] == 'admin') {
            return true;
        }else{
            throw new CHttpException(404, 'The specified post cannot be found.');
        }
    }
    
    public function getActiveScholarType() {
        if(empty(Yii::app()->session['scholar_type'])){
            $this->redirect(Yii::app()->createUrl('scholar/home'));
        }else{
            return Yii::app()->session['scholar_type'];
        }
    }
    
    public function getActiveScholarId() {
        if(empty(Yii::app()->session['tmpActiveScholarId'])){
            $this->redirect(Yii::app()->createUrl(WorkflowData::$home));
        }else{
            return Yii::app()->session['tmpActiveScholarId'];
        }
    }
    
    public function getActiveScholarIdType() {
        $scholar_type = ConfigWeb::getActiveScholarType();
        if(empty(Yii::app()->session['tmpActiveScholarId']) || empty($scholar_type)){
            return '';
        }else{
            $criteria = new CDbCriteria;
            $criteria->condition = "id=" . Yii::app()->session['tmpActiveScholarId'];
            $criteria->limit = 1;
            $Scholar = Scholar::model()->find($criteria);
            if(!empty($Scholar['scholar' . ucfirst($scholar_type)]))
                return '-'.$Scholar['scholar' . ucfirst($scholar_type)]->id;
            return '';
        }
    }
    public function getActiveScholarIdComment() {
        $scholar_type = ConfigWeb::getActiveScholarType();
        if(empty(Yii::app()->session['tmpActiveScholarId']) || empty($scholar_type)){
            return '';
        }else{
            $criteria = new CDbCriteria;
            $criteria->condition = ""
                    . "scholar_id = " . Yii::app()->session['tmpActiveScholarId']
                    . " and person_id = " . ConfigWeb::getActivePersonId();
            $criteria->limit = 1;
            $Comment = Comment::model()->find($criteria);
            if(!empty($Comment))
                return '-'.$Comment->id;
            return '';
        }
    }
    
    public function getActivePersonId() {
        if(!empty(Yii::app()->session['tmpActivePersonId'])){
            return Yii::app()->session['tmpActivePersonId'];
        }else{
            if(empty(Yii::app()->session['user_type'])){
                $this->redirect(Yii::app()->createUrl('scholar/home'));
            }else{
                return Yii::app()->session['person_id'];
            }
        }
    }
    
    public function getActivePersonType() {
        if(!empty(Yii::app()->session['tmpActivePersonType'])){
            return Yii::app()->session['tmpActivePersonType'];
        }else{
            return Yii::app()->session['person_type'];
        }
    }
    
    public function getActiveLoginByToken() {
        if(!empty(Yii::app()->session['tmpActiveLoginByToken'])){
            return Yii::app()->session['tmpActiveLoginByToken'];
        }else{
            return Yii::app()->session['LoginByToken'];
        }
    }
    
    public function getRunningStem($is_taist) {
        $sql = "";
        if(ConfigWeb::getActivePersonType() == 'professor' && !$is_taist)
            $sql = "select concat('P', right(year(adddate(now(),interval 3 month))+543,2), '-', count(*)+1) AS cc 
                    from scholar
                    LEFT JOIN scholar_stem stem ON scholar.scholar_stem_id = stem.id
                    where status='pending_scholarships'
                        and type='stem'
                        and stem.is_taist=false
                        and professor_id is not null";
        else if(ConfigWeb::getActivePersonType() == 'mentor' && !$is_taist)
            $sql = "select concat('M', right(year(adddate(now(),interval 3 month))+543,2), '-', count(*)+1) AS cc 
                    from scholar
                    LEFT JOIN scholar_stem stem ON scholar.scholar_stem_id = stem.id
                    where status='pending_scholarships' 
                        and type='stem' 
                        and is_taist=false 
                        and mentor_id is not null";
        else{
            $sql = "select concat('T', right(year(adddate(now(),interval 3 month))+543,2), '-', count(*)+1) AS cc 
                    from scholar
                    LEFT JOIN scholar_stem stem ON scholar.scholar_stem_id = stem.id
                    where status='pending_scholarships' 
                        and type='stem' 
                        and stem.is_taist=true 
                        and mentor_id is not null";
        }
        $data = Yii::app()->db->createCommand($sql)->queryAll();
        return $data[0]['cc'];
    }
    
    public function getUserID() {
        return (isset(Yii::app()->session['person_id'])) ? Yii::app()->session['person_id'] : null;
        // return (isset(Yii::app()->user->id))? Yii::app()->user->id : null;
    }

    public function getUserName() {
        return (isset(Yii::app()->user->fname)) ? Yii::app()->user->fname : 'guest';
    }

    public function getUserRoles() {
        return (isset(Yii::app()->session['roles'])) ? Yii::app()->session['roles'] : null;
    }

    public function getTypeFile($file) {
        $ext = substr($file, strrpos($file, '.') + 1);
        return $ext;
    }

    public function checkDir($dir) {
        if (!is_dir($dir)) {
            mkdir($dir);
        }
    }

    public function checkFile($file) {
        if (is_file($file))
            return true;
        else
            return false;
    }

    public function removeDir($dir) {
        if (is_dir($dir)) {
            foreach (scandir($dir) as $item) {
                if ($item == '.' || $item == '..')
                    continue;
                unlink($dir . DIRECTORY_SEPARATOR . $item);
            }
            rmdir($dir);
        }
    }

    public function removeFile($file) {
        if (is_file($file))
            unlink($file);
    }

    public function copeFile($from, $to) {
        if (is_file($from)) {
            copy($from, $to);
        }
    }

    public function moveFile($from, $to) {
        ConfigWeb::copeFile($from, $to);
        ConfigWeb::removeFile($from);
    }

    public function getMonthShotTh($month) {
        $monthShotTh = array('เดือน', 'ม.ค.', 'ก.พ.', 'มี.ค.', 'เม.ย.', 'พ.ค.', 'มิ.ย.', 'ก.ค.', 'ส.ค.', 'ก.ย.', 'ต.ค.', 'พ.ย.', 'ธ.ค.');
        return $monthShotTh[(int) $month];
    }

    public function getYearTh($year) {
        return ($year == '') ? '' : $year + 543;
    }

    public function getYearShotTh($year) {
        return $year + 43;
    }

    public function getYearFull($year) {
        return (Yii::app()->language == 'th') ? $year + 543 : $year;
    }

    public function getYearNow($lh = 'th') {
        $year = date('Y');
        return ($lh == 'th') ? $year + 543 : $year;
    }

    public function getMonthFullTh($month, $lan = '') {
        if ($month == '')
            return '';

        $monthFullTh = array('เดือน', "มกราคม", "กุมภาพันธ์", "มีนาคม", "เมษายน", "พฤษภาคม", "มิถุนายน", "กรกฎาคม", "สิงหาคม", "กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม");
        $lan = (empty($lan)) ? Yii::app()->language : $lan;
        return ($lan == 'th') ? $monthFullTh[(int) $month] : date("F", mktime(0, 0, 0, $month, 1, 2000));
    }

    public function getMonthFullAll() {
        $monthFullTh = array('เดือน', "มกราคม", "กุมภาพันธ์", "มีนาคม", "เมษายน", "พฤษภาคม", "มิถุนายน", "กรกฎาคม", "สิงหาคม", "กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม");
        $month = array();
        for ($i = 1; $i <= 12; $i++) {
            $month[$i] = (Yii::app()->language == 'th') ? $monthFullTh[$i] : date('F', mktime(0, 0, 0, $i, 1, 2000));
        }
        return $month;
    }

    public function getYearAll($plus = 2) {
        $thisYear = date('Y') + $plus;
        $year = array();
        for ($i = $thisYear; $i >= $thisYear - $plus - 12; $i--) {
            $year[$i] = (Yii::app()->language == 'th') ? ($i + 543) : $i;
        }
        return $year;
    }

    public function getYearSet($plus = 2, $setYear = 1997) { //250
        $thisYear = date('Y') + $plus;
        $year = array();
        for ($i = $thisYear; $i >= $setYear; $i--) {
            $year[$i] = (Yii::app()->language == 'th') ? ($i + 543) : $i;
        }
        return $year;
    }

    public function getYearNSTDA($lh = '', $year = '', $month = '') {
        $thisYear = ($year == '') ? date('Y') : $year;
        $month = ($month == '') ? date('n') : $month;
        $thisYear = ($month >= 10) ? $thisYear + 1 : $thisYear;
        return ($lh == 'th') ? $thisYear + 543 : $thisYear;
    }

    public function getYearNSTDAAll($down = 12) {
        $thisYear = ConfigWeb::getYearNSTDA();
        $year = array();
        for ($i = $thisYear; $i >= $thisYear - $down; $i--) {
            $year[$i] = (Yii::app()->language == 'th') ? ($i + 543) : $i;
        }
        return $year;
    }

    /*
     *  Ex:
      ConfigWeb::createTableView(array(
      'class'=>'table-striped table-bordered table-condensed',
      'header'=>array('h1','h2'),
      'body'=>array(
      array('c1','c2'),
      array('c3','c4'),
      ),
      ));
     */

    public function createTableView($data) {
        if (is_array($data)) {
            $i = 1;

            if (!isset($data['class'])) {
                $data['class'] = ''; // table-striped table-bordered table-condensed
            }

            if (!isset($data['idTable'])) {
                $data['idTable'] = '';
            } else {
                $data['idTable'] = " id='" . $data['idTable'] . "' ";
            }

            $tmpTable = '<table class="items table ' . $data['class'] . '" ' . $data['idTable'] . '>';

            if (isset($data['header'])) {
                $tmpTable .= '<thead>';
                $tmpTable .= '<tr class="bootstrap-widget-header">';
                foreach ($data['header'] as $value) {
                    $style = '';
                    if (is_array($value)) {
                        if (isset($value['css'])) {
                            $style = ' style=\'' . $value['css'] . '\'';
                        }
                        $value = $value['value'];
                    }
                    $tmpTable .= "<th {$style}>{$value}</th>";
                }
                $tmpTable .= '</tr>';
                $tmpTable .= '</thead>';
            }

            $tmpTable .= '<tbody>';

            $col = (isset($data['header'])) ? count($data['header']) : 0;

            if (isset($data['noDataNotShow']) && $data['noDataNotShow'] == true) {
                $tmpTable .= '';
            } else {
                $tmpTable .= '<tr>';
                $tmpTable .= "<td class='odd noData' [style] colspan='{$col}'>ไม่มีข้อมูล</td>";
                $tmpTable .= '</tr>';
            }


            if (is_array($data['body'])) {
                foreach ($data['body'] as $dataKey => $dataValue) {

                    if (!is_numeric($dataKey)) {
                        $tmpTable .= "<tr id='{$dataKey}'>";
                    } else {
                        $tmpTable .= '<tr>';
                    }
                    foreach ($dataValue as $value) {
                        $class = "class='odd'";
                        if ($i % 2 == 0)
                            $class = "class='even'";

                        if (is_array($value)) {
                            if (isset($value['css'])) {
                                $class .= ' style=\'' . $value['css'] . '\'';
                            }
                            if (isset($value['encode'])) {
                                eval("\$value = " . $value['encode'] . "('" . $value['value'] . "');");
                            } else {
                                $value = $value['value'];
                            }
                        }

                        $tmpTable .= "<td {$class}>{$value}</td>";
                    }
                    $tmpTable .= '</tr>';
                    $i++;
                }
            }
            if ($i == 1) {
                $tmpTable = str_replace('[style]', '', $tmpTable);
            } else {
                $tmpTable = str_replace('[style]', "style='display:none;'", $tmpTable);
            }

            $tmpTable .= '</tbody>';
            $tmpTable .= '</table>';

            return $tmpTable;
        }

        return null;
    }

    public function createTableViewList($data) {
        if (is_array($data)) {
            $i = 1;
            $tmpTable = '<table class="table">';
            foreach ($data as $dataValue) {
                $tmpTable .= '<tr>';
                foreach ($dataValue as $value) {
                    $class = 'style="background-color: #f9f9f9;"';
                    if ($i % 2 == 0)
                        $class = 'style="background-color: #fff;"';
                    $tmpTable .= "<td {$class}>{$value}</td>";
                }
                $tmpTable .= '</tr>';
                $i++;
            }
            $tmpTable .= '</table>';
            return $tmpTable;
        }

        return null;
    }

    public function setSearchBar($grid, $url) {
        $url = Yii::app()->createUrl($url);
        $script = "
			$(function(){
				$('#btnSearchBar').click(function(){
					var tbSearchBar = $('#tbSearchBar').val();
					$.fn.yiiGridView.update('{$grid}', {
						data: {'oSearchBar':tbSearchBar}
					});
				});
				
				$('#tbSearchBar').keypress(function(event){
					if(event.keyCode == 13){
						$('#btnSearchBar').click();
					}
				});
			});
			";

        Yii::app()->clientScript->registerScript('SearchBar', $script, CClientScript::POS_HEAD);
    }

    public function array_unshift_assoc($arr, $key, $val) { // เพิ่ม array ที่ส่วนบน
        $arr = array_reverse($arr, true);
        $arr[$key] = $val;
        return array_reverse($arr, true);
    }

    public function getFooter() {
        $year = (Yii::app()->params['footerYear'] == date('Y')) ? date('Y') : Yii::app()->params['footerYear'] . '-' . date('Y');
        return str_replace("{year}", $year, Yii::app()->params['footer']);
    }

    /*     * *  1.16  * */

    public function setWriterFile($path, $text) {
        $file = fopen($path, "w");
        fwrite($file, $text);
        fclose($file);
    }

    public function getReadFile($path) {
        if (ConfigWeb::checkFile($path) && filesize($path)) {
            $file = fopen($path, "r");
            $read = fread($file, filesize($path));
            fclose($file);

            return $read;
        }
        return '';
    }

    ///////

    /*     * *  1.17, 1.20.2, 1.24.3  * */
    public function getMenuListItem($id) {
        $items = array();
        $arr = array();
        if (isset(Yii::app()->params['filterMenuListItem'][$id])) {
            foreach (Yii::app()->params['filterMenuListItem'][$id] as $item) {
                $check = false;
                if ($item['role'] == 'all') {
                    $check = true;
                } else {
                    foreach ($item['role'] as $vRole) {
                        if (KR_GROUP_ADMIN::checkRoleOnly($vRole)) {
                            $check = true;
                            break;
                        }
                    }
                }
                if ($check === true) {
                    unset($item['role']);
                    foreach ($item as $key => $value) {
                        if ($key == 'label') {
                            $pos = strpos($value, "t|");
                            if ($pos !== false) {
                                list($c, $f, $t) = explode("|", $value);
                                $value = Yii::t($f, $t);
                            }
                        }

                        if ($key == 'url') {
                            $value = (!empty($value)) ? Yii::app()->createUrl($value) : '';
                        }
                        if (is_array($value)) {
                            $tmp = array();
                            foreach ($value as $keySub => $sub) {
                                $pos = strpos($sub, "eval|");
                                if ($pos !== false) {
                                    $sub = ConfigWeb::execValue($sub);
                                }

                                $tmp[$keySub] = $sub;
                            }
                            $value = $tmp;
                        } else {
                            $pos = strpos($value, "eval|");
                            if ($pos !== false) {
                                $value = ConfigWeb::execValue($value);
                            }
                        }

                        $arr[$key] = $value;
                    }
                    $items[] = $arr;
                }
            }
        }
        return $items;
    }

    public function execValue($val) {
        list($tmp, $comm) = explode("|", $val);
        $output = '';
        eval("\$output = " . $comm . ";");
        return $output;
    }

    ////

    /*     * *  1.18  * */
    public function getDateTimeNow($len = 'th') {
        $date = date('d/m/');
        $date .= ($len == 'th') ? intval(date('Y')) + 543 : date('Y');
        $date .= date(' H:i:s');
        return $date;
    }

    public function getDateNow($len = 'th') {
        $date = date('d/m/');
        $date .= ($len == 'th') ? intval(date('Y')) + 543 : date('Y');
        return $date;
    }

    ////

    /*     * *  1.19  * */
    public function getCharExcel($data = array()) {
        /*
          $data = array(
          'firstChar'=>65, // A
          'count'=>0,
          'tmp'=>'',
          'tmpFirstChar'=>'',
          'char'=>'',
          );
         */
        if (!isset($data['tmp'])) {
            $data['tmp'] = '';
        }
        if (!isset($data['tmpFirstChar']) || $data['tmpFirstChar'] == '') {
            $data['tmpFirstChar'] = 65;
        }
//        if(isset($data['tmp']) && empty($data['tmp'])){
//            $code = $data['firstChar']+$data['count'];
//            if($code > 90){
//                $data['tmp'] = 65;
//                $data['char'] = 'AA';
//                $data['count'] = 0;
//            }else{
//                $data['char'] = chr($code);
//                $data['count'] += 1;
//            }
//        }else if(!empty($data['tmp'])){
//            $code = $data['tmpFirstChar']+$data['count'];
//            if($code > 90){
//                $data['tmp'] += 1;
//                $data['char'] = chr($data['tmp']).'A';
//                $data['count'] = 0;
//            }else{
//                $data['char'] = chr($data['tmp']).chr($code);
//                $data['count'] += 1;
//            }
//        }

        $code = $data['firstChar'] + $data['count'] - 65;
        $data['char'] = ConfigWeb::getCharPhpExcel($code);
        $data['count'] += 1;

        return $data;
    }

    ////

    /*     * *  1.20  * */
    public function getHeaderProfile() {
        if (isset(Yii::app()->user->id)) {
            if (isset(Yii::app()->params['classProfileName']) && !empty(Yii::app()->params['classProfileName'])) {
                eval('echo ' . Yii::app()->params['classProfileName'] . ';');
            }
            if (isset(Yii::app()->params['showChooseLanguage']) && Yii::app()->params['showChooseLanguage'] === true) {
                echo '&nbsp;&nbsp;';
//                echo CHtml::link('English',Yii::app()->request->url.'?ln=en&ln=en');
                echo CHtml::link('English', Yii::app()->createUrl('site/changeLanguage', array('lang' => 'en', 'redirect' => Yii::app()->request->url)));
                echo '&nbsp;|&nbsp;';
                echo CHtml::link('Thai', Yii::app()->createUrl('site/changeLanguage', array('lang' => 'th', 'redirect' => Yii::app()->request->url)));
            }

            echo '&nbsp;&nbsp;';
            echo CHtml::link(Yii::t('web', 'Logout'), Yii::app()->createUrl('site/logout'));
        }
    }

    ////

    /*     * *  1.21  * */
    public function changeMenuName($menu) {
        $arr = array();
        $tmp = array();
        if (is_array($menu)) {
            foreach ($menu as $item) {
                foreach ($item as $key => $value) {
                    if ($key == 'name') {
                        $pos = strpos($value, "t|");
                        if ($pos !== false) {
                            list($c, $f, $t) = explode("|", $value);
                            $value = Yii::t($f, $t);
                        }
                    }
                    $arr[$key] = $value;
                }
                $tmp[] = $arr;
            }
        }
        return $tmp;
    }

    ////

    /*     * *  1.22  * */
    public function createWidgetBtn($btn) {
        foreach ($btn as $value) {
            if (!empty($value)) {
                $this->widget('bootstrap.widgets.TbButton', $value);
                echo '&nbsp;';
            }
        }
    }

    ////

    /*     * *  1.23  * */
    public function getTypeIcon($typeFile) {
        if ($typeFile == 'jpg' || $typeFile == 'jpeg' || $typeFile == 'gif' || $typeFile == 'bmp' || $typeFile == 'png') {
            $typeFile = 'image';
        } else if ($typeFile == 'doc' || $typeFile == 'docx' || $typeFile == 'opt') {
            $typeFile = 'docx';
        } else if ($typeFile == 'ppt' || $typeFile == 'pptx') {
            $typeFile = 'pptx';
        } else if ($typeFile == 'xls' || $typeFile == 'xlsx') {
            $typeFile = 'xlsx';
        } else if ($typeFile == 'tif' || $typeFile == 'tiff') {
            $typeFile = 'tiff';
        } else if ($typeFile == 'pdf') {
            $typeFile = 'pdf';
        } else if ($typeFile == 'zip') {
            $typeFile = 'zip';
        } else if ($typeFile == 'rar') {
            $typeFile = 'rar';
        } else if ($typeFile == 'txt') {
            $typeFile = 'txt';
        } else if ($typeFile == 'tar') {
            $typeFile = 'tar';
        } else {
            $typeFile = 'other';
        }

        return $typeFile;
    }

    ////

    public function getHeaderTablePopover($name, $align = 'center') {
        if ($align == 'center') {
            $align = "
                var position = $('#btnHeaderTablePopover_{$name}').position();
                $('#popTableHeader_{$name}').css({'top':position.top+5-$('#popTableHeader_{$name}').height()/2,'left':position.left+15});
            ";
        } else if ($align == 'top') {
            $align = "
                var position = $('#btnHeaderTablePopover_{$name}').position();
                $('#popTableHeader_{$name}').css({'top':position.top-10,'left':position.left+15});
                $('.popover.right .arrow').css({'top':'5%'});
            ";
        }
        $script = "
            $(function(){
                ckTablePopover();
            });

            $(document).mouseup(function (e)
            {
                var container = $('#popTableHeader_{$name}');

                if (!container.is(e.target) // if the target of the click isn't the container...
                    && container.has(e.target).length === 0) // ... nor a descendant of the container
                {
                    container.hide();
                }
            });

            var popover_{$name} = '';

            function ckTablePopover(){
                var popover = \"<div class='popover right' id='popTableHeader_{$name}'><div class='arrow'></div><h3 class='popover-title'></h3><div class='popover-content'></div></div>\";
                $('#{$name}').append(popover);

                var tmp = '';
                var tr = $(\"#{$name} table thead tr\").find('th');
                $.each(tr, function(key,value){
                    if($(this).text() != ''){
                        tmp += \"<label class='checkbox'><input id='c_{$name}_\"+key+\"' value='\"+key+\"' type='checkbox' checked='checked' name='filterShow[]' onclick=\\\"getHeaderTablePopover(\"+key+\");\\\"><label for='KR_TAGS_LIST_TAG_ID_0'><span>\"+$(this).text()+\"</span></label></label>\";
                    }
                });

                $('#popTableHeader_{$name} .popover-content').html(tmp);

                $('#btnHeaderTablePopover_{$name}').click(function(){
                    showTablePopover();
                });
            }

            function beforeTablePopover(){
                popover_{$name} = '';
                var tr = $(\"#{$name} table thead tr\").find('th');
                $.each(tr, function(key,value){
                    if($(this).text() != ''){
                        if($(this).css('display') == 'none'){
                            popover_{$name} += key+',';
                        }
                    }
                });
            }

            function showTablePopover(){
                $('#popTableHeader_{$name}').toggle();

                {$align}
            }

            function getHeaderTablePopover(id){
                $('#{$name} table thead tr').find('th').eq(id).toggle();
                var tr = $(\"#{$name} table tbody\").find('tr');
                $.each(tr, function(key,value){
                    $('#{$name} table tbody').find('tr').eq(key).find('td').eq(id).toggle();
                });
            }

            function afterTablePopover(){
                ckTablePopover();

                if(popover_{$name} != ''){
                    var tr = popover_{$name}.split(\",\");
                    $.each(tr, function(key,value){
                        if(value != ''){
                            getHeaderTablePopover(value);
                            $('#c_{$name}_'+value).attr('checked',false);
                        }
                    });
                }
            }
        ";
        Yii::app()->clientScript->registerScript(__class__ . '#getHeaderTablePopover_' . $name, $script, CClientScript::POS_HEAD);

        return CHtml::link('<i class="icon-th-list">', '#', array('id' => 'btnHeaderTablePopover_' . $name));
    }

    public function createTableHtml($data) {
        $tmpTable = '<table id="' . $data['idTable'] . '" class="display cell-border ' . $data['class'] . '" cellspacing="0" width="100%">';

        if (isset($data['header'])) {
            $tmpTable .= '<thead>';
            $tmpTable .= '<tr>';
            foreach ($data['header'] as $value) {
                if (is_array($value)) {
                    $tmp = '';
                    foreach ($value[1] as $key => $val) {
                        $tmp .= "$key = '$val' ";
                    }
                    $tmpTable .= "<th {$tmp}>" . $value[0] . "</th>";
                } else {
                    $tmpTable .= "<th>{$value}</th>";
                }
            }
            $tmpTable .= '</tr>';
            $tmpTable .= '</thead>';
        }

        if (isset($data['headerMultiple'])) {
            $tmpTable .= '<thead>';

            foreach ($data['headerMultiple'] as $header) {
                $tmpTable .= '<tr>';
                foreach ($header as $value) {
                    if (is_array($value)) {
                        $tmp = '';
                        foreach ($value[1] as $key => $val) {
                            $tmp .= "$key = '$val' ";
                        }
                        $tmpTable .= "<th {$tmp}>" . $value[0] . "</th>";
                    } else {
                        $tmpTable .= "<th>{$value}</th>";
                    }
                }
                $tmpTable .= '</tr>';
            }

            $tmpTable .= '</thead>';
        }

        $tmpTable .= '<tbody>';

        if (is_array($data['body'])) {
            foreach ($data['body'] as $dataKey => $dataValue) {

                $tmpTable .= '<tr>';
                foreach ($dataValue as $value) {
                    if (is_array($value)) {
                        $tmp = '';
                        foreach ($value[1] as $key => $val) {
                            $tmp .= "$key = '$val' ";
                        }
                        $tmpTable .= "<td {$tmp}>" . $value[0] . "</td>";
                    } else {
                        $tmpTable .= "<td>{$value}</td>";
                    }
                }
                $tmpTable .= '</tr>';
            }
        }

        $tmpTable .= '</tbody>';

        if (isset($data['foot'])) {
            $tmpTable .= '<tfoot>';
            $tmpTable .= '<tr>';
            foreach ($data['foot'] as $value) {
                if (is_array($value)) {
                    $tmp = '';
                    foreach ($value[1] as $key => $val) {
                        $tmp .= "$key = '$val' ";
                    }
                    $tmpTable .= "<th {$tmp}>" . $value[0] . "</th>";
                } else {
                    $tmpTable .= "<th>{$value}</th>";
                }
            }
            $tmpTable .= '</tr>';
            $tmpTable .= '</tfoot>';
        }

        $tmpTable .= '</table>';

        return $tmpTable;
    }

    /*
     * PHPExcel $sheet
     */

    public function createPhpExcel($data, $sheet, $i = 1) {
        $da = array(
            'firstChar' => 65, // A
            'count' => 0,
            'tmp' => '',
            'tmpFirstChar' => '',
            'char' => '',
        );

        if (isset($data['header'])) {
            foreach ($data['header'] as $value) {
                $da = ConfigWeb::getCharExcel($da);
                $sheet->setCellValue($da['char'] . $i, Yii::app()->input->stripClean($value));
            }
        }
        $i++;

        if (is_array($data['body'])) {
            foreach ($data['body'] as $dataKey => $dataValue) {
                $da = array(
                    'firstChar' => 65, // A
                    'count' => 0,
                    'tmp' => '',
                    'tmpFirstChar' => '',
                    'char' => '',
                );
                foreach ($dataValue as $value) {
                    if (is_array($value)) {
                        $value = $value[0];
                    }
                    $da = ConfigWeb::getCharExcel($da);

                    $value = str_replace("<br>", "[br]", $value);

                    $val = Yii::app()->input->stripClean($value);
                    $val = str_replace("[br]", "\n", $val);

//                    $sheet->setCellValue($da['char'] . $i, $val);
                    $sheet->setCellValueExplicit($da['char'] . $i, $val, PHPExcel_Cell_DataType::TYPE_STRING);
                }
                $i++;
            }
        }

        return $sheet;
    }

    public function getCharPhpExcel($i = 0) { // 0 = 'A', max 3 char
        return PHPExcel_Cell::stringFromColumnIndex($i);
    }

    /*
     * PHPExcel $objPHPExcel
     */

    public function setAutoWidthAllSheet($objPHPExcel, $borderHeader = 0) {
        foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {
            $objPHPExcel->setActiveSheetIndex($objPHPExcel->getIndex($worksheet));

            $sheet = $objPHPExcel->getActiveSheet();
            $cellIterator = $sheet->getRowIterator()->current()->getCellIterator();
            $cellIterator->setIterateOnlyExistingCells(true);
            /** @var PHPExcel_Cell $cell */
            if ($borderHeader == 1) {
                $firstHeader = '';
                $LastHeader = '';
            }
            foreach ($cellIterator as $cell) {
                $sheet->getColumnDimension($cell->getColumn())->setAutoSize(true);
                if ($borderHeader == 1) {
                    if ($firstHeader == '') {
                        $firstHeader = $cell->getColumn();
                    } else {
                        $LastHeader = $cell->getColumn();
                    }
                }
            }
        }
        if ($borderHeader == 1) {
            $objPHPExcel->getActiveSheet()->getStyle($firstHeader . '1:' . $LastHeader . '1')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            ConfigWeb::setPhpExcelcellColor($objPHPExcel, $firstHeader . '1:' . $LastHeader . '1', 'EAEAEA');
        }
    }

    public function setPhpExcelcellColor($objPHPExcel, $cells, $color) {
        $objPHPExcel->getActiveSheet()->getStyle($cells)->getFill()->applyFromArray(array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'startcolor' => array(
                'rgb' => $color
            )
        ));
    }

    public function getCUrl($urlWithoutProtocol, $request = '', $isRequestHeader = false, $exHeaderInfoArr = array()) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $urlWithoutProtocol);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $request);
        curl_setopt($ch, CURLOPT_HEADER, (($isRequestHeader) ? 1 : 0));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        if (is_array($exHeaderInfoArr) && !empty($exHeaderInfoArr)) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $exHeaderInfoArr);
        }
        $response = curl_exec($ch);
        curl_close($ch);

        return $response;
    }

    /// 1.30
    public function createTableExcel($data) {
        $filename = date('Ymd');
        header("Content-Type: application/vnd.ms-excel");
        header('Content-Disposition: attachment; filename="' . $filename . '.xls"');

        $tmpTable = '<html xmlns:o="urn:schemas-microsoft-com:office:office"xmlns:x="urn:schemas-microsoft-com:office:excel"xmlns="http://www.w3.org/TR/REC-html40">';
        $tmpTable .= '
<HEAD>
<meta http-equiv="Content-type" content="text/html;charset=utf-8" />
</HEAD><BODY>';
        $tmpTable .= '<TABLE  x:str BORDER="1">';
        if (isset($data['header'])) {
            $tmpTable .= '<thead>';
            $tmpTable .= '<tr>';
            foreach ($data['header'] as $value) {
                if (is_array($value)) {
                    $tmp = '';
                    foreach ($value[1] as $key => $val) {
                        $tmp .= "$key = '$val' ";
                    }
                    $tmpTable .= "<th {$tmp}>" . $value[0] . "</th>";
                } else {
                    $tmpTable .= "<th>{$value}</th>";
                }
            }
            $tmpTable .= '</tr>';
            $tmpTable .= '</thead>';
        }

        $tmpTable .= '<tbody>';

        if (is_array($data['body'])) {
            foreach ($data['body'] as $dataKey => $dataValue) {

                $tmpTable .= '<tr>';
                foreach ($dataValue as $value) {
                    if (is_array($value)) {
                        $tmp = '';
                        foreach ($value[1] as $key => $val) {
                            $tmp .= "$key = '$val' ";
                        }
                        $tmpTable .= "<td {$tmp}>" . $value[0] . "</td>";
                    } else {
                        $tmpTable .= "<td>{$value}</td>";
                    }
                }
                $tmpTable .= '</tr>';
            }
        }

        $tmpTable .= '</tbody>';
        $tmpTable .= '</table>';

        $tmpTable .= '</body></html>';
        echo $tmpTable;
    }

    /*

      create table WWW_TAB_COLUMNS
      (
      column_id    NUMBER(10) not null,
      table_name   VARCHAR2(50) not null,
      column_name  VARCHAR2(50) not null,
      data_type    VARCHAR2(100) not null,
      nullable     CHAR(1),
      data_default VARCHAR2(100),
      key          CHAR(1)
      );
      comment on column WWW_TAB_COLUMNS.nullable is 'Y/N';
      comment on column WWW_TAB_COLUMNS.key is 'P/NULL';
      alter table WWW_TAB_COLUMNS add constraint PK_WWW_TAB_COLUMNS primary
      key (TABLE_NAME, COLUMN_NAME);

      create table WWW_TABLES
      (
      table_name VARCHAR2(50) not null
      );
      alter table WWW_TABLES add constraint PK_WWW_TABLES primary key (TABLE_NAME);

      create table WWW_TAB_CONS
      (
      table_name        VARCHAR2(50) not null,
      column_name       VARCHAR2(50) not null,
      position          NUMBER(10) not null,
      r_constraint_name VARCHAR2(50) not null,
      table_ref         VARCHAR2(50) not null,
      column_ref        VARCHAR2(200) not null
      );
      alter table WWW_TAB_CONS add constraint PK_WWW_TAB_CONS primary key
      (TABLE_NAME, COLUMN_NAME);
      alter table WWW_TAB_CONS add constraint FK_WWW_TAB_CONS foreign key
      (TABLE_NAME) references WWW_TABLES (TABLE_NAME);

     */

    public function inseartAROci($schemaName, $firstName = '') {
        set_time_limit(300);
        $sqlAll = array();
        $sqlAll[] = 'delete from ' . $firstName . 'WWW_TAB_CONS';
        $sqlAll[] = 'delete from ' . $firstName . 'WWW_TABLES';
        $sqlAll[] = 'delete from ' . $firstName . 'WWW_TAB_COLUMNS';

        $i = 1;
        $connection = Yii::app()->db;
        $sql = "SELECT OWNER, TABLE_NAME  FROM ALL_TABLES WHERE OWNER='{$schemaName}' union SELECT OWNER, view_name as TABLE_NAME  FROM ALL_VIEWS WHERE OWNER='{$schemaName}'";
        $command = $connection->createCommand($sql);
        $dataReader = $command->query();
        foreach ($dataReader as $all) {
            $sqlAll[] = "insert into {$firstName}WWW_TABLES values('{$all['TABLE_NAME']}')";

            $sql = "SELECT a.COLUMN_NAME, a.DATA_TYPE ||
					case
						when data_precision is not null
							then '(' || a.data_precision ||
									case when a.data_scale > 0 then ',' ||
				a.data_scale else '' end
								|| ')'
						when data_type = 'DATE' then ''
						else '(' || to_char(a.data_length) || ')'
					end as data_type,
					a.NULLABLE, a.DATA_DEFAULT,
					(   SELECT D.constraint_type
						FROM user_CONS_COLUMNS C
						inner join user_constraints D On D.constraint_name = C.constraint_name
						Where C.table_name = A.TABLE_NAME
						   and C.column_name = A.column_name
						   and D.constraint_type = 'P') as Key
				FROM user_TAB_COLUMNS A
				WHERE
					A.TABLE_NAME = '{$all['TABLE_NAME']}'
				ORDER by a.column_id";
            $command = $connection->createCommand($sql);
            $dataReader = $command->query();
            foreach ($dataReader as $col) {
                $sqlAll[] = "insert into {$firstName}WWW_TAB_COLUMNS(COLUMN_ID, TABLE_NAME, COLUMN_NAME, DATA_TYPE, NULLABLE, DATA_DEFAULT, KEY) values({$i},'{$all['TABLE_NAME']}','{$col['COLUMN_NAME']}','{$col['DATA_TYPE']}','{$col['NULLABLE']}','" . CHtml::encode($col['DATA_DEFAULT']) . "','{$col['KEY']}')";
                $i++;
            }

            $sql = "SELECT D.constraint_type, C.COLUMN_NAME, C.position, D.r_constraint_name,
					E.table_name as table_ref, f.column_name as column_ref
							FROM ALL_CONS_COLUMNS C
							inner join ALL_constraints D on D.OWNER = C.OWNER and
					D.constraint_name = C.constraint_name
							left join ALL_constraints E on E.OWNER = D.r_OWNER and
					E.constraint_name = D.r_constraint_name
							left join ALL_cons_columns F on F.OWNER = E.OWNER and
					F.constraint_name = E.constraint_name and F.position = c.position
							WHERE C.OWNER = '{$schemaName}'
							   and C.table_name = '{$all['TABLE_NAME']}'
							   and D.constraint_type = 'R'
							order by d.constraint_name, c.position";
            $command = $connection->createCommand($sql);
            $dataReader = $command->query();
            foreach ($dataReader as $con) {
                $sqlAll[] = "insert into {$firstName}WWW_TAB_CONS(TABLE_NAME, COLUMN_NAME, POSITION, R_CONSTRAINT_NAME, TABLE_REF, COLUMN_REF) values('{$all['TABLE_NAME']}','{$con['COLUMN_NAME']}','{$con['POSITION']}','{$con['R_CONSTRAINT_NAME']}','{$con['TABLE_REF']}','{$con['COLUMN_REF']}')";
            }
        }


        // print_r($sqlAll);
        $transaction = $connection->beginTransaction();
        try {
            foreach ($sqlAll as $value) {
                $connection->createCommand($value)->execute();
            }
            $transaction->commit();
        } catch (Exception $e) {
            echo $e;
            $transaction->rollback();
        }
        $connection->active = false;
    }

    public function diffDay($date1, $date2) {
        //date - YYYY-mm-dd
        $your_date = strtotime($date1);
        $now = strtotime($date2);
        $datediff = $now - $your_date;
        return floor($datediff / (60 * 60 * 24));
    }

    public function mime_content_type($filename) {
        $mime_types = array(
            'txt' => 'text/plain',
            'htm' => 'text/html',
            'html' => 'text/html',
            'php' => 'text/html',
            'css' => 'text/css',
            'js' => 'application/javascript',
            'json' => 'application/json',
            'xml' => 'application/xml',
            'swf' => 'application/x-shockwave-flash',
            'flv' => 'video/x-flv',
            // images
            'png' => 'image/png',
            'jpe' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'jpg' => 'image/jpeg',
            'gif' => 'image/gif',
            'bmp' => 'image/bmp',
            'ico' => 'image/vnd.microsoft.icon',
            'tiff' => 'image/tiff',
            'tif' => 'image/tiff',
            'svg' => 'image/svg+xml',
            'svgz' => 'image/svg+xml',
            // archives
            'zip' => 'application/zip',
            'rar' => 'application/x-rar-compressed',
            'exe' => 'application/x-msdownload',
            'msi' => 'application/x-msdownload',
            'cab' => 'application/vnd.ms-cab-compressed',
            // audio/video
            'mp3' => 'audio/mpeg',
            'qt' => 'video/quicktime',
            'mov' => 'video/quicktime',
            // adobe
            'pdf' => 'application/pdf',
            'psd' => 'image/vnd.adobe.photoshop',
            'ai' => 'application/postscript',
            'eps' => 'application/postscript',
            'ps' => 'application/postscript',
            // ms office
            'doc' => 'application/msword',
            'dot' => 'application/msword',
            'rtf' => 'application/rtf',
            'xls' => 'application/vnd.ms-excel',
            'ppt' => 'application/vnd.ms-powerpoint',
            'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'dotx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.template',
            'docm' => 'application/vnd.ms-word.document.macroEnabled.12',
            'dotm' => 'application/vnd.ms-word.template.macroEnabled.12',
            'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'xltx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.template',
            'xlsm' => 'application/vnd.ms-excel.sheet.macroEnabled.12',
            'xltm' => 'application/vnd.ms-excel.template.macroEnabled.12',
            'xlam' => 'application/vnd.ms-excel.addin.macroEnabled.12',
            'xlsb' => 'application/vnd.ms-excel.sheet.binary.macroEnabled.12',
            'pptx' => 'application/vnd.openxmlformats-officedocument.presentationml.presentation',
            // open office
            'odt' => 'application/vnd.oasis.opendocument.text',
            'ods' => 'application/vnd.oasis.opendocument.spreadsheet',
        );

        $ext = strtolower(array_pop(explode('.', $filename)));
        if (array_key_exists($ext, $mime_types)) {
            return $mime_types[$ext];
        } elseif (function_exists('finfo_open')) {
            $finfo = finfo_open(FILEINFO_MIME);
            $mimetype = finfo_file($finfo, $filename);
            finfo_close($finfo);
            return $mimetype;
        } else {
            return 'application/octet-stream';
        }
    }

    public function createTablePhpExcel($table, $title = 'Report', $type = 'xlsx') {
        Yii::import('application.extensions.PHPExcel.PHPExcel');
        $objPHPExcel = new PHPExcel();
        // Set document properties
        $objPHPExcel->getProperties()->setCreator("nstda")
                ->setLastModifiedBy("nstda")
                ->setTitle("Document")
                ->setSubject("Document")
                ->setDescription("Document")
                ->setKeywords("pdf php")
                ->setCategory("Document");
        $objPHPExcel->getActiveSheet()->setTitle($title);

        $sheet = $objPHPExcel->setActiveSheetIndex(0);
        $sheet = ConfigWeb::createPhpExcel($table, $sheet);

        ConfigWeb::setAutoWidthAllSheet($objPHPExcel, 1);

//        $sheet2 = $objPHPExcel->createSheet(2);
//        $sheet2->setTitle('Report 2');
//        $sheet2 = ConfigWeb::createPhpExcel($table2, $sheet2);

        $filename = date('Ymd');

        if ($type == 'xls') {
            $app = 'application/vnd.ms-excel';
            $typeName = 'Excel5';
        } else if ($type == 'csv') {
            $app = 'text/csv';
            $typeName = 'CSV';
        } else if ($type == 'pdf') {
            $app = 'application/pdf';
            $typeName = 'PDF';
        } else if ($type == 'xlsx') {
            $app = 'application/vnd.ms-excel';
            $typeName = 'Excel2007'; // Required PHP Version 5.2.9 +
        }

        header('Content-Type: ' . $app . '; charset=utf-8');
        header('Content-Disposition: attachment;filename="' . $filename . '.' . $type . '"');
        header('Cache-Control: max-age=0');

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, $typeName);
        $objWriter->save('php://output');
    }

    public function createTableExcelAuto($table, $title = 'Report', $type = 'xlsx') {
        if (count($table['body']) > 200) {
            ConfigWeb::createTableExcel($table);
        } else {
            ConfigWeb::createTablePhpExcel($table, $title, $type);
        }
    }

    public function filterSearchBar($data) {
        $data = trim($data);
        $text = $data;
        $text = str_replace(": ", ":", $text);
        $int = strlen($text) - 1;
        $first = false;
        $tmp = '';
        for ($i = 0; $i <= $int; $i++) {
            if ($text[$i] == '"') {
                if ($first === false) {
                    $first = true;
                    $tmp .= $text[$i];
                } else {
                    $first = false;
                    $tmp .= $text[$i];
                }
            } else if ($first === true && $text[$i] == ' ') {
                $tmp .= '_';
            } else {
                $tmp .= $text[$i];
            }
        }
        $tmp = str_replace(" ", "$", $tmp);
        $tmp = str_replace('"', "", $tmp);
        $tmp = str_replace("_", " ", $tmp);
        $tmp = explode("$", $tmp);

        $data = $tmp;
        if (!is_array($data)) {
            $data = array($data);
        }

        return $data;
    }

    public function setDefaultDisplay($val) {
        $display = 'style="display:none"';
        if (isset($val)) {
            if ($val == '0') {
                $display = '';
            }
        }
        return $display;
    }
    
    
    function setWord($val) {
        if ($val != '') {
            return str_replace("\n", "<w:br />", htmlspecialchars($val));
        }
        return '-';
    }

    function setCheckFileImage($val) {
        if (!empty($val)) {
            return $val;
        }
        return Yii::app()->basePath . '/../images/user.png';
    }

    function setCheck($val) {
        $notCkeck = '<w:sym w:font="Wingdings" w:char="F0A8"/>';
        $Ckeck = '<w:sym w:font="Wingdings" w:char="F0FE"/>';
        
        if ($val == '1') {
            return $Ckeck;
        }
        return $notCkeck;
    }

}
    