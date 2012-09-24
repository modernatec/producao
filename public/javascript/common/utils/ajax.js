function ajax(element, page, id) {
	$.ajax(
        {
            type: "POST",
            url: page,
            data: id,
            beforeSend: function() {
                // enquanto a fun��o esta sendo processada, voc�
                // pode exibir na tela uma
                // msg de carregando
        	},
            success: function(txt) {
                // pego o id da div que envolve o select com
                // name="id_modelo" e a substituiu
                // com o texto enviado pelo php, que � um novo
                //select com dados da marca x
                $('#'+element).html(txt);
            },
            error: function(txt) {
                // em caso de erro voc� pode dar um alert('erro');
            }
		}
    );
} 