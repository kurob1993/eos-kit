<script>
  (handleTimePicker = function () {
      "use strict";
      $("#from, #to").timepicker({ })
  }),

  
  (handleSelectpicker = function() {
    // posisition object data
    var abbrPoisitionOnject =  {
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

    // function ajax. get data posisition
    AbbrPositionsFunct = function(param, value, abbr, level) {
      $.ajax({
          url: '{{ url('api/zhrom0007/AbbrPosition/') }}/'+ param + '/' + value + '/' + level,
              type: 'GET',
              dataType: 'json',
              error: function() {},
              success: function(res) {
                var newOptions = [];              
                if ( window["posisiSelectize"] === undefined ) {
                    var posisiSelect = $(".posisi-selectize").selectize(abbr);
                    window["posisiSelectize"] = posisiSelect[0].selectize;
                } else {
                    window["posisiSelectize"].destroy();
                    var posisiSelect = $(".posisi-selectize").selectize(abbr);
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

    // option data
    var subOptions = {
      onChange: function(value) {
        var abbrSubditOptions = {
            onChange: function(value) {
            var abbrDivisiOptions = {
                  onChange: function(value) {
                      var abbrDinasOptions = {
                        onChange: function(value) { 
                          var abbrSeksiOptions = {
                            onChange: function(value) {
                              // get data posisition
                              var level = $('#level').val();
                              AbbrPositionsFunct('AbbrOrgUnitSeksi', value, abbrPoisitionOnject, level);
                            },
                            persist: false,
                            valueField: "AbbrOrgUnitSeksi",
                            labelField: "NameofOrgUnitSeksi",
                            searchField: ["AbbrOrgUnitSeksi", "NameofOrgUnitSeksi"],
                            options: [ ],
                            render: {
                              item: function(item, escape) {
                                return ( "<div>" + (item.AbbrOrgUnitSeksi ? '<span class="label label-default">' + escape(item.AbbrOrgUnitSeksi) + "</span>&nbsp;" : "") + (item.NameofOrgUnitSeksi ? '<span class="AbbrOrgUnitSeksi">' + escape(item.NameofOrgUnitSeksi) + "</span>" : "") + "</div>" );
                              },
                              option: function(item, escape) {
                                var label = item.AbbrOrgUnitSeksi || item.NameofOrgUnitSeksi;
                                var caption = item.AbbrOrgUnitSeksi ? item.NameofOrgUnitSeksi : null;
                                return ( "<div>" + '<span class="label label-default">' + escape(label) + "</span>&nbsp;" + (caption ? '<span class="caption">' + escape(caption) + "</span>" : "") + "</div>" );
                              }
                            }
                          }

                          $.ajax({
                            url: '{{ url('api/zhrom0007/AbbrOrganization') }}/5/' + value,
                            type: 'GET',
                            dataType: 'json',
                            error: function() {},
                            success: function(res) {
                              var newOptions = [];              
                              if ( window["seksiSelectize"] === undefined ) {
                                var seksiSelect = $(".seksi-selectize").selectize(abbrSeksiOptions);
                                window["seksiSelectize"] = seksiSelect[0].selectize;
                              } else {
                                window["seksiSelectize"].destroy();
                                var seksiSelect = $(".seksi-selectize").selectize(abbrSeksiOptions);
                                window["seksiSelectize"] = seksiSelect[0].selectize;
                              }

                              for (var key in res) {
                                  var o = {NameofOrgUnitSeksi: res[key].NameofOrgUnitSeksi, AbbrOrgUnitSeksi: res[key].AbbrOrgUnitSeksi};
                                  newOptions.push(o);
                              }

                              window["seksiSelectize"].clearOptions();
                              var o = newOptions;
                              window["seksiSelectize"].addOption(o);
                              window["seksiSelectize"].setValue(res.AbbrOrgUnitSeksi, false);
                              
                            }
                          });

                          // get data posisition
                          var level = $('#level').val();
                          AbbrPositionsFunct('AbbrOrgUnitDinas', value, abbrPoisitionOnject, level);
                        },
                        persist: false,
                        valueField: "AbbrOrgUnitDinas",
                        labelField: "NameofOrgUnitDinas",
                        searchField: ["AbbrOrgUnitDinas", "NameofOrgUnitDinas"],
                        options: [ ],
                        render: {
                          item: function(item, escape) {
                            return ( "<div>" + (item.AbbrOrgUnitDinas ? '<span class="label label-default">' + escape(item.AbbrOrgUnitDinas) + "</span>&nbsp;" : "") + (item.NameofOrgUnitDinas ? '<span class="AbbrOrgUnitDinas">' + escape(item.NameofOrgUnitDinas) + "</span>" : "") + "</div>" );
                          },
                          option: function(item, escape) {
                            var label = item.AbbrOrgUnitDinas || item.NameofOrgUnitDinas;
                            var caption = item.AbbrOrgUnitDinas ? item.NameofOrgUnitDinas : null;
                            return ( "<div>" + '<span class="label label-default">' + escape(label) + "</span>&nbsp;" + (caption ? '<span class="caption">' + escape(caption) + "</span>" : "") + "</div>" );
                          }
                        }
                      }

                      $.ajax({
                        url: '{{ url('api/zhrom0007/AbbrOrganization') }}/4/' + value,
                        type: 'GET',
                        dataType: 'json',
                        error: function() {},
                        success: function(res) {
                          var newOptions = [];              
                          if ( window["dinasSelectize"] === undefined ) {
                            var dinasSelect = $(".dinas-selectize").selectize(abbrDinasOptions);
                            window["dinasSelectize"] = dinasSelect[0].selectize;
                          } else {
                            window["dinasSelectize"].destroy();
                            var dinasSelect = $(".dinas-selectize").selectize(abbrDinasOptions);
                            window["dinasSelectize"] = dinasSelect[0].selectize
                          }

                          for (var key in res) {
                              var o = {NameofOrgUnitDinas: res[key].NameofOrgUnitDinas, AbbrOrgUnitDinas: res[key].AbbrOrgUnitDinas};
                              newOptions.push(o);
                          }

                          window["dinasSelectize"].clearOptions();
                          var o = newOptions;
                          window["dinasSelectize"].addOption(o);
                          window["dinasSelectize"].setValue(res.AbbrOrgUnitDinas, false);
                          
                        }
                      });

                      // get data posisition
                      var level = $('#level').val();
                      AbbrPositionsFunct('AbbrOrgUnitDivisi', value, abbrPoisitionOnject, level);

                      if ( window["seksiSelectize"] !== undefined ) {
                        window["seksiSelectize"].destroy();
                      }
                  },
                  persist: false,
                  valueField: "AbbrOrgUnitDivisi",
                  labelField: "NameofOrgUnitDivisi",
                  searchField: ["AbbrOrgUnitDivisi", "NameofOrgUnitDivisi"],
                  options: [ ],
                  render: {
                    item: function(item, escape) {
                      return ( "<div>" + (item.AbbrOrgUnitDivisi ? '<span class="label label-default">' + escape(item.AbbrOrgUnitDivisi) + "</span>&nbsp;" : "") + (item.NameofOrgUnitDivisi ? '<span class="NameofOrgUnitSubDirektorat">' + escape(item.NameofOrgUnitDivisi) + "</span>" : "") + "</div>" );
                    },
                    option: function(item, escape) {
                      var label = item.AbbrOrgUnitDivisi || item.NameofOrgUnitDivisi;
                      var caption = item.AbbrOrgUnitDivisi ? item.NameofOrgUnitDivisi : null;
                      return ( "<div>" + '<span class="label label-default">' + escape(label) + "</span>&nbsp;" + (caption ? '<span class="caption">' + escape(caption) + "</span>" : "") + "</div>" );
                    }
                  }
              }

              $.ajax({
                url: '{{ url('api/zhrom0007/AbbrOrganization') }}/3/' + value,
                type: 'GET',
                dataType: 'json',
                error: function() {},
                success: function(res) {
                  var newOptions = [];              
                  if ( window["divisiSelectize"] === undefined ) {
                    var divisiSelect = $(".divisi-selectize").selectize(abbrDivisiOptions);
                    window["divisiSelectize"] = divisiSelect[0].selectize;
                  } else {
                    window["divisiSelectize"].destroy();
                    var divisiSelect = $(".divisi-selectize").selectize(abbrDivisiOptions);
                    window["divisiSelectize"] = divisiSelect[0].selectize;
                  }

                  for (var key in res) {
                      var o = {NameofOrgUnitDivisi: res[key].NameofOrgUnitDivisi, AbbrOrgUnitDivisi: res[key].AbbrOrgUnitDivisi};
                      newOptions.push(o);
                  }

                  window["divisiSelectize"].clearOptions();
                  var o = newOptions;
                  window["divisiSelectize"].addOption(o);
                  window["divisiSelectize"].setValue(res.AbbrOrgUnitDivisi, false);
                  
                  
                  // abbrDivisiOptions.options = newOptions;
                  // var subSelect = $(".divisi-selectize").selectize(abbrDivisiOptions);
                  // var selectize = subSelect[0].selectize;
                }
              });

              // get data posisition
              var level = $('#level').val();
              AbbrPositionsFunct('AbbrOrgUnitSubDirektorat', value, abbrPoisitionOnject, level);

              if ( window["dinasSelectize"] !== undefined ) {
                window["dinasSelectize"].destroy();
              }

              if ( window["seksiSelectize"] !== undefined ) {
                window["seksiSelectize"].destroy();
              }  

          },
          persist: false,
          valueField: "AbbrOrgUnitSubDirektorat",
          labelField: "NameofOrgUnitSubDirektorat",
          searchField: ["AbbrOrgUnitSubDirektorat", "NameofOrgUnitSubDirektorat"],
          options: [ ],
          render: {
            item: function(item, escape) {
              return ( "<div>" + (item.AbbrOrgUnitSubDirektorat ? '<span class="label label-default">' + escape(item.AbbrOrgUnitSubDirektorat) + "</span>&nbsp;" : "") + (item.NameofOrgUnitSubDirektorat ? '<span class="NameofOrgUnitSubDirektorat">' + escape(item.NameofOrgUnitSubDirektorat) + "</span>" : "") + "</div>" );
            },
            option: function(item, escape) {
              var label = item.AbbrOrgUnitSubDirektorat || item.NameofOrgUnitSubDirektorat;
              var caption = item.AbbrOrgUnitSubDirektorat ? item.NameofOrgUnitSubDirektorat : null;
              return ( "<div>" + '<span class="label label-default">' + escape(label) + "</span>&nbsp;" + (caption ? '<span class="caption">' + escape(caption) + "</span>" : "") + "</div>" );
            }
          },
        };

        $.ajax({
        url: '{{ url('api/zhrom0007/AbbrOrganization/') }}/2/' + value,
            type: 'GET',
            dataType: 'json',
            error: function() {},
            success: function(res) {
              var newOptions = [];              
              if ( window["subditSelectize"] === undefined ) {
                  var subditSelect = $(".subdit-selectize").selectize(abbrSubditOptions);
                  window["subditSelectize"] = subditSelect[0].selectize;
              } 

              for (var key in res) {
                var o = {NameofOrgUnitSubDirektorat: res[key].NameofOrgUnitSubDirektorat, AbbrOrgUnitSubDirektorat: res[key].AbbrOrgUnitSubDirektorat};
                newOptions.push(o);
              }

              window["subditSelectize"].clearOptions();
              var o = newOptions;
              window["subditSelectize"].addOption(o);
              window["subditSelectize"].setValue(res.AbbrOrgUnitSubDirektorat, false);
          }
        });

        // get data function
        var level = $('#level').val();
        AbbrPositionsFunct('AbbrOrgUnitDirektorat', value, abbrPoisitionOnject, level);  

        if ( window["dinasSelectize"] !== undefined ) {
          window["dinasSelectize"].destroy();
        }

        if ( window["divisiSelectize"] !== undefined ) {
          window["divisiSelectize"].destroy();
        }

        if ( window["dinasSelectize"] !== undefined ) {
          window["dinasSelectize"].destroy();
        }

        if ( window["seksiSelectize"] !== undefined ) {
          window["seksiSelectize"].destroy();
        }     
        
      },
      persist: false,
      valueField: "AbbrOrgUnitDirektorat",
      labelField: "NameofOrgUnitDirektorat",
      searchField: ["AbbrOrgUnitDirektorat", "NameofOrgUnitDirektorat"],
      options: [    ],
      render: {
        item: function(item, escape) {
          return (
            "<div>" +
            (item.AbbrOrgUnitDirektorat ? '<span class="label label-default">' + escape(item.AbbrOrgUnitDirektorat) + "</span>&nbsp;" : "") +
            (item.NameofOrgUnitDirektorat ? '<span class="name">' + escape(item.NameofOrgUnitDirektorat) + "</span>" : "") +
            "</div>"
          );
        },
        option: function(item, escape) {
          var label = item.AbbrOrgUnitDirektorat || item.NameofOrgUnitDirektorat;
          var caption = item.AbbrOrgUnitDirektorat ? item.NameofOrgUnitDirektorat : null;
          return (
            "<div>" + '<span class="label label-default">' + escape(label) + "</span>&nbsp;" +
            (caption ? '<span class="caption">' + escape(caption) + "</span>" : "") +
            "</div>"
          );
        }
      }
    };
    
    $.ajax({
    url: '{{ url('api/zhrom0007/AbbrDirektorat/') }}',
        type: 'GET',
        dataType: 'json',
        error: function() {},
        success: function(res) {
          var newOptions = [];
          for (var key in res) {
            var o = {NameofOrgUnitDirektorat: res[key].NameofOrgUnitDirektorat, AbbrOrgUnitDirektorat: res[key].AbbrOrgUnitDirektorat};
            newOptions.push(o);
          }
          subOptions.options = newOptions;
          var subSelect = $(".sub-selectize").selectize(subOptions);
          var selectize = subSelect[0].selectize;
      }
    });
  }),

  (PreferencePlugins = (function() {
    "use strict";
    return {
      init: function() {
        handleSelectpicker();
      }
    };
  })());

  </script>