<script>
        jQuery(document).ready(function(){
              var abbrPoisitionOnjects =  {
                      persist: false,
                      valueField: "AbbrPosition",
                      labelField: "NameofPosition",
                      searchField: ["AbbrPosition", "NameofPosition"],
                      options: [ ],
                      render: {
                        item: function(item, escape) {
                          return ( "<div>" + (item.AbbrPosition ? '<span class="label label-default">' + escape(item.AbbrPosition) + "</span>&nbsp;" : "") + (item.NameofPosition ? '<span class="AbbrPosition">' + escape(item.NameofPosition) + "</span>" : "") + "</div>" );
                        },
                        option: function(item, escape) {
                          var label = item.AbbrPosition || item.NameofPosition;
                          var caption = item.AbbrPosition ? item.NameofPosition : null;
                          return ( "<div>" + '<span class="label label-default">' + escape(label) + "</span>&nbsp;" + (caption ? '<span class="caption">' + escape(caption) + "</span>" : "") + "</div>" );
                        }
                      }
                };
        
                var f = {
                  maxItems:6,        
                  persist: false,
                  plugins: ['remove_button'],
                  valueField: "a",
                  labelField: "b",
                  searchField: ["a", "b"],
                  options: [
                  ],
                  items:[],
                  render: {
                    item: function(item, escape) {
                      return ( "<div>" + (item.a ? '<span class="label label-default">' + escape(item.a) + "</span>&nbsp;" : "") + (item.b ? '<span class="a">' + escape(item.b) + "</span>" : "") + "</div>" );
                    },
                    option: function(item, escape) {
                      var label = item.a || item.b;
                      var caption = item.a ? item.b : null;
                      return ( "<div>" + '<span class="label label-default">' + escape(label) + "</span>&nbsp;" + (caption ? '<span class="caption">' + escape(caption) + "</span>" : "") + "</div>" );
                    }
                  }
                };
        
              $('#perusahaan').on('change',function(e){
                var selValue = $('#perusahaan').val();
                if(selValue == 1) {
                  $('#ks').show();
                }
                else {
                  $('#ks').hide();
        
                 $.ajax({
                  url: '{{ url('api/company/idcompany/') }}/'+ selValue,
                      type: 'GET',
                      dataType: 'json',
                      error: function() {},
                      success: function(res) {
                        var newOptions = [];              
                        if ( window["posisiSelectize"] === undefined ) {
                            var posisiSelect = $(".posisi-selectize").selectize(abbrPoisitionOnjects);
                            window["posisiSelectize"] = posisiSelect[0].selectize;
                        } else {
                            window["posisiSelectize"].destroy();
                            var posisiSelect = $(".posisi-selectize").selectize(abbrPoisitionOnjects);
                            window["posisiSelectize"] = posisiSelect[0].selectize;
                        }
        
                        for (var key in res) {
                          var LabelOrg = res[key].NameofPosition + " " + "("+res[key].LvlOrg+")";
                          var o = {NameofPosition: LabelOrg, AbbrPosition: res[key].AbbrPosition};
                          newOptions.push(o);
                        }
        
                        window["posisiSelectize"].clearOptions();
                        var o = newOptions;
                        window["posisiSelectize"].addOption(o);
                        window["posisiSelectize"].setValue('res.AbbrPosition, false');
                    }
                  });
                }
              });

              var dataPreferdis = {!! json_encode($dataPreferdis) !!};
              console.log(dataPreferdis);
                 
              $('#AbbrPosition').on('change',function(e) {
                // get id
                f.options     = [];
                var golongan  = [];
                golongan      = [];
                var stringgol = '';
                var jumlahgol = 0;
                var result    = [];
                var dataitems = [];
                var mylevel   = '';
                var maxData   = '';
                var idlevel   = $('#level').val();
                var options   = $('#test-selectize > option');
                var opt;
                var ar        = {};
                var dataDuplicate = '';                
                var jumlahFromDatabase = 0;

                for (var i=0, iLen=options.length; i<iLen; i++) {
                  opt = options[i];
                  if (opt.selected) {
                    ar = {a:opt.value, b:opt.text};
                    f.items.push(opt.value);
                    f.options.push(ar);
                    stringgol = opt.text;
                    golongan.push(stringgol.substr(-2,1));
                  }
                }                
                
                var valuedata = $(this).val();
                var textdata = $(this).text();
                ar = {a:valuedata, b:textdata};
                f.options.push(ar);
                f.items.push(valuedata);
        
                for(var i = 0; i < golongan.length; ++i){
                    if(golongan[i] == textdata.substr(-2,1))
                    {
                      jumlahgol++;
                    }                   
                }
        
                // stringgol = textdata;
                golongan.push(textdata.substr(-2,1));
                mylevel = textdata.substr(-2,1);
        
                if(idlevel == mylevel) {
                  maksData = 3; 
                  jumlahFromDatabase = $('#jumSameLevel').val();
                  jumlahgol += parseInt(jumlahFromDatabase);
                }
                else {
                  maksData = 3;
                  jumlahFromDatabase = $('#jumNotSameLevel').val();
                  jumlahgol += parseInt(jumlahFromDatabase);
                }

                console.log(golongan);
                console.log(jumlahgol);
                console.log(stringgol.substr(-2,1));
        
                if(jumlahgol < maksData) {                  
                    $('#duplicateId').val(valuedata);
                    if((dataPreferdis.indexOf($('#duplicateId').val()))  == -1) {
                      $('#test-selectize').selectize(f);
                      var selectize= $('#test-selectize')[0].selectize;
                      selectize.clear();
                      selectize.clearOptions();
                      selectize.renderCache['option']   = {};
                      selectize.renderCache['item']     = {};
                      selectize.addOption(f.options);
                      selectize.setValue(f.items);
                      selectize.refreshOptions();
                    }
                    else {
                      alert('Maaf, Jabatan tersebut sudah pernah dipilih');
                    }
                } else {
                  alert('Maaf, Jumlah Pilihan Sudah Maksimal');
                }
            });
                 
        });
        </script>
     