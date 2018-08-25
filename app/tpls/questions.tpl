{x2;if:!$userhash}
{x2;include:header}
<body>
{x2;include:nav}
<div class="viewFrameWork-body">
<div class="body-wrapper">
    <div class="body-content">
        <table id="grid-data" class="table bootgrid-table JColResizer" aria-busy="false">
    <thead> 
     <tr>
      <th class="select-cell" style="width: 2.9%;"><label class="select-label"><input name="select" type="checkbox" class="select-box" value="all" {{ctx.checked}}="" /><span class="select-box"><i class="icons8-checked-checkbox"></i></span></label></th>
      <th data-column-id="type" class="text-left" style="width: 7.4%;"><a href="javascript:void(0);" class="column-header-anchor "><span class="text">题型</span><span class="icon glyphicon "></span></a></th>
      <th data-column-id="classification" class="text-left" style="width: 9.6%;"><a href="javascript:void(0);" class="column-header-anchor "><span class="text">分类</span><span class="icon glyphicon "></span></a></th>
      <th data-column-id="content" class="text-left" style="width: 41.8%;"><a href="javascript:void(0);" class="column-header-anchor "><span class="text">试题内容</span><span class="icon glyphicon "></span></a></th>
      <th data-column-id="difficult" class="text-left" style="width: 7.4%;"><a href="javascript:void(0);" class="column-header-anchor sortable"><span class="text">难度</span><span class="icon glyphicon "></span></a></th>
      <th data-column-id="creater" class="text-left" style="width: 7.4%;"><a href="javascript:void(0);" class="column-header-anchor "><span class="text">创建人</span><span class="icon glyphicon "></span></a></th>
      <th data-column-id="createTime" class="text-left" style="width: 11.8%;"><a href="javascript:void(0);" class="column-header-anchor sortable"><span class="text">创建时间</span><span class="icon glyphicon "></span></a></th>
      <th data-column-id="sender" class="text-left" style="width: 11.8%;"><a href="javascript:void(0);" class="column-header-anchor "><span class="text">操作</span><span class="icon glyphicon icons8-settings"></span></a></th>
     </tr> 
    </thead> 
    <tbody>
<!--     {x2;tree:$questions['data'],question,qid}
    <tr>
		<td>
			{x2;$questypes[v:question['questiontype']]['questype']}
		</td>
		<td>
			<a title="查看试题" class="selfmodal" href="javascript:;" url="index.php?exam-app-questions-detail&questionid={x2;v:question['questionid']}" data-target="#modal">{x2;substring:strip_tags(html_entity_decode(v:question['question'])),135}</a>
		</td>
		<td>
			{x2;if:v:question['questionlevel']==2}中{x2;elseif:v:question['questionlevel']==3}难{x2;elseif:v:question['questionlevel']==1}易{x2;endif}
		</td>
    </tr>
    {x2;endtree} -->
    {x2;tree:$questions['data'],question,qid}
     <tr data-row-id="5b778cf936098f6ee332d910">
      <td class="select-cell" style="{{ctx.style}}"><label class="select-label"><input name="select" type="checkbox" class="select-box" value="5b778cf936098f6ee332d910" /><span class="select-box"><i class="icons8-checked-checkbox"></i></span></label></td>
      <td class="text-left" style="width:8%;">{x2;$questypes[v:question['questiontype']]['questype']}</td>
      <td class="text-left" style="width:10%;">试题分类</td>
      <td class="text-left" style="">{x2;substring:strip_tags(html_entity_decode(v:question['question'])),135}</td>
      <td class="text-left" style="width:8%;">{x2;if:v:question['questionlevel']==2}中{x2;elseif:v:question['questionlevel']==3}难{x2;elseif:v:question['questionlevel']==1}易{x2;endif}</td>
      <td class="text-left" style="width:8%;">暗暗上</td>
      <td class="text-left" style="width:12%;"><?php echo date('Y-m-d H:i:s',$question['questioncreatetime'])?></td>
      <td class="text-left" style="width:12%;">
      	<a href="index.php?exam-master-questions-modifyquestion&page=&questionid=<?php echo $question['questionid']?>" class="icons8-edit updateQuestion" questionid="5b778cf936098f6ee332d910" data-toggle="tooltip" data-placement="top" data-container="body" title="" data-original-title="编辑">编辑</a>
      	<a href="index.php?exam-master-questions-delquestion&questionparent=0&page=&questionid=<?php echo $question['questionid']?>" class="icons8-trash-can removeQuestion" questionid="5b778cf936098f6ee332d910" data-toggle="tooltip" data-placement="top" data-container="body" title="" data-original-title="删除">删除</a>
      </td>
     </tr>
    {x2;endtree}
    </tbody>
   </table>
   <div id="grid-data-footer" class="bootgrid-footer container-fluid">
    <div class="row">
     <div class="col-sm-6 infoBar">
      <div class="infos">
       共24项记录
      </div>
      <div class="actions btn-group">
       <div class="btn-group">
        <button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown"><span class="dropdown-text">每页10项</span> <span class="caret"></span></button>
        <ul class="dropdown-menu pull-right" role="menu">
         <li class="active" aria-selected="true"><a href="#10" class="dropdown-item dropdown-item-button">10</a></li>
         <li aria-selected="false"><a href="#20" class="dropdown-item dropdown-item-button">20</a></li>
         <li aria-selected="false"><a href="#50" class="dropdown-item dropdown-item-button">50</a></li>
         <li aria-selected="false"><a href="#100" class="dropdown-item dropdown-item-button">100</a></li>
        </ul>
       </div>
       <div class="btn-group">
        <button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown"><span class="dropdown-text"><span class="icon glyphicon icons8-settings"></span>自定义设置</span> <span class="caret"></span></button>
        <ul class="dropdown-menu pull-right" role="menu">
         <li><label class="dropdown-item"><input name="id" type="checkbox" value="1" class="dropdown-item-checkbox" /> </label></li>
         <li><label class="dropdown-item"><input name="type" type="checkbox" value="1" class="dropdown-item-checkbox" checked="checked" /> 题型</label></li>
         <li><label class="dropdown-item"><input name="classification" type="checkbox" value="1" class="dropdown-item-checkbox" checked="checked" /> 分类</label></li>
         <li><label class="dropdown-item"><input name="content" type="checkbox" value="1" class="dropdown-item-checkbox" checked="checked" /> 试题内容</label></li>
         <li><label class="dropdown-item"><input name="difficult" type="checkbox" value="1" class="dropdown-item-checkbox" checked="checked" /> 难度</label></li>
         <li><label class="dropdown-item"><input name="creater" type="checkbox" value="1" class="dropdown-item-checkbox" checked="checked" /> 创建人</label></li>
         <li><label class="dropdown-item"><input name="createTime" type="checkbox" value="1" class="dropdown-item-checkbox" checked="checked" /> 创建时间</label></li>
         <li><label class="dropdown-item"><input name="sender" type="checkbox" value="1" class="dropdown-item-checkbox" checked="checked" /> 操作</label></li>
        </ul>
       </div>
      </div>
     </div>
     <div class="col-sm-6">
      	<ul class="pagination">
        	{x2;$questions['pages']}
    	</ul>
     
     </div>
    </div>
   </div> 
  </div>
</div>
</div>
</div>
{x2;include:footer}
</body>
</html>
{x2;endif}