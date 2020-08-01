<div class="col-12">
	<div class="row plugins-list">
		
	</div>
</div>

<div class="plugin-tpl col-12" style="display:">
	<div class="col-xl-4 col-md-6 mb-4">
      <div class="card border-left-primary shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">[name] [version]</div>
              <div class="h5 mb-0 font-weight-bold text-gray-800">[title]</div>
              <div class="pt-1 mb-0 font-weight-bold text-gray-600">[description]</div>
            </div>
            <div class="col-auto">
            	<div class="bg-sakura" style="background: url([image])" width=50 height=50></div>
            </div>
          </div>
          <div class="pt-1 float-right">
          	<button class="btn btn-sm btn-success"><i class="fa fa-download"></i> Install</button>
          	<button class="btn btn-sm btn-warning"><i class="fa fa-calendar"></i> Update</button>
          	<button class="btn btn-sm btn-danger"><i class="fa fa-trash"></i> Delete</button>
          </div>
        </div>
      </div>
    </div>
</div>

<script type="text/javascript">
window.addEventListener("DOMContentLoaded", (event) => {
	$(function () {
		let l= $('.plugins-list');
		let e= $('.plugin-tpl');
		let tpl = e.html();

		e.remove();


		$(document).ready(function () {
			refresh_plugins();
		});

		function refresh_plugins(){
			$.get('{{ page.get('base_route') }}/ajaxAll' , function(data) {
				// l.slideDown();
				l.html("");
				if (data.status === 'success') {
					for(var d in data.data){
						let p = data.data[d];
						let _tpl_ = tpl;
						for(var x in p)
							_tpl_ = _tpl_.replace(`[`+x+`]`,p[x]);
						l.append(_tpl_);
					}
				}else{
					for(var m in data.msg){
						let t = data.msg[m];
						for(var n in t)
						{
							l.append("<div class='col-12 alert alert-danger'>"+(t[n])+"</div>");
						}
					}
				}
			});
		}
	 });
});
</script>