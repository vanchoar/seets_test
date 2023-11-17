$(document).ready(function () {
    $("#year-form").submit(function (event) {
      var formData = {
        year: $("#year").val(),
      };
  
      $.ajax({
        type: "POST",
        url: "send_info.php",
        data: formData,
        dataType: "json",
        encode: true,
      }).done(function (data) {
          // console.log(data);
          $('#table_info').empty();
          $.each(data, function(k, v) {
              $("#table_info").append('<tr> <td>' + v.year + '</td> <td>'+ v.day +' </td> </tr>');
          });
      });
  
      event.preventDefault();
    });
});