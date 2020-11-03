<div class="col-12">
    <!-- DataTales Example -->
    <div class="card shadow mt-0 mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold">Permissions</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="permissions-dataTable" width="100%" cellspacing="0">
                    <thead>
                        <th data-name="title">Resource</th>
                        <th data-name="access">Access</th>

                        <!-- dynamic -->
                        {% for role in roles %}
                            <th data-name="{{ role.name }}"><i class="fa fa-circle"></i> {{ role.title }}</th>
                        {% endfor %}
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function(event) { 
        $('#permissions-dataTable').DataTable({
            serverSide: true,
            ajax: {
                url: '/admin/permissions/ajax',
                method: 'POST'
            },
            columns:[
                {data: 'resource'},
                {data: 'access'},
                {% for role in roles %}
                    {data: 'role_{{ role.name }}'},
                {% endfor %}
            ]
        });
    });
</script>