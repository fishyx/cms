<style type="text/css">
@import '<?php echo $_CSS ?>/err.css';
</style>

<script type="text/javascript">
function showExceptionBacktrace(){
	var backtrace = document.getElementById('detailErrMsg');
	backtrace.style.display = (backtrace.style.display == '') ? 'none' : '';
}
</script>

<div id="errPage">
	<h4><?= $errmsg['title'] ?></h4>
	<div class="cnt"> 
		您可以尝试以下操作:
		<ul>
			<li><a href="###" onclick="window.history.back()">返回前一页面</a></li>
			<li><a href="###" onclick="window.location.reload();">重试当前操作</a></li>
			<?php if ($errmsg['info']){ ?>
			<li>
				<a href="###" onclick="showExceptionBacktrace()">查看详细错误信息</a>
				<div id="detailErrMsg" style="display:none;">
					<?= $errmsg['info'] ?>
				</div>
			</li>
			<?php } ?>
		</ul>
	</div>
</div>
