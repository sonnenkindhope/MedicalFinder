$(document).ready(function () {
    $('#resultContainer').css("visibility", "hidden");

    var delayTime = 6000;
    var searchable = false;

    function delay(){
        setTimeout(delayTime);
        searchable = true;
    }

    $("#searchTerm").keyup(function(){

        if(this.value.length >= 3 && searchable === true) {
            $.ajax({
                type: "GET",
                url: "/symptoms/autocomplete",
                data: {
                    'param': this.value
                },
                beforeSend: function(){
                    $("#possibleTerms").html('');
                    $("#loader").css('display', 'block');
                },
                success: function(data){
                    var acList = $('#possibleTerms');
                    if (acList[0].innerHTML.length == 0 ){
                        var head = $('<p/>').html("Mögliche Symptome");
                        acList.append(head);
                        $.each(data, function(index, item) {
                            var li = $('<li/>')
                                .attr('class', 'acItem')
                                .html(item.value);

                            acList.append(li);
                        });
                    }

                    $("#possibleTerms").show();
                    $("#loader").css('display', 'none');
                }
            })

            searchable = false;
        } else {
            delay();
        }

    });

    $(document).on("click",".acItem", function(e){
        $("#searchTerm").val(this.innerText);

        $("#possibleTerms").html('');
        $("#possibleTerms").hide();
    });

    $('#search').on('click', function(e){
        e.preventDefault();

        $.ajax({
            url: "/medicals",
            method: "GET",
            data: {
                'param': $('#searchTerm').val()
            },
            dataType: 'json',
            cors: true,
            secure: true,
            headers: {
                'Access-Control-Allow-Origin': '*',
            },
            beforeSend: function() {
                $('#resultContainer').css("visibility", "hidden");
                $("#loader").css('display', 'block');
                $('#searchResult').DataTable({retrieve: true,
                    searching: false,
                    paging: false,
                    info: false,
                    sort: false,}).clear().destroy();
            },

        })
        .done( function(data, textStatus, jqXHR) {
            let content = data;

            if (jqXHR.status == 204) {
                msg = '[{"msg": "Leider gibt es in unserer Praxis keinen Experten für dieses Symptom."}]';
                $('#searchResult').DataTable( {
                    retrieve: true,
                    searching: false,
                    paging: false,
                    info: false,
                    sort: false,
                    "aaData": JSON.parse(msg),
                    "columns": [
                        { "data": "msg" }
                    ]
                });
                $("#searchResult thead").remove();

            } else {
                $('#searchResult').DataTable( {
                    retrieve: true,
                    searching: false,
                    paging: false,
                    info: false,
                    sort: false,
                    "aaData": content,
                    "columns": [
                        { "data": 'name'},
                        { "data": 'field'},
                        { "data": 'phone'}
                    ]
                });

            }
            $('#resultContainer').css("visibility", "visible");
            $("#loader").css('display', 'none');
        })
        .fail( function(data, textStatus, jqXHR) {
            let msg;
            if (data.status == 400) {
                msg = '[{"msg": "Leider stimmt etwas mit der Sucheingabe nicht. Bitte überprüfen!"}]';
            }
            else {
                msg = '[{"msg": "Es ist leider ein Fehler aufgetreten"}]';
            }

            $('#searchResult').DataTable( {
                retrieve: true,
                searching: false,
                paging: false,
                info: false,
                sort: false,
                "aaData": JSON.parse(msg),
                "columns": [
                    { "data": "msg" }
                ]
            });
            $("#loader").css('display', 'none');
            $("#searchResult thead").remove();
            $('#resultContainer').css("visibility", "visible");
        });
    });
});