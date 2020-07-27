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