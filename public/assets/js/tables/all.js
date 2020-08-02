$('#users-dataTable').DataTable({
    serverSide: true,
    ajax: {
        url: '/member/users/ajax',
        method: 'POST'
    },
    columns: [
        {data: "id"},
        {data: "fullname"},
        {data: "email"}
    ]
});
$('#wsettings-dataTable').DataTable({
    serverSide: true,
    ajax: {
        url: '/member/website-settings/ajax',
        method: 'POST'
    },
    columns: [
        {data: "id"},
        {data: "key"},
        {data: "val"},
        {data: "type"},
        {data: "actions"}
    ]
});

$('body').on('click','.table-action-btn' , function () {
   let e = $(this);

   let id = e.data('id');
   let action = e.data('action');
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
      text: `Do you confirm to ${action} ${id} ?`,
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
                  (action.charAt(0).toUpperCase()) + action.substr(1,action.length) + ' Action',
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


 