function getAllunAssignedCitiesToDriver(elem) {
  $.ajax({
    url: "script/_getAllunAssignedCitiesToDriver.php",
    type: "POST",
    beforeSent: function () {},
    success: function (res) {
      elem.html("");
      console.log(res);
      $.each(res.data, function () {
        elem.append(
          "<option value='" + this.id + "'>" + this.name + "</option>"
        );
      });
      elem.selectpicker("refresh");
    },
    error: function (e) {
      elem.append("<option value='' class='bg-danger'>خطا</option>");
      console.log(e);
    },
  });
}
