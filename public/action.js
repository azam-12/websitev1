$(document).ready(function() {
    


    // get data
    const getData=()=>{
        $.ajax({
            url:'/get',
            type:'GET',
            success:(data)=>{
                $('#get-data').html(data);
            }
        })
    }
    getData();



    // insert data
    $('#save').on('submit', function(e) {
        e.preventDefault();
        const title = $('#title').val();
        const image = $('#image').val();
        const description = $('#description').val();

        const formdata = new FormData(this);

        if(title == '' || image == '' || description == ''){
            alert("Please fill the field");
        }
        else{
            $.ajax({
                url:"/create",
                type:"POST",
                data:formdata,
                processData:false,
                contentType:false,
                success:(data)=>{
                    if(data == 1){
                        alert("Data inserted successfully.");
                        $('#save').trigger('reset');
                        $('#myModal').modal('hide');
                        getData();
                    }
                    else{
                        alert("Data insertion failed.");
                    }
                }
            })
        }
    });



    // edit data
    $(document).on('click', '#edit-product', function(){
        const id = $(this).data('id');
        $.ajax({
            url:"/edit",
            type:"POST",
            data:{id:id},
            success:(data)=>{
                $("#get-product-form").html(data);
                
            }
        })
    })


    // update data
    $('#update').on('submit', function(e) {
        e.preventDefault();
        const title = $('#edit_title').val();
        const image = $('#new_image').val();
        const description = $('#edit_description').val();

        const formdata = new FormData(this);
        //image == "" ||
        if(title == "" || description == ""){
            alert("Please fill the field");
        }
        else{
            $.ajax({
                url:"/update",
                type:"POST",
                data:formdata,
                processData:false,
                contentType:false,
                success:(data)=>{
                    if(data == 1){
                        alert("Data updated successfully.");
                        $('#update').trigger('reset');
                        $('#update-product').modal('hide');
                        getData();
                    }
                    else{
                        alert("Data updation failed.");
                    }
                }
            })
        }
    });


    // delete data
    $(document).on('click', '#delete-product', function(){
        const id = $(this).data('id');
        $.ajax({
            url:"/delete",
            type:"POST",
            data:{id:id},
            success:(data)=>{
                if(data == 1){
                    alert("delete successful");
                    getData();
                }
                else{
                    alert("delete failed!");
                }
            }
        })
    });


});