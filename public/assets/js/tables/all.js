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