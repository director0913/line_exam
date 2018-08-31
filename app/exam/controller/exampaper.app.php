<?php
/*
 * Created on 2016-5-19
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
class action extends app
{
	public function display()
	{
		$action = $this->ev->url(3);
		if(!method_exists($this,$action))
		$action = "index";
		$this->$action();
		exit;
	}

	private function reload()
	{
		$args = array('examsessionkey' => 0);
		$this->exam->modifyExamSession($args);
		header("location:index.php?exam-app-exampaper");
	}

	private function sign()
	{
		$sessionvars = $this->exam->getExamSessionBySessionid();
		$questype = $this->basic->getQuestypeList();
		$this->tpl->assign('questype',$questype);
		$this->tpl->assign('sessionvars',$sessionvars);
		$this->tpl->display('exampaper_sign');
	}

	private function ajax()
	{
		switch($this->ev->url(4))
		{
			//获取剩余考试时间
			case 'getexampaperlefttime':
			$sessionvars = $this->exam->getExamSessionBySessionid();
			$lefttime = 0;
			if($sessionvars['examsessionstatus'] == 0 && $sessionvars['examsessiontype'] == 1)
			{
				if(TIME > ($sessionvars['examsessionstarttime'] + $sessionvars['examsessiontime']*60))
				{
					$lefttime = $sessionvars['examsessiontime']*60;
				}
				else
				{
					$lefttime = TIME - $sessionvars['examsessionstarttime'];
				}
			}
			echo $lefttime;
			exit();
			break;

			case 'saveUserAnswer':
			$question = $this->ev->post('question');
			foreach($question as $key => $t)
			{
				if($t == '')unset($question[$key]);
			}
			$this->exam->modifyExamSession(array('examsessionuseranswer'=>$question));
			echo is_array($question)?count($question):0;
			exit;
			break;

			default:
		}
	}

	private function view()
	{
		$sessionvars = $this->exam->getExamSessionBySessionid();
		if($sessionvars['examsessiontype'] != 1)
		{
			if($sessionvars['examsessiontype'])
			header("location:index.php?exam-app-exam-view");
			else
			header("location:index.php?exam-app-exercise-view");
			exit;
		}
		$this->tpl->assign('questype',$this->basic->getQuestypeList());
		$this->tpl->assign('sessionvars',$sessionvars);
		$this->tpl->display('exampaper_view');
	}

	private function makescore()
	{
		$questype = $this->basic->getQuestypeList();
		$sessionvars = $this->exam->getExamSessionBySessionid();
		if($this->ev->get('makescore'))
		{
			$score = $this->ev->get('score');
			$sumscore = 0;
			if(is_array($score))
			{
				foreach($score as $key => $p)
				{
					$sessionvars['examsessionscorelist'][$key] = $p;
				}
			}
			foreach($sessionvars['examsessionscorelist'] as $p)
			{
				$sumscore = $sumscore + floatval($p);
			}

			$sessionvars['examsessionscore'] = $sumscore;
			$args['examsessionscorelist'] = $sessionvars['examsessionscorelist'];
			$allnumber = floatval(count($sessionvars['examsessionscorelist']));
			$args['examsessionscore'] = $sessionvars['examsessionscore'];
			$args['examsessionstatus'] = 2;
			$this->exam->modifyExamSession($args);
			if(!$sessionvars['examsessionissave'])
			{
				$id = $this->favor->addExamHistory();
			}
			if($this->ev->get('direct'))
			{
				header("location:index.php?exam-app-history-view&ehid={$id}");
				exit;
			}
			else
			{
				$message = array(
					'statusCode' => 200,
					"message" => "操作成功",
				    "callbackType" => 'forward',
				    "forwardUrl" => "index.php?exam-app-history-view&ehid={$id}"
				);
				$this->G->R($message);
			}
		}
		else
		{
			$ehid = $this->ev->get('ehid');
			$eh = $this->favor->getExamHistoryById($ehid);
			$sessionvars = array(
				'examsession' => $eh['ehexam'],
				'examsessiontype'=> $eh['ehtype'] == 2?1:$eh['ehtype'],
				'examsessionsetting'=> $eh['ehsetting'],
				'examsessionbasic'=> $eh['ehbasicid'],
				'examsessionquestion'=> $eh['ehquestion'],
				'examsessionuseranswer'=>$eh['ehanswer'],
				'examsessiontime'=> $eh['ehtime'],
				'examsessionscorelist'=> $eh['ehscorelist'],
				'examsessionscore'=>$eh['ehscore'],
				'examsessionstarttime'=>$eh['ehstarttime']
			);
			$number = array();
			$right = array();
			$score = array();
			$allnumber = 0;
			$allright = 0;
			foreach($questype as $key => $q)
			{
				$number[$key] = 0;
				$right[$key] = 0;
				$score[$key] = 0;
				if($sessionvars['examsessionquestion']['questions'][$key])
				{
					foreach($sessionvars['examsessionquestion']['questions'][$key] as $p)
					{
						$number[$key]++;
						$allnumber++;
						if($sessionvars['examsessionscorelist'][$p['questionid']] == $sessionvars['examsessionsetting']['examsetting']['questype'][$key]['score'])
						{
							$right[$key]++;
							$allright++;
						}
						$score[$key] = $score[$key]+$sessionvars['examsessionscorelist'][$p['questionid']];
					}
				}
				if($sessionvars['examsessionquestion']['questionrows'][$key])
				{
					foreach($sessionvars['examsessionquestion']['questionrows'][$key] as $v)
					{
						foreach($v['data'] as $p)
						{
							$number[$key]++;
							$allnumber++;
							if($sessionvars['examsessionscorelist'][$p['questionid']] == $sessionvars['examsessionsetting']['examsetting']['questype'][$key]['score'])
							{
								$right[$key]++;
								$allright++;
							}
							$score[$key] = $score[$key]+$sessionvars['examsessionscorelist'][$p['questionid']];
						}
					}
				}
			}
			$this->tpl->assign('ehid',$ehid);
			$this->tpl->assign('allright',$allright);
			$this->tpl->assign('allnumber',$allnumber);
			$this->tpl->assign('right',$right);
			$this->tpl->assign('score',$score);
			$this->tpl->assign('number',$number);
			$this->tpl->assign('questype',$questype);
			$this->tpl->assign('sessionvars',$sessionvars);
			$this->tpl->display('exampaper_score');
		}
	}

	private function score()
	{
		$questype = $this->basic->getQuestypeList();
		$sessionvars = $this->exam->getExamSessionBySessionid();
		$needhand = 0;
		if($this->ev->get('insertscore'))
		{
			$question = $this->ev->get('question');
			foreach($question as $key => $a)
			$sessionvars['examsessionuseranswer'][$key] = $a;
			foreach($sessionvars['examsessionquestion']['questions'] as $key => $tmp)
			{
				if(!$questype[$key]['questsort'])
				{
					foreach($tmp as $p)
					{
						if(is_array($sessionvars['examsessionuseranswer'][$p['questionid']]))
						{
							$nanswer = '';
							$answer = $sessionvars['examsessionuseranswer'][$p['questionid']];
							asort($answer);
							$nanswer = implode("",$answer);
							if($nanswer == $p['questionanswer'])$score = $sessionvars['examsessionsetting']['examsetting']['questype'][$key]['score'];
							else
							{
								if($questype[$key]['questchoice'] == 3)
								{
									$alen = strlen($p['questionanswer']);
									$rlen = 0;
									foreach($answer as $t)
									{
										if(strpos($p['questionanswer'],$t) === false)
										{
											$rlen = 0;
											break;
										}
										else
										{
											$rlen ++;
										}
									}
									$score = floatval($sessionvars['examsessionsetting']['examsetting']['questype'][$key]['score'] * $rlen/$alen);
								}
								else $score = 0;
							}
						}
						else
						{
							$answer = $sessionvars['examsessionuseranswer'][$p['questionid']];
							if($answer == $p['questionanswer'])$score = $sessionvars['examsessionsetting']['examsetting']['questype'][$key]['score'];
							else $score = 0;
						}
						$scorelist[$p['questionid']] = $score;
					}
				}
				else
				{
					if(is_array($tmp) && count($tmp))
					$needhand = 1;
				}
			}
			foreach($sessionvars['examsessionquestion']['questionrows'] as $key => $tmp)
			{
				if(!$questype[$key]['questsort'])
				{
					foreach($tmp as $tmp2)
					{
						foreach($tmp2['data'] as $p)
						{
							if(is_array($sessionvars['examsessionuseranswer'][$p['questionid']]))
							{
								$answer = $sessionvars['examsessionuseranswer'][$p['questionid']];
								asort($answer);
								$nanswer = implode("",$answer);
								if($nanswer == $p['questionanswer'])$score = $sessionvars['examsessionsetting']['examsetting']['questype'][$key]['score'];
								else
								{
									if($questype[$key]['questchoice'] == 3)
									{
										$alen = strlen($p['questionanswer']);
										$rlen = 0;
										foreach($answer as $t)
										{
											if(strpos($p['questionanswer'],$t) === false)
											{
												$rlen = 0;
												break;
											}
											else
											{
												$rlen ++;
											}
										}
										$score = $sessionvars['examsessionsetting']['examsetting']['questype'][$key]['score'] * $rlen/$alen;
									}
									else $score = 0;
								}
							}
							else
							{
								$answer = $sessionvars['examsessionuseranswer'][$p['questionid']];
								if($answer == $p['questionanswer'])$score = $sessionvars['examsessionsetting']['examsetting']['questype'][$key]['score'];
								else $score = 0;
							}
							$scorelist[$p['questionid']] = $score;
						}
					}
				}
				else
				{
					if(!$needhand)
					{
						if(is_array($tmp) && count($tmp))
						$needhand = 1;
					}
				}
			}
			$args['examsessionuseranswer'] = $question;
			$args['examsessionscorelist'] = $scorelist;
			if(!$needhand)
			{
				$args['examsessionstatus'] = 2;
				$this->exam->modifyExamSession($args);
				$message = array(
					'statusCode' => 200,
					"message" => "操作成1功",
				    "callbackType" => 'forward',
				    "forwardUrl" => "index.php?exam-app-exampaper-makescore&makescore=1&direct=1"
				);
			}
			else
			{
				if($sessionvars['examsessionsetting']['examdecide'])
				{
					$args['examsessionstatus'] = 2;
					$this->exam->modifyExamSession($args);
					$id = $this->favor->addExamHistory(0,0);
					$message = array(
						'statusCode' => 200,
						"message" => "操作成功，本试卷需要教师评分，请等待评分结果",
					    "callbackType" => 'forward',
					    "forwardUrl" => "index.php?exam-app-history&ehtype=1"
					);
				}
				else
				{
					$args['examsessionstatus'] = 1;
					$this->exam->modifyExamSession($args);
					$message = array(
						'statusCode' => 200,
						"message" => "操作成功",
					    "callbackType" => 'forward',
					    "forwardUrl" => "index.php?exam-app-exampaper-score"
					);
				}
			}
			$this->G->R($message);
		}
		else
		{
			if($sessionvars['examsessionstatus'] == 2)
			{
				header("location:index.php?exam-app-exampaper-makescore");
				exit;
			}
			else
			{
				$this->tpl->assign('sessionvars',$sessionvars);
				$this->tpl->assign('questype',$questype);
				$this->tpl->display('exampaper_mkscore');
			}
		}
	}
	private function collect(){
		if($this->ev->post('submit')){
			$info['uname'] = $this->ev->post('uname');
			$info['idcard'] = $this->ev->post('idcard');
			$info['date'] = $this->ev->post('date');
			$forward = $this->ev->post('forward');
			if(!$info['uname'] || !$info['idcard'] || !$info['date'])
			{	
				$message = array(
					'statusCode' => 200,
					"message" => "信息填写不全，请重新填写！",
				    "callbackType" => 'forward',
				    "forwardUrl" => "index.php?exam-app-exampaper-collect"
				);
				$this->G->R($message);
				exit();	
			}
			if (!$this->isCreditNo($info['idcard'])) {
				$message = array(
					'statusCode' => 200,
					"message" => "身份证信息填写错误，请重新填写！",
				    "callbackType" => 'forward',
				    "forwardUrl" => "index.php?exam-app-exampaper-collect"
				);
				$this->G->R($message);
				exit();
			}
			$args['examsessionuserinfo'] = serialize($info);
			$res = $this->exam->modifyExamSession($args);
			$message = array(
				'statusCode' => 200,
				"message" => "填写成功，即将开始考试！",
			    "callbackType" => 'forward',
			    "forwardUrl" => "index.php?exam-app-exampaper-paper"
			);
			$this->G->R($message);
			exit();
		}else{
			$this->tpl->display('collect');	
		}
		
	}
	private function paper()
	{
		$sessionvars = $this->exam->getExamSessionBySessionid();
		$lefttime = 0;
		$questype = $this->basic->getQuestypeList();
		if($sessionvars['examsessionstatus'] == 2)
		{
			header("location:index.php?exam-app-exampaper-makescore");
			exit;
		}
		elseif($sessionvars['examsessionstatus'] == 1)
		{
			header("location:index.php?exam-app-exampaper-score");
			exit;
		}
		else
		{	
			if (!$sessionvars['examsessionuserinfo']) {
				header("location:index.php?exam-app-exampaper-collect");
			 	exit;
			}
			//$exams = $this->exam->getExamSettingList(1,3,array(array("AND","examsubject = :examsubject",'examsubject',$this->data['currentsubject']['subjectid']),array("AND","examtype = 1")));
			$this->tpl->assign('questype',$questype);
			$this->tpl->assign('sessionvars',$sessionvars);
			$this->tpl->assign('lefttime',$lefttime);
			$this->tpl->assign('donumber',is_array($sessionvars['examsessionuseranswer'])?count($sessionvars['examsessionuseranswer']):0);
			if($this->data['currentbasic']['basicexam']['autotemplate'])
			$this->tpl->display($this->data['currentbasic']['basicexam']['autotemplate']);
			else
			$this->tpl->display('exampaper_paper');
		}
	}

	private function selectquestions()
	{
		$sessionvars = $this->exam->getExamSessionBySessionid();
		$examid = $this->ev->get('examid');
		$r = $this->exam->getExamSettingById($examid);
		if(!$r['examid'])
		{
			$message = array(
				'statusCode' => 300,
				"message" => "参数错误，尝试退出后重新进入"
			);
			$this->G->R($message);
		}
		else
		{
			if($r['examtype'] == 1)
			{
				$questionids = $this->question->selectQuestions($examid,$this->data['currentbasic']);
				$questions = array();
				$questionrows = array();
				$str = '';
				foreach($questionids['question'] as $key => $p)
				{
					$ids = "";
					if(count($p))
					{
						foreach($p as $t)
						{
							$ids .= $t.',';
						}
						$ids = trim($ids," ,");
						$str .= $ids."\n";
						if(!$ids)$ids = 0;
						$questions[$key] = $this->exam->getQuestionListByIds($ids);
					}
				}
				foreach($questionids['questionrow'] as $key => $p)
				{
					$ids = "";
					if(is_array($p))
					{
						if(count($p))
						{
							foreach($p as $t)
							{
								$questionrows[$key][$t] = $this->exam->getQuestionRowsById($t);
							}
						}
					}
					else $questionrows[$key][$p] = $this->exam->getQuestionRowsById($p);
				}
				$sargs['examsessionquestion'] = array('questionids'=>$questionids,'questions'=>$questions,'questionrows'=>$questionrows);
				$sargs['examsessionsetting'] = $questionids['setting'];
				$sargs['examsessionstarttime'] = TIME;
				$sargs['examsession'] = $questionids['setting']['exam'];
				$sargs['examsessiontime'] = $questionids['setting']['examsetting']['examtime']>0?$questionids['setting']['examsetting']['examtime']:60;
				$sargs['examsessionstatus'] = 0;
				$sargs['examsessiontype'] = 1;
				$sargs['examsessionsign'] = NULL;
				$sargs['examsessionuseranswer'] = NULL;
				$sargs['examsessionbasic'] = $this->data['currentbasic']['basicid'];
				$sargs['examsessionkey'] = $examid;
				$sargs['examsessionissave'] = 0;
				$sargs['examsessionsign'] = '';
				$sargs['examsessionuserid'] = $this->_user['sessionuserid'];
				if($sessionvars['examsessionid'])
				$this->exam->modifyExamSession($sargs);
				else
				$this->exam->insertExamSession($sargs);
				$message = array(
					'statusCode' => 200,
					"message" => "抽题完毕，转入试卷页面",
				    "callbackType" => 'forward',
				    "forwardUrl" => "index.php?exam-app-exampaper-paper"
				);
				$this->G->R($message);
			}
			elseif($r['examtype'] == 2)
			{
				$sessionvars = $this->exam->getExamSessionBySessionid();
				$questions = array();
				$questionrows = array();
				foreach($r['examquestions'] as $key => $p)
				{
					$qids = '';
					$qrids = '';
					if($p['questions'])$qids = trim($p['questions']," ,");
					if($qids)
					$questions[$key] = $this->exam->getQuestionListByIds($qids);
					if($p['rowsquestions'])$qrids = trim($p['rowsquestions']," ,");
					if($qrids)
					{
						$qrids = explode(",",$qrids);
						foreach($qrids as $t)
						{
							$qr = $this->exam->getQuestionRowsById($t);
							if($qr)
							$questionrows[$key][$t] = $qr;
						}
					}
				}
				$args['examsessionquestion'] = array('questions'=>$questions,'questionrows'=>$questionrows);
				$args['examsessionsetting'] = $r;
				$args['examsessionstarttime'] = TIME;
				$args['examsession'] = $r['exam'];
				$args['examsessionscore'] = 0;
				$args['examsessionuseranswer'] = NULL;
				$args['examsessionscorelist'] = NULL;
				$args['examsessionsign'] = NULL;
				$args['examsessiontime'] = $r['examsetting']['examtime'];
				$args['examsessionstatus'] = 0;
				$args['examsessiontype'] = 1;
				$args['examsessionkey'] = $r['examid'];
				$args['examsessionissave'] = 0;
				$args['examsessionbasic'] = $this->data['currentbasic']['basicid'];
				$args['examsessionuserid'] = $this->_user['sessionuserid'];
				if($sessionvars['examsessionid'])
				$this->exam->modifyExamSession($args);
				else
				$this->exam->insertExamSession($args);
				$message = array(
					'statusCode' => 200,
					"message" => "抽题完毕，转入试卷页面",
				    "callbackType" => 'forward',
				    "forwardUrl" => "index.php?exam-app-exampaper-paper"
				);
				$this->G->R($message);
			}
			else
			{
				$sessionvars = $this->exam->getExamSessionBySessionid();
				$args['examsessionquestion'] = $r['examquestions'];
				$args['examsessionsetting'] = $r;
				$args['examsessionstarttime'] = TIME;
				$args['examsession'] = $r['exam'];
				$args['examsessionscore'] = 0;
				$args['examsessionuseranswer'] = '';
				$args['examsessionscorelist'] = '';
				$args['examsessionsign'] = '';
				$args['examsessiontime'] = $r['examsetting']['examtime'];
				$args['examsessionstatus'] = 0;
				$args['examsessiontype'] = 1;
				$args['examsessionkey'] = $r['examid'];
				$args['examsessionissave'] = 0;
				$args['examsessionbasic'] = $this->data['currentbasic']['basicid'];
				$args['examsessionuserid'] = $this->_user['sessionuserid'];
				if($sessionvars['examsessionid'])
				$this->exam->modifyExamSession($args);
				else
				$this->exam->insertExamSession($args);
				$message = array(
					'statusCode' => 200,
					"message" => "抽题完毕，转入试卷页面",
				    "callbackType" => 'forward',
				    "forwardUrl" => "index.php?exam-app-exampaper-paper"
				);
				$this->G->R($message);
			}
		}
	}

	private function index()
	{
		$page = $this->ev->get('page');
		$ids = trim($this->data['currentbasic']['basicexam']['auto'].','.$this->data['currentbasic']['basicexam']['train'],', ');
		if(!$ids)$ids = 0;
		$exams = $this->exam->getExamSettingList($page,20,array(array("AND","find_in_set(examid,:examid)",'examid',$ids)));
		$this->tpl->assign('exams',$exams);
		$this->tpl->display('exampaper');
	}
	private function isCreditNo($vStr){
		  $vCity = array(
		    '11','12','13','14','15','21','22',
		    '23','31','32','33','34','35','36',
		    '37','41','42','43','44','45','46',
		    '50','51','52','53','54','61','62',
		    '63','64','65','71','81','82','91'
		  );
		  if (!preg_match('/^([\d]{17}[xX\d]|[\d]{15})$/', $vStr)) return false;
		  if (!in_array(substr($vStr, 0, 2), $vCity)) return false;
		  $vStr = preg_replace('/[xX]$/i', 'a', $vStr);
		  $vLength = strlen($vStr);
		  if ($vLength == 18) {
		    $vBirthday = substr($vStr, 6, 4) . '-' . substr($vStr, 10, 2) . '-' . substr($vStr, 12, 2);
		  } else {
		    $vBirthday = '19' . substr($vStr, 6, 2) . '-' . substr($vStr, 8, 2) . '-' . substr($vStr, 10, 2);
		  }
		  if (date('Y-m-d', strtotime($vBirthday)) != $vBirthday) return false;
		  if ($vLength == 18) {
		    $vSum = 0;
		    for ($i = 17 ; $i >= 0 ; $i--) {
		      $vSubStr = substr($vStr, 17 - $i, 1);
		      $vSum += (pow(2, $i) % 11) * (($vSubStr == 'a') ? 10 : intval($vSubStr , 11));
		    }
		    if($vSum % 11 != 1) return false;
		  }
		  return true;
		}
}


?>
