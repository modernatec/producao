$(document).ready(function() {
        $("#frmCreateUsers").validate({            
            ignore: 'input[ignore="true"], select[ignore="true"]',
            rules: {
                username: {required:true},
                password: {required:true},
                password_confirm: {
                    required:true,
                    equalTo: "#password"
                },
                role: {required:true},
                nome: {required:true},
                email: {
                    required:true,
                    email:true
                }
            },
            messages: {
                username: { required:"Digite o username."},
                password: { required: "Digite a senha." },
                password_confirm: {
                    required:"Confirme a senha.",
                    equalTo:"As senhas não conhecidem."
                },
                role: {required:"Escolha uma permissão"},
                nome: {required:"Digite o nome."},
                email: {
                    required:"Digite o e-mail.",
                    email:"Digite um e-mail válido."
                }
            },
            submitHandler: function(form){
              $(form).submit();
            }
        })        
});