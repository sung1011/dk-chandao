<?php
/**
 * TO module/statis/control.php
 */

class statis extends control
{
    public function __construct($moduleName = '', $methodName = '')
    {
        parent::__construct($moduleName, $methodName);
        $this->loadModel('bug');
        $this->loadModel('report');
        $this->loadModel('webhook');
    }

    public function daily($product)
    {
        $ret = [];
        $charts = [
            'openedBugsPerDay' => '当日新增', // 每日新增 -- 当日
            'resolvedBugsPerDay' => '当日解决', // 每日解决 -- 当日
            'closedBugsPerDay' => '当日关闭', // 每日关闭 -- 当日
            'bugsPerStatus' => '所有bug状态', // bug状态 -- 已解决和未解决
            'bugsPerSeverity' => '未解决严重程度', // bug严重程度 -- 未解决的严重程度
            'bugsPerAssignedTo' => '未解决的指派人', // bug指派人 -- 未解决的指派人
        ];

        foreach ($charts as $chart => $trans) {
            $chartFunc   = 'getDataOf' . $chart;
            switch ($chart) {
                case 'bugsPerSeverity':
                case 'bugsPerAssignedTo':
                    $q = sprintf('product=%s AND status=\'active\' AND deleted=\'0\'', $product);
                    break;
                
                default:
                    $q = sprintf('product=%s AND deleted=\'0\'', $product);
                    break;
            }
            $_SESSION['bugQueryCondition'] = $q;
            $this->bug->session->bugOnlyCondition = $q;
            $this->bug->session->bugQueryCondition = $q;
            $chartData = $this->bug->$chartFunc();

            switch ($chart) {
                case 'openedBugsPerDay':
                case 'resolvedBugsPerDay':
                case 'closedBugsPerDay':
                    $rs = array_pop($chartData)->value;
                    break;
                case 'bugsPerStatus':
                case 'bugsPerSeverity':
                    case 'bugsPerAssignedTo':
                    $rs = new \stdClass();
                    ksort($chartData);
                    foreach ($chartData as $row) {
                        $rs->{$row->name} = $row->value;
                    }
                    break;
                default:
                    $rs = $chartData;
                    break;
            }
            $ret[$trans]  = $rs;
        }
        // print_r($ret);
        $webhook = $this->webhook->getByID(1);
        
        $title = 'DAILY BUG';
        $obj = $this->webhook->getDingdingData($title, $ret, '');
        $obj->markdown->text = $this->arrToMarkdown(json_decode(json_encode($obj->markdown->text), true));
        $result = $this->webhook->fetchHook($webhook, json_encode($obj));
        // print_r($result);
    }

    private function arrToMarkdown($arr)
    {
        $p1 = '# ';
        $p3 = '### ';
        $p5 = '##### ';
        $bold = "**";
        $italic = "*";
        $text = '';
        foreach ($arr as $k => $v) {
            $text .= $p3 . $k ."\n";
            if (is_array($v)) {
                foreach ($v as $vk => $vv) {
                    $text .= $italic . $vk . $italic . " : " . $vv . "\n\n";
                }
            } else {
                $text .= $v . "\n";
            }
        }
        return $text;
    }
}
