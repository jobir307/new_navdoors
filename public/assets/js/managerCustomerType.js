$('body').on('change', 'select#customer_type', function() {
    let customerType = $(this).val();
    if(customerType == "Yuridik")
        $(".inn_div").css("display", "block");
    else
        $(".inn_div").css("display", "none");
});