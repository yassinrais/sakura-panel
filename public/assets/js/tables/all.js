$('#users-dataTable').DataTable({
    serverSide: true,
    ajax: {
        url: '/admin/users/ajax',
        method: 'POST'
    },
    columns: [
        {data: "id"},
        {data: "fullname"},
        {data: "email"},
        {data: "c_role"},
        {data: "c_status"},
        {data: "c_actions"},
    ]
});
$('#roles-dataTable').DataTable({
  serverSide: true,
  ajax: {
      url: '/admin/roles/ajax',
      method: 'POST'
  },
  columns: [
      {data: "id"},
      {data: "title"},
      {data: "name"},
      {data: "inherit"},
      {data: "c_status"},
      {data: "c_actions"},
  ]
});
$('#wsettings-dataTable').DataTable({
    serverSide: true,
    ajax: {
        url: '/admin/website-settings/ajax',
        method: 'POST'
    },
    columns: [
        {data: "id"},
        {data: "key"},
        {data: "val"},
        {data: "type"},
        {data: "c_actions"},
    ]
});

$('#theme-dataTable').DataTable({
  serverSide: true,
  ajax: {
      url: '/admin/website-theme/ajax',
      method: 'POST'
  },
  columns: [
      {data: "name"},
      {data: "size"},
      {data: "type"},
      {data: "c_actions"},
  ]
});

$('body').on('click','.table-action-btn' , function () {
   let e = $(this);

   let id = e.data('id');
   let action =  e.data('action');
   let title = e.attr('title') ? e.attr('title') : action;
   let path = e.data('path');
   let cb = e.data('cb');

   const tableAction = Swal.mixin({
      customClass: {
        confirmButton: 'btn btn-success',
        cancelButton: 'btn btn-danger'
      },
      buttonsStyling: true
    })

    tableAction.fire({
      title: 'Are you sure?',
      text: `Do you confirm to \`${title}\` ${id} ?`,
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Yes, do it!',
      cancelButtonText: 'No, cancel!',
      reverseButtons: true,
      showLoaderOnConfirm: true,
    }).then((result) => {
      if (result.value) {
          $.post(location.protocol + '//' + location.host + ( ( path ? path : location.pathname ) + '/' + action).replace(/\/\// , '/') , {
              id : id,
              confirm : true
            }, (a)=>{
                let data = {};
                try{
                     data = JSON.parse(a);
                }catch(e){
                     data = a;
                }
                tableAction.fire(
                  (title.charAt(0).toUpperCase()) + title.substr(1,title.length) + ' Action',
                  typeof data.msg === "string" ? data.msg : (data.msg[data.status] ? data.msg[data.status] : "Error parsing response message"),
                  (data.status !== "danger") ? data.status : "error"
                );
                // we just reload all table :p
                if ($('table')) 
                   $('table').DataTable().ajax.reload();

                if (cb) 
                  eval(cb);
            }
          );
      } else if (
        /* Read more about handling dismissals below */
        result.dismiss === Swal.DismissReason.cancel
      ) {
        tableAction.fire(
          'Cancelled',
          'This request was cancelled successfully :)',
          'error'
        )
      }
    });
});


 