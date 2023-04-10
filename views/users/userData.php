<h1>User Data</h1>


<script src="https://code.jquery.com/jquery-3.6.4.min.js" integrity="sha256-oP6HI9z1XaZNBrJURtCoUT5SUnxFr8s3BzRl+cbzUq8=" crossorigin="anonymous"></script>
<script src="/js/jquery.js"></script>
<script>
    $.ajax({
        url: "/users/userData",
        method: "GET",
        // data: {
        // id: 1,
        // name: "John Doe",
        // email: "john@example.com",
        // },
        dataType: "json",
        success: function(response) {
            console.log(response);
        },
        error: function(error) {
            console.log(error);
        },
    });
</script>