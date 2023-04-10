$.ajax({
  url: "/users/userData",
  method: "GET",
  //   data: {
  //     id: 1,
  //     name: "John Doe",
  //     email: "john@example.com",
  //   },
  dataType: "json",
  success: function (response) {
    console.log(response);
  },
  error: function (error) {
    console.log(error);
  },
});
