<?php //定义菜单数组$list = [			'exam'=>[						['action'=>'exams','title'=>'考试信息管理'],						['action'=>'questions','title'=>'试题库'],						['action'=>'exams','title'=>'试卷库'],						['action'=>'','title'=>'成绩查询批改'],						['action'=>'','title'=>'统计分析'],					],			'user'=>[						['action'=>'user','title'=>'考生信息'],						['action'=>'questions','title'=>'注册设置'],					],		];$this->apps = $this->G->make('apps','core');$apps = $this->apps->getAppList();?><div class="viewFrameWork-sidebar">            <div class="sidebar-inner">             	<div class="sidebar-fold icon-unfold" id="sidebar-fold" data-toggle="tooltip" data-placement="right" data-container="body" title="" data-original-title="收起导航">                    <i class="icons8 icons8-icon"></i>                </div>                     <div class="sidebar-nav">                    <ul class="sidebar-trans" id="ksxAdminSidebar">                    	<li class="nav-item nav-item-index nav-item-active" id="navItemIndex">                            <a href="index.php?core-master" data-toggle="tooltip" data-placement="right" data-container="body" title="" data-original-title="首页">                                <div class="nav-icon">                                    <i class="icon icon-a_nav_home icon-index"></i>                                </div>                                <span class="nav-title">首页</span>                            </a>                        </li>                        <?php foreach ($apps as $k => $v) { ?>                        	<?php if ($v['appstatus'] && $v['appid'] != 'core') { ?>                        		  <li class="nav-item has-sub-menu" id="navItem0">	                        		  	<a href="#" class="menu-title" data-toggle="tooltip" data-placement="right" data-container="body" title="考试管理">	                        		  		<div class="nav-icon">	                        		  			<i class="icon icon-a_nav_exam"></i>	                        		  		</div>	                        		  		<span class="nav-title"><?php echo $v['appname']?></span>	                        		  	</a>	                        		  	<ul class="sub-menu animate">                                      	                        		  		<?php foreach ($list[$v['appid']] as $k1 => $v1) {?>	                        		  			<li class="nav-item sub-nav-item" id="subNavItem00">	                        		  				<a href="index.php?<?php echo $v['appid'];?>-master-<?php echo $v1['action'];?>">	                        		  				<span class="nav-title"><?php echo $v1['title'];?></span></a>	                        		  			</li>	                        		  		<?php }?>	                        		  			                        		  	</ul>                        		  	</li>                        	<?php }?>                        <?php }?>                 	</ul>                </div>            </div>            <div class="sidebar-bottom sidebar-btn">                <ul class="sidebar-trans">                    <li class="nav-item">                        <a class="sidebar-bottom-wrap" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">                            <div class="nav-icon">                                <i class="icon icon-a_nav_help"></i>                            </div>                            <span class="nav-title">帮助                            <img class="new-svg" src="https://admin.kaoshixing.com/static/admin/images/a_nav_new.svg" alt="">                        </span>                        </a>                        <div class="dropdown-menu company-info-panel person-servers-panel">                            <div class="btn-help">                                <i class="icon icon-a_help_document"></i>                                <p><a href="https://www.kancloud.cn/exam-star/helpdoc/483440">帮助文档</a></p>                            </div>                            <div class="btn-service">                                <i class="icon icon-a_help_service"></i>                                <span>人工客服</span>                                <p>                                    可<a href="http://wpa.qq.com/msgrd?v=201806271557&amp;uin=3236665960&amp;site=qq&amp;menu=yes">在线咨询</a>或拔打电话<span>400-870-1477</span>                                </p>                            </div>                        </div>                    </li>                    <li class="nav-item nav-item-user">                        <a class="sidebar-bottom-wrap" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">                            <div class="nav-icon">                                <i class="icon icon-a_nav_my"></i>                            </div>                            <span class="nav-title">暗暗上</span>                        </a>                        <div class="dropdown-menu company-info-panel">                            <ul class="info-header">                                <li class="nav-state-icon nav-state-online" style="border-right: 1px solid #f7f7f7;">                                    <div id="stateUserCountBar" class="stateCountBar" role="progressbar" aria-valuenow="4.0" aria-valuemin="0" aria-valuemax="100" style="width: 4.0%">                                        <span class="state-title">用户数量</span><span class="state-user-count" id="stateUserCount" style="position: absolute">1&nbsp;/&nbsp;25</span>                                    </div>                                </li>                                <li class="nav-state-icon nav-state-online">                                    <div id="stateOnlineStoreBar" class="stateCountBar" role="progressbar" aria-valuenow="0.0" aria-valuemin="0" aria-valuemax="100" style="width: 0.0%">                                        <span class="state-title">存储空间</span><span class="state-online-store" id="stateOnlineStore" style="position: absolute">0.0&nbsp;/&nbsp;1024.0MB</span>                                    </div>                                </li>                            </ul>                            <div class="info-content">                                <div class="info-content-img">                                    <img src="http://thirdwx.qlogo.cn/mmopen/vi_32/Q0j4TwGTfTKhibjoLW7YAGpwj9eYTHwkLbribjSxiaJWDRibQdEvbicgWlyngpz5RzrEfsn9zvolUfRnhM6zczluWVw/132" class="user_img" alt="">                                </div>                                <div class="info-content-wrap">                                    <div class="user" id="user">                                        <span>暗暗1上</span>                                        <a href="https://admin.kaoshixing.com/admin/admin_information" class="icons8-edit-property information-edit">                                                                                    </a>                                    </div>                                    <div class="company">                                        暗暗上                                    </div>                                    <a role="button" class="btn btn-default exam-entrance" href="https://exam.kaoshixing.com/exam">                                        <i class="icons8-icon-6 btn-icon-left"></i>                                        考试入口                                    </a>                                    <a role="button" class="btn btn-danger btn-exit" id="logoutBtn">                                        <i class="icons8-shutdown btn-icon-left"></i>                                        退出                                    </a>                                </div>                            </div>                        </div>                    </li>                </ul>            </div>        </div>