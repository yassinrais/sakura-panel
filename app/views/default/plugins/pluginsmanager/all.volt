<div class="col-12">
	<div class="row plugins-list">
		<div class="col-12 p-5 text-center">
			<h2><i class="fas fa-fw fa-spin fa-sync"></i> Loading ...</h2>
		</div>
	</div>
</div>

<div class="plugin-tpl col-12" style="display:none;">
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
            	<div class="plugin-logo" style="background-image: url([image]);" ></div>
            </div>
          </div>
          <div class="pt-1 float-right">
          	<button data-id="[name]" style="display:none" class="btn btn-install btn-sm btn-success"><i class="fa fa-download"></i> Install</button>
          	<button data-id="[name]" style="display:none" class="btn btn-update btn-sm btn-warning"><i class="fa fa-calendar"></i> Update</button>
          	<button data-id="[name]" style="display:none" class="btn btn-delete btn-sm btn-danger"><i class="fa fa-trash"></i> Delete</button>
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
					let plugins = data.data.plugins;
					for(var d in plugins){
						let p = plugins[d];
						let _tpl_ = tpl;

						// to replace after xd : this is very very bad :p but no more wasting time
						_tpl_ = _tpl_.replace(/\[name\]/gm, p.config.name);
						_tpl_ = _tpl_.replace(/\[title\]/gm, p.config.title);
						_tpl_ = _tpl_.replace(/\[description\]/gm, p.config.description);
						_tpl_ = _tpl_.replace(/\[image\]/gm, p.config.image);
						_tpl_ = _tpl_.replace(/\[version\]/gm, p.config.version);

						let domTpl = $(_tpl_);

						if (p.installed) 
							domTpl.find('.btn-install').hide();
						if (p.active || p.installed) 
							{
								domTpl.find('.btn-delete').show();
								domTpl.find('.btn-update').show();
							}
						if (!p.installed) {
							domTpl.find('.btn-install').show();
						}

						l.append(domTpl);
					}
				}else{
					l.append('<div class="col-12 p-1 form-group msgs"></div>');
					let em = l.find('.msgs');
					for(var m in data.msg){
						em.append(`<div class='alert alert-${m}'>${data.msg[m]}</div>`);
					}
				}
			});
		}


		$('body').on('click', '.btn-install', function () {
			// install plugin

		});

		$('body').on('click', '.btn-delete', function () {
			// delete plugin :: working in ::
			let id = $(this).data('id');


			$.post('{{ page.get('base_route') }}/deletePlugin/'+id , function (a) {
				alert(a);
			});

		});
	 });
});
</script>