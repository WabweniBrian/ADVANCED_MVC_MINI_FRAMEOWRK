$.ajax({
  url: "/users/update",
  method: "POST",
  data: {
    id: 1,
    name: "John Doe",
    email: "john@example.com",
  },
  dataType: "json",
  success: function (response) {
    console.log(response);
  },
  error: function (error) {
    console.log(error);
  },
});
