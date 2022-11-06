$(document).ready(function () {
            $("#notification").click(function () {
                if ($('#count').val() != 0){
                    Swal.fire("В корзину добавлено " + $('#count').val() + " товаров");}
                else {
                    Swal.fire("Добавьте товар")
                }
            });
    
            $("#plus").click(function () {
                var $var = $('#count').val();
                $var++;
                $("#count").val($var);
            });
    
            $("#minus").click(function () {
                var $var = $('#count').val();
                if ($var > 0){
                    $var--;
                    $("#count").val($var);
                }
            });
            $(".photo").on("mouseover", function() {
                document.getElementById("main").src=this.src; 
            });
            $(".photo").on("mouseout", function() {
                document.getElementById("main").src="img/photo_1.png";
            });
});

