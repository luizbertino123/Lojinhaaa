var App_checkout = function () {


    var set_session_payment = function () {


        $.ajax({
            
            url: BASE_URL + 'pagar/pag_seguro_session_id',
            dataType: 'json',
            success: function (response) {
                
                if(response.erro === 0){
                    
                    var session_id = response.session_id;
                    
                    if(session_id){
                        
                        PagSeguroDirectPayment.setSessionId('session_id');
                        
                        alert('session_id');
                        
                    }else{
                        
                        window.location.href = BASE_URL + 'checkout';  
                        
                    }
                    
                }else{
                  
                  console.log(response.mensagem);
                    
                }
            },
            error: function (error) {
                
                aler('Não foi possivel integrar com o pagseguro');
            }

        });

    }

    var calcula_frete = function () {

        $('#btn-busca-cep').on('click', function () {

            var cliente_cep = $("#cliente_cep").val();

            $.ajax({

                type: 'post',
                url: BASE_URL + 'checkout/calcula_frete',
                dataType: 'json',
                data: {
                    cliente_cep: cliente_cep,
                },

                beforeSend: function () {
                    $('#erro_frete').html('');
                    $('.endereco').addClass('d-none');
                    $('#btn-busca-cep').html('<span class="text-white"><i class="fa fa-cog fa-spin"></i>&nbsp;Calculando...</span>');
                },

            }).then(function (response) {

                if (response.erro === 0) {

                    //Exibe os campos escondidos
                    $('.endereco').removeClass('d-none');

                    $('#btn-busca-cep').html('Calcular');
                    $('#retorno-frete').html(response.retorno_endereco);

                    //Preenchendo os inputs com os valores adequados
                    $('[name="cliente_endereco"]').val(response.endereco);
                    $('[name="cliente_bairro"]').val(response.bairro);
                    $('[name="cliente_cidade"]').val(response.cidade);

                    get_opcao_frete_carrinho(); //Chamo a função no response para criar o name                    

                } else {

                    //Erro de formatação ou validação

                    $('#btn-busca-cep').html('Calcular');

                    $('#erro_frete').html(response.retorno_endereco);
                }


                console.log(response);


            });


        });

    }

    var get_opcao_frete_carrinho = function () {

        $('[name="opcao_frete_carrinho"]').on('click', function () {

            var opcao_frete_escolhido = $(this).attr('data-valor_frete');
            var total_final_carrinho = $(this).attr('data-valor_final_carrinho');

            $('#opcao_frete_escolhido').html('R$&nbsp;' + opcao_frete_escolhido);
            $('#total_final_carrinho').html('R$&nbsp;' + total_final_carrinho);


        });
    }


    var forma_pagamento = function () {


        $('.forma_pagamento').on('change', function () {

            var opcao = $(this).val();
            switch (opcao) {

                case '1':

                    $('.cartao').removeClass('d-none');
                    $('.opcao-debito-conta').addClass('d-none');
                    $('.opcao-boleto').addClass('d-none');
                    $('.cartao input').prop('disabled', false);
                    $('.opcao-debito-conta select').prop('disabled', true);
                    break;

                case'2':

                    $('.cartao').addClass('d-none');
                    $('.opcao-boleto').removeClass('d-none');
                    $('.opcao-debito-conta').addClass('d-none');
                    $('.cartao input').prop('disabled', true);
                    $('.opcao-debito-conta select').prop('disabled', true);
                    break;

                case'3':

                    $('.cartao').addClass('d-none');
                    $('.opcao-debito-conta').removeClass('d-none');
                    $('.opcao-boleto').addClass('d-none');
                    $('.cartao input').prop('disabled', true);
                    $('.opcao-debito-conta select').prop('disabled', false);
                    break;
            }

        });
    }


    return {

        init: function () {
            set_session_payment();
            calcula_frete();
            forma_pagamento();            


        }

    }

}();//Inicializa ao carregar


jQuery(document).ready(function () {

    $(window).keydown(function (event) {

        if (event.keyCode == 13) {

            event.preventDefault();
            return false;

        }


    });

    App_checkout.init();


});
