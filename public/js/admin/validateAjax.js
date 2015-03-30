function validateAjax(){
    //console.log("chamou validate")
    //$(".date").datepicker({dateFormat: 'dd/mm/yy'}).val();

    $('.date').live('focus', function () {
        $(this).not('.hasDatePicker').datepicker({dateFormat: 'dd/mm/yy'}).val();
    });

    $("#frmCreateTask").validate({
        rules: {
            topic: {required:true},
            crono_date: {required:true},
            description: {required:true},
        },
        messages: {
            topic: { required:'Campo não pode ser vazio'},
            crono_date: { required:'Campo não pode ser vazio'},
            description: { required:'Campo não pode ser vazio'},
        },
        submitHandler: function(form) {
            $('input[type=submit]').attr('disabled', 'disabled');
            ajaxPost(form, $(form).data("panel"));
            return false;       
        }
    })

    $("#frmCreateTask2").validate({
        rules: {
            topic: {required:true},
            crono_date: {required:true},
            description: {required:true},
        },
        messages: {
            topic: { required:'Campo não pode ser vazio'},
            crono_date: { required:'Campo não pode ser vazio'},
            description: { required:'Campo não pode ser vazio'},
        },
        submitHandler: function(form) {
            $('input[type=submit]').attr('disabled', 'disabled');
            ajaxPost(form, $(form).data("panel"));
            return false;       
        }
    })

    $("#frmAlteraStatus").validate({
        rules: {
            status_id: {required:true},
            prova: {required:true},
            crono_date: {required:true},
        },
        messages: {
            status_id: { required:'Selecione uma opção'},
            prova: { required:'Selecione uma opção'},
            crono_date: { required:'Campo não pode ser vazio'},
        },
        submitHandler: function(form) {
            //console.log('validate frmStatus');
            $('input[type=submit]').attr('disabled', 'disabled');
            ajaxPost(form, $(form).data("panel"));
            return false;       
        }
    })

    $("#frmStatus2").validate({
        rules: {
            status_id: {required:true},
            prova: {required:true},
            crono_date: {required:true},
        },
        messages: {
            status_id: { required:'Selecione uma opção'},
            prova: { required:'Selecione uma opção'},
            crono_date: { required:'Campo não pode ser vazio'},
        },
        submitHandler: function(form) {
            $('input[type=submit]').attr('disabled', 'disabled');
            ajaxPost(form, $(form).data("panel"));
            return false;       
        }
    })

    $("#frmSupplier").validate({
        rules: {
            empresa:{required:true},
            "nome[]": {required:true},
            "email[]": {required:true,email:true},
            "telefone[]": {required:true}
        },
        messages: {
            empresa: { required:"Digite o nome da empresa."},
            "nome[]": { required:"Digite o nome do contato."},
            "email[]": { required: "Digite o email do contato." },
            "telefone[]": {required:"Digite o telefone o contato."}
        },
        submitHandler: function(form) {
            $('input[type=submit]').attr('disabled', 'disabled');
            ajaxPost(form, $(form).data("panel"));
            return false;       
        }
    })

    

    $("#frmAnotacoes").validate({
        rules: {
            anotacoes: {required:true},
        },
        messages: {
            anotacoes: { required:'Campo não pode ser vazio'},
        },
        submitHandler: function(form) {
            $('input[type=submit]').attr('disabled', 'disabled');
            ajaxPost(form, $(form).data("panel"));
            return false;       
        }
    });

    $("#frmAnotacoes_edit").validate({
        rules: {
            anotacoes: {required:true},
        },
        messages: {
            anotacoes: { required:'Campo não pode ser vazio'},
        },
        submitHandler: function(form) {
            $('input[type=submit]').attr('disabled', 'disabled');
            ajaxPost(form, $(form).data("panel"));
            return false;       
        }
    });

    $("#startTask").validate({
        submitHandler: function(form) {
            $('input[type=submit]').attr('disabled', 'disabled');
            ajaxPost(form, $(form).data("panel"));
            return false;       
        }
    });

    $("#frmEditTask").validate({
        submitHandler: function(form) {
            $('input[type=submit]').attr('disabled', 'disabled');
            ajaxPost(form, $(form).data("panel"));
            return false;       
        }
    });

    $("#frmCreateSegmento").validate({
        rules: {
            nome: {required:true}
        },
        messages: {
            nome: { required:'Campo não pode ser vazio'}
        },
        submitHandler: function(form) {
            $('input[type=submit]').attr('disabled', 'disabled');
            ajaxPost(form, $(form).data("panel"));
            return false;    
        }
    })

    $("#frmCreateCollection").validate({
        rules: {
            name: {required:true},
            materia_id: {required:true},
            segmento_id: {required:true},
            ano: {required:true},
            fechamento: {required:true},
        },
        messages: {
            name: { required:'Campo não pode ser vazio'},
            materia_id: {required:'Campo não pode ser vazio'},
            segmento_id: {required:'Campo não pode ser vazio'},
            ano: {required:'Campo não pode ser vazio'},
            fechamento: {required:'Campo não pode ser vazio'},
        },
        submitHandler: function(form) {
            $('input[type=submit]').attr('disabled', 'disabled');
            ajaxPost(form, $(form).data("panel"));
            return false;    
        }
    })

    $("#frmCreateMaterias").validate({
        rules: {
            name: {required:true}
        },
        messages: {
            name: { required:'Campo não pode ser vazio'}
        },
        submitHandler: function(form) {
            $('input[type=submit]').attr('disabled', 'disabled');
            ajaxPost(form, $(form).data("panel"));
            return false;    
        }
    })

    $("#frmCreatePais").validate({
        rules: {
            name: {required:true}
        },
        messages: {
            name: { required:'Campo não pode ser vazio'}
        },
        submitHandler: function(form) {
            $('input[type=submit]').attr('disabled', 'disabled');
            ajaxPost(form, $(form).data("panel"));
            return false;    
        }
    })

    $("#frmCreateTipoObj").validate({
        rules: {
            name: {required:true}
        },
        messages: {
            name: { required:'Campo não pode ser vazio'}
        },
        submitHandler: function(form) {
            $('input[type=submit]').attr('disabled', 'disabled');
            ajaxPost(form, $(form).data("panel"));
            return false;    
        }
    })


    $("#frmCreateSfwprod").validate({
        rules: {
            name: {required:true}
        },
        messages: {
            name: { required:'Campo não pode ser vazio'}
        },
        submitHandler: function(form) {
            $('input[type=submit]').attr('disabled', 'disabled');
            ajaxPost(form, $(form).data("panel"));
            return false;    
        }
    })

    $("#frmCreateProject").validate({
        rules: {
            name: {required:true},
            target: {required:true},
            description: {required:true}
        },
        messages: {
            name: { required:"Digite o nome do projeto."},
            target: { required: "Digite o seguimento do projeto." },
            description: {required:"Digite uma descrição para o projeto."}
        },
        submitHandler: function(form) {
            $('input[type=submit]').attr('disabled', 'disabled');
            ajaxPost(form, $(form).data("panel"));
            return false;    
        }
    });

    
    $("#sync_gdocs").validate({
        submitHandler: function(form) {
            $('input[type=submit]').attr('disabled', 'disabled');
            ajaxPost(form, $(form).data("panel"));
            return false;       
        }
    });

       
    

    $("#formEndTask").validate({
        submitHandler: function(form) {
            $('input[type=submit]').attr('disabled', 'disabled');
            ajaxPost(form, $(form).data("panel"));
            return false;       
        }
    });

    $("#frmTeam").validate({
        submitHandler: function(form) {
            $('input[type=submit]').attr('disabled', 'disabled');
            ajaxPost(form, $(form).data("panel"));
            return false;       
        }
    });

    $("#frm_oeds").validate({
        submitHandler: function(form) {
            $('input[type=submit]').attr('disabled', 'disabled');
            ajaxPost(form, $(form).data("panel"));
            return false;       
        }
    });

    $("#frm_reset_oeds").validate({
        submitHandler: function(form) {
            $('input[type=submit]').attr('disabled', 'disabled');
            ajaxPost(form, $(form).data("panel"));
            return false;       
        }
    });

    $("#frm_acervo").validate({
        submitHandler: function(form) {
            $('input[type=submit]').attr('disabled', 'disabled');
            ajaxPost(form, $(form).data("panel"));
            return false;       
        }
    });

    $("#frm_reset_acervo").validate({
        submitHandler: function(form) {
            $('input[type=submit]').attr('disabled', 'disabled');
            ajaxPost(form, $(form).data("panel"));
            return false;       
        }
    });

    $("#frm_reset_suppliers").validate({
        submitHandler: function(form) {
            $('input[type=submit]').attr('disabled', 'disabled');
            ajaxPost(form, $(form).data("panel"));
            return false;       
        }
    });

    

    

    $("#frm_suppliers").validate({
        submitHandler: function(form) {
            $('input[type=submit]').attr('disabled', 'disabled');
            ajaxPost(form, $(form).data("panel"));
            return false;       
        }
    });

    $("#frm_usuarios").validate({
        submitHandler: function(form) {
            $('input[type=submit]').attr('disabled', 'disabled');
            ajaxPost(form, $(form).data("panel"));
            return false;       
        }
    });

    
    

    

    $("#frmCusto2").validate({
        rules: {
            team_id: {required:true},
            supplier_id: {required:true},
            valor: {required:true},
        },
        messages: {
            team_id: { required:'Campo não pode ser vazio'},
            supplier_id: { required:'Campo não pode ser vazio'},
            valor: { required:'Campo não pode ser vazio'},
        },
        submitHandler: function(form) {
            $('input[type=submit]').attr('disabled', 'disabled');
            ajaxPost(form, $(form).data("panel"));
            return false;       
        }
    })   


    $("#frmCreateObject").validate({
        rules: {
            title: {required:true},
            taxonomia: {required:true},
            collection_id: {required:true},
            country_id: {required:true},
            fase: {required:true},
            typeobject_id: {required:true},
            supplier_id: {required:true},
            crono_date: {required:true},
            ini_date: {required:true},
            arq_aberto: {required:true},
            interatividade: {required:true},
            format_id:{required:true},
            reaproveitamento:{required:true},
        },
        messages: {
            title: { required:'Campo não pode ser vazio'},
            taxonomia: { required:'Campo não pode ser vazio'},
            collection_id: { required:'Selecione uma opção'},
            country_id: { required:'Selecione uma opção'},
            fase: { required:'Selecione uma opção'},
            typeobject_id: { required:'Selecione uma opção'},
            supplier_id: { required:'Selecione uma opção'},
            crono_date: { required:'Campo não pode ser vazio'},
            ini_date: { required:'Campo não pode ser vazio'},
            arq_aberto: { required:'Selecione uma opção'},
            interatividade: { required:'Selecione uma opção'},
            format_id:{ required:'Selecione uma opção'},
            reaproveitamento:{ required:'Selecione uma opção'},
        },
        submitHandler: function(form) {
            $('input[type=submit]').attr('disabled', 'disabled');
            ajaxPost(form, $(form).data("panel"));
            return false; 
        }
    })   

     $("#frmCreateUsers").validate({            
            ignore: ".ignore",
            rules: {
                username: {required:true},
                password: {required:true},
                password_confirm: {
                    required:true,
                    equalTo: "#password"
                },
                role: {required:true},
                team: {required:true},
                nome: {required:true}
                /*,
                email: {
                    required:true,
                    email:true
                }*/
            },
            messages: {
                username: { required:"Digite o username."},
                password: { required: "Digite a senha." },
                password_confirm: {
                    required:"Confirme a senha.",
                    equalTo:"As senhas não conhecidem."
                },
                role: {required:"Escolha uma permissão"},
                team: {required:"Escolha uma equipe"},
                nome: {required:"Digite o nome."}
                /*,
                email: {
                    required:"Digite o e-mail.",
                    email:"Digite um e-mail válido."
                }*/
            },
            submitHandler: function(form){
                $('input[type=submit]').attr('disabled', 'disabled');
                ajaxPost(form, $(form).data("panel"));
                return false; 
            }
        })   
    
    $("#frmEditUsers").validate({            
        ignore: ".ignore",
        rules: {
            nome: {required:true},
            email: {
                required:true,
                email:true
            }
        },
        messages: {
            nome: {required:"Digite o nome."},
            email: {
                required:"Digite o e-mail.",
                email:"Digite um e-mail válido."
            }
        },
        submitHandler: function(form){
            $('input[type=submit]').attr('disabled', 'disabled');
            checkUpload(form, $(form).data("panel"));
            return false; 
        }
    });

    $("#frmEditPass").validate({            
            ignore: ".ignore",
            rules: {
                username: {required:true},
                password: {required:true},
                password_confirm: {
                    required:true,
                    equalTo: "#password"
                },
                
            },
            messages: {
                username: { required:"Digite o username."},
                password: { required: "Digite a senha." },
                password_confirm: {
                    required:"Confirme a senha.",
                    equalTo:"As senhas não conhecidem."
                },
            },
            submitHandler: function(form){
                ajaxPost(form, $(form).data("panel"));
            }
        })   

            
}

