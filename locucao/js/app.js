var fimPlaylist, inicioPlaylist;

(function ($) {
  $(document).ready(function(){
    var playlist = [];
    var playlistProdutos = [];
    var playlistIndex = 0;
    
    $('body').addClass('has-js');

    $.datepicker.setDefaults({
      dateFormat: 'dd/mm/yy',
      dayNames: ['Domingo', 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado'],
      dayNamesMin: ['D', 'S', 'T', 'Q', 'Q', 'S', 'S', 'D'],
      dayNamesShort: ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb', 'Dom'],
      monthNames: ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'],
      monthNamesShort: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'],
      nextText: 'Próximo',
      prevText: 'Anterior'
    });

    $( ".calendario" ).datepicker();

    $('.ajuda.tooltip').click(function(e){e.preventDefault(); });

    $('.tooltip').tooltipster({theme: 'tooltipster-shadow', maxWidth:300});

    //$( "#preco-real" ).spinner();
    //$( "#preco-centavos" ).spinner();

    if ($(".hora-sistema span").length > 0)
    {
      window.setInterval(function() 
      {
         atualizaHora();
      }, 30000);
    }

    function atualizaHora()
    {
      $.get( baseUrl+"/hora", function( data ) {
        //alert( "Data Loaded: " + data );
        $('.hora-sistema .hora').html(data);
      });
    }


    $('.button.testar').click(function(e)
    {
      e.preventDefault();

      playlist = [];
      playlistProdutos = [];

      
      if($(this).find('.icon-stop').size()>0)
      {

        audioStop();

        return;
      }

      if($('#zz_locucao_abertura').length>0 && $('#zz_locucao_abertura').val()!="") playlist.push(baseUrl+"/arquivo/zz_locucao_abertura/"+$('#zz_locucao_abertura').val());

      $('#produtos .produto').each(function(index)
      {
        var linha = $(this);
        var produto = linha.find('.nome').find('select').val();
        var de = linha.find('.de').val();
        var parcelamento = linha.find('.preco .parcelamento').find('select').val();
        var real = linha.find('.preco .real').find('select').val();
        var centavos = linha.find('.preco .centavos').find('select').val();
        var por = linha.find('.por').val();
        var parcelamentoDepor = linha.find('.row-de-por .parcelamento').find('select').val();
        var realDepor = linha.find('.row-de-por .real').find('select').val();
        var centavosDepor = linha.find('.row-de-por .centavos').find('select').val();
        var extra = linha.find('.row-de-por .extra').val();

        if(produto!="") playlistProdutos.push(baseUrl+"/arquivo/zz_locucao_produto_"+produto);
        if(de!="") playlistProdutos.push(baseUrl+"/arquivo/zz_locucao_depor/"+de);
        if(parcelamento!="") playlistProdutos.push(baseUrl+"/arquivo/zz_locucao_parcelamento/"+parcelamento);
        if(real!="") playlistProdutos.push(baseUrl+"/arquivo/zz_locucao_preco/"+real);
        if(centavos!="") 
        {
          if(real!="")
          {
            playlistProdutos.push(baseUrl+"/arquivo/zz_locucao_preco/"+centavos);
          }
          else
          {
            playlistProdutos.push(baseUrl+"/arquivo/zz_locucao_preco/"+centavos.replace(".mp3", "_sem_e.mp3"));
          }
        }
        if(por!="") playlistProdutos.push(baseUrl+"/arquivo/zz_locucao_depor/"+por);
        if(parcelamentoDepor!="") playlistProdutos.push(baseUrl+"/arquivo/zz_locucao_parcelamento/"+parcelamentoDepor);
        if(realDepor!="") playlistProdutos.push(baseUrl+"/arquivo/zz_locucao_preco/"+realDepor);
        if(centavosDepor!="") 
        {
          if(realDepor!="")
          {
            playlistProdutos.push(baseUrl+"/arquivo/zz_locucao_preco/"+centavosDepor);
          }
          else
          {
            playlistProdutos.push(baseUrl+"/arquivo/zz_locucao_preco/"+centavosDepor.replace(".mp3", "_sem_e.mp3"));
          }
        }
        if(extra!="") playlistProdutos.push(baseUrl+"/arquivo/zz_locucao_depor/"+extra);
        
      });

      playlist = playlist.concat(playlistProdutos);

      if($(".locucao-repetir input[name='repetir']:checked").val()=='1')
      {
        if($('#zz_locucao_repeticao').length>0 && $('#zz_locucao_repeticao').val()!="") playlist.push(baseUrl+"/arquivo/zz_locucao_repeticao/"+$('#zz_locucao_repeticao').val());

        playlist = playlist.concat(playlistProdutos);
      }

      if($('#zz_locucao_fechamento').length>0 && $('#zz_locucao_fechamento').val()!="") playlist.push(baseUrl+"/arquivo/zz_locucao_fechamento/"+$('#zz_locucao_fechamento').val());

      if($('#zz_locucao_avisos').length>0 && $('#zz_locucao_avisos').val()!="") playlist.push(baseUrl+"/arquivo/zz_locucao_avisos/"+$('#zz_locucao_avisos').val());

      //console.log(playlist);
      audioPlay();
      
    });

    var a = audiojs.createAll({
      trackEnded: function() {
        if(playlistIndex < playlist.length-1)
        {
          playlistIndex++;
          audioPlay();
        }
        else
        {
          playlistIndex = 0;
          $('.button.testar .icon').removeClass('icon-stop').addClass('icon-play');
        }
      }
    });
    

    var audio = a[0];

    function audioPlay()
    {
      if(playlist.length < 1) return;

      audio.load(playlist[playlistIndex]);
      audio.play();
      
      $('.button.testar .icon').removeClass('icon-play').addClass('icon-stop');
    }

    function audioStop()
    {
      audio.playPause();
      playlistIndex = 0;
      $('.button.testar .icon').removeClass('icon-stop').addClass('icon-play');
    }

    inicioPlaylist = function() 
    {
         $('.button.testar .icon').removeClass('icon-play').addClass('icon-stop');
    }

    fimPlaylist = function() 
    {
         $('.button.testar .icon').removeClass('icon-stop').addClass('icon-play');
    }

    $('#adicionar_produto').click(function(e)
    {
      e.preventDefault();

      $clone = $('#produtos .produto').first().clone();

      console.log($clone);

      $clone.find('.select2-container').remove();

      $clone.find('.select2').removeAttr('style');

      $clone.find('option').removeAttr('selected');

      $clone.find('.row-de-por').attr('style', 'display:none');

      var items = [];
      var temp = [];
      items.push( "<option value='' ></option>" );

      for(c in produtos)
      {
        var arquivos = produtos[c].arquivos

        for(a in arquivos)
        {
          temp.push(arquivos[a]);
        }
      }

      temp.sort(function(a, b){
       var nameA=a.arquivo.toLowerCase(), nameB=b.arquivo.toLowerCase()
       if (nameA < nameB) //sort string ascending
        return -1 
       if (nameA > nameB)
        return 1
       return 0 //default return value (no sorting)
      })

      for(a in temp)
      {
        items.push( "<option value='"+temp[a].genero.replace("zz_locucao_produto_", "")+"/"+temp[a].arquivo+"' >"+(temp[a].nome.charAt(0).toUpperCase() + temp[a].nome.slice(1))+"</option>" );
      }

      $clone.find('.produtos').html(items.join( "" ));


      $('#produtos').append($clone);

      $clone.find(".select2.produtos").select2({width:'element', placeholder: "Selecione um produto"}).on("change", atualizaSelect);
      $clone.find(".select2.categoria").select2({width:'element'}).on("change", atualizaSelect).on("change", atualizaProdutos);
      $clone.find(".select2.de-por").select2({width:'element'}).on("change", atualizaSelect).on("change", atualizaDePor);
      $clone.find(".select2").not(".produtos, .categoria, .de-por").select2({width:'element'}).on("change", atualizaSelect);
      $clone.find(".remover").click(removerProdutoLista);

      atualizaIndexLista();
    });

    $('#produtos .produto .remover').click(removerProdutoLista);

    $('.usuarios a.deletar-usuario').click(function(e)
    {
      e.preventDefault();

      var c = confirm('Confirmar exclusão de usuário?');

      if(c != true) return;

      $.ajax({
        type: "POST",
        url: $(this).attr('href'),
        context: $(this),
        success: function(result)
        {
          if(result=="ok")
          {
            $(this).parent().parent().remove();
          }
          else
          {
            alert('Ocorreu algum erro!');
          }
        }
      });
    });

    $('.locucoes a.deletar-locucao').click(function(e)
    {
      e.preventDefault();

      var c = confirm('Confirmar exclusão?');

      if(c != true) return;

      $.ajax({
        type: "POST",
        url: $(this).attr('href'),
        context: $(this),
        success: function(result)
        {
          if(result=="ok")
          {
            $(this).parent().parent().remove();
          }
          else
          {
            alert('Ocorreu algum erro!');
          }
        }
      });
    });

    $(".select2").not(".categoria, .de-por").select2({width:'element'}).on("change", atualizaSelect).each(atualizaSelect);

    $(".select2.categoria").select2({width:'element'}).on("change", atualizaSelect).each(atualizaSelect).on("change", atualizaProdutos);

    $(".select2.de-por").select2({width:'element'}).on("change", atualizaSelect).on("change", atualizaDePor).each(atualizaDePor).each(atualizaSelect);

    $('input[type=radio][name=repetir]').change(function() 
    {
      if (this.value == '1') {
        $('.zz_locucao_repeticao .select2-container').fadeIn();
      }
      else if (this.value == '0') {
        $('.zz_locucao_repeticao .select2-container').fadeOut();
      }
    });

    function removerProdutoLista(e)
    {
      e.preventDefault();

      $(this).parent().detach();

      atualizaIndexLista();
    }

    function atualizaIndexLista()
    {

      var $produtos = $( '#produtos .produto' );      
      if($produtos.length == 1)
      {
        $produtos.find('.remover').hide();
      }
      else
      {
        $produtos.find('.remover').show();
      }

      $produtos.each(function( index ) {

        var categoria = $( this ).find('.categoria');
        categoria.find('select').attr("id", "zz_locucao_categoria-"+index).attr("name", "zz_locucao_categoria-"+index);
        categoria.find('label').attr("for", "zz_locucao_categoria-"+index);

        var nome = $( this ).find('.nome');
        nome.find('select').attr("id", "zz_locucao_produto-"+index).attr("name", "zz_locucao_produto-"+index);
        nome.find('label').attr("for", "zz_locucao_produto-"+index);
        
        var parcelas = $( this ).find('.preco .parcelamento');
        parcelas.find('select').attr("id", "zz_locucao_parcelamento-"+index).attr("name", "zz_locucao_parcelamento-"+index);
        parcelas.find('label').attr("for", "zz_locucao_parcelamento-"+index);

        var real = $( this ).find('.preco .real');
        real.find('select').attr("id", "preco-real-"+index).attr("name", "preco-real-"+index);
        real.find('label').attr("for", "preco-real-"+index);

        var centavos = $( this ).find('.preco .centavos');
        centavos.find('select').attr("id", "preco-centavos-"+index).attr("name", "preco-centavos-"+index);
        centavos.find('label').attr("for", "preco-centavos-"+index);

        /**diferenciar de por**/
        var de = $( this ).find('.preco .de');
        de.attr("id", "zz_locucao_de-"+index).attr("name", "zz_locucao_de-"+index);
        //console.log(de.val());

        var por = $( this ).find('.row-de-por .por');
        por.attr("id", "zz_locucao_por-"+index).attr("name", "zz_locucao_por-"+index);

        parcelas = $( this ).find('.row-de-por .parcelamento');
        parcelas.find('select').attr("id", "zz_locucao_parcelamento-"+index+"-de_por").attr("name", "zz_locucao_parcelamento-"+index+"-de_por");
        parcelas.find('label').attr("for", "zz_locucao_parcelamento-"+index+"-de_por");

        real = $( this ).find('.row-de-por .real');
        real.find('select').attr("id", "preco-real-"+index+"-de_por").attr("name", "preco-real-"+index+"-de_por");
        real.find('label').attr("for", "preco-real-"+index+"-de_por");

        centavos = $( this ).find('.row-de-por .centavos');
        centavos.find('select').attr("id", "preco-centavos-"+index+"-de_por").attr("name", "preco-centavos-"+index+"-de_por");
        centavos.find('label').attr("for", "preco-centavos-"+index+"-de_por");

        var extra = $( this ).find('.row-de-por .extra');
        extra.attr("id", "zz_locucao_deporextra-"+index).attr("name", "zz_locucao_deporextra-"+index);
        
      });
    }
    atualizaIndexLista();

    function atualizaSelect()
    {
      $t = $(this).parent().find('.select2-container a');
      

      if($(this).val() != "")
      {
        $t.addClass('selecionado');
      }
      else
      {
        $t.removeClass('selecionado');
      }
    } 

    function atualizaProdutos()
    {
      var categoria = $(this).val();
      var idSelect = $(this)[0].id;
      idSelect = idSelect.split("-");
      idSelect = idSelect.pop();

      $produto = $("#zz_locucao_produto-"+idSelect);

      $produto.select2("destroy");

      var items = [];
      var temp = [];
      items.push( "<option value='' ></option>" );

      for(c in produtos)
      {
        if(categoria=="" || produtos[c].nome==categoria)
        {
          var arquivos = produtos[c].arquivos

          for(a in arquivos)
          {
            temp.push(arquivos[a]);
          }
        }
      }

      temp.sort(function(a, b){
       var nameA=a.arquivo.toLowerCase(), nameB=b.arquivo.toLowerCase()
       if (nameA < nameB) //sort string ascending
        return -1 
       if (nameA > nameB)
        return 1
       return 0 //default return value (no sorting)
      })

      for(a in temp)
      {
        items.push( "<option value='"+temp[a].genero.replace("zz_locucao_produto_", "")+"/"+temp[a].arquivo+"' >"+(temp[a].nome.charAt(0).toUpperCase() + temp[a].nome.slice(1))+"</option>" );
      }

      $produto.html(items.join( "" ));

      $produto.select2({width:'element', placeholder: "Selecione um produto"}).on("change", atualizaSelect);
      /*
      $.getJSON(baseUrl+"/arquivos/zz_locucao_produto_"+categoria, function( data ) {
        var items = [];
        items.push( "<option value='' ></option>" );
        $.each( data, function( key, val ) {
          items.push( "<option value='"+categoria+"/"+val.arquivo+"' >"+(val.nome.charAt(0).toUpperCase() + val.nome.slice(1))+"</option>" );
        });

        $produto.html(items.join( "" ));

        $produto.select2({width:'element', placeholder: "Selecione um produto"}).on("change", atualizaSelect);

       });
      */
    }

    function atualizaDePor()
    {
      //console.log($(this).parent().parent().parent()); 

      $t = $(this).parent().parent().parent().find(".row-de-por");
      $de = $(this).parent().parent().find(".de");
      $por = $t.find(".por");

      $t.find(".extra").val("");

      if($(this).val() != "")
      {
        $de.val($de.data("valor"));
        $por.val($por.data("valor"));

        if($(this)[0].selectedIndex>1)
        {
          $t.find(".extra").val($(this).val());
        }

        $t.find(".select2").select2("destroy");
        $t.show();
        $t.find(".select2").select2({width:'element'}).on("change", atualizaSelect).each(atualizaSelect);
      }
      else
      {
        $t.hide();
        $de.val("");
        $por.val("");
        $t.find(".select2").val("");
      }
    } 

    $( "#dialog-message" ).dialog({
      modal: true,
      width: 400,
      buttons: {
        Ok: function() {
          $( this ).dialog( "close" );
        }
      }
    });

  });
})(jQuery);