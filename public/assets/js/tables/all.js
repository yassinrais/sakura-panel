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

   const tableAction = Swal.mixin({
      customClass: {
        confirmButton: 'btn btn-success',
        cancelButton: 'btn btn-danger'
      },
      buttonsStyling: true
    })

    tableAction.fire({
      title: 'Are you sure?',
      text: `Do you confirm to ${action} Row ${id} ?`,
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Yes, do it!',
      cancelButtonText: 'No, cancel!',
      reverseButtons: true,
      showLoaderOnConfirm: true,
    }).then((result) => {
      if (result.value) {
          $.post(location.protocol + '//' + location.host + (location.pathname + '/' + action).replace(/\/\// , '/') , {
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
                  data.msg,
                  data.status
                );
                // we just reload all table :p
               $('table').DataTable().ajax.reload();
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
