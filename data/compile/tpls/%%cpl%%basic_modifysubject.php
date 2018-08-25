<?php if(!$this->tpl_var['userhash']){ ?>
<?php $this->_compileInclude('header'); ?>
<body>
<?php $this->_compileInclude('nav'); ?>
<div class="container-fluid">
	<div class="row-fluid">
		<div class="main">
			<div class="col-xs-10" id="datacontent">
<?php } ?>
				<div class="box itembox" style="margin-bottom:0px;border-bottom:1px solid #CCCCCC;">
					<div class="col-xs-12">
						<ol class="breadcrumb">
							<li><a href="index.php?<?php echo $this->tpl_var['_app']; ?>-master"><?php echo $this->tpl_var['apps'][$this->tpl_var['_app']]['appname']; ?></a></li>
							<li><a href="index.php?<?php echo $this->tpl_var['_app']; ?>-master-basic-subject">试卷分类</a></li>
							<li class="active">修改科目</li>
						</ol>
					</div>
				</div>
				<div class="box itembox" style="padding-top:10px;margin-bottom:0px;">
					<h4 class="title" style="padding:10px;">
						修改科目
						<a class="btn btn-primary pull-right" href="index.php?exam-master-basic-subject">试卷分类</a>
					</h4>
			        <form action="index.php?exam-master-basic-modifysubject" method="post" class="form-horizontal">
						<fieldset>
							<div class="form-group">
								<label for="subject" class="control-label col-sm-2">分类名称：</label>
								<div class="col-sm-9">
									<input class="form-control" name="args[subject]" id="subject" type="text" size="30" value="<?php echo $this->tpl_var['subject']['subject']; ?>" needle="needle" msg="您必须输入一个分类名称" />
								</div>
							</div>
							<div class="form-group">
								<label for="subject" class="control-label col-sm-2">科目题型：</label>
								<div class="col-sm-9">
									<?php $qid = 0;
 foreach($this->tpl_var['questypes'] as $key => $questype){ 
 $qid++; ?>
									<label class="checkbox-inline">
						          		<input type="checkbox" name="args[subjectsetting][questypes][<?php echo $questype['questid']; ?>]" value="1"<?php if($this->tpl_var['subject']['subjectsetting']['questypes'][$questype['questid']]){ ?> checked<?php } ?>/> <?php echo $questype['questype']; ?>
						          	</label>
						          	<?php } ?>
								</div>
							</div>
							<div class="form-group">
							  	<label for="subject" class="control-label col-sm-2"></label>
							  	<div class="col-sm-9">
								  	<button class="btn btn-primary" type="submit">提交</button>
									<input type="hidden" name="modifysubject" value="1"/>
									<input type="hidden" name="subjectid" value="<?php echo $this->tpl_var['subject']['subjectid']; ?>"/>
									<input type="hidden" name="page" value="<?php echo $this->tpl_var['page']; ?>"/>
								</div>
							</div>
						</fieldset>
					</form>
				</div>
			</div>
<?php if(!$this->tpl_var['userhash']){ ?>
		</div>
	</div>
</div>
<?php $this->_compileInclude('footer'); ?>
</body>
</html>
<?php } ?>